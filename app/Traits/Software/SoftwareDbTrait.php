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

    protected $db;
    protected $connection;
    protected $Schema;

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
        // var_dump(__METHOD__);
        // $this->db_config = isset($config['db_config']) ? $config['db_config'] : $this->db_config;
    }
    public function setDbConfig($config)
    {
        // var_dump(__METHOD__);
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
        // var_dump(__METHOD__);
        if (empty($key)) return $this->db_config;
        return $this->db_config[$key];
    }
    /**
     * 检测 DB 配置
     */
    public function canDbConnect()
    {
        // var_dump(__METHOD__);
        if (empty($this->db_config['driver']) || empty($this->db_config['host']) || empty($this->db_config['port']) || empty($this->db_config['username']) || empty($this->db_config['password']) || empty($this->db_config['database'])) {
            return false;
        }
        return true;
    }
    // 连接 MySQL
    public function db_connect($slug)
    {
        // var_dump(__METHOD__);
        if (!$this->canDbConnect()) {
            $this->db_connect_status = 0;
        } else {
            try {
                app('config')->set('database.connections.' . $slug, [
                    'driver' => $this->db_config['driver'],
                    'host' => $this->db_config['host'],
                    'database' => $this->db_config['port'],
                    'username' => $this->db_config['username'],
                    'password' => $this->db_config['password'],
                    'prefix' => $this->db_config['prefix']
                ]);
                $this->connection = \Illuminate\Support\Facades\DB::connection($slug);

                $this->connection->select('show databases');

                $this->Schema = \Illuminate\Support\Facades\Schema::connection($slug);
            } catch (\Exception $e) {
                $this->db_connect_status = $e->getCode();
            }
            // 连接成功
            return true;
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
