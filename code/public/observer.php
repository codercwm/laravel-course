<?php

//观察者接口类
interface Observer{
    public function update($event_info = null);
}

//观察者1
class Observer1 implements Observer{
    public function update($event_info = null) {
        echo '观察者1';
    }
}

//观察者2
class Observer2 implements Observer{
    public function update($event_info = null) {
        echo '观察者2';
    }
}

//事件
class Event{
    public $observer = [];
    //添加观察者
    public function add(Observer $observer){
        $this->observer[] = $observer;
    }

    //事件通知
    public function notify(){
        foreach ($this->observer as $observer){
            $observer->update();
        }
    }

    //触发事件
    public function trigger(){
        //通知观察者
        $this->notify();
    }
}

//服务类，去实现事件的创建和触发，不关心具体多少调用方需要监听
/*class DemoService{
    public function demo(){
        //创建一个事件
        $event = new Event();

        //执行事件，通知旁观者
        $event->trigger();
    }
}

//调用方，调用方仅仅需要知道服务类创建了哪些事件
class DoService {
    public function do(){
        //为事件添加旁观者
        $event = new Event();
        $event->add(new Observer1());
        $event->add(new Observer2());
    }
}*/

$event = new Event();
$event->add(new Observer1());
$event->add(new Observer2());

$event->trigger();

