<!DOCTYPE html>
<html lang="zxx">

@include('home.shared.head')

<body>
  @include('home.shared.header')

  <main class="container" style="min-height:calc(100vh - 265px);">
    @section('content')
      <form action="" method="post">

        @include('soft.shared.jumbotron')
        <section name="progress">
          {{-- @if (isset($ftp_connect_error_message) && $ftp_connect_error_message === true && isset($db_connect_error_message) && $db_connect_error_message === true)
          @include('soft.shared.progress')
          @include('shared.result')
        @else --}}
          @if ($request->method() === 'POST')
            @include('soft.shared.progress')
            @include('shared.result')
          @endif
        </section>

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
            @include('soft.shared.environment', ['envs' => $package['env']])

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
        {{-- @endif --}}

      </form>
    @show
  </main>

  @include('home.shared.footer')
  @include('home.shared.script')
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
  set_time_limit(0); //设置程序执行时间
  ignore_user_abort(true); //设置断开连接继续执行
  header('X-Accel-Buffering: no'); //关闭buffer
  ob_start(); //打开输出缓冲控制
  if ($request->method() === 'POST') {
      echo '<script>
        localStorage.setItem("SoftInstaller", JSON.stringify('.json_encode($request->all()).'))
      </script>';
      $software->on_start = function ($software) {
          appendProgressInfo('连接 FTP.');
      };
      $software->on_ftp_connect = function ($status, $ftp_config, $software) {
          if ($status === 1) {
              appendProgressInfo('连接 FTP.', 'success', true);
              appendProgressInfo('检测本地应用包.');
          } elseif ($status === 0) {
              appendProgressInfo('连接 FTP.', 'danger', true);
          } else {
          }
      };
      $software->on_local_package = function ($path, $package, $software) {
          if (empty($path)) {
              appendProgressInfo('未检测到本地应用包', 'danger', true);
              appendProgressInfo('连接远程应用包.');
          } else {
              appendProgressInfo('检测到本地应用包. ' . basename($path), 'success', true);
              appendProgressInfo('解压缩当前应用包.');
          }
      };
      $software->on_unzip_package = function ($path, $package, $software) {
          if (empty($path)) {
              appendProgressInfo('解压缩当前应用包.', 'danger', true);
          } else {
              appendProgressInfo('解压缩当前应用包.', 'success', true);
              appendProgressInfo('生成配置文件.');
              appendProgressInfo('上传至 FTP 服务器.');
          }
      };
      $software->on_ftp_upload = function ($status, $ftp_config, $software) {
          if (empty($status)) {
              appendProgressInfo('上传至 FTP 服务器.', 'success', true);
          } else {
              appendProgressInfo('上传至 FTP 服务器.', 'danger', true);
          }
      };
      $software->install();
  }
  
@endphp
