<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SoftwareController extends Controller
{
    public $name;
    public $description;
    public $packages;
    public $package;
    public $root_path = "scripts";

    public static $config_file = "config.json";

    public static $controller_file = "controller.php";

    public static $scripts;

    public $script;
    public $github;
    public $request;
    public $local;
    public $ftp;
    public $db;
    public $app;
    public $connection;
    public $Schema;
    public $faker;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->db = app('db');
        $this->faker = app('Faker\Generator');
        $this->ftp = new \FtpClient\FtpClient();
        $this->local = new \League\Flysystem\Filesystem(new \League\Flysystem\Local\LocalFilesystemAdapter($this->root_path));
        // $this->request = $request;
    }
    // 获取继承的子类
    public function getSubClass($name = null)
    {
        $classes = parent::getSubClass();
        $result = [];
        foreach ($classes as  $class) {
            if (!$class instanceof \App\Http\Controllers\SoftwareController) {
                $class = new $class($this);
                if (!empty($name) && $class->name === $name) {
                    return $class;
                }
                $result[$class->name] = $class;
            }
        }
        return $result;
    }

    public function getCategorySubClass()
    {
        $classes = $this->getSubClass();
        $result = [];
        foreach ($classes as $class) {
            $category = $class->category;
            if (empty($category)) $category = 'unknown';
            if (!isset($result[$category])) $result[$category] = [];
            array_push($result[$category], $class);
        }
        return $result;
    }

    public function getPackage($version = null)
    {
        if (empty($version)) return;
        foreach ($this->packages as $package) {
            if ($package['version'] == $version) {
                $this->package = $package;
                return $package;
            }
        }
    }
    // public static function load_scripts()
    // {
    //     var_dump(__METHOD__);
    //     $adapter = new \League\Flysystem\Local\LocalFilesystemAdapter($this->root_path);
    //     $filesystem = new \League\Flysystem\Filesystem($adapter);
    //     $scripts = [];
    //     /** @var \League\Flysystem\StorageAttributes $item */
    //     foreach ($filesystem->listContents('') as $item) {
    //         $path = $item->path();
    //         if ($item instanceof \League\Flysystem\FileAttributes) {
    //             // handle the file
    //         } elseif ($item instanceof \League\Flysystem\DirectoryAttributes) {
    //             $configPath = $path . '/' . self::$config_file;
    //             $controllerPath = $path . '/' . self::$controller_file;
    //             if ($filesystem->fileExists($configPath)) {
    //                 $config = json_decode($filesystem->read($configPath));
    //                 $config->dirname = $path;
    //                 var_dump($config);
    //                 array_push($scripts, $config);
    //             }
    //             if ($filesystem->fileExists($configPath)) {
    //                 require_once __DIR__ . '/../../../' . $this->root_path . '/' . $controllerPath;
    //             }
    //             // handle the directory
    //         }
    //     }
    //     self::$scripts = $scripts;
    //     return $scripts;
    // }
    // public function get_scripts()
    // {
    //     $filesystem = new \League\Flysystem\Filesystem(new \League\Flysystem\Local\LocalFilesystemAdapter($this->root_path));
    //     $scripts = [];
    //     /** @var \League\Flysystem\StorageAttributes $item */
    //     foreach ($filesystem->listContents('') as $item) {
    //         $path = $item->path();
    //         if ($item instanceof \League\Flysystem\FileAttributes) {
    //             // handle the file
    //         } elseif ($item instanceof \League\Flysystem\DirectoryAttributes) {
    //             $configPath = $path . '/' . self::$config_file;
    //             if ($filesystem->fileExists($configPath)) {
    //                 $config = json_decode($filesystem->read($configPath));
    //                 $config->dirname = $path;
    //                 array_push($scripts, $config);
    //             }
    //             // handle the directory
    //         }
    //     }
    //     return $scripts;
    // }

    // public function get_config($name)
    // {
    //     $filesystem = new \League\Flysystem\Filesystem(new \League\Flysystem\Local\LocalFilesystemAdapter($this->root_path));
    //     if ($filesystem->fileExists($name . '/' . self::$config_file)) {
    //         $config = json_decode($filesystem->read($name . '/' . self::$config_file));
    //         return $config;
    //     } else {
    //         return;
    //     }
    // }
    // public function get_package($name, $version)
    // {
    //     $config = self::get_config($name);
    //     // 默认选中唯一安装包
    //     if (count($config->packages) == 1) return $config->packages[0];
    //     else if (!empty($version)) {
    //         foreach ($config->packages as $item) {
    //             if ($item->version === $version) {
    //                 return $item;
    //             }
    //         }
    //     }
    //     return;
    // }
    // 连接 FTP
    public function ftp_connect(Request $request)
    {
        if (!empty($request->ftp_host) && !empty($request->ftp_port) && !empty($request->ftp_username) && !empty($request->ftp_password)) {
            try {
                $this->ftp->connect($request->ftp_host, false, $request->ftp_port);
                $this->ftp->login($request->ftp_username, $request->ftp_password);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            // 连接成功
            return true;
        }
        return false;
    }
    // 连接 MySQL
    public function db_connect(Request $request)
    {
        if (!empty($request->db_host) && !empty($request->db_username) && !empty($request->db_password) && !empty($request->db_database) && !empty($request->db_port)) {
            try {
                app('config')->set('database.connections.' . $this->name, [
                    'driver' => $request->db_driver,
                    'host' => $request->db_host,
                    'database' => $request->db_database,
                    'username' => $request->db_username,
                    'password' => $request->db_password,
                    'prefix' => $request->db_table_prefix
                ]);
                $this->connection = DB::connection($this->name);

                $this->connection->select('show databases');

                $this->Schema = Schema::connection($this->name);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            // 连接成功
            return true;
        }
        return false;
    }
    // 检测存在本地安装包
    public function get_local_package($callback = null)
    {
        foreach ($this->package['urls'] as $url) {
            $extension = $this->get_zip_extension($url);
            $file = $this->name . '-' . $this->package['version'] . '.' . $extension;
            $path = $this->name . '/' . $file;
            if ($this->local->fileExists($path)) {
                $this->package['local_file'] = $path;
                return $path;
            }
        }
        return;
    }
    // 下载应用包
    public function download_package($url, $callback = null)
    {
        $extension = $this->get_zip_extension($url);
        $path = $this->name . '/' . $this->name . '-' . $this->package['version'] . '.' . $extension;
        $file = fopen($url, 'r');
        if (empty($file)) {
            throw new Exception("Unable to open file!");
        }
        $this->local->writeStream($path, $file);
        $this->package['local_file'] = $path;
        return $path;
    }
    // 解压缩应用包
    public function unzip_package()
    {
        $extension = $this->get_zip_extension($this->package['local_file']);
        $this->package['local_dir'] = $this->name . '/' . $this->name . '-' . $this->package['version'];
        $file_path = __DIR__ . '/../../../' . $this->root_path . '/' . $this->package['local_file'];
        $dir_path = __DIR__ . '/../../../' . $this->root_path . '/' . $this->package['local_dir'];
        if (in_array($extension, ['tar.gz'])) {
            $zip = new \PharData($file_path);
            //解压后的路径 数组或者字符串指定解压解压的文件，null为全部解压  是否覆盖
            $zip->extractTo($dir_path, null, true);
        } else {
            $zip = app('zip')::open($file_path);
            $zip->extract($dir_path, true);
        }
        return $this->package['local_dir'];
    }
    // 上传应用
    public function ftp_upload_package($target_directory)
    {
        // var_dump($this->package);
        // $filename = pathinfo($request->zip_name)['filename'];
        $this->ftp->putAll(__DIR__ . '/../../../' . $this->root_path . '/' . $this->package['local_dir'] . '/' . $this->package['root_path'], $target_directory);
    }
    public function install()
    {
    }
    // 获取压缩包文件格式
    public static function get_zip_extension($path)
    {
        $types = ['7z', 'rar', 'zip', 'tar', 'tar.gz'];
        foreach ($types as $type) {
            if (substr($path, -strlen($type)) === $type) {
                return $type;
                break;
            }
        }
        return false;
    }

    public function migration_up()
    {
    }
    public function migration_down()
    {
    }
    public function factory_definition()
    {
    }
    public function factory_unverified()
    {
    }
    public function seeder_run()
    {
    }
}
