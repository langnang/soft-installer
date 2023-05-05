<?php

namespace App\Http\Controllers\Software;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;

class MonstaFtpController extends \App\Http\Controllers\SoftwareController
{
    public $name = 'Monsta FTP';
    public $packages = [
        [
            "version" => "v2.10.4",
            "urls" => [
                "https://www.monstaftp.com/downloads/monsta_ftp_2.10.4_install.zip",
            ]
        ]
    ];
}
