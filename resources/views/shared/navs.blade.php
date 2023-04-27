<ul class="nav nav-pills nav-fill" role="tablist">
  @foreach ($navs as $nav)
    <li class="nav-item" role="presentation">
      <a class="nav-link @if ($nav['active']) active @endif"
        href="{!! $nav['href'] ?? '#' !!}">{!! $nav['name'] ?? '' !!}</a>
    </li>
  @endforeach
</ul>
