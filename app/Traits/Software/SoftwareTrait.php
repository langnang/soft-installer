<?php

namespace App\Traits\Software;

trait SoftwareTrait
{
    // 名称
    public $name;
    // 标识
    public $slug;
    // 状态 ['public', 'private', 'protected']
    public $status = "public";
    // 徽标
    public $logo = [
        "icon" => null,
        "file" => "software.svg",
        "img" => null,
        "svg" => null,
    ];
    // 分类
    public $category;
    // 概览
    public $summary;
    // 描述
    public $description;
    // 仓库地址
    public $git;
    // 链接
    public $links = [];
    // 参考手册
    public $manuals = [];
    // 声明
    public $statement;

    public function software_construct($config = [], $mini = false)
    {

        $this->name = isset($config['name']) ? $config['name'] : $this->name;
        $this->slug = isset($config['slug']) ? $config['slug'] : $this->slug;
        $this->status = isset($config['status']) ? $config['status'] : $this->status;
        $this->logo = isset($config['logo']) ? $config['logo'] : $this->logo;
        $this->category = isset($config['category']) ? $config['category'] : $this->category;
        if ($mini) return;
        $this->summary = isset($config['summary']) ? $config['summary'] : $this->summary;
        $this->description = isset($config['description']) ? $config['description'] : $this->description;
        $this->git = isset($config['git']) ? $config['git'] : $this->git;
        $this->links = isset($config['links']) ? $config['links'] : $this->links;
        $this->manuals = isset($config['manuals']) ? $config['manuals'] : $this->manuals;
        $this->statement = isset($config['statement']) ? $config['statement'] : $this->statement;
    }
    // 获取继承的子类
    public function getSubClass($slug = null)
    {
        // var_dump(__METHOD__);
        $classes = [];
        if (app('files')->exists(env('SOFTWARE_LOCAL_FILE'))) {
            $classes = array_merge($classes, json_decode(app('files')->get(env('SOFTWARE_LOCAL_FILE')), true));
        };
        $result = [];
        foreach ($classes as  $class) {
            // 跳过没有名称(name)的
            if (!isset($class['name']) || empty($class['name'])) continue;
            // var_dump($class['name']);
            $class = new $this($class, empty($slug));
            if (!empty($slug) && $class->getSlug() === $slug) return $class;
            $result[$class->getSlug()] = $class;
        }
        return $result;
    }

    public function getCategorySubClass()
    {
        // var_dump(__METHOD__);
        $classes = $this->getSubClass();
        $result = [];
        foreach ($classes as $class) {
            $category = empty($class->category) ? null : $class->category;
            if (empty($category)) $category = 'Unknown';
            if (!isset($result[$category])) $result[$category] = [];
            array_push($result[$category], $class);
        }
        // 数组关键字排序
        ksort($result);
        return $result;
    }
    public function getSlug()
    {
        if (!empty($this->slug)) return $this->slug;
        return preg_replace("/\W/i", "-", strtolower($this->name));
    }
    public function getGit()
    {
        if (empty($this->git)) return [];
        return [[
            "icon" => "fab fa-github",
            "name" => "GitHub",
            "href" => $this->git,
        ]];
    }
    public function getLinks()
    {
        return array_merge($this->getGit(), (array)$this->links, (array)$this->manuals);
    }
}
