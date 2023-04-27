<div class="section">
  <div class="container">
    <div class="title-section">
      <h2>Administrator</h2>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label>Admin</label>
        <input type="text" class="form-control" name="user_name" placeholder="Admin"
          @isset($request)value="{{ $request['user_name'] }}"@endisset>
      </div>
      <div class="form-group col-md-6">
        <label>Password</label>
        <input type="password" class="form-control" name="user_password" placeholder="Password"
          @isset($request)value="{{ $request['user_password'] }}"@endisset>
      </div>
    </div>
    <div class="form-group">
      <label>Email address</label>
      <input type="email" class="form-control" name="user_email" placeholder="Email"
        @isset($request)value="{{ $request['user_email'] }}"@endisset>
    </div>
  </div>
</div>
