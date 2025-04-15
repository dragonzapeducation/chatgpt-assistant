<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */

namespace Dragonzap\OpenAI\ChatGPT;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

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
        // Explicit binding if required
        $this->app->singleton('files', function () {
            return new Filesystem;
        });
    }
}

