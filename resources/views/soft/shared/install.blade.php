@php
  if (isset($ftp_connect_error_message) && $ftp_connect_error_message === true && isset($db_connect_error_message) && $db_connect_error_message === true) {
      $software->install();
      echo '<script>
        localStorage.setItem("SoftInstaller", JSON.stringify('.json_encode($request->all()).'))
      </script>';
      appendProgressInfo('连接 FTP.');
      appendProgressInfo('FTP 连接成功.', 'success', true);
      updateProgress(5);
  
      appendProgressInfo('检测本地应用包.');
      $localFile = $software->get_local_package();
      if (!empty($localFile)) {
          appendProgressInfo('检测到本地应用包.' . basename($localFile), 'success', true);
      } else {
          appendProgressInfo('未检测到本地应用包', 'danger', true);
          appendProgressInfo('连接远程应用包.');
          foreach ($package['urls'] as $index => $url) {
              try {
                  appendProgressInfo('连接远程应用包(' . ($index + 1) . '/' . sizeof($package['urls']) . ').');
                  $localFile = $software->download_package($url);
                  appendProgressInfo('下载远程应用包(' . ($index + 1) . '/' . sizeof($package['urls']) . ')' . basename($localFile), 'success');
                  break;
              } catch (Exception $e) {
                  appendProgressInfo($e->getMessage(), 'danger', true);
              }
          }
      }
      if (!empty($localFile)) {
          try {
              appendProgressInfo('解压缩当前本地应用包.');
              $localDir = $software->unzip_package();
              appendProgressInfo('解压缩当前本地应用包.', 'success', true);
              appendProgressInfo('生成配置文件.');
              $software->generate_config_file($request);
              appendProgressInfo('生成配置文件.', 'success', true);
              updateProgress(30);
          } catch (Exception $e) {
              appendProgressInfo($e->getMessage(), 'danger', true);
          }
      }
      if (!empty($localDir)) {
          try {
              appendProgressInfo('上传至 FTP 服务器.');
              $software->ftp_upload($request->ftp_dir_path);
              //   $localDir = $software->unzip_package();
              appendProgressInfo('上传至 FTP 服务器.', 'success', true);
              updateProgress(50);
          } catch (Exception $e) {
              appendProgressInfo($e->getMessage(), 'danger', true);
          }
      }
  
      try {
          appendProgressInfo('连接 MySQL.');
          appendProgressInfo('MySQL 连接成功', 'success', true);
          appendProgressInfo('数据库迁移.');
          $software->migration_up();
          appendProgressInfo('数据库迁移.', 'success', true);
          appendProgressInfo('数据填充.');
          $software->seeder_run();
          appendProgressInfo('数据填充.', 'success', true);
      } catch (Exception $e) {
          appendProgressInfo($e->getMessage(), 'danger', true);
      }
  }
@endphp
