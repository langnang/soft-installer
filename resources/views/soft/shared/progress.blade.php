<style>
  #install-progress p {
    margin-bottom: 0;
    padding-left: 20px;
  }

  #install-progress p>i {
    width: 14px;
    height: 14px;
    margin-right: 6px;
    text-align: center;
    vertical-align: middle;
  }

  #install-progress p>i:first-child {
    margin-left: -20px;

  }

  @keyframes ellipsis {

    /*动态改变显示宽度, 但始终让总占据空间不变, 避免抖动*/
    0% {
      width: 0;
      margin-right: 12px;
    }

    33% {
      width: 4px;
      margin-right: 8px;
    }

    66% {
      width: 8px;
      margin-right: 4px;
    }

    100% {
      width: 12px;
      margin-right: 0;
    }
  }

  #install-progress p>i.fa-ellipsis {
    animation: ellipsis 3s infinite step-start;
    overflow: hidden;
  }

  #install-progress p .spinner-grow-sm {
    vertical-align: middle;
  }
</style>
<div class="section install-progress">
  <div class="container">
    <div class="title-section">
      <h2>Installation Progress</h2>
    </div>
    <div class="progress" style="height: 30px;border-radius: 10px;">
      <div class="progress-bar progress-bar-striped bg-primary progress-bar-animated" role="progressbar" aria-valuenow="75"
        aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
    </div>
    <div class="card" style="margin-top: 10px;">
      <div class="card-body text-light" id="install-progress" style="background: #302C42;">
        {{-- <p>FTP 连接成功.</p> --}}
        {{-- <p>MySQL 连接成功.</p> --}}
        {{-- <p>未检测到本地应用包，连接远程应用包</p> --}}
        {{-- <p>远程应用包连接成功，启动下载应用包.(0/2000)</p> --}}
        {{-- <p>应用包下载完成，启动解压缩.</p> --}}
        {{-- <p>解压缩完成，FTP 上传.</p> --}}
      </div>
    </div>
  </div>
</div>
<script>
  const progress = document.getElementById('install-progress');

  function appendProgressInfo(message, type, replace = false) {
    if (replace) progress.removeChild(progress.children[progress.children.length - 1]);
    const p = document.createElement('p');
    p.innerHTML = message.trim();
    if (type) p.className = 'text-' + type;
    progress.appendChild(p);
  }

  function updateProgress(percent) {
    document.getElementsByClassName("progress-bar")[0].style.width = percent + "%";
    // document.getElementsByClassName("progress-bar")[0].innerHTML = "%u%% Complete";
  }
</script>

@php
  function appendProgressInfo($message, $type = 'light', $replace = false)
  {
      //   sleep(rand(1, 2));
      $suffixMessage = '';
      $prefixMessage = '';
      switch ($type) {
          case 'light':
              $prefixMessage = '<i class="spinner-grow spinner-grow-sm"></i>';
              $suffixMessage = '    <i class="fa fa-ellipsis"></i>';
              break;
          case 'success':
              $prefixMessage = '<i class="fa fa-check"></i>';
              break;
          case 'danger':
              $prefixMessage = '<i class="fa fa-xmark"></i>';
              break;
          default:
              break;
      }
      echo '<script>
        appendProgressInfo(\''.addslashes(trim($prefixMessage.$message.$suffixMessage)).
            '\', \''.$type.
            '\', '.$replace.
            ');
      </script>';
      echo ob_get_clean();
      flush();
  }
  function updateProgress($percent)
  {
      $script = '<script>
        updateProgress("%u")
      </script>';
      echo sprintf($script, $percent);
      echo ob_get_clean(); //获取当前缓冲区内容并清除当前的输出缓冲
      flush(); //刷新缓冲区的内容，输出
  }
  //   if ($ftp_connect_error_message === true && $db_connect_error_message === true) {
  
  //       $localFile;
  //       // 检测本地应用包
  //       foreach ($package['urls'] as $url) {
  //           $file = $software->name . '-' . $package['version'] . '.zip';
  //           if (file_exists(__DIR__ . '/../../../scripts/' . $software->name . '/' . $file)) {
  //               $localFile = $file;
  //               break;
  //           }
  //       }
  //       if (!empty($localFile)) {
  //           appendProgressInfo('<i class="fa fa-check"></i>检测到本地应用包.' . $localFile, 'success');
  //       } else {
  //           //   appendProgressInfo('<i class="spinner-grow spinner-grow-sm"></i>未检测到本地应用包');
  //           appendProgressInfo('未检测到本地应用包');
  //       }
  //       // 未检测到本地应用包，下载远程应用包
  //       if (empty($localFile)) {
  //           $remoteFile;
  //           foreach ($package['urls'] as $index => $url) {
  //               var_dump(pathinfo($url));
  //               $file = $software->name . '-' . $package['version'] . '.zip';
  //               appendProgressInfo('<i class="spinner-grow spinner-grow-sm"></i>连接远程应用包(' . ($index + 1) . '/' . sizeof($package['urls']) . ')');
  //               $fp_input;
  //               try {
  //                   $fp_input = fopen($url, 'r');
  //                   appendProgressInfo('<i class="fa fa-check"></i>连接远程应用包(' . ($index + 1) . '/' . sizeof($package['urls']) . ')', 'success', true);
  //                   file_put_contents(__DIR__ . '/../../../scripts/' . $software->name . '/' . $file, $fp_input);
  //                   $localFile = $file;
  //                   break;
  //               } catch (Exception $e) {
  //                   appendProgressInfo($e->getMessage(), 'danger');
  //               }
  //           }
  //       }
  //       if (!file_exists(__DIR__ . '/../../../scripts/' . $software->name . '/' . pathinfo($localFile)['filename'])) {
  //           try {
  //               appendProgressInfo('<i class="spinner-grow spinner-grow-sm"></i>解压缩应用包');
  //               var_dump(__DIR__ . '/../../../scripts/' . $software->name . '/' . $localFile);
  //               var_dump(pathinfo($localFile));
  //               $zip = app('zip')::open(__DIR__ . '/../../../scripts/' . $software->name . '/' . $localFile);
  //               $zip->extract(__DIR__ . '/../../../scripts/' . $software->name . '/' . pathinfo($localFile)['filename'], true);
  //               appendProgressInfo('<i class="fa fa-check"></i>应用包解压完成');
  //           } catch (Exception $e) {
  //               appendProgressInfo('<i class="fa fa-close"></i>' . $e->getMessage(), 'danger');
  //           }
  //       } else {
  //           appendProgressInfo('<i class="fa fa-check"></i>检测到本地存在已解压应用包目录.', 'success');
  //       }
  //       try {
  //           appendProgressInfo('<i class="spinner-grow spinner-grow-sm"></i>上传至 FTP');
  //           //   var_dump($controller);
  //           $controller->ftp->putAll(__DIR__ . '/../../../scripts/' . $software->name . '/' . pathinfo($localFile)['filename'], $request->ftp_dir_path);
  //           appendProgressInfo('<i class="fa fa-check"></i>上传至 FTP', 'success', true);
  //           updateProgress(50);
  //       } catch (Exception $e) {
  //           appendProgressInfo('<i class="fa fa-close"></i>' . $e->getMessage(), 'danger');
  //       }
  
  //       //   var_dump($request->all());
  //       //   var_dump($package);
  //       //   appendProgressInfo('未检测到本地应用包，连接远程应用包.<i class="spinner-grow spinner-grow-sm"></i>');
  //   }
  //   ob_end_flush();
@endphp
{{-- $script = '<script>
    appendProgressInfo("%u%", "%u%", );
  </script>'; --}}
{{-- for ($i = 0; $i < 101; $i++) {
          sleep(rand(1, 2));
          $_script = '<script>
            document.getElementsByClassName("progress-bar")[0].style.width = "%u%%";
            document.getElementsByClassName("progress-bar")[0].innerHTML = "%u%% Complete";
          </script>';
          echo sprintf($_script, $i, $i);
          echo ob_get_clean(); //获取当前缓冲区内容并清除当前的输出缓冲
          flush(); //刷新缓冲区的内容，输出
      } --}}
