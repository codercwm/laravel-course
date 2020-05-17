<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject
{
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public static function qExExport($params=[]){
        $query = self::query();
        if(!empty($params['name'])){
            $query->where('name','like','%'.$params['name'].'%');
        }
        return $query;
    }
}
