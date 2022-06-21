<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Support\Facades\Redirect;

class SiteRedirect extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'site_redirect';
    }

    public static function getAll(){
        $keyCache = "redirect-all";
        //$data = Cache::get($keyCache);
        if (empty($data)){
            $data = self::all()->toArray();
            Cache::put($keyCache, $data, 3600);
        }
        return $data;
    }
}
