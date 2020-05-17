<?php
$workerNum = 5;
$pool = new \Swoole\Process\Pool($workerNum);

$pool->on("WorkerStart", function ($pool, $workerId) {
    echo "进程#{$workerId} 已启动\n";
    $redis = new \Redis();
    //pconnect：脚本结束之后连接不释放，连接保持在php-fpm进程中
    $redis->pconnect('127.0.0.1', 6379);
    $key = "key1";
    while (true) {
        //Redis Brpop 命令移出并获取列表的最后一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止
        $msgs = $redis->brpop($key, 2);
        if ( $msgs == null){
            continue;
        }
        var_dump($msgs);
        echo "进程#{$workerId} 处理了数据\n";
    }
});

$pool->on("WorkerStop", function ($pool, $workerId) {
    echo "进程#{$workerId} 已停止\n";
});

$pool->start();