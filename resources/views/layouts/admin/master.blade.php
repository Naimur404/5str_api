<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{!! $app_setting->description !!}">
    <meta name="keywords" content="">
    <meta name="author" content="Pigeon Soft">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{asset('icon/'.$app_setting->favicon)}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('icon/'.$app_setting->favicon)}}" type="image/x-icon">
    <title>
        @if(View::hasSection('title'))
        @yield('title')
    @else
 {{ $app_setting->app_name }}
    @endif
</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    @includeIf('layouts.admin.partials.css')
  </head>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="theme-loader"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      @includeIf('layouts.admin.partials.header')
      <!-- Page Header Ends -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper horizontal-menu">
        <!-- Page Sidebar Start-->
        @includeIf('layouts.admin.partials.sidebar')
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <!-- Container-fluid starts-->
          @yield('content')
          <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 footer-copyright">
                <p class="mb-0">{{ $app_setting->footer_text }}</p>
              </div>
              <div class="col-md-6">
                <p class="pull-right mb-0">{{ env('VERSION') }} <i class="fa fa-heart font-secondary"></i></p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- latest jquery-->
    @includeIf('layouts.admin.partials.js')

    <script type="text/javascript">
        {{-- localStorage.clear(); --}}
        var div = document.querySelector("div.page-wrapper")
        if(div.classList.contains('compact-sidebar')){
            div.classList.remove("compact-sidebar");
        }
        if(div.classList.contains('modern-sidebar')){
          div.classList.remove("modern-sidebar");
        }
    </script>

  </body>
</html>
