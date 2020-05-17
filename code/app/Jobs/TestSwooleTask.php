<?php
namespace App\Jobs;

use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Support\Facades\Log;

class TestSwooleTask extends Task
{
    // 待处理的任务数据
    private $data;

    // 处理结果
    private $result;

    public function __construct($data)
    {
        $this->data = $data;
    }

    //任务的具体处理代码写在这里
    public function handle()
    {
        Log::info(__METHOD__ . ': 开始处理任务', [$this->data]);
        sleep(3); // 模拟任务需要3秒才能执行完毕
        $this->result = $this->data.' 的任务处理成功';
    }

    // 任务完成时触发
    public function finish()
    {
        Log::info(__METHOD__ . ': 任务处理完成', [$this->result]);

        // 任务处理完成后要处理的逻辑写在这
    }
}