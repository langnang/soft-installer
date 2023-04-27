<!DOCTYPE html>
<html lang="zxx">

@include('home.shared.head')

<body>

  @include('home.shared.header')

  <main class="container" style="min-height:calc(100vh - 265px);">
    @section('content')
      <form action="" method="post">

        @include('shared.jumbotron', [
            'jumbotron' => [
                'title' => "<a href=''>" . substr(basename(get_class($software)), 0, -10) . '</a>',
                'subTitle' => $software->summary,
                'description' => $software->description,
                'links' => array_merge($software->links, [
                    [
                        'icon' => 'fab fa-github',
                        'name' => 'GitHub',
                        'href' => 'https://github.com/' . $software->github,
                    ],
                ]),
            ],
        ])

        @if (isset($ftp_connect_error_message) &&
                $ftp_connect_error_message === true &&
                isset($db_connect_error_message) &&
                $db_connect_error_message === true)
          @include('soft.shared.progress')
          @include('shared.result')
        @else
        @empty(!$software->statement)
          @include('shared.section', [
              'section' => [
                  'title' => 'Statement',
                  'content' => '<p class="lead">' . $software->statement . '</p>',
              ],
          ])
        @endempty
        {{-- @include('soft.shared.statement') --}}
        @include('shared.section', [
            'section' => [
                'title' => 'Version Select',
                'includes' => [
                    [
                        'include' => 'shared.navs',
                        'navs' => array_map(function ($item) use ($package) {
                            $item['name'] = $item['version'];
                            $item['href'] = '?version=' . $item['version'];
                            $item['active'] = $package['version'] == $item['version'];
                            return $item;
                        }, $software->packages),
                    ],
                ],
            ],
        ])
        {{-- @include('soft.shared.version') --}}
        @if (!empty($package))
          @include('soft.shared.environment', ['envs' => $package['env']])

          {{-- @include('shared.section', [
              'section' => [
                  'title' => 'Remote FTP',
                  'includes' => [
                      [
                          'include' => 'shared.forms',
                          'groups' => [
                              [
                                  'col' => 8,
                                  'label' => 'FTP Host',
                                  'control' => [
                                      'element' => 'input',
                                      'name' => 'ftp_host',
                                  ],
                              ],
                              [
                                  'col' => 4,
                                  'label' => 'FTP Host',
                              ],
                              ['label' => 'FTP Host'],
                              ['label' => 'FTP Host'],
                              ['label' => 'FTP Host'],
                              ['label' => 'FTP Host'],
                              ['label' => 'FTP Host'],
                          ],
                          'navs' => array_map(function ($item) use ($package) {
                              $item['name'] = $item['version'];
                              $item['href'] = '?version=' . $item['version'];
                              $item['active'] = $package['version'] == $item['version'];
                              return $item;
                          }, $software->packages),
                      ],
                  ],
              ],
          ]) --}}
          @include('soft.shared.ftp')

          @include('soft.shared.database')

          {{-- @include('soft.shared.administrator') --}}

          <div class="section">
            <div class="container text-center">
              <button class="button" type="submit">Submit</button>
            </div>
          </div>
        @endif

      @endif

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
  if (isset($ftp_connect_error_message) && $ftp_connect_error_message === true && isset($db_connect_error_message) && $db_connect_error_message === true) {
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
              updateProgress(30);
          } catch (Exception $e) {
              appendProgressInfo($e->getMessage(), 'danger', true);
          }
      }
      if (!empty($localDir)) {
          try {
              appendProgressInfo('上传至 FTP 服务器.');
              $software->ftp_upload_package($request->ftp_dir_path);
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
