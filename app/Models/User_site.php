<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

class User_site extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "site_id"];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'user_site';
    }
    static function getListIdSiteFromUserId($user_id){
        if (ADMIN)
            return null;
        return self::where("user_id", $user_id)->get()->pluck("site_id")->toArray();
    }
    static function checkPermission($user_id, $website_id){
        if (!ADMIN && $website_id){
            $listIdSite = static::getListIdSiteFromUserId($user_id);
            if (!in_array($website_id, $listIdSite))
                return Redirect::route("banner.list");
        }
        return false;
    }
}
