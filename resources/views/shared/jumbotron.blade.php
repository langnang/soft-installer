<div class="jumbotron">
  <h1 class="display-4 mb-2">{!! $jumbotron['title'] ?? '' !!}</h1>
  <p class="lead">{!! $jumbotron['subTitle'] ?? '' !!}</p>
  <hr class="my-4">
  <p>{!! $jumbotron['description'] ?? '' !!}</p>
  @foreach ($jumbotron['links'] ?? [] as $link)
    <a class="btn btn-primary" href="{{ $link['href'] }}" target="_blank" role="button">
      @isset($link['icon'])
        <i class="{{ $link['icon'] }}"></i>
      @endisset
      {!! $link['name'] !!}
    </a>
  @endforeach
</div>
