  <div class="section">
    <div class="container">
      <div class="title-section">
        <h2>Environmental Requirement</h2>
      </div>
      <ul class="list-group">
        @foreach ($envs as $env => $required)
          <li class="list-group-item">{{ $env }} {{ $required }}</li>
        @endforeach
      </ul>
    </div>
  </div>
