  <div class="section">
    <div class="container">
      <div class="title-section">
        <h2>Remote FTP</h2>
      </div>
      @if (isset($ftp_connect_status) && $ftp_connect_status !== true)
        <div class="alert alert-danger" role="alert">
          ftp connect error.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="form-row">
        <div class="form-group col-md-8">
          <label>FTP Host </label>
          <input type="text" class="form-control" name="ftp_host" placeholder="FTP Host"
            @isset($request)value="{{ $request['ftp_host'] }}"@endisset>
          @if ($request->method() === 'POST' && empty($request['ftp_host']))
            <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
              ftp host can not be empty.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>

        <div class="form-group col-md-4">
          <label>FTP Port</label>
          <input type="number" class="form-control" name="ftp_port" placeholder="FTP Port"
            value="{{ $request->input('ftp_port', 21) }}">
          @if ($request->method() === 'POST' && empty($request['ftp_port']))
            <div class="alert alert-danger" role="alert">
              ftp port can not be empty.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>FTP Username</label>
          <input type="text" class="form-control" name="ftp_username" placeholder="FTP Username"
            value="{{ $request->ftp_username }}">
          @if ($request->method() === 'POST' && empty($request->ftp_username))
            <div class="alert alert-danger" role="alert">
              ftp username can not be empty.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
        <div class="form-group col-md-6">
          <label>FTP Password</label>
          <input type="password" class="form-control" name="ftp_password" placeholder="FTP Password"
            @isset($request)value="{{ $request['ftp_password'] }}"@endisset>
          @if ($request->method() === 'POST' && empty($request['ftp_password']))
            <div class="alert alert-danger" role="alert">
              ftp password can not be empty.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>
      <div class="form-group">
        <label>FTP Directory Path</label>
        <input type="text" class="form-control" name="ftp_dir_path" placeholder="FTP Directory Path"
          value="{{ $request->input('ftp_dir_path', '/') }}">
        @if ($request->method() === 'POST' && empty($request['ftp_dir_path']))
          <div class="alert alert-danger" role="alert">
            ftp directory path can not be empty.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>
