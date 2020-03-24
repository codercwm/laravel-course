<?php
/**
 * 探讨中间件的实现原理
 * Author: cwm
 * Date: 2019-8-28
 */


function dd($data){
    echo '<pre>';
    print_r($data);
    exit;
}


interface Milldeware{
    public static function handle(Closure $next);
}

class VerifyCsrfToken implements Milldeware{
    public static function handle(Closure $next) {
        echo '验证 csrf token <br>';
        $next();
    }
}

class VerifyAuth implements Milldeware{
    public static function handle(Closure $next) {
        echo '验证是否登录 <br>';
        $next();
    }
}

class SetCookie implements Milldeware{
    public static function handle(Closure $next) {
        $next();
        echo '设置cookie信息 <br>';
    }
}

/*function call_milldeware(){
    SetCookie::handle(function(){
        VerifyAuth::handle(function (){
            VerifyCsrfToken::handle(function (){
                echo '当前要执行的程序 <br>';
            });
        });
    });
}*/

//call_milldeware();

$handle = function (){
    echo '当前要执行的程序 <br>';
};

$pipe_arr = [
    'VerifyCsrfToken',
    'VerifyAuth',
    'SetCookie',
];

$callback = array_reduce($pipe_arr,function($stack,$pipe){
    return function () use($stack,$pipe){
        return $pipe::handle($stack);
    };
},$handle);
//dd($callback);
call_user_func($callback);

