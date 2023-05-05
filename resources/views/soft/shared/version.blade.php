<div class="section">
  <div class="container">
    <div class="title-section">
      <h2>Version Select</h2>
    </div>

    <ul class="nav nav-pills nav-fill" role="tablist">
      @foreach ($software->packages as $pkg)
        <li class="nav-item">
          <a class="nav-link @if ($pkg['version'] === $package['version']) active @endif"
            href="?version={{ $pkg['version'] }}">{{ $pkg['version'] }}</a>
        </li>
      @endforeach
    </ul>
    <ul class="list-group" style="margin-top:6px;">
      @foreach ($package['urls'] as $url)
        <a href="{{ $url }}" class="list-group-item list-group-item-action">{{ $url }}</a>
      @endforeach
    </ul>
  </div>
</div>
