<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner_numsofshow extends Model
{
    use HasFactory;
    public $timestamps = null;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'banner_numsofshow';
    }
}
