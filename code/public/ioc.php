<?php

function dd($data){
    echo '<pre>';
    print_r($data);
    exit;
}

interface Log{
    public function write();
}

class FileLog implements Log{
    /*public function __construct(DatabaseLog $log)
    {
        $this->log = $log;
    }*/
    public function write() {
        echo 'file log write...';
    }
}

class DatabaseLog implements Log{
    public function write() {
        echo 'database log write...';
    }
}

/*class User {
    protected $fileLog;

    public function __construct() {
        $this->fileLog = new FileLog();
    }

    public function login(){
        echo 'login success...';
        $this->fileLog->write();
    }
}*/

class User
{
    protected $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function login()
    {
        // 登录成功，记录登录日志
        echo 'login success...';
        $this->log->write();
    }

}

/*$user = new User(new DatabaseLog());
$user->login();*/

//获取反射
/*$reflector = new ReflectionClass(User::class);

//获取构造函数
$constructor = $reflector->getConstructor();

//获取构造函数的参数
$dependencies = $constructor->getParameters();

//创建user对象
if(!empty($dependencies)){
    $user = $reflector->newInstanceArgs($dependencies=[]);//这样回报错
}else{
    $user = $reflector->newInstance();
}
$user->login();*/

function make($concrete){
    //获取反射信息
    $reflector = new ReflectionClass($concrete);

    //获取构造函数
    $constructor = $reflector->getConstructor();
    if(is_null($constructor)){
        //如果没有构造函数直接返回实例
        return $reflector->newInstance();
    }else{
        //获取构造函数参数
        $dependecies = $constructor->getParameters();

        $instances = getDependencies($dependecies);
        return $reflector->newInstanceArgs($instances);
    }
}

function getDependencies($paramters){
    $dependecies = [];
    foreach($paramters as $paramter){
        $dependecies[] = make($paramter->getClass()->name);
    }
    return $dependecies;
}
/*
$user = make(User::class);
dd($user);
$user->login();*/


class Ioc{
    public $binding = [];

    public function bind($abstract,$concrete){
        $this->binding[$abstract]['concrete'] = function($ioc)use($concrete){
            return $ioc->build($concrete);
        };
    }

    public function make($abstract){
        $concrete = $this->binding[$abstract]['concrete'];
        return $concrete($this);
    }

    public function build($concrete){
        $reflector = new ReflectionClass($concrete);
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)){
            return $reflector->newInstance();
        }else{
            $dependecies = $constructor->getParameters();
            $instances = $this->getDependencies($dependecies);
            return $reflector->newInstanceArgs($instances);
        }
    }

    protected function getDependencies($paramters){
        $dependencies = [];
        foreach($paramters as $paramter){
            $dependencies[] = $this->make($paramter->getClass()->name);
        }
        return $dependencies;
    }
}

$ioc = new Ioc();
$ioc->bind('User','User');
$ioc->bind('Log','DatabaseLog');
$user = $ioc->make('User');
$user->login();