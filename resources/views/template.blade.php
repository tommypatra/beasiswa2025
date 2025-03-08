<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Layanan Beasiswa Terpadu</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/materialm/assets/css/styles.min.css?v=2') }}" />
  <link href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
  <style>
    .menu-admin, 
    .menu-mahasiswa, 
    .menu-surveyor, 
    .menu-verifikator, 
    .menu-pengelola, 
    .menu-pewawancara {
      display: none; /* Default sembunyi */
    }
  </style>
  <script type="text/javascript">
    const base_url = '{{ url("/") }}';
    var user_id;
  </script>
  @yield('scriptHead')  
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!--  App Topstrip -->
    <div class="app-topstrip bg-primary py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
        <a class="d-flex justify-content-center" href="https://www.wrappixel.com/" target="_blank">
          <img src="{{ asset('images/logo.png') }}" alt="" height="45px">
        </a>

        <div class="d-none d-xl-flex align-items-center gap-3">
          <a href="https://support.wrappixel.com/"
            class="btn btn-outline-primary d-flex align-items-center gap-1 border-0 text-white px-6">
            <i class="ti ti-lifebuoy fs-5"></i>
            IAIN Kendari
          </a>
          <a href="https://www.wrappixel.com/"
            class="btn btn-outline-primary d-flex align-items-center gap-1 border-0 text-white px-6">
            <i class="ti ti-gift fs-5"></i>
            Akademik
          </a>
        </div>
      </div>

      <div class="d-lg-flex align-items-center gap-2">
        <h3 class="text-white mb-2 mb-lg-0 fs-5 text-center">Layanan Beasiswa IAIN Kendari</h3>
      </div>

    </div>

    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="{{ asset('images/logo-beasiswa-app.png') }}" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            @include('partials.menu_mahasiswa')
            @include('partials.menu_surveyor')
            @include('partials.menu_pewawancara')
            @include('partials.menu_verifikator')
            @include('partials.menu_pengelola')
            @include('partials.menu_admin')
            <li>
              <span class="sidebar-divider lg"></span>
            </li>  
            
            <div class="unlimited-access d-flex align-items-center hide-menu bg-secondary-subtle position-relative mb-7 mt-4 p-3 rounded-3">
              <div class="flex-shrink-0">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-75 lh-sm">Ganti akses akun anda</h6>
                <a href="javascript:;" class="btn btn-secondary fs-2 fw-semibold lh-sm" id="ganti-akses">Ganti akses</a>
              </div>
              <div class="unlimited-access-img">
                <img src="{{ asset('images/key-user.png') }}" alt="" class="img-fluid">
              </div>
            </div>            
          </ul>        
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->


    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link " href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                <div class="message-body">
                  <a href="javascript:void(0)" class="dropdown-item">
                    Item 1
                  </a>
                  <a href="javascript:void(0)" class="dropdown-item">
                    Item 2
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <div class="fs-5 me-2" id="user-name">Pengguna</div>
                  <img src="{{ asset('images/user-avatar.png') }}" alt="" width="35" height="35" class="rounded-circle" id="user-foto">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  
                  <div class="message-body">
                    <a href="{{ route('identitas') }}" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">Identitas</p>
                    </a>
                    <a href="javascript:;" onclick="forceLogout()" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <!--  Row 1 -->
          <div class="row">
            @yield('container')
          </div>

          <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Design and Developed by <a href="https://www.wrappixel.com/" target="_blank"
                class="pe-1 text-primary text-decoration-underline">Wrappixel.com</a>
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- MULAI MODAL -->
  <div class="modal fade" id="modal-pilih-akses" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">PILIH AKSES AKUN</h5>
      </div>
      <div class="modal-body" id="daftar-akses">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
    </div>
  </div>
  <!-- AKHIR MODAL -->	

  <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template/materialm/assets/js/app.min.js') }}"></script>

  <script src="{{ asset('template/materialm/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/materialm/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('template/materialm/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('template/materialm/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('template/materialm/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js')}}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    const token = localStorage.getItem('access_token');
    function forceLogout(){
        localStorage.clear();
        window.location.replace(`${base_url}/login`);
      }

    $(document).ready(function() {
      $.ajaxSetup({
        beforeSend: function(xhr) {
          xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        },          
        complete: function(xhr) {
          //baca respon jika ada authorization maka refresh token
          let responHeader = xhr.getResponseHeader('Authorization');
          if (responHeader) {
            let newToken = responHeader.replace('Bearer ', '').trim();
            access_token = newToken;
            localStorage.setItem('access_token', newToken);
          }
          //jika token tidak berlaku
          if (xhr.status === 401) {
              forceLogout();
          }
        }
      });

      $(document).ajaxStart(function() {
        $('#navbar-loading').show();
        $('button[type="submit"], input[type="submit"]').prop('disabled', true);
      }).ajaxStop(function() {
        $('#navbar-loading').hide();
        $('button[type="submit"], input[type="submit"]').prop('disabled', false);
      });

      cekAkses();
      $(".sidebartoggler").click(function () {
          let wrapper = $("#main-wrapper");
          if (wrapper.hasClass("mini-sidebar")) {
              wrapper.removeClass("mini-sidebar").addClass("show-sidebar");
              wrapper.attr("data-sidebartype", "full");
          }
      }); 

      $("#sidebarCollapse").click(function () {
          let wrapper = $("#main-wrapper");
          if (wrapper.hasClass("show-sidebar")) {
              wrapper.removeClass("show-sidebar").addClass("mini-sidebar");
              wrapper.attr("data-sidebartype", "mini-sidebar");
          }
      }); 

      var akses = localStorage.getItem('akses');
      switch (akses) {
          case '1':
            $('.menu-admin').show();
            break;
          case '2':
            $('.menu-mahasiswa').show();
            break;
          case '3':
            $('.menu-surveyor').show();
            break;
          case '4':
            $('.menu-verifikator').show();
            break;
          case '5':
            $('.menu-pewawancara').show();
            break;
          case '6':
            $('.menu-pengelola').show();
            break;
      }      

      $('#user-foto').attr('src',base_url+'/'+localStorage.getItem('foto'));
      $('#user-name').text(localStorage.getItem('nama'));
      $('#ganti-akses').click(function(){
				showAkses();
        showModal('modal-pilih-akses');
      });
    });
  </script>
  @yield('scriptJs')
</body>

</html>