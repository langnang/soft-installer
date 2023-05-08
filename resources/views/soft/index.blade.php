@extends('home.layout.main')
<style>
  .softwares .entry img {
    width: 75px;
  }
</style>
@section('content')
  <div class="softwares container">
    @foreach ($categories as $category => $softwares)
      <div class="section">
        <div class="title-section">
          <h2>{{ ucfirst($category) }}</h2>
        </div>
        <div class="row first-row">
          @foreach ($softwares as $software)
            <div class="col-md-3">
              <a href='/software/{{ $software->getSlug() }}' class="entry aos-init aos-animate" data-aos="zoom-in">
                @isset($software->logo)
                  @if (isset($software->logo['icon']) && !empty($software->logo['icon']))
                    <i class="{{ $software->logo['icon'] }}"></i>
                  @elseif (isset($software->logo['file']) && !empty($software->logo['file']))
                    <img src="/public/images/brands/{{ $software->logo['file'] }}" alt="">
                  @elseif (isset($software->logo['img']) && !empty($software->logo['img']))
                    <img src="{{ $software->logo['img'] }}" alt="">
                  @elseif (isset($software->logo['svg']) && !empty($software->logo['svg']))
                    <svg viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" width="75"
                      height="75">
                      {!! $software->logo['svg'] !!}
                    </svg>
                  @endif
                @endisset
                <h5>{{ $software->name }}</h5>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
    {{-- <div class="section">
      <div class="title-section">
        <h2>Software</h2>
      </div>
      <div class="row first-row">
         @foreach ($softwares as $software)
          <div class="col-md-3">
            <a href='/software/{{ $software->name }}' class="entry aos-init aos-animate" data-aos="zoom-in">
              @if (array_key_exists('icon', $software->logo))
                <i class="{{ $software->logo['icon'] }}"></i>
              @elseif (array_key_exists('img', $software->logo))
                <img src="/public/images/brands/{{ $software->logo['img'] }}" alt="">
              @endif
              <h5>{{ substr(basename(get_class($software)), 0, -10) }}</h5>
            </a>
          </div>
        @endforeach

     <div class="col-md-3">
          <div class="entry aos-init aos-animate" data-aos="zoom-in">
            <i class="fab fa-laravel"></i>
            <h5>Laravel</h5>
          </div>
        </div>
        <div class="col-md-3">
          <div class="entry aos-init aos-animate" data-aos="zoom-in">
            <i class="fab fa-wordpress"></i>
            <h5>WordPress</h5>
          </div>
        </div>
        <div class="col-md-3">
          <div class="entry aos-init aos-animate" data-aos="zoom-in" data-aos-duration="1500">
            <i class="fab fa-magento"></i>
            <h5>Magento</h5>
          </div>
        </div>
        <div class="col-md-3">
          <div class="entry aos-init aos-animate" data-aos="zoom-in" data-aos-duration="1500">
            <i class="fab fa-drupal"></i>
            <h5>Drupal</h5>
          </div>
        </div>
  </div>
  </div> --}}
  </div>
@endsection

@section('content_temp')
  <div class="header-intro">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
          <div class="entry" data-aos="slide-up">
            <span>We are professional</span>
            <h2>We are the Best Proffesional <br> Creative Agency</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec orem ipsum dolor sit
              amet, consectetur adipiscing elit. Ut elit tellus, luctus ne ullamcorper mattis, pulvinar dapibus leo.</p>
            <button class="button">Get Started</button>
          </div>
        </div>
        <div class="col-md-6 col-sm-12">
          <div class="entry entry-img" data-aos="zoom-in">
            <img src="/resources/views/home/static/picture/hero.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end header intro -->

  <!-- services -->
  <div class="services section">
    <div class="container">
      <div class="title-section">
        <span>Sevices</span>
        <h2>Choose our<br>creative services</h2>
      </div>
      <div class="row first-row">
        <div class="col-md-4">
          <div class="entry" data-aos="zoom-in">
            <i class="fab fa-wordpress"></i>
            <h5>WordPress</h5>
            <p>Lorem ipsum dolor sit consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="entry" data-aos="zoom-in" data-aos-duration="1500">
            <i class="fab fa-magento"></i>
            <h5>Magento</h5>
            <p>Lorem ipsum dolor sit consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="entry" data-aos="zoom-in" data-aos-duration="1500">
            <i class="fab fa-drupal"></i>
            <h5>Drupal</h5>
            <p>Lorem ipsum dolor sit consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="entry" data-aos="zoom-in" data-aos-duration="2000">
            <i class="far fa-chart-bar"></i>
            <h5>Marketing</h5>
            <p>Lorem ipsum dolor sit consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="entry" data-aos="zoom-in" data-aos-duration="2500">
            <i class="fas fa-mobile-alt"></i>
            <h5>Mobile</h5>
            <p>Lorem ipsum dolor sit consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="entry" data-aos="zoom-in" data-aos-duration="3000">
            <i class="fas fa-credit-card"></i>
            <h5>Graphic</h5>
            <p>Lorem ipsum dolor sit consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end services -->

  <!-- about us -->
  <div class="about-us section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="entry">
            <img src="/resources/views/home/static/picture/about-us.png" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="entry">
            <span>About us</span>
            <h2>We grow brands beyond set targets</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis,
              pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec
              ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit
              tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
            <button class="button">Read More</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end about us -->

  <!-- counter -->
  <div class="counter section">
    <div class="container">
      <div class="content">
        <div class="row">
          <div class="col-md-4">
            <div class="entry">
              <span>250</span>
              Project
            </div>
          </div>
          <div class="col-md-4">
            <div class="entry">
              <span>182</span>
              Clients
            </div>
          </div>
          <div class="col-md-4">
            <div class="entry">
              <span>145</span>
              Campany
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end counter -->

  <!-- pricing -->
  <div class="pricing">
    <div class="container">
      <div class="title-section">
        <span>Pricing</span>
        <h2>Choose our<br>pricing plans</h2>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="entry">
            <h5>Start</h5>
            <h3>$50</h3>
            <ul>
              <li><span>Responsive</span></li>
              <li><span>Creative</span></li>
              <li><span>Clean Design</span></li>
              <li><span>Documentation</span></li>
              <li><span>Update</span></li>
              <li><span>Free Support</span></li>
            </ul>
            <button class="button pricing-button">Get Now</button>
          </div>
        </div>
        <div class="col-md-4">
          <div class="entry entry-center">
            <h5>Medium</h5>
            <h3>$70</h3>
            <ul>
              <li><span>Responsive</span></li>
              <li><span>Creative</span></li>
              <li><span>Clean Design</span></li>
              <li><span>Documentation</span></li>
              <li><span>Update</span></li>
              <li><span>Free Support</span></li>
            </ul>
            <button class="button">Get Now</button>
          </div>
        </div>
        <div class="col-md-4">
          <div class="entry">
            <h5>Expert</h5>
            <h3>$90</h3>
            <ul>
              <li><span>Responsive</span></li>
              <li><span>Creative</span></li>
              <li><span>Clean Design</span></li>
              <li><span>Documentation</span></li>
              <li><span>Update</span></li>
              <li><span>Free Support</span></li>
            </ul>
            <button class="button pricing-button">Get Now</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
