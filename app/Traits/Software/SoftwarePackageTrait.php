<?php

namespace App\Traits\Software;

trait SoftwarePackageTrait
{
    public $packages = [];

    public $package;

    public $on_local_package;
    public $on_download_package;
    public $on_unzip_package;
    public $on_generate_package_files;
    public $on_upload_package;

    public function software_package_construct($config = [])
    {
        $this->packages = isset($config['packages']) ? $config['packages'] : $this->packages;
    }
    public function setPackage($package)
    {
        $package['local_file'] = isset($package['local_file']) ? $package['local_file'] : null;
        $package['files'] = isset($package['files']) ? $package['files'] : [];
        $package['database'] = isset($package['database']) ? $package['database'] : [];
        $this->package = $package;
    }
    public function getPackage($version = null)
    {
        if (sizeof($this->packages) === 0) return;
        if (empty($version)) {
            $this->setPackage($this->packages[0]);
        } else {
            foreach ($this->packages as $package) {
                if ($package['version'] == $version) {
                    $this->setPackage($package);
                    break; // 终止循环
                }
            }
        }
        return $this->package;
    }

    /**
     * 检测存在本地安装包
     */
    public function get_local_package($slug, $callback = null)
    {
        foreach ($this->package['urls'] as $url) {
            $extension = $this->get_zip_extension($url);
            $file = $slug . '-' . $this->package['version'] . '.' . $extension;
            $path = env('SOFTWARE_ROOT') . '/' . $slug . '/' . $file;
            if (app('files')->exists($path)) {
                $this->package['local_file'] = $path;
                break;
            }
        }
        if ($this->on_local_package) {
            $return = call_user_func($this->on_local_package, $this->package['local_file'], $this->package, $this);
            unset($return);
        }
        return $this->package['local_file'];
    }
    // 下载应用包
    public function download_package($slug, $callback = null)
    {
        try {
            if (!app('files')->exists(env('SOFTWARE_ROOT') . '/' . $slug)) {
                app('files')->makeDirectory(env('SOFTWARE_ROOT') . '/' . $slug);
            }

            foreach ($this->package['urls'] as $url) {
                $extension = $this->get_zip_extension($url);
                $path = env('SOFTWARE_ROOT') . '/' . $slug . '/' . $slug . '-' . $this->package['version'] . '.' . $extension;
                $file = fopen($url, 'r');
                if (empty($file)) {
                    throw new \Exception("Unable to open file!");
                }
                app('files')->put($path, $file);
                $this->package['local_file'] = $path;
                break;
            }
        } catch (\Exception $e) {
        }
        if ($this->on_download_package) {
            $return = call_user_func($this->on_download_package, $this->package['local_file'], $this->package, $this);
            unset($return);
        }
        return $this->package['local_file'];
    }
    // 解压缩应用包
    public function unzip_package($slug, $token, $package = null, $path = null)
    {
        $extension = $this->get_zip_extension($this->package['local_file']);
        $this->package['local_dir'] = env('SOFTWARE_ROOT') . '/' . $slug . '/' . $slug . '-' . $this->package['version'] . "." . $token;
        $file_path = __DIR__ . '/../../../' . $this->package['local_file'];
        $dir_path = __DIR__ . '/../../../' . $this->package['local_dir'];
        if (in_array($extension, ['tar.gz'])) {
            $zip = new \PharData($file_path);
            //解压后的路径 数组或者字符串指定解压解压的文件，null为全部解压  是否覆盖
            $zip->extractTo($dir_path, null, true);
        } else {
            $zip = app('zip')::open($file_path);
            $zip->extract($dir_path, true);
        }
        // 更新 filesystem
        // $this->local = new \League\Flysystem\Filesystem(new \League\Flysystem\Local\LocalFilesystemAdapter($this->root_path . '/' . $this->package['local_dir']));
        if ($this->on_unzip_package) {
            $return = call_user_func($this->on_unzip_package, $this->package['local_dir'], $this->package, $this);
            unset($return);
        }
        return $this->package['local_dir'];
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
    public function get_config_files()
    {
        // return array_merge()
    }
}
