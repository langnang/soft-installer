<div class="card text-center">
  <div class="card-body">
    <h1 style="font-size: 120px;">
      @switch ($result['type']??'')
        @case('success')
          <i class="fa fa-check-circle text-success"></i>
        @break

        @case('danger')
          <i class="fa fa-circle-xmark text-danger"></i>
        @break
      @endswitch
    </h1>
    <h5 class="card-title">{!! $result['title'] ?? '' !!}</h5>
    <p class="card-text">{!! $result['description'] ?? '' !!}</p>

  </div>
</div>
