<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'business_name',
        'area',
        'city',
        'mobile_no',
        'category',
        'sub_category',
        'address',
        'is_duplicate',
        'duplicate_group_id',
    ];
}