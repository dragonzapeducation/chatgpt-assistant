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
class ChatGptAssistantProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/dragonzap.php' => config_path('dragonzap.php'),
            ], 'dragonzap-config');
        }

        $this->mergeConfigFrom(__DIR__.'/config/dragonzap.php', 'dragonzap');
    }

    public function register()
    {
        // No filesystem references here; keep it empty if you don't need anything else
    }
}
