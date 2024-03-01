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
    public static function getWebsiteWithCountPosition(){
        $keyCache = __FUNCTION__;
        $data = Cache::get($keyCache);
        if (empty($data)){
            $queryGetCount = DB::raw("(SELECT COUNT(DISTINCT id_position) FROM banner WHERE `status` = 1 AND NOW() BETWEEN COALESCE(start_date, NOW()) AND COALESCE(end_date, NOW()) AND id_website = banner_site.id) AS count_position");
            $data = self::select("id", "title", $queryGetCount)
                ->where("type", "website");
            if (!ADMIN){
                $listIdSite = User_site::getListIdSiteFromUserId(Auth::user()->id);
                $data->whereIn("id", $listIdSite);
            }

            $data = $data->orderBy('title', 'asc')
                ->get();
            Cache::put($keyCache, $data, 5);
        }
        return $data;
    }
    public static function getPositionWithCountBanner($id_website = 0){
        $keyCache = __FUNCTION__.$id_website;
        $data = Cache::get($keyCache);
        if (empty($data)){
            if ($id_website){
                $queryGetCount = DB::raw("(SELECT COUNT(id) FROM banner WHERE `status` = 1 AND NOW() BETWEEN COALESCE(start_date, NOW()) AND COALESCE(end_date, NOW()) AND id_position = banner_site.id AND id_website = $id_website) AS count_banner");
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
