<?php

namespace Dragonzap\OpenAI\ChatGPT;
use OpenAI;
use Exception;

/**
 * An abstract class representing an Assistant.
 * 
 * This class serves as a blueprint for creating various types of assistants.
 * Each assistant will have its own implementation of the handleFunction method.
 */
abstract class Assistant
{
    protected $api_config;
    protected $client;
    public function __construct(APIConfiguration $api_config=NULL)
    {
        $this->api_config = $api_config;
        if ($this->api_config == NULL) {
            try {
                $this->api_key = new APIConfiguration(config('services.openai.key'));
            } catch (Exception $e) {
                throw new Exception('If you do not provide a ' . APIConfiguration::class . ' then you must be using this module within Laravel framework. Details:'  . $e->getMessage());
            }
        }

        $this->client = OpenAI::client($this->api_config->getApiKey());
    }
    /**
     * 
     * The creator of an assistant should return the assistant ID here, generally this would be returned directly unless you plan
     * to pass the ID into a constructor of some kind.
     * @return string Returns the assistant ID for the assistant
     */
    public abstract function getAssistantId(): string;

    public function newChat()
    {
        $response = $this->client->threads()->create([]);
        print_r($response->toArray());
    }
    /**
     * Sends a message to the assistant
     * @param string $message The message to send to this assistant
     */
    public function sendMessage(string $message): void
    {

    }

    /**
     * Handles a specific function required by the assistant.
     * 
     * @param string $function The name of the function to handle.
     * @return string The result or response of the handled function.
     */
    public abstract function handleFunction(string $function): string;


 

}
