<?php

$process = new swoole_process(function (\Swoole\Process $worker) {
    // 子进程逻辑
    // 使用pop从消息队列读取返回消息
    $cmd = $worker->pop();
    echo $cmd;
    // 使用push将数据推送到消息队列
    $worker->push("来自子进程的bbb\n");
    $worker->exit(0);  // 退出子进程
}, false, false);  // 关闭管道

// 第一个参数表示消息队里的 key，第二个参数表示通信模式，2 表示争抢模式
// 使用争抢模式进行通信时，哪个子进程先读取到消息先消费，因此无法实现与指定子进程的通信
// 消息队列不支持事件循环，因此引入了 \Swoole\Process::IPC_NOWAIT 表示以非阻塞模式进行通信
$process->useQueue(1, 2 | \Swoole\Process::IPC_NOWAIT);
// 使用push将数据推送到消息队列
$process->push("来自主进程的aaa\n");
// 使用pop从消息队列读取返回消息
$msg = $process->pop();
echo '接收到子进程中的数据: ' . $msg;

// 启动子进程
$process->start();

swoole_process::wait();   // 要调用这段代码，否则子进程中的 push 或 pop 可能会报错