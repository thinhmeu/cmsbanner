<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Page extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'page';
    }

    public static function getBySlug($slug){
        $keyCache = "page-getBySlug-$slug";
//        $data = Cache::get($keyCache);
        $data = '';
        if (empty($data)){
            $data = self::where('slug', $slug)->first();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }
}
