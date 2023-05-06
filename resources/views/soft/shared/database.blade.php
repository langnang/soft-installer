<div class="section">
  <div class="container">
    <div class="title-section">
      <h2>Database</h2>
    </div>
    @if (isset($db_connect_status) && $db_connect_status !== true)
      <div class="alert alert-danger" role="alert">
        database connect error.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
    <div class="form-group">
      <label>Database Driver</label>
      <select class="form-control" name="db_driver" placeholder="Database Driver">
        <option value="sqlite" disabled>SQLite</option>
        <option value="mysql" selected>MySQL</option>
        <option value="postgresql" disabled>PostgreSQL</option>
      </select>
      @if ($request->method() === 'POST' && empty($request['db_driver']))
        <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
          database driver can not be empty.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
    </div>
    <div class="form-row">
      <div class="form-group col-md-5">
        <label>Database Host</label>
        <input type="text" class="form-control" name="db_host" placeholder="Database Host"
          value="{{ $request->input('db_host') }}">
        @if ($request->method() === 'POST' && empty($request['db_host']))
          <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
            database host can not be empty.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
      <div class="form-group col-md-5">
        <label>Database Name</label>
        <input type="text" class="form-control" name="db_database" placeholder="Database Name"
          value="{{ $request->input('db_database') }}">
        @if ($request->method() === 'POST' && empty($request['db_database']))
          <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
            database name can not be empty.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
      <div class="form-group col-md-2">
        <label>Database Port</label>
        <input type="number" class="form-control" name="db_port" placeholder="Database Port"
          value="{{ $request->input('db_port', 3306) }}">
        @if ($request->method() === 'POST' && empty($request['db_port']))
          <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
            database port can not be empty.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>Database Username</label>
        <input type="text" class="form-control" name="db_username" placeholder="Database Username"
          value="{{ $request->input('db_username') }}">
        @if ($request->method() === 'POST' && empty($request['db_username']))
          <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
            database username can not be empty.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
      <div class="form-group col-md-6">
        <label>Database Password</label>
        <input type="password" class="form-control" name="db_password" placeholder="Database Password"
          value="{{ $request->input('db_password') }}">
        @if ($request->method() === 'POST' && empty($request['db_password']))
          <div class="alert alert-danger" role="alert" style="margin-top: 4px;">
            database password can not be empty.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>
    <div class="form-group">
      <label>Table Prefix</label>
      <input type="text" class="form-control" name="db_table_prefix" placeholder="Table Prefix"
        value="{{ $request->input('db_table_prefix', $software->getSlug() . '_') }}">
    </div>
  </div>
</div>
