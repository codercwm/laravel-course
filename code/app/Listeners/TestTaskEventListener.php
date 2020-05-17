<?php

namespace App\Listeners;

use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;
use Illuminate\Support\Facades\Log;

class TestTaskEventListener extends Listener
{
    public function __construct()
    {
    }

    public function handle(Event $event)
    {
        Log::info(__METHOD__ . ': 开始处理', [$event->getData()]);
        // 模拟任务耗时
        sleep(3);
        Log::info(__METHOD__ . ': 处理完毕');
    }
}