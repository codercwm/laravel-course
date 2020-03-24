<?php
/**
 * 探索ioc容器和facade门面的实现原理
 * Author: cwm
 * Date: 2019-8-28
 */


function dd($data){
    echo '<pre>';
    print_r($data);
    exit;
}

function make($concrete){
    //获取反射类
    $reflector = new \ReflectionClass($concrete);

    //获取构造函数
    $constructor = $reflector->getConstructor();
    //    dump($concrete,$constructor);

    if(is_null($constructor)){
        $instance = $reflector->newInstance();
    }else{
        //获取构造函数的参数
        $dependencies = $constructor->getParameters();
        //        dump($concrete,$dependencies);

        //根据参数返回实例
        $instances = getDependencies($dependencies);

        //创建实例
        $instance = $reflector->newInstanceArgs($instances);
    }

    return $instance;
}

function getDependencies($paramters){
    $dependecies = [];

    foreach ($paramters as $paramter){
        $dependecies[] = make($paramter->getClass()->name);
    }

    return $dependecies;
}

interface Log{
    public function write();
}

class FileLog implements Log
{
    public function __construct() { }

    public function write(){
        echo 'file log write...';
    }
}

class DatabaseLog implements Log{
    public function write() {
        echo 'database log write...';
    }
}

class User{
    protected $log;

    public function __construct(Log $log) {
        $this->log = $log;
    }

    public function login($param1=0,$param2=0){
        echo 'login success...';
        $this->log->write();
        echo $param1;
        echo $param2;
    }
}

//$user = make(User::class);
//$user->login();

class Ioc{
    public $binding = [];

    public function bind($abstract,$concrete){
        $this->binding[$abstract]['concrete'] = function ($ioc) use ($concrete) {
            return $ioc->build($concrete);
        };
    }

    public function make($abstract){
        //根据key获取binding的值
        $concrete = $this->binding[$abstract]['concrete'];
        return $concrete($this);
    }

    //创建对象
    public function build($concrete){
        //获取反射Contracts
        $reflector = new ReflectionClass($concrete);
        //获取构造函数
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)){//如果class中没有定义构造函数，这里将会是null
            return $reflector->newInstance();
        }else{
            $dependencies = $constructor->getParameters();
            $instances = $this->getDependencies($dependencies);
            return $reflector->newInstanceArgs($instances);
        }
    }

    //获取参数的依赖
    protected function getDependencies($paramters){
        $dependencies = [];
        foreach($paramters as $paramter){
            $dependencies[] = $this->make($paramter->getClass()->name);
        }
        return $dependencies;
    }
}

/*$ioc = new Ioc();
$ioc->bind('Log',DatabaseLog::class);
$ioc->bind('User',User::class);
$user = $ioc->make('User');
$user->login();*/

class UserFacade{
    protected static $ioc;

    public static function setFacadeIoc($ioc){
        static::$ioc = $ioc;
    }

    //返回user在Ioc中bind的key
    protected static function getFacadeAccessor(){
        return 'User';
    }

    public static function __callStatic($method, $args) {
        $instance = static::$ioc->make(static::getFacadeAccessor());
        return $instance->$method(...$args);
    }
}

$ioc = new Ioc();
$ioc->bind('Log','DatabaseLog');
$ioc->bind('User','User');
UserFacade::setFacadeIoc($ioc);
UserFacade::login(12,'啊');
