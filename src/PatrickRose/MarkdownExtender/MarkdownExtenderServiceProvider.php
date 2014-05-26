<?php namespace PatrickRose\MarkdownExtender;

use Illuminate\Support\ServiceProvider;

class MarkdownExtenderServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared(
            'MarkdownExtender',
            function () {
                return new MarkdownExtender();
            }
        );
    }
}