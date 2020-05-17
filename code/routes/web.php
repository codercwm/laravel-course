<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', 'Home\IndexController@test');

Route::get('test-swoole-task', function () {

    $task = new \App\Jobs\TestSwooleTask('测试任务');
    //$task->delay(3); // 延迟3秒投递
    //$task->setTries(3); // 出现异常时，累计尝试3次
    // 进行任务投递
    $res = Hhxsv5\LaravelS\Swoole\Task\Task::deliver($task);
    var_dump($res);// 判断是否投递成功
});

Route::get('test-swoole-get', function (\Illuminate\Http\Request $request) {
    dump($request->all());
});

Route::get('test-swoole-event', function () {
    $event = new \App\Events\TestTaskEvent('event data');
    // $event->delay(10); // 延迟10秒触发
    // $event->setTries(3); // 出现异常时，累计尝试3次
    //通过fire触发，此操作是异步的，触发后立即返回，由Task进程继续处理监听器中的handle逻辑
    $success = \Hhxsv5\LaravelS\Swoole\Task\Event::fire($event);
    var_dump($success);
});

Route::resource('users', 'Home\UsersController');

Route::get('log','Home\UsersController@log');

Route::get('avatar','Home\IndexController@avatar');


Route::middleware('auth:api')->group(function($router){
    $router->get('/jwtTest','Home\UsersController@jwtTest');
});

Route::get('image-hash-test','Home\ImageHashTest@index');
