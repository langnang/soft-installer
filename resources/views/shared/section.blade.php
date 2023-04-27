<div class="section">
  <div class="title-section">
    <h2>{!! $section['title'] ?? '' !!}</h2>
  </div>
  {!! $section['content'] ?? '' !!}
  @isset($section['includes'])
    @foreach ($section['includes'] as $vars)
      @include($vars['include'], $vars ?? [])
    @endforeach
  @endisset
</div>
