<?php

namespace App\Http\Controllers\Software;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;

class WordpressController extends \App\Http\Controllers\SoftwareController
{
    public $name = 'Wordpress';
    public $logo = [
        "icon" => "fab fa-wordpress",
    ];
    public $summary = "一款能让您建立出色网站、博客或应用程序的开源软件。";
    public $description = "WordPress 软件为每个人而设计，强调无障碍、性能、安全和易用。我们相信，伟大的软件应在较少的设置下就能运行，这样您就可以专注于自由地分享您的故事、产品或服务。基本的WordPress软件简单易懂，所以您可以轻松上手。其还为发展和成功提供了强大的功能。<br/><br/>我们相信发布内容的大众化和开放源代码带来的自由。支持这一理念的是一个庞大的社区，其成员们在此项目中进行合作并做出贡献。WordPress社区是欢迎和包容的。贡献者们的激情推动了WordPress的成功，从而帮助您实现目标。 ";
    public $category = "Blogs";
    public $github = "WordPress/WordPress";
    public $links = [
        [
            "name" => "Official Site",
            "href" => "https://wordpress.org/"
        ]
    ];
    public $packages = [
        [
            "version" => "v6.2",
            "urls" => [
                "https://wordpress.org/wordpress-6.2.zip",
                "https://wordpress.org/wordpress-6.2.tar.gz",
            ],
            "root_path" => "/",
            "env" => [
                "php" => "> 7.x"
            ]
        ],
        [
            "version" => "v6.1.1",
            "urls" => [
                "https://wordpress.org/wordpress-6.1.1.zip",
                "https://wordpress.org/wordpress-6.1.1.tar.gz",
            ],
            "root_path" => "/",
            "env" => [
                "php" => "> 7.x"
            ]
        ]
    ];
    public $database = [
        "mysql"
    ];
    public $statement = "";


    public function migration_up()
    {
        $this->Schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function migration_down()
    {
        $this->Schema->dropIfExists('users');
    }
    public function factory_definition($table)
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
    public function factory_unverified($table)
    {
        // return $this->state(function (array $attributes) {
        //     return [
        //         'email_verified_at' => null,
        //     ];
        // });
    }
    public function seeder_run()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->connection->table('users')->insert($this->factory_definition(''));
        }
        // var_dump(\App\Models\Software::factory(20));
        // var_dump(\Illuminate\Database\Eloquent\Factories\Factory);
        // ::factory(20)->create()
        // $factory = new \Illuminate\Database\Eloquent\Factories\Factory();
        // var_dump($factory);
    }
}
