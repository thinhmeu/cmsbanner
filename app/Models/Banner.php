<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Banner extends Model
{
    use HasFactory;
    public $timestamps = null;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'banner';
    }
    public static function getListBanner($id_website = 0, $id_position = 0, $keyword = ''){
        $queryRecentStatus = DB::raw("`status` && NOW() BETWEEN COALESCE(start_date, NOW()) AND COALESCE(end_date, NOW()) as recent_status");
        $tmp = self::select("id", "order", "title", "width", "height", "link", $queryRecentStatus)
        ->orderBy("recent_status", "desc")
        ->orderBy('order', 'asc')
        ->orderBy('id', 'desc');
        if ($keyword)
            $tmp->where("title", "like", "%$keyword%");
        if ($id_website)
            $tmp->where("id_website", $id_website);
        if ($id_position)
            $tmp->where("id_position", $id_position);
        return $tmp->paginate(10);
    }
}
