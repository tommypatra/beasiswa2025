
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Layanan Beasiswa Terpadu - Masuk</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/materialm/assets/css/styles.min.css') }}" />
  <link href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
  <script type="text/javascript">
    // const base_url = "https://ioss.iainkendari.ac.id";
    const base_url = '{{ url("/") }}';
  </script>
<style>
    .login-loading {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(255, 255, 255, 0.75);

        align-items: center;
        justify-content: center;

        cursor: wait;
    }

    .login-loading-box {
        background: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,.15);
        min-width: 260px;
    }
</style>

</head>

<body>
  <!--  Body Wrapper -->

  {{-- <div class="loading-progress">Loading <img src="{{ url('images/loading-2.gif') }}"></div> --}}
<div id="content-login" style="display:none;">
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="{{ asset('images/logo-beasiswa-app.png') }}" alt="">
                </a>
                <p class="text-center">Layanan Beasiswa Terpadu</p>
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="login-form">
                  <div class="mb-3">
                    <label for="user" class="form-label">Email AKUN SIAKAD</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>

                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remeber this Device
                      </label>
                    </div>
                    {{-- <a class="text-primary fw-bold" href="./index.html">Forgot Password ?</a> --}}
                  </div>
                  <button type="submit" data-method="masuk" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Masuk</button>

                  <div class="d-flex align-items-center justify-content-center">

                        <script src="https://accounts.google.com/gsi/client" async defer></script>

                        <div id="g_id_onload"
                            data-client_id="{{$client_id}}"
                            data-callback="handleCredentialResponse">
                        </div>

                        <div class="d-flex justify-content-center mb-3">
                            <div class="g_id_signin"></div>
                        </div>

                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="login-loading" class="login-loading">
    <div class="login-loading-box">
        <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

        <div class="fw-semibold">
            Sedang login...
        </div>

        <small class="text-muted">
            Mohon tunggu sebentar
        </small>
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
		</div>
    </div>
</div>
<!-- AKHIR MODAL -->

  <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template/materialm/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
  <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js')}}"></script>
  <script src="{{ asset('js/app.js')}}"></script>
<!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script>
    function handleCredentialResponse(response){
        showLoginLoading('Sedang login dengan Google...');
        $.ajax({
            url: `${base_url}/api/login-google`,
            type: "POST",
            data: {
                credential: response.credential
            },
            dataType: "json",
            success:function(response){
                hideLoginLoading();
                if (response.status) {
                    setSession(response.data);
                } else {
                    appShowNotification(false, [
                        response.message || 'Login gagal.'
                    ]);
                }
            },
            error: function(xhr) {
                hideLoginLoading();
                let message = 'Terjadi kesalahan.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);

            }

        });
    }

    function showLoginLoading(message = 'Sedang login...') {
        $('#login-loading').css('display', 'flex');
        $('#login-loading .fw-semibold').text(message);
    }

    function hideLoginLoading() {
        $('#login-loading').hide();
    }

    var myModalAkses = new bootstrap.Modal(document.getElementById('modal-pilih-akses'), {
        backdrop: 'static', // nda bisa klik diluar modal
        keyboard: false     // tombol esc tidak berfungsi untuk tutup modal
    });

    function setSession(param){
        localStorage.setItem('access_token', param.access_token);
        localStorage.setItem('akses', param.akses);
        localStorage.setItem('email', param.user_email);
        localStorage.setItem('foto', param.foto);
        localStorage.setItem('hakakses', JSON.stringify(param.daftar_akses));
        localStorage.setItem('id', param.user_id);
        localStorage.setItem('nama', param.user_name);
        showModalAkses();
    }

    function showAkses() {

        let daftar_akses = localStorage.getItem('hakakses');

        if (!daftar_akses) {
            return;
        }

        try {
            daftar_akses = JSON.parse(daftar_akses);
        } catch (e) {
            return;
        }

        let html = '';

        daftar_akses.forEach(function (akses) {

            html += `
                <div class="mb-3">
                    <button
                        type="button"
                        class="btn btn-outline-primary w-100 text-start p-3 pilih-akses"
                        data-user-role-id="${akses.user_role_id}"
                        data-role-id="${akses.role_id}"
                    >
                        <div class="fw-bold fs-4">
                            ${akses.role}
                        </div>

                        <small class="text-muted">
                            Pilih akses sebagai ${akses.role}
                        </small>
                    </button>
                </div>
            `;
        });

        $('#daftar-akses').html(html);
    }

    function showModalAkses() {
        $('#daftar-akses').html('');

        let daftar_akses = localStorage.getItem('hakakses');

        if (!daftar_akses) {
            window.location.replace(base_url + '/dashboard');
            return;
        }

        try {
            daftar_akses = JSON.parse(daftar_akses);
        } catch (e) {
            localStorage.removeItem('hakakses');
            window.location.replace(base_url + '/dashboard');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | HANYA 1 AKSES
        |--------------------------------------------------------------------------
        */

        if (!Array.isArray(daftar_akses) || daftar_akses.length <= 1) {
            window.location.replace(base_url + '/dashboard');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | LEBIH DARI 1 AKSES → TAMPILKAN MODAL
        |--------------------------------------------------------------------------
        */

        showAkses();

        myModalAkses.show();
    }

    $(document).on('click', '.pilih-akses', function () {

        const userRoleId = $(this).data('user-role-id');
        const roleId = $(this).data('role-id');

        /*
        | Simpan akses yang dipilih
        */

        localStorage.setItem('akses', roleId);
        localStorage.setItem('user_role_id', userRoleId);

        /*
        | Tutup modal
        */

        myModalAkses.hide();

        /*
        | Masuk dashboard
        */

        window.location.replace(
            base_url + '/dashboard'
        );
    });



	$(document).ready(function() {
        $('#content-login').hide();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var access_token=localStorage.getItem('access_token');

        showLoginLoading('Cek status login...');

        if(access_token){

            $.ajax({
                headers: {
                    'Authorization': 'Bearer ' + access_token
                },
                type: 'GET',
                url: 'api/cek-akses',
                async: false,
                success: function(response) {
                    window.location.replace(base_url+'/dashboard');
                },
                complete: function(xhr) {
                    let responHeader = xhr.getResponseHeader('Authorization');
                    if (responHeader) {
                        let newToken = responHeader.replace('Bearer ', '').trim();
                        localStorage.setItem('access_token', newToken);
                        window.location.replace(base_url+'/dashboard');
                    }
                    if (xhr.status === 401) {
                        localStorage.clear();
                        $('#content-login').show();
                    }
                },
                error: function(xhr, status, error) {
                    localStorage.clear();
                    $('#content-login').show();
                }
            });
        }else{
            $('#content-login').show();
        }
        hideLoginLoading();

        // $(document)
        //     .ajaxStart(function () {
        //         $(".loading-progress").fadeIn(200);
        //         $('button[type="submit"], input[type="submit"]').prop('disabled', true);
        //     })
        //     .ajaxStop(function () {
        //         $(".loading-progress").fadeOut(200);
        //         $('button[type="submit"], input[type="submit"]').prop('disabled', false);
        //     })
        //     .ajaxError(function () {
        //         $(".loading-progress").fadeOut(200);
        //         $('button[type="submit"], input[type="submit"]').prop('disabled', false);
        //     });


        $("#login-form").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 4
                }
            },
            messages: {
                password: {
                    required: "Password wajib diisi!",
                    minlength: "Password minimal 4 karakter!"
                }
            },
            submitHandler: function (form) {
            login(form);
            }
        });


        function login(form) {
            $('#daftar-hakakses').html('');

            // const $button = $('#login-form button[type="submit"]');

            // $button.prop('disabled', true);
            // $(".loading-progress").stop(true, true).fadeIn(200);

            showLoginLoading('Sedang login...');

            $.ajax({
                type: 'POST',
                url: `${base_url}/api/login-siakad`,
                data: $(form).serialize(),
                success: function(response) {
                    // console.log(response.data);
                    // return;
                    hideLoginLoading();
                    if (response.status) {
                        setSession(response.data);
                    } else {
                        appShowNotification(false, [
                            response.message || 'Login gagal.'
                        ]);
                    }
                },
                error: function(xhr) {
                    hideLoginLoading();
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi!';

                    if (xhr.responseJSON) {
                        errorMessage =
                            xhr.responseJSON.message ||
                            errorMessage;
                    }

                    appShowNotification(false, [errorMessage]);
                },
                complete: function() {
                    // $button.prop('disabled', false);
                    // $(".loading-progress").stop(true, true).fadeOut(200);
                }
            });
        }


    });
  </script>
</body>

</html>
