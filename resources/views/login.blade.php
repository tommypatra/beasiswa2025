
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
    const base_url = '{{ url("/") }}';
  </script>
</head>

<body>
  <!--  Body Wrapper -->
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
                    <label for="email" class="form-label">User/Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
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
                  <button type="submit" data-method="simpeg" class="btn btn-success w-100 py-8 fs-4 mb-4 rounded-2">Masuk Dengan Simpeg</button>
                  <a href="{{ route('google.login') }}" class="btn btn-danger w-100 py-8 fs-4 mb-4 rounded-2">Masuk Dengan Google</a>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Belum ada akun?</p>
                    <a class="text-primary fw-bold ms-2" href="{{ route('daftar-baru','mahasiswa') }}">Daftar Akun disini</a>
                  </div>
                </form>
              </div>
            </div>
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
	$(document).ready(function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var access_token=localStorage.getItem('access_token');

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
                    }
                },
                error: function(xhr, status, error) {
                    localStorage.clear();
                }
            });
        }

        $(document).ajaxStart(function() {
            $('button[type="submit"], input[type="submit"]').prop('disabled', true);
        }).ajaxStop(function() {
            $('button[type="submit"], input[type="submit"]').prop('disabled', false);
        }).ajaxError(function() {
            $('button[type="submit"], input[type="submit"]').prop('disabled', false);
        });

		var myModalAkses = new bootstrap.Modal(document.getElementById('modal-pilih-akses'), {
			backdrop: 'static', // nda bisa klik diluar modal
			keyboard: false     // tombol esc tidak berfungsi untuk tutup modal  
		});

    $("#login-form").validate({
        rules: {
            password: {
                required: true,
                minlength: 8
            }
        },
        messages: {
            password: {
                required: "Password wajib diisi!",
                minlength: "Password minimal 8 karakter!"
            }
        },
        submitHandler: function (form) {
            var method = $('button[type="submit"]:focus').data('method');

            if (method === 'simpeg') {
                alert('Login dengan Simpeg');
            } else {
                login(form);
            }
        }
    });

    function login(form){
			$('#daftar-hakakses').html('');
			$.ajax({
				type: 'POST',
				url: `${base_url}/api/auth-cek`,
				data: $(form).serialize(),
				success: function(response) {
					if (response.status) {
						setSession(response.data);		
					} else {
						appShowNotification(false,[response.message]);
					}
				},
				error: function(xhr) {
          if (xhr.responseJSON) {
              let errorMessage = xhr.responseJSON.message || "Terjadi kesalahan. Silakan coba lagi!";
              appShowNotification(false, [errorMessage]);
          } else {
              appShowNotification(false, ["Terjadi kesalahan. Silakan coba lagi!"]);
          }
        }
			});            
        }

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


		function showModalAkses() {
			$('#daftar-akses').html('');
			var daftar_akses = localStorage.getItem('hakakses');
			var nama = localStorage.getItem('nama');
			daftar_akses = JSON.parse(daftar_akses);
			if (daftar_akses && daftar_akses.length > 1) {
				showAkses();
				myModalAkses.show();
			}else{
				window.location.replace(base_url+'/dashboard');
			}
		}

        @if(session('respon_google_login'))
			const response = @json(session('respon_google_login'));
            // console.log(response);
			if (response.status) {
				setSession(response.data);
			}
		@endif   

    });
  </script>
</body>

</html>