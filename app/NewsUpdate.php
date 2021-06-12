<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsUpdate extends Model
{
    protected $fillable = [
        'id','title','body'
    ];
}
