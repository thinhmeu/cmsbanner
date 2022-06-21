<?php

namespace App\Models;

use Illuminate\Cache\Events\CacheHit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;
use App\Models\Post;
use Cache;

class Category extends Model
{
    use HasFactory;

    public static $_tree = [];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'category';
    }

    static function getTree(){
        self::_getTree();
        return self::$_tree;
    }

    private static function _getTree($parent_id = 0, $prefix_title = ''){
        $listChild = parent::where('parent_id', $parent_id)->withCount('Post')->get();
        if (!empty($listChild)) foreach ($listChild as $item) {
            self::$_tree[$item->id] = [
                'id' => $item->id,
                'title' => $prefix_title.$item->title,
                'parent_id' => $item->parent_id,
                'slug' => $item->slug,
                'count' => self::CountPostchildren($item->id),
            ];
            self::_getTree($item->id, $prefix_title.'---');
        }
    }

    public static function allCate(){
        $keyCache = "allCate";
//        $data = Cache::get($keyCache);
        $data = '';
        if (empty($data)){
            $data = self::where('status', 1)->get();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }

    public static function getOneParent($oneItem){
        $allCate = self::allCate();
        foreach ($allCate as $item){
            if ($item->id == $oneItem->parent_id)
                return $item;
        }

        return $oneItem;
    }

    public static function getBySlug($slug){
        $keyCache = "category-getBySlug-$slug";
//        $data = Cache::get($keyCache);
        $data = '';
        if (empty($data)){
            $data = self::where('slug', $slug)->first();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }

    public static function getById($id){
        $keyCache = "category-getById-$id";
//        $data = Cache::get($keyCache);
        $data = '';
        if (empty($data)){
            $data = self::where('id', $id)->first();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }

    public function Post(){
        return $this->belongsToMany(Post::class, 'post_category', 'category_id', 'post_id');
    }

    public static function CountPostchildren($id){
        $countPost = 0;
        $listCateChild = self::ChildRecursive($id);
        $list_id_category = [];

        foreach($listCateChild as $item){
            $list_id_category[] = $item->id;
        }
        $countPost = Post::whereHas('Category', function($query) use ($list_id_category){
            $query->whereIn('category_id', $list_id_category);
        })->get()->count();

        return $countPost;
    }

    private static function ChildRecursive($id){
        $ls = [];
        $cate = parent::with('children')->where('id', $id)->first();
        $ls[] = $cate;
        if($cate->children->count() == 0) return $ls;
        foreach($cate->children as $item){
            $ls = array_merge($ls,self::ChildRecursive($item->id));
        }
        return $ls;
    }

    public function children(){
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id' );
    }
    public function childrenRecursive() {
        return $this->children()->with('childrenRecursive');
    }
    public function parentRecursive() {
        return $this->parent()->with('parentRecursive');
    }

}