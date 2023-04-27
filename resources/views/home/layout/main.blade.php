<!DOCTYPE html>
<html lang="zxx">

@include('home.shared.head')

<body>

  @include('home.shared.header')

  <main style="min-height:calc(100vh - 265px);">
    @section('content')
    @show
  </main>

  @include('home.shared.footer')
  @include('home.shared.script')

</body>

</html>
