
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
    .loading-progress {
        position: fixed;
        top: 10px;
        right: 10px;
        background: rgba(51, 51, 51, 0.9);
        color: #fff;
        padding: 6px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 14px;
        z-index: 99999;
        display: none; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        transition: opacity 0.3s ease;
    }
    .loading-progress img {
        height: 30px;
    }
  </style>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="loading-progress">Loading <img src="{{ url('images/loading-2.gif') }}"></div>
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
                    <label for="user" class="form-label">User AKUN SIA</label>
                    <input type="text" class="form-control" id="user" name="user" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>

                  <div class="mb-4">
                    <label class="form-label">Login Sebagai</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="status" id="mahasiswa" value="mahasiswa" required>
                      <label class="form-check-label" for="mahasiswa">
                        Mahasiswa
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="status" id="dosen" value="dosen" required>
                      <label class="form-check-label" for="dosen">
                        Dosen/ Pegawai
                      </label>
                    </div>
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
                    <p class="fs-4 mb-0 fw-bold">silahkan login menggunakan akun SIA masing masing</p>
                    <a class="text-primary fw-bold ms-2" href="{{ url('/login-email') }}">Login pakai email disini</a>
                    
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

        $(document)
            .ajaxStart(function () {
                $(".loading-progress").fadeIn(200);
                $('button[type="submit"], input[type="submit"]').prop('disabled', true);
            })
            .ajaxStop(function () {
                $(".loading-progress").fadeOut(200);
                $('button[type="submit"], input[type="submit"]').prop('disabled', false);
            })
            .ajaxError(function () {
                $(".loading-progress").fadeOut(200);
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

    function cekDataAkunSia(dataPost){
        $.ajax({
          type: 'POST',
          url: `${base_url}/api/cek-data-akun-sia`,
          data:   dataPost,
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

    function cekDataPegawai(dataPost){
        appAjax("https://kkn.iainkendari.ac.id/cek-data-pegawai", dataPost).done(function(vRet) {
            if(vRet.status) {
                let timerInterval;
                Swal.fire({
                title: 'Login Berhasil!',
                html: 'Anda akan di arahkan secara otomatis dalam <b></b> milliseconds, silahkan menunggu',
                timer: 2000,
                icon: 'success',
                allowOutsideClick: false,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        location.href = "app/dashboard";
                    }
                })
            }
        });
    }

    function login(form){
			$('#daftar-hakakses').html('');
			$.ajax({
				type: 'POST',
				url: `https://sia.iainkendari.ac.id/api/kkn/login`,
				data: $(form).serialize(),
				success: function(response) {
					if (response.status) {
            response.data.grup = response.grup;
            const dataPost = $.param(response.data);
            cekDataAkunSia(dataPost);
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

    });
  </script>
</body>

</html>