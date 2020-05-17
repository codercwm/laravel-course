<?php

namespace App\Services;

use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketService implements WebSocketHandlerInterface
{
    public function __construct()
    {

    }

    // 连接建立时触发
    public function onOpen(Server $server, Request $request)
    {
        // 在触发 WebSocket 连接建立事件之前，Laravel 应用初始化的生命周期已经结束，你可以在这里获取 Laravel 请求和会话数据
        // 调用 push 方法向客户端推送数据，fd 是客户端连接标识字段
        Log::info('WebSocket 连接建立');
        //这里是score表示laravels.php配置文件中的字段名
        app('swoole')->wsTable->set('fd:' . $request->fd, ['score' => $request->fd]);
        $server->push($request->fd, '往表里写入的数据key是'.'fd:' . $request->fd.' 字段score的值是'.$request->fd);
    }

    // 收到消息时触发
    public function onMessage(Server $server, Frame $frame)
    {
        // 调用 push 方法向客户端推送数据
        //$server->push($frame->fd, '服务器已接收到客户端信息并且回复了一条信息于 ' . date('Y-m-d H:i:s'));

        foreach (app('swoole')->wsTable as $key => $row) {
            if (strpos($key, 'fd:') === 0 && $server->exist($row['score'])) {
                Log::info('从表中获取的数据是: ' . $row['score']);
                // 调用 push 方法向客户端推送数据
                $server->push($frame->fd, '从表中获取的数据是 ' . $row['score']);
            }
        }
    }

    // 关闭连接时触发
    public function onClose(Server $server, $fd, $reactorId)
    {
        Log::info('WebSocket 连接关闭');
    }
}