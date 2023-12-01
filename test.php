<?php
require "vendor/autoload.php";
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;

class JessicaAssistant extends Assistant
{

    public function __construct($api_config = NULL)
    {
        parent::__construct($api_config);
    }
    
    public function getAssistantId(): string
    {
        return 'asst_jpXoAmpECT1IJumoV1Uwvddx';
    }

    public function handleFunction(string $function): string
    {
        return '{"success":false, "message":"No functions are allowed"}';
    }

}

$assistant = new JessicaAssistant(new APIConfiguration('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'));
$chat = $assistant->newChat();
$chat->sendMessage('How much money did we make today?');
$chat->awaitReply();

