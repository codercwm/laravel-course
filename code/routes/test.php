<?php
use Illuminate\Http\Request;
Route::get('route-test',function(){
    dd(123);
});

Route::get('csrf',function(Request $request){
    dump($request->session()->token());
    dump($request->cookie('XSRF-TOKEN'));
    return view('csrf');
    //dd('get-csrf');
});

Route::post('csrf',function(){
    dd('post-csrf');
});


Route::get('middleware-test',function (){
    dd('hello');
});