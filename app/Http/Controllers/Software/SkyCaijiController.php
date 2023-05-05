<?php

namespace App\Http\Controllers\Software;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;

class SkyCaijiController extends \App\Http\Controllers\SoftwareController
{
    public $name = '蓝天采集器';
    public $slug = 'sky-caiji';
    public $category = "Spider";
}
