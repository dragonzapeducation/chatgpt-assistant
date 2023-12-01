<?php
require "vendor/autoload.php";
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\RunState;

class JessicaAssistant extends Assistant
{

    public function __construct($api_config = NULL)
    {
        parent::__construct($api_config);
    }

    public function getAssistantId(): string
    {
        return 'asst_0q46BUiesPu5XStGHufJVCba';
    }

    public function handleFunction(string $function): string
    {
        return '{"success":false, "message":"No functions are allowed"}';
    }

}

$assistant = new JessicaAssistant(new APIConfiguration('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'));
$conversation = $assistant->newConversation();

while(1)
{
    $input_message = fgets(STDIN);
    echo 'User:' . $input_message . "\n";
    $conversation->sendMessage($input_message);
    $conversation->blockUntilResponded();
    
    echo 'Assistant: ' . $conversation->getResponse() . "\n";
    
}

