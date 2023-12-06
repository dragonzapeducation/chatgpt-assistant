<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */


namespace Dragonzap\OpenAI\ChatGPT;


/**
 * Represents a function call that GPT has made
 */
class GPTFunctionCall
{

    protected $function_name;

    protected array $function_arguments;
    protected string|array $response;
    public function __construct(string $function_name, array $function_arguments, string|array $response)
    {
        $this->function_name = $function_name;
        $this->function_arguments = $function_arguments;
        $this->response = $response;
    }


    public function getFunctionName() : string
    {
        return $this->function_name;
    }

    public function getFunctionArguments() : array
    {
        return $this->function_arguments;
    }
    
    public function getFunctionResponse() : string|array
    {
        return $this->response;
    }

}
