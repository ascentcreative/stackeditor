<?php

namespace AscentCreative\StackEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Support\Str;


class Category extends Model
{
    use HasFactory;

    public $table = 'stackeditor_categories';

}

