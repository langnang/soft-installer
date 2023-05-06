<?php

namespace App\Traits\Software;

trait SoftwareDbTrait
{
    public $tables = [
        [
            // 表名称
            "name" => "",
            // 表字段
            "columns" => [],
            // 表填充数据
            "seeder" => [],
        ]
    ];

    protected $Schema;
    private $db;
    private $db_slug;
    private $db_config = [
        "driver" => 'mysql',
        "host" => null,
        "port" => 3306,
        "username" => null,
        "password" => null,
        "database" => null,
        "prefix" => null,
    ];
    private $db_connect_status;

    public $on_db_connect;
    // 检测数据表
    public $on_table_exists;
    // 创建数据表
    public $on_table_create;
    // 删除数据表
    public $on_table_drop;
    public $on_table_migration;
    // 迁移数据
    public $on_table_seeder;

    public function software_db_construct($config = [])
    {
        $this->db_config = isset($config['db_config']) ? $config['db_config'] : $this->db_config;
    }
    public function setDbConfig($config)
    {
        $this->db_config['driver'] = isset($config['driver']) ? $config['driver'] : $this->db_config['driver'];
        $this->db_config['host'] = isset($config['host']) ? $config['host'] : $this->db_config['host'];
        $this->db_config['port'] = isset($config['port']) ? $config['port'] : $this->db_config['port'];
        $this->db_config['username'] = isset($config['username']) ? $config['username'] : $this->db_config['username'];
        $this->db_config['password'] = isset($config['password']) ? $config['password'] : $this->db_config['password'];
        $this->db_config['database'] = isset($config['database']) ? $config['database'] : $this->db_config['database'];
        $this->db_config['prefix'] = isset($config['prefix']) ? $config['prefix'] : $this->db_config['prefix'];
    }
    public function getDbConfig($key = null)
    {
        if (empty($key)) return $this->db_config;
        return $this->db_config[$key];
    }
    /**
     * 检测 DB 配置
     */
    public function canDbConnect()
    {
        if (empty($this->db_config['driver']) || empty($this->db_config['host']) || empty($this->db_config['port']) || empty($this->db_config['username']) || empty($this->db_config['password']) || empty($this->db_config['database'])) {
            return false;
        }
        return true;
    }
    // 连接 MySQL
    public function db_connect($slug)
    {
        // 标识不一致 或 状态码非TRUE
        if ($this->db_slug !== $slug || $this->db_connect_status !== true) {
            $this->db_slug = $slug;
            if (!$this->canDbConnect()) {
                $this->db_connect_status = false;
            } else {
                try {

                    $this->db = app('db');
                    $this->db->setApplication(app());
                    app('config')->set('database.connections.' . $slug, [
                        'driver' => $this->db_config['driver'],
                        'host' => $this->db_config['host'],
                        'port' => $this->db_config['port'],
                        'database' => $this->db_config['database'],
                        'username' => $this->db_config['username'],
                        'password' => $this->db_config['password'],
                        'charset'  => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix' => $this->db_config['prefix'],
                    ]);
                    $this->db = $this->db->connection($slug);
                    $this->db->select('show databases');
                    $this->Schema = \Illuminate\Support\Facades\Schema::connection($slug);
                    $this->db_connect_status = true;
                } catch (\Exception $e) {
                    $this->db_connect_status = $e->getCode();
                }
            }
        }
        if ($this->on_db_connect) {
            $return = call_user_func($this->on_db_connect, $this->db_connect_status, $this->db_config, $this);
            unset($return);
        }
        return $this->db_connect_status;
    }

    // 数据库迁移
    public function table_create()
    {
    }
    public function table_exists()
    {
    }
    public function table_drop()
    {
    }
    public function migration_up()
    {
    }
    // 数据库删除
    public function migration_down()
    {
    }
    // 数据填充
    public function factory_definition($table)
    {
    }
    public function factory_unverified($table)
    {
    }
    public function seeder_run()
    {
    }
}
