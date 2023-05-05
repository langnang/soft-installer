<?php

namespace App\Http\Controllers\Software;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;

class TypechoController extends \App\Http\Controllers\SoftwareController
{
    public $name = 'Typecho';
    public $logo = [
        "local" => 'typecho.svg',
    ];
    public $summary = "一款内核强健﹑扩展方便﹑体验友好﹑运行流畅的轻量级开源博客程序。";
    public $description = "Typecho是由type和echo两个词合成的，来自于开发团队的头脑风暴。<br/>Typecho基于PHP5开发，支持多种数据库，是一款内核强健﹑扩展方便﹑体验友好﹑运行流畅的轻量级开源博客程序。<br/>选用Typecho，搭建独一无二个人网络日志发布平台，享受创作的快乐。";
    public $category = "Blogs";
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
            ],
            "files" => [],
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

    public function generate_config_file(Request $request)
    {
        $this->local->write('config.inc.php', "
<?php
// site root path
define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));

// plugin directory (relative path)
define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');

// theme directory (relative path)
define('__TYPECHO_THEME_DIR__', '/usr/themes');

// admin directory (relative path)
define('__TYPECHO_ADMIN_DIR__', '/admin/');

// register autoload
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';

// init
\Typecho\Common::init();

// config db
\$db = new \Typecho\Db('Pdo_Mysql', 'typecho_');
\$db->addServer(array (
  'host' => 'localhost',
  'port' => 3306,
  'user' => 'test_com',
  'password' => '3czr63kNeTRmmfiL',
  'charset' => 'utf8mb4',
  'database' => 'test_com',
  'engine' => 'InnoDB',
), \Typecho\Db::READ | \Typecho\Db::WRITE);
\Typecho\Db::set(\$db);

        ");
    }
    public function migration_up()
    {
        $this->Schema->create('users', function (Blueprint $table) {
            $table->id("mid")->comment("编号");
            $table->string("name")->nullable()->comment("名称");
            $table->string("password")->nullable()->comment("密码");
            $table->timestamp("created_at")->nullable()->comment("创建时间");
            $table->timestamp("updated_at")->nullable()->comment("修改时间");
            $table->timestamp("release_at")->nullable()->comment("发布时间");
            $table->timestamp("deleted_at")->nullable()->comment("删除时间");
        });
        $this->Schema->create('options', function (Blueprint $table) {
            $table->integer("user");
            $table->string("name");
            $table->text("value");
            $table->timestamp("created_at")->nullable()->comment("创建时间");
            $table->timestamp("updated_at")->nullable()->comment("修改时间");
            $table->timestamp("release_at")->nullable()->comment("发布时间");
            $table->timestamp("deleted_at")->nullable()->comment("删除时间");
        });
        $this->Schema->create('metas', function (Blueprint $table) {
            $table->id("mid")->comment("编号");
            $table->string("name")->nullable()->comment("名称");
            $table->string("slug")->nullable()->unique()->comment("标识");
            $table->string("description")->nullable()->comment("说明");
            $table->string("type")->nullable()->comment("类型: branch, category, tag");
            $table->string("status")->nullable()->comment("状态");
            $table->integer("order")->nullable()->default(0)->comment("排序");
            $table->integer("count")->nullable()->default(0)->comment("计数");
            $table->integer("parent")->nullable()->default(0)->comment("父本");
            $table->timestamp("created_at")->nullable()->comment("创建时间");
            $table->timestamp("updated_at")->nullable()->comment("修改时间");
            $table->timestamp("release_at")->nullable()->comment("发布时间");
            $table->timestamp("deleted_at")->nullable()->comment("删除时间");
        });
        $this->Schema->create('contents', function (Blueprint $table) {
            $table->id("cid")->comment("编号");
            $table->string("title")->nullable()->comment("标题");
            $table->string("slug")->nullable()->unique()->comment("标识");
            $table->longText("text")->nullable()->comment("内容");
            $table->string("type")->nullable()->comment("类型");
            $table->string("status")->nullable()->default('draft')->comment("状态: draft, private, public");
            $table->integer("template")->nullable()->default(0)->comment("模板");

            $table->integer('user',)->nullable()->default(0)->comment('用户ID');

            $table->integer("order")->nullable()->default(0)->comment("排序");
            $table->integer("count")->nullable()->default(0)->comment("计数");
            $table->integer("parent")->nullable()->default(0)->comment("父本");

            $table->timestamp("created_at")->nullable()->comment("创建时间");
            $table->timestamp("updated_at")->nullable()->comment("修改时间");
            $table->timestamp("release_at")->nullable()->comment("发布时间");
            $table->timestamp("deleted_at")->nullable()->comment("删除时间");
        });
        $this->Schema->create('fields', function (Blueprint $table) {
            $table->integer("cid")->comment("_contents");

            $table->timestamp("created_at")->nullable()->comment("创建时间");
            $table->timestamp("updated_at")->nullable()->comment("修改时间");
            $table->timestamp("release_at")->nullable()->comment("发布时间");
            $table->timestamp("deleted_at")->nullable()->comment("删除时间");
        });
        $this->Schema->create('relationships', function (Blueprint $table) {
            $table->integer("mid")->comment("_metas");
            $table->integer("cid")->comment("_contents");
        });
    }
    public function migration_down()
    {
        $this->Schema->dropIfExists('users');
        $this->Schema->dropIfExists('options');
        $this->Schema->dropIfExists('metas');
        $this->Schema->dropIfExists('contents');
        $this->Schema->dropIfExists('fields');
        $this->Schema->dropIfExists('relationships');
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
        $this->connection->table('options')->insert([]);
        // var_dump(\App\Models\Software::factory(20));
        // var_dump(\Illuminate\Database\Eloquent\Factories\Factory);
        // ::factory(20)->create()
        // $factory = new \Illuminate\Database\Eloquent\Factories\Factory();
        // var_dump($factory);
    }
}
