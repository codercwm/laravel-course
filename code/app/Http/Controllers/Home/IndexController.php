<?php

namespace App\Http\Controllers\Home;

use App\Facades\Char;
use App\Http\Controllers\Controller;
use App\Models\User;
use Codercwm\QueueExport\QueueExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function test(Request $request){
        /*Cache::put('sdklfjsaklf',0,10000);
        for($i=0;$i<1000;$i++){
            Cache::increment('sdklfjsaklf',1);
        }
        dd(Cache::get('sdklfjsaklf'));*/
        $queue_export = new QueueExport();
        $params = $request->all();
        $cid = 'sakldfj2';
        $filename = 'User'.date('Y_m_d_H_i_s').'_'.rand(1000,9999);
        $headers = [
            '编号',
            '名称',
            '可以使用字典',
            '可以使用函数',
            '单元格转换成字符串',
            '使用``符号包围可以原样输出',
            '双问号??可以指定默认值',
            '这些功能可以同时使用',
        ];
        $fields = [
            'id',
            'name',
            'id|1:原来的值是1;9:原来的值是9;',
            "sprintf('%s%s%s',   id,  '-',  name )",
            'id|tabs',
            '`name`',
            'name??name为空时默认输出',
            "sprintf('%s%s%s',   `id`,  '-',  name??啊 )|1:一;2:二;3:三",
        ];
        $res = $queue_export->setCid($cid)
            ->setModel(User::class,$params)
            ->setFilename($filename)
            ->setHeadersFields($headers,$fields)
            ->setExportType('queue')
            ->export();

        return $res;
    }
}
