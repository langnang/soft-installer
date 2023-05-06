<?php

namespace App\Traits\Software;

trait SoftwareFtpTrait
{
    private $ftp;
    private $ftp_slug;
    private $ftp_config = [
        "host" => null,
        "port" => 21,
        "username" => null,
        "password" => null,
        "path" => '/'
    ];
    private $ftp_connect_status;

    public $on_ftp_connect;

    public $on_ftp_upload;

    public function software_ftp_construct($config = [])
    {
        // $this->ftp = new \FtpClient\FtpClient();
        $this->ftp_config = isset($config['ftp_config']) ? $config['ftp_config'] : $this->ftp_config;
    }
    public function setFtpConfig($config)
    {
        $this->ftp_config['host'] = isset($config['host']) ? $config['host'] : $this->ftp_config['host'];
        $this->ftp_config['port'] = isset($config['port']) ? $config['port'] : $this->ftp_config['port'];
        $this->ftp_config['username'] = isset($config['username']) ? $config['username'] : $this->ftp_config['username'];
        $this->ftp_config['password'] = isset($config['password']) ? $config['password'] : $this->ftp_config['password'];
        $this->ftp_config['path'] = isset($config['path']) ? $config['path'] : $this->ftp_config['path'];
    }
    public function getFtpConfig($key = null)
    {
        if (empty($key)) return $this->ftp_config;
        return $this->ftp_config[$key];
    }
    /**
     * 检测 FTP 配置
     */
    public function canFtpConnect()
    {
        if (empty($this->ftp_config['host']) || empty($this->ftp_config['port']) || empty($this->ftp_config['username']) || empty($this->ftp_config['password'])) {
            return false;
        }
        return true;
    }

    // 连接 FTP
    public function ftp_connect($slug)
    {
        // 标识不一致 或 状态码非TRUE
        if ($this->ftp_slug !== $slug || $this->ftp_connect_status !== true) {
            $this->ftp_slug = $slug;
            if (!$this->canFtpConnect()) {
                $this->ftp_connect_status = false;
            } else {
                try {
                    $this->ftp = app('ftp');
                    $this->ftp->connect($this->ftp_config['host'], false, $this->ftp_config['port']);
                    $this->ftp->login($this->ftp_config['username'], $this->ftp_config['password']);
                    $this->ftp_connect_status = true;
                } catch (\Exception $e) {
                    $this->ftp_connect_status = $e->getCode();
                }
            }
        }
        if ($this->on_ftp_connect) {
            $return = call_user_func($this->on_ftp_connect, $this->ftp_connect_status, $this->ftp_config, $this);
            unset($return);
        }
        return $this->ftp_connect_status;
    }

    /**
     * 上传应用
     * @var string $local_directory 本地解压目录
     * @var string $directory 相对目录
     */
    public function ftp_upload($local_directory, $directory = "/")
    {
        $status = null;
        try {
            app('ftp')->putAll(__DIR__ . '/../../../' . $local_directory . $directory, $this->ftp_config['path']);
            // 上传完成后删除本地解压目录
            app('files')->deleteDirectory($local_directory);
        } catch (\Exception $e) {
            $status = $e->getCode();
        }

        if ($this->on_ftp_upload) {
            $return = call_user_func($this->on_ftp_upload, $status, $this->ftp_config, $this);
            unset($return);
        }
    }
}
