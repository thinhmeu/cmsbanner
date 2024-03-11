<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Banner_site extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'banner_site';
    }
    public static function getWebsiteWithCountPosition($listId = null){
        $listId = (array) $listId;
        $keyCache = __FUNCTION__.json_encode($listId);
        $data = Cache::get($keyCache);
        if (empty($data)){
            $queryGetCount = DB::raw("(SELECT COUNT(DISTINCT id_position) FROM banner WHERE `status` = 1 AND NOW() BETWEEN COALESCE(start_date, NOW()) AND COALESCE(end_date, NOW()) AND id_website = banner_site.id) AS count_position");
            $data = self::select("id", "title", $queryGetCount)
                ->where("type", "website");
            if ($listId){
                $data->whereIn("id", $listId);
            }
            $data = $data->orderBy('title', 'asc')
                ->get();
            Cache::put($keyCache, $data, 5);
        }
        return $data;
    }
    public static function getPositionWithCountBanner($id_website = null){
        $id_website = (array) $id_website;
        $keyCache = __FUNCTION__.json_encode($id_website);
        $data = Cache::get($keyCache);
        if (empty($data)){
            if ($id_website){
                $queryGetCount = DB::raw("(SELECT COUNT(id) FROM banner WHERE `status` = 1 AND NOW() BETWEEN COALESCE(start_date, NOW()) AND COALESCE(end_date, NOW()) AND id_position = banner_site.id AND id_website in (".implode(',', $id_website).")) AS count_banner");
            } else {
                $queryGetCount = DB::raw("(SELECT COUNT(id) FROM banner WHERE `status` = 1 AND NOW() BETWEEN COALESCE(start_date, NOW()) AND COALESCE(end_date, NOW()) AND id_position = banner_site.id) AS count_banner");
            }
            $data = self::select("id", "title", $queryGetCount)
                ->where("type", "position")
                ->orderBy('title', 'asc')
                ->get();
            Cache::put($keyCache, $data, 5);
        }
        return $data;
    }
}
