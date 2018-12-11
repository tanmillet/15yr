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
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        /*'Illuminate\Database\Events\QueryExecuted' => [ 2018-07-05  hwz注释  这个是sql输出日志 有bug 所以注释。。报错因为占位符的跟mysql的日期函数有冲突 。。  
            'App\Listeners\DatabaseEventListener'
        ]*/
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
