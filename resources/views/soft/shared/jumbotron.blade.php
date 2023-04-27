<div class="section">
  <div class="container">
    <div class="jumbotron text-center">
      <h1 style="font-size: 60px;">Typecho</h1>
      <br>
      <p class="lead">{{ $config->description }}</p>
      <hr class="my-4">
      @foreach ($config->links as $link)
        <a class="btn btn-primary" href="{{ $link->href }}" target="_blank" role="button">
          {{ $link->name }}
        </a>
      @endforeach
      <a class="btn btn-primary" href="https://github.com/{{ $config->github }}" target="_blank" role="button">
        <i class="fab fa-github"></i>
        GitHub
      </a>
    </div>
  </div>
</div>
