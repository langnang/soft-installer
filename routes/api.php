<?php

use App\Http\Controllers\SoftwareController;
use Illuminate\Http\Request;


$router->group(['prefix' => 'software'], function () use ($router) {
    $router->post('ftp_connect', function (Request $request) {
    });
    $router->post('db_connect', function (Request $request) {
    });
    $router->post('get_local_package', function (Request $request) {
        $token = $request->header('token');
        $config = json_decode(app('files')->get('storage/app/' . $token . '.json'), true);
        var_dump($config);
        $software = new SoftwareController($config);
        var_dump($software);
        return response()->json([
            'token' => $request->header('token'),
            'name' => 'Abigail',
            'state' => 'CA',
        ]);
    });
    $router->post('download_local_package', function (Request $request) {
    });
    $router->post('unzip_package', function (Request $request) {
    });
});
