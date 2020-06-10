<?php

namespace App\Models;

use TCG\Voyager\Traits\Resizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Resizable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}

