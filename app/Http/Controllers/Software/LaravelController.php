<?php

namespace App\Http\Controllers\Software;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;

class LaravelController extends \App\Http\Controllers\SoftwareController
{
    public $name = 'Laravel';
    public $logo = [
        "icon" => "fab fa-laravel",
    ];
    public $summary = "";
    public $category = "Framework";
    public $github = "laravel/laravel";
    public $links = [
        [
            "name" => "Official Site",
            "href" => "https://laravel.com/"
        ]
    ];
    public $packages = [
        [
            "version" => "v10.1.1",
            "urls" => [
                "https://github.com/laravel/laravel/archive/refs/tags/v10.1.1.zip",
                "https://github.com/laravel/laravel/archive/refs/tags/v10.1.1.tar.gz",
            ],
            "root_path" => "/",
            "env" => [
                "php" => "> 7.x"
            ]
        ],
        [
            "version" => "v9.5.2",
            "urls" => [
                "https://github.com/laravel/laravel/archive/refs/tags/v9.5.2.zip",
                "https://github.com/laravel/laravel/archive/refs/tags/v9.5.2.tar.gz",
            ],
            "root_path" => "/",
            "env" => [
                "php" => "> 7.x"
            ]
        ],
        [
            "version" => "v8.6.11",
            "urls" => [
                "https://github.com/laravel/laravel/archive/refs/tags/v8.6.11.zip",
                "https://github.com/laravel/laravel/archive/refs/tags/v8.6.11.tar.gz",
            ],
            "root_path" => "/",
            "env" => [
                "php" => "> 7.x"
            ]
        ],
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
