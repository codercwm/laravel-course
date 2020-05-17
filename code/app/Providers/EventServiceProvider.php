<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\TestEvent' => [
            'App\Listeners\TestListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('event.*', function ($eventName, array $data) {
            //
        });

        Event::listen('laravels.received_request', function (\Illuminate\Http\Request $request, $app) {
            // 添加一个 GET 请求参数
            $request->query->set('get_key', 'swoole-get-param');
            // 添加一个 POST 请求参数
            $request->request->set('post_key', 'swoole-post-param');
        });

        Event::listen('laravels.generated_response', function (\Illuminate\Http\Request $request, \Illuminate\Http\Response $response, $app) {
            //在响应中添加一个headers
            $response->headers->set('header-key', 'swoole-header');
        });
    }
}
