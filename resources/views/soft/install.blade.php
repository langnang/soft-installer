@php
  // 是否启动安装
  $isInstall = $request->method() === 'POST' && (isset($ftp_connect_status) ? $ftp_connect_status === true : true) && (isset($db_connect_status) ? $db_connect_status === true : true);
@endphp
<!DOCTYPE html>
<html lang="zxx">

@include('home.shared.head')

<body>
  @include('home.shared.header')

  <main class="container" style="min-height:calc(100vh - 265px);">
    @section('content')
      <form action="" method="post">

        @include('soft.shared.jumbotron')
        @if ($isInstall)
          <section name="progress">
            @include('soft.shared.progress')
            @include('shared.result')
          </section>
        @endif

        @if (!$isInstall)
          <section name="install">

          @empty($software->statement)
          @else
            @include('soft.shared.statement')
          @endempty

        @empty($software->packages)
        @else
          @include('soft.shared.version')
        @endempty

      @empty($package)
      @else
        @empty($package['env'])
        @else
          @include('soft.shared.environment', ['envs' => $package['env']])
        @endempty

        @include('soft.shared.ftp')

        @if ($software->has('db'))
          @include('soft.shared.database')
        @endif

        {{-- @include('soft.shared.administrator') --}}
        <div class="section" name="submit">
          <div class="container text-center">
            <button class="button" type="submit">Submit</button>
          </div>
        </div>
      @endempty

    </section>
  @endif

</form>
@show
</main>

@include('home.shared.footer')
@include('home.shared.script')
<script>
  function install() {
    appendProgressInfo('<i class=\"fa fa-check\"></i>连接 FTP.', 'success');
    updateProgress(5);
    axios.interceptors.request.use(function(config) {
      // 在发送请求之前做些什么
      config.headers.token = '{{ $software->getToken() }}';
      console.log(config);
      return config;
    }, function(error) {
      // 对请求错误做些什么
      return Promise.reject(error);
    });
    console.log(axios);
    axios.post('/api/software/get_local_package', {}).then(res => {
      console.log(res);
    })
  }
</script>
@if ($request->method() === 'GET')
<script>
  const storage = JSON.parse(localStorage.getItem('SoftInstaller'));
  if (localStorage.getItem('SoftInstaller') && storage) {
    for (key in storage) {
      if (['ftp_dir_path', 'db_table_prefix'].includes(key)) continue;
      $(`[name="${key}"]`).val(storage[key])
    }
  }
</script>
@endif

</body>

</html>

@php
  if ($isInstall) {
      echo '<script>
        localStorage.setItem("SoftInstaller", JSON.stringify('.json_encode($request->all()).'))
      </script>';
      $hasOldTable = false;
  
      $software->on_start = function ($software) {
          appendProgressInfo('连接 FTP.');
      };
      $software->on_ftp_connect = function ($status, $ftp_config, $software) {
          if ($status === true) {
              appendProgressInfo('连接 FTP.', 'success', true);
              if ($software->has('db')) {
                  appendProgressInfo('连接 Database.');
              } else {
                  appendProgressInfo('检测本地应用包.');
              }
          } elseif ($status === 0) {
              appendProgressInfo('连接 FTP.', 'danger', true);
          } else {
          }
          updateProgress(10);
      };
      $software->on_db_connect = function ($status, $db_config, $software) {
          if ($status === true) {
              appendProgressInfo('连接 Database.', 'success', true);
              appendProgressInfo('检测本地应用包.');
          } elseif ($status === 0) {
              appendProgressInfo('连接 Database.', 'danger', true);
          } else {
          }
          updateProgress(20);
      };
      $software->on_local_package = function ($path, $package, $software) {
          if (empty($path)) {
              appendProgressInfo('未检测到本地应用包', 'danger', true);
              appendProgressInfo('连接远程应用包.');
          } else {
              appendProgressInfo('检测到本地应用包. ' . basename($path), 'success', true);
              appendProgressInfo('解压缩当前应用包.');
          }
          updateProgress(30);
      };
      $software->on_download_package = function ($path, $package, $software) {
          if (empty($path)) {
              appendProgressInfo('连接远程应用包.', 'danger', true);
          } else {
              appendProgressInfo('连接远程应用包.' . basename($path), 'success', true);
              appendProgressInfo('解压缩当前应用包.');
          }
          updateProgress(30);
      };
      $software->on_unzip_package = function ($path, $package, $software) {
          if (empty($path)) {
              appendProgressInfo('解压缩当前应用包.', 'danger', true);
          } else {
              appendProgressInfo('解压缩当前应用包.', 'success', true);
              appendProgressInfo('生成配置文件.');
          }
          updateProgress(40);
      };
      $software->on_generate_config_file = function ($content, $path, $package, $software) {
          if (!empty($content)) {
              appendProgressInfo('生成配置文件.', 'success', true);
              appendProgressInfo('上传至 FTP 服务器.');
          } else {
              appendProgressInfo('生成配置文件.', 'danger', true);
          }
          updateProgress(45);
      };
      $software->on_ftp_upload = function ($status, $ftp_config, $software) {
          if (empty($status)) {
              appendProgressInfo('上传至 FTP 服务器.', 'success', true);
          } else {
              appendProgressInfo('上传至 FTP 服务器.', 'danger', true);
          }
          updateProgress(50);
      };
      $software->on_table_exists = function ($status, $table, $tables, $software) use (&$hasOldTable) {
          if ($status === true) {
              appendProgressInfo('检测到表[ ' . $software->getDbConfig('prefix') . $table . ' ].', 'danger');
              $hasOldTable = true;
          } else {
              appendProgressInfo('未检测到表[ ' . $software->getDbConfig('prefix') . $table . ' ].', 'success');
              appendProgressInfo('迁移数据表[ ' . $software->getDbConfig('prefix') . $table . ' ].');
          }
      };
      $software->on_table_create = function ($status, $table, $tables, $software) {
          if ($status === true) {
              appendProgressInfo('迁移数据表[ ' . $software->getDbConfig('prefix') . $table . ' ].', 'success');
              appendProgressInfo('填充数据表[ ' . $software->getDbConfig('prefix') . $table . ' ].');
          } else {
              appendProgressInfo('迁移数据表[ ' . $software->getDbConfig('prefix') . $table . ' ].', 'danger');
          }
      };
      $software->on_table_seeder = function ($status, $table, $tables, $software) {
          if ($status === true) {
              appendProgressInfo('填充数据表[ ' . $software->getDbConfig('prefix') . $table . ' ].', 'success');
          } else {
          }
      };
      if (in_array('set_time_limit', explode(',', ini_get('disable_functions')))) {
          echo '<script>
            install()
          </script>';
      } else {
          set_time_limit(0); //设置程序执行时间
          ignore_user_abort(true); //设置断开连接继续执行
          header('X-Accel-Buffering: no'); //关闭buffer
          ob_start(); //打开输出缓冲控制
          $software->install();
      }
      var_dump($software);
      var_dump($hasOldTable);
  }
@endphp
