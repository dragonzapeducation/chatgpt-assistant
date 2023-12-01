<?php

namespace Dragonzap\OpenAI\ChatGPT;

use Dragonzap\OpenAI\ChatGPT\Exceptions\IncompleteRunException;
use OpenAI;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;
use OpenAI\Responses\Threads\ThreadResponse;
use Exception;


enum RunState: string
{
    // NON_EXISTANT status means that the conversation has not called run() yet or that a previous run() has been handled already
    case NON_EXISTANT = 'non_existant';
    case QUEUED = 'queued';

    case RUNNING = 'running';
    case COMPLETED = 'completed';

    // INVOKING_ACTION is returned when chatgpt wants to call a function defined in your assistant.
    // We set this when we are aware and will attempt to invoke this action on your behalf.
    // You do not have to do anything with this.
    case INVOKING_ACTION = 'invoking_action';
    case FAILED = 'failed';

    case UNKNOWN = 'unknown';
}

/**
 * Represents a conversation
 */
class Conversation
{
    protected Assistant $assistant;
    protected ThreadResponse $thread;

    protected ThreadRunResponse $current_run;

    public function __construct(Assistant $assistant, ThreadResponse $thread)
    {
        $this->assistant = $assistant;
        $this->thread = $thread;
    }


    public function sendMessage(string $message, string $role = 'user', bool $autorun = true): void
    {
        $this->assistant->getOpenAIClient()->threads()->messages()->create($this->thread->id, [
            'role' => $role,
            'content' => $message,
        ]);
        if ($autorun) {
            $this->run();
        }
    }

    public function run(): void
    {
        $this->current_run = $this->assistant->getOpenAIClient()->threads()->runs()->create(
            threadId: $this->thread->id,
            parameters: [
                'assistant_id' => $this->assistant->getAssistantId(),
            ],
        );

    }

    public function getThreadResponse(): ThreadResponse
    {
        return $this->thread;
    }

    private function getRunStateFromOpenAIRunState(string $state): RunState
    {
        $run_state = RunState::UNKNOWN;

        switch ($state) {
            case 'queued':
                $run_state = RunState::QUEUED;
                break;

            case 'in_progress':
                $run_state = RunState::RUNNING;
                break;

            case 'completed':
                $run_state = RunState::COMPLETED;
                break;

            case 'requires_action':
                // We will automatically invoke the action later, so mark it as invoking action
                $run_state = RunState::INVOKING_ACTION;
                break;

            case 'failed':
            case 'expired':
            case 'cancelled':
                $run_state = RunState::FAILED;
                break;
        }

        return $run_state;
    }

    public function getResponse(): string
    {
        if ($this->current_run->status != 'completed') {
            throw new IncompleteRunException('The status of the job is not yet completed. Run Conversation::getRunState() to refresh the cache of this current run if it returns RunState::COMPLETED you will be able to get a response by calling this function again');
        }

        $response = $this->assistant->getOpenAIClient()->threads()->messages()->list($this->thread->id, [
            'limit' => 1,
        ]);


        return $response->data[0]->content[0]->text->value;
    }

    /**
     * Blocks the execution until ChatGPT responds to a message or there was a failure of some kind
     * 
     * Warning: Ideally should only be used in API's or console applications, avoid use if possible as long timeouts
     * disrupt user experience and strain the web server.
     */
    public function blockUntilResponded(): RunState
    {
        $run_state = $this->getRunState();
        while ($run_state != RunState::COMPLETED && $run_state != RunState::FAILED) {
            sleep(1);
            $run_state = $this->getRunState();
        }

        return $run_state;
    }

    public function getRunState(): RunState
    {
        if (!$this->current_run) {
            return RunState::NON_EXISTANT;
        }

        $this->current_run = $this->assistant->getOpenAIClient()->threads()->runs()->retrieve(
            threadId: $this->thread->id,
            runId: $this->current_run->id,
        );


        return $this->getRunStateFromOpenAIRunState($this->current_run->status);
    }

}
