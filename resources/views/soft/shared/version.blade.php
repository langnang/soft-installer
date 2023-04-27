  <div class="section">
    <div class="container">
      <div class="title-section">
        <h2>Version Select</h2>
      </div>

      <ul class="nav nav-pills nav-fill">
        @foreach ($config->packages as $pkg)
          <li class="nav-item">
            <a class="nav-link @if ($pkg->version === $request->version) active @endif"
              href="?version={{ $pkg->version }}">{{ $pkg->version }}</a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
