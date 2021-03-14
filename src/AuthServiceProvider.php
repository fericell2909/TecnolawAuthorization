<?php

namespace Tecnolaw\Authorization;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        
        $this->app->middleware([
            'TecnolawCross' => Middleware\CorsMiddleware::class,
        ]);

        $this->app->configure('mail');
        $this->app->alias('mailer', Illuminate\Mail\Mailer::class);
        $this->app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
        $this->app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);

        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'TecnolawAuth');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'TecnolawAuth');
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->routeMiddleware([
            'TecnolawAuthAdmin' => Middleware\AuthenticateAdminMiddleware::class,
            'TecnolawAuth' => Middleware\AuthenticateMiddleware::class,
            'IsAdmin' => Middleware\IsAdminMiddleware::class,
        ]);
        $this->app->register(CheckAuthServiceProvider::class);
    }
}
