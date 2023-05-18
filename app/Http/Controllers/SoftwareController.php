<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

use function PHPUnit\Framework\returnSelf;

class SoftwareController extends Controller
{
    use \App\Traits\Software\SoftwareTrait;
    use \App\Traits\Software\SoftwarePackageTrait;
    use \App\Traits\Software\SoftwareFtpTrait;
    use \App\Traits\Software\SoftwareDbTrait;

    public $root_path = "scripts";

    public static $config_file = "config.json";

    public static $controller_file = "controller.php";

    public static $scripts;

    public $script;

    private $local;
    private $request;

    private $faker;
    // 校验码
    private $token;

    public $on_start;
    public $on_generate_config_file;
    public $on_end;
    public $config;
    public $app;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($config = [], $mini = false)
    {
        // 绑定到 Application
        if ($config instanceof \Laravel\Lumen\Application) {
            $this->app = $config;
            return;
        }
        $this->setConfig($config);
        $this->software_construct($config, $mini);
        if ($mini) return;
        $this->software_package_construct($config);
        $this->software_ftp_construct($config);
        $this->software_db_construct($config);

        // \App\Traits\Software\SoftwareFtpTrait::__construct();
        // if ($config instanceof \Laravel\Lumen\Application) $this->app = $config;
        // if ($config instanceof \Illuminate\Http\Request) $this->request = $config;
        // $this->db = app('db');
        // $this->faker = app('Faker\Generator');
        // $this->local = new \League\Flysystem\Filesystem(new \League\Flysystem\Local\LocalFilesystemAdapter($this->root_path));
    }
    public function setConfig($config)
    {
        $config['files'] = isset($config['files']) ? $config['files'] : [];
        $config['database'] = isset($config['database']) ? $config['database'] : [];
        $this->config = $config;
    }
    public function setToken()
    {
        $this->token = md5(json_encode([
            "ftp_config" => $this->ftp_config,
            "db_config" => $this->db_config,
        ]));
    }
    public function getToken()
    {
        return $this->token;
    }
    public function has($key)
    {
        switch ($key) {
            case 'ftp':
                return !empty($this->packages);
            case 'db':
                return !empty($this->package['database']) || !empty($this->config['database']);
            case 'files':
                return !empty($this->config['files']) || !empty($this->package['files']);
            default:
                break;
        }
    }
    // 创建配置文件
    public function generate_config_files()
    {
        $files = array_merge($this->config['files'], $this->package['files']);
        var_dump($files);
        foreach ($files as $file) {
            $path = $this->package['local_dir'] . '/' . $file['path'];
            $content = str_replace([
                '{{ftp.host}}',
                '{{ftp.port}}',
                '{{ftp.username}}',
                '{{ftp.password}}',
                '{{ftp.path}}',
                '{{db.driver}}',
                '{{db.host}}',
                '{{db.port}}',
                '{{db.username}}',
                '{{db.password}}',
                '{{db.database}}',
                '{{db.prefix}}',
            ], [
                $this->ftp_config['host'],
                $this->ftp_config['port'],
                $this->ftp_config['username'],
                $this->ftp_config['password'],
                $this->ftp_config['path'],
                $this->db_config['driver'],
                $this->db_config['host'],
                $this->db_config['port'],
                $this->db_config['username'],
                $this->db_config['password'],
                $this->db_config['database'],
                $this->db_config['prefix'],
            ], $file['content']);
            if ($this->on_generate_config_file) {
                $result = call_user_func($this->on_generate_config_file, $content, $path, $this->package, $this);
                if (!empty($result)) {
                    $content = $result;
                }
                unset($result);
            }
            app('files')->put($path, $content);
        }
    }
    public function install()
    {
        // set_time_limit(0); //设置程序执行时间
        // ignore_user_abort(true); //设置断开连接继续执行
        // header('X-Accel-Buffering: no'); //关闭buffer
        // ob_start(); //打开输出缓冲控制
        if ($this->on_start) {
            $return = call_user_func($this->on_start, $this);
            unset($return);
        }
        // 链接 FTP
        if (!$this->ftp_connect($this->getSlug())) return;

        // 存在则链接 DB
        if ($this->has('db')) {
            if (!$this->db_connect($this->getSlug())) return;
        }

        $this->setToken();

        // 应用包
        $local_package = $this->get_local_package($this->getSlug());
        if (empty($local_package)) {
            $local_package = $this->download_package($this->getSlug());
        }
        // 解压应用包
        $directory_package = $this->unzip_package($this->getSlug(), $this->token, $local_package);

        $this->generate_config_files();

        $this->ftp_upload($directory_package, isset($this->package['directory']) ? $this->package['directory'] : '/');
        foreach (array_keys($this->tables) as $table) {
            $hasTable = $this->table_exists($table);
            if ($hasTable) continue;

            if ($this->table_create($table)) {
                $this->seeder_run($table);
            }
        }
    }
}
