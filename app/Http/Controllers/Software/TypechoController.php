<?php

namespace App\Http\Controllers\Software;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;

class TypechoController extends \App\Http\Controllers\SoftwareController
{
    public $name = 'typecho';
    public $logo = [
        "img" => 'typecho.svg',
    ];
    public $summary = "一款内核强健﹑扩展方便﹑体验友好﹑运行流畅的轻量级开源博客程序。";
    public $description = "Typecho是由type和echo两个词合成的，来自于开发团队的头脑风暴。<br/>Typecho基于PHP5开发，支持多种数据库，是一款内核强健﹑扩展方便﹑体验友好﹑运行流畅的轻量级开源博客程序。<br/>选用Typecho，搭建独一无二个人网络日志发布平台，享受创作的快乐。";
    public $category = "blogs";
    public $github = "typecho/typecho";
    public $links = [
        [
            "name" => "Official Site",
            "href" => "https://typecho.org/"
        ]
    ];
    public $packages = [
        [
            "version" => "v1.2.0",
            "urls" => [
                "https://github.com/typecho/typecho/releases/download/v1.2.0/typecho.zip"
            ],
            "root_path" => "/",
            "env" => [
                "php" => "> 7.x"
            ]
        ],
        [
            "version" => "v1.1-17.10.30-release",
            "urls" => [
                "https://github.com/typecho/typecho/releases/download/v1.1-17.10.30-release/1.1.17.10.30.-release.tar.gz"
            ],
            "root_path" => "/build/",
            "env" => [
                "php" => "> 7.x"
            ]
        ]
    ];
    public $database = [
        "mysql"
    ];
    public $statement = "Typecho 基于 <a href='http: //www.gnu.org/copyleft/gpl.html'>GPL</a> 协议发布, 我们允许用户在 GPL 协议许可的范围内使用, 拷贝, 修改和分发此程序.<br/><br/>在GPL许可的范围内, 您可以自由地将其用于商业以及非商业用途.<br/><br/>Typecho 软件由其社区提供支持, 核心开发团队负责维护程序日常开发工作以及新特性的制定.<br/><br/>如果您遇到使用上的问题, 程序中的 BUG, 以及期许的新功能, 欢迎您在社区中交流或者直接向我们贡献代码.对于贡献突出者, 他的名字将出现在贡献者名单中.";


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
    public function factory_definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
    public function factory_unverified()
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
            $this->connection->table('users')->insert($this->factory_definition());
        }
        // var_dump(\App\Models\Software::factory(20));
        // var_dump(\Illuminate\Database\Eloquent\Factories\Factory);
        // ::factory(20)->create()
        // $factory = new \Illuminate\Database\Eloquent\Factories\Factory();
        // var_dump($factory);
    }
}
