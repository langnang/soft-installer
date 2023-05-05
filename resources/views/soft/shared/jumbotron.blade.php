<div class="section">
  <div class="container">
    <div class="jumbotron">
      <h1 style="font-size: 48px;">{!! $software->name ?? '' !!}</h1>
      @empty($software->summary)
      @else
        <br>
        <p class="lead">{!! $software->summary ?? '' !!}</p>
      @endempty
      <hr class="my-4">
      <p>{!! $software->description ?? '' !!}</p>

      @foreach ($software->getLinks() as $link)
        <a class="btn btn-primary btn-sm {{ $link['class'] ?? '' }}" href="{{ $link['href'] ?? '' }}" target="_blank"
          role="button">
          <i class="{{ $link['icon'] ?? '' }}"></i>
          {{ $link['name'] ?? '' }}
        </a>
      @endforeach
    </div>
  </div>
</div>
