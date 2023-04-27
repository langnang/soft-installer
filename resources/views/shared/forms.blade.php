<form>
  <div class="form-row">
    @foreach ($groups as $group)
      <div class="form-group col-md-{{ $group['col'] ?? '12' }}">
        <label>{!! $group['label'] ?? '' !!}</label>
        @isset($group['control'])
          @php
            $control = $group['control'];
          @endphp
          <{{ $control['element'] ?? 'input' }} type="{{ $control['id'] ?? 'text' }}" id="{{ $control['id'] ?? '' }}"
            name="{{ $control['name'] ?? '' }}" class="form-control"
            placeholder="{{ $control['placeholder'] ?? ($group['label'] ?? '') }}">
            </{{ $control['element'] }}>
          @endisset
      </div>
    @endforeach
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1">
  </div>
  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
