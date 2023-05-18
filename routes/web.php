<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\SoftwareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
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
        $categories = app('software')->getCategorySubClass();
        return view('soft.index', ["categories" => $categories]);
    });
    $router->get('/{software}', function ($software, Request $request) use ($router) {
        $software = app('software')->getSubClass($software);
        // 远程配置文件不存在
        if (!($software instanceof SoftwareController)) return redirect('software');
        $package = $software->getPackage($request->version);
        $view = 'soft.install';
        // 自定义视图
        if (View::exists('soft.' . $software->getSlug())) $view = 'soft.' . $software->name;
        return view($view, [
            'request' => $request,
            'software' => $software,
            'package' => $package
        ]);
    });
    $router->post('/{software}', function ($software, Request $request) use ($router) {
        $software = app('software')->getSubClass($software);
        $package = $software->getPackage($request->version);
        $software->setFtpConfig([
            'host' => $request->input('ftp_host'),
            'port' => $request->input('ftp_port', 21),
            'username' => $request->input('ftp_username'),
            'password' => $request->input('ftp_password'),
            'path' => $request->input('ftp_dir_path'),
        ]);
        // 连接FTP
        $ftp_connect_status = $software->ftp_connect($software->getSlug());
        $db_connect_status = null;
        if ($software->has('db')) {
            $software->setDbConfig([
                'driver' => $request->input('db_driver', 'mysql'),
                'host' => $request->input('db_host'),
                'port' => $request->input('db_port', 3306),
                'username' => $request->input('db_username'),
                'password' => $request->input('db_password'),
                'database' => $request->input('db_database'),
                'prefix' => $request->input('db_table_prefix'),
            ]);
            // 连接MySQL
            $db_connect_status = $software->db_connect($software->getSlug());
        }
        $software->setToken();
        app('files')->put('storage/app/' . $software->getToken() . '.json', json_encode($software));
        $view = 'soft.install';
        if (View::exists('soft.' . $software->getSlug())) $view = 'soft.' . $software->name;
        return view($view, [
            'request' => $request,
            'software' => $software,
            'package' => $package,
            'ftp_connect_status' => $ftp_connect_status,
            'db_connect_status' => $db_connect_status,
        ]);
    });
});

$router->get('/about-us', function () use ($router) {
    return view('home.about-us');
});

$router->get('/contact-us', function () use ($router) {
    return view('home.contact-us');
});
