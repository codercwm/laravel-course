<?php
$count = 0;
\Swoole\Timer::tick(1000, function ($timerId, $count) {
    global $count;
    $count++;
    echo "第{$count}次执行\n";
    if ($count == 5) {
        \Swoole\Timer::clear($timerId);
        echo "完毕\n";
    }
}, $count);