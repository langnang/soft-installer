<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return view('home.index');
});
$router->group(['prefix' => 'software'], function () use ($router) {
    $router->get('/', function (Request $request) use ($router) {
        $softwares = app('software')->getSubClass();
        return view('soft.index', ["softwares" => $softwares]);
    });
    $router->get('/{software}', function ($software, Request $request) use ($router) {
        $software = app('software')->getSubClass($software);
        $package = $software->getPackage($request->version);
        // var_dump(class_exists('PharData'));
        // var_dump(\App\Http\Controllers\SoftwareController::get_zip_extension('1.1.17.10.30.-release.tar.gz'));
        // $config = \App\Http\Controllers\Soft\TypechoController::get_config($software);
        // $package = \App\Http\Controllers\SoftwareController::get_package($software, $request->version);
        return view('soft.install', [
            'request' => $request,
            'software' => $software,
            'package' => $package
        ]);
    });
    $router->post('/{software}', function ($software, Request $request) use ($router) {
        // $request['script_name'] = $software;
        $software = app('software')->getSubClass($software);
        $package = $software->getPackage($request->version);
        // $config = App\Http\Controllers\SoftwareController::get_config($soft);
        // $package = App\Http\Controllers\SoftwareController::get_package($soft, $request->version);
        // $controller = new \App\Http\Controllers\SoftwareController($request);
        // 连接FTP
        $ftp_connect_error_message = $software->ftp_connect($request);
        // var_dump($ftp_connect_error_message);
        // 连接MySQL
        $db_connect_error_message = $software->db_connect($request);
        // var_dump($db_connect_error_message);
        // $software->seeder_run();
        // return;
        // if ($ftp_connect_error_message === true) {
        // $request['zip_name'] = $soft . '-' . $request->version . '.' . pathinfo($package->package_url)['extension'];
        // $controller->download_package($request);

        // $controller->unzip_package($request);

        // $controller->ftp_upload_package($request);
        // }
        // var_dump($request->all());
        // // var_dump(app('zip'));
        // $zip = app('zip')::open(__DIR__ . '/../scripts/typecho/typecho.zip');
        // var_dump($zip);
        // // var_dump($zip->listFiles());
        // // $zip->extract('/../scripts/typecho/', true);
        // var_dump($zip->extract(__DIR__ . '/../scripts/typecho/v1.2'));

        // $controller->ftp_upload_package('/');
        // $ftp->copy(__DIR__ . '/../scripts/typecho/v1.2', '/',);
        // var_dump($config);
        // var_dump($package);
        return view('soft.install', [
            'request' => $request,
            'software' => $software,
            'package' => $package,
            'ftp_connect_error_message' => $ftp_connect_error_message,
            'db_connect_error_message' => $db_connect_error_message,
        ]);
    });
});

$router->get('/about-us', function () use ($router) {
    return view('home.about-us');
});

$router->get('/contact-us', function () use ($router) {
    return view('home.contact-us');
});
