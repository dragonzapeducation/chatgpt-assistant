<?php

namespace Dragonzap\OpenAI\ChatGPT;

use Illuminate\Support\ServiceProvider;

class ChatGptAssistantProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/dragonzap.php' => config_path('dragonzap.php'),
        ], 'config');
    
        $this->mergeConfigFrom(
            __DIR__.'/config/dragonzap.php', 'dragonzap'
        );
    }
    
    public function register()
    {
        // Code for bindings, if necessary
    }
}

