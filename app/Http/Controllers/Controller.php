<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getSubClass()
    {
        $dir = substr(basename(get_class($this)), 0, -10);
        if (empty($dir)) return;
        return array_map(function ($item) use ($dir) {
            $item = pathinfo($item)['filename'];
            return __NAMESPACE__ . '\\' . $dir . '\\' . $item;
        }, glob(__DIR__ . '/' . $dir . '/' . "*.php"));
    }
}
