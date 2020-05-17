<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Jenssegers\ImageHash\Implementations\AverageHash;
use Jenssegers\ImageHash\Implementations\BlockHash;
use Jenssegers\ImageHash\Implementations\PerceptualHash;

class ImageHashTest extends Controller
{
    public function index(){
        //$hasher = new ImageHash(new DifferenceHash());
        $hasher = new ImageHash(new AverageHash());
        //$hasher = new ImageHash(new PerceptualHash());

        $hash1 = $hasher->hash(storage_path('app/public/789.png'));
        $hash2 = $hasher->hash(storage_path('app/public/978.png'));

        $distance = $hasher->distance($hash1, $hash2);

        dd($hash1->toBits(),$hash2->toBits(),$distance);
    }
}
