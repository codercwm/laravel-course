<?php

namespace App\Jobs;

use Hhxsv5\LaravelS\Swoole\Timer\CronJob;
use Illuminate\Support\Facades\Log;

class TestSwooleCronJob extends CronJob
{
    protected $i = 0;

    // 主程序的执行方法
    public function run()
    {
        $this->i++;
        Log::info(__METHOD__, ["第{$this->i}次执行"]);

        if ($this->i == 5) { // 总共运行3次
            Log::info(__METHOD__, ['完毕']);
            $this->stop(); // 清除定时器
        }
    }

    // 设置任务间隔，单位为毫秒
    public function interval()
    {
        return 1000000;
    }

    // 是否立即执行第一次，false则等待间隔时间后执行第一次
    public function isImmediate()
    {
        return false;
    }
}