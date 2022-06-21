<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;
use App\Models\Category;

class Post extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'post';
    }

    public static function getBySlug($slug){
        $keyCache = "post-getbyslug-$slug";
//        $data = Cache::get($keyCache);
        $data = '';
        if (empty($data)){
            $data = self::where('slug', $slug)->first();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }

    public static function getById($id){
        $keyCache = "post-getById-$id";
//        $data = Cache::get($keyCache);
        $data = '';
        if (empty($data)){
            $data = self::where('id', $id)->first();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }

    public function Category(){
        return $this->belongsToMany(Category::class, 'post_category', 'post_id', 'category_id');
    }
}

