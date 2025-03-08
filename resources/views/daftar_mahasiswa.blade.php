<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Mahasiswa</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <style>
        body {
            padding-top: 56px;
        }
        .ui-datepicker {
            z-index: 9999 !important;
        }        
        .tab-content {
            height: auto !important;
        }        
    </style>
    <script type="text/javascript">
        const base_url = '{{ url("/") }}';
    </script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Layanan Beasiswa IAIN Kendari</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                <!-- SmartWizard -->
                <div id="smartwizard">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link" href="#step-1"><div class="num">1</div> Akun</a></li>
                        <li class="nav-item"><a class="nav-link" href="#step-2"><span class="num">2</span> Asal Sekolah</a></li>
                        <li class="nav-item"><a class="nav-link" href="#step-3"><span class="num">3</span> Orang Tua</a></li>
                        <li class="nav-item"><a class="nav-link" href="#step-4"><span class="num">4</span> Rumah</a></li>
                        <li class="nav-item"><a class="nav-link" href="#step-5"><span class="num">5</span> Mahasiswa</a></li>
                    </ul>
                
                    <div class="tab-content">
                        <div id="step-1" class="tab-pane" role="tabpanel">
                            <form id="form-step-1">
                                <div class="row">
                                    <div class="col-lg-8 mb-3 row">
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input name="email" id="email" type="email" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Password Login</label>
                                            <input name="password" id="password" type="password" class="form-control" required minlength="8">
                                        </div>
                                        <div class="col-sm-7 mb-3">
                                            <label class="form-label">Nama</label>
                                            <input name="name" id="name" type="text" class="form-control" required minlength="3">
                                        </div>
                                        <div class="col-sm-5 mb-3">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                                <option value="">Pilih</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input name="tempat_lahir" id="tempat_lahir" type="text" class="form-control" required>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input name="tanggal_lahir" id="tanggal_lahir" type="text" class="form-control datepicker" value="{{ date('Y')-19}}-{{ date('m-d') }}" required>
                                        </div>
                                        <div class="col-sm-9 mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" id="alamat" rows="3" class="form-control" required></textarea>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label">Kota/ Kabupaten</label>
                                            <input name="wilayah_kabupaten" id="wilayah_kabupaten" data-id="" type="text" class="form-control" required>
                                            wajib ketik dan pilih
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label">Kelurahan/ Desa</label>
                                            <input name="wilayah_desa" id="wilayah_desa" data-id="" type="text" class="form-control" required>
                                            wajib ketik dan pilih
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label">Nomor HP/WA</label>
                                            <input name="no_hp" id="no_hp" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Foto Pengguna</label>
                                        <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg, image/gif" required>
                                        ukuran foto maksimal 750kb
                                        <br>
                                        <img id="previewImage" class="preview" width="100%">                
                                    </div>
                                </div>                    
                            </form>
                        </div>
                        <div id="step-2" class="tab-pane" role="tabpanel">
                            <form id="form-step-2">
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input name="tahun_lulus" id="tahun_lulus" type="number" class="form-control" value="{{ date('Y')-20 }}" required>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Jenis Sekolah</label>
                                        <select name="jenis" id="jenis" class="form-control" required>
                                            <option value="">PILIH</option>
                                            <option value="SMA">SMA</option>
                                            <option value="SMK">SMK</option>
                                            <option value="MA">MA</option>
                                            <option value="PONDOK PESANTREN">PONDOK PESANTREN</option>
                                            <option value="LAINNYA">LAINNYA</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">NISN</label>
                                        <input name="nisn" id="nisn" type="text" class="form-control" required minlength="10">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Nama Sekolah</label>
                                        <input name="nama_sekolah" id="nama_sekolah" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Jurusan</label>
                                        <select name="jurusan" id="jurusan" class="form-control" required>
                                            <option value="">PILIH</option>
                                            <option value="IPA">IPA</option>
                                            <option value="IPS">IPS</option>
                                            <option value="BAHASA">AGAMA</option>
                                            <option value="KEAGAMAAN">KEAGAMAAN</option>
                                            <option value="TEKNOLOGI">TEKNOLOGI</option>
                                            <option value="BISNIS MANAJEMEN">BISNIS MANAJEMEN</option>
                                            <option value="KESEHATAN">KESEHATAN</option>
                                            <option value="PARIWISATA">PARIWISATA</option>
                                            <option value="SENI">SENI</option>
                                            <option value="PERTANIAN">PERTANIAN</option>
                                            <option value="LAINNYA">LAINNYA</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Nilai Akhir</label>
                                        <input name="nilai_akhir_lulus" id="nilai_akhir_lulus" type="text" class="form-control" required>
                                    </div>
                                </div>
                            </form>                    
                        </div>
                        <div id="step-3" class="tab-pane" role="tabpanel">
                            <form id="form-step-3">                
                                <h5>Bapak Kandung</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input name="bapak_nama" id="bapak_nama" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <select name="pekerjaan_bapak_id" id="pekerjaan_bapak_id" class="form-control pekerjaan_id" required></select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Pendidikan</label>
                                        <select name="pendidikan_bapak_id" id="pendidikan_bapak_id" class="form-control pendidikan_id" required></select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Pendapatan</label>
                                        <select name="pendapatan_bapak_id" id="pendapatan_bapak_id" class="form-control pendapatan_id" required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Status bapak kandung</label>
                                        <select name="status_hidup_bapak_kandung" id="status_hidup_bapak_kandung" class="form-control" required>
                                            <option value="">Pilih</option>
                                            <option value="1">Hidup</option>
                                            <option value="0">Meninggal</option>
                                        </select>
                                    </div>
                                </div>
                
                                <h5>Ibu Kandung</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input name="ibu_nama" id="ibu_nama" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <select name="pekerjaan_ibu_id" id="pekerjaan_ibu_id" class="form-control pekerjaan_id" required></select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Pendidikan</label>
                                        <select name="pendidikan_ibu_id" id="pendidikan_ibu_id" class="form-control pendidikan_id" required></select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Pendapatan</label>
                                        <select name="pendapatan_ibu_id" id="pendapatan_ibu_id" class="form-control pendapatan_id" required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Status ibu Kandung</label>
                                        <select name="status_hidup_ibu_kandung" id="status_hidup_ibu_kandung" class="form-control" required>
                                            <option value="">Pilih</option>
                                            <option value="1">Hidup</option>
                                            <option value="0">Meninggal</option>
                                        </select>
                                    </div>
                                </div>            
                            </form>
                        </div>
                        <div id="step-4" class="tab-pane" role="tabpanel">                            
                            <form id="form-step-4">
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Status Rumah</label>
                                        <select name="status_id" id="status_id" class="form-control" required></select>
                                    </div>
                                </div>                    
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Luas Tanah</label>
                                        <input name="luas_tanah" id="luas_tanah" type="text" class="form-control" required>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Luas Bangunan</label>
                                        <input name="luas_bangunan" id="luas_bangunan" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Rata rata bayar Listrik</label>
                                        <select name="bayar_listrik_id" id="bayar_listrik_id" class="form-control" required></select>
                                        akumulasi dalam satu bulan
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Jumlah Orang Tinggal</label>
                                        <input name="jumlah_orang_tinggal" id="jumlah_orang_tinggal" type="number" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">MCK</label>
                                        <select name="mck_id" id="mck_id" class="form-control" required></select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Sumber Air</label>
                                        <select name="sumber_air_id" id="sumber_air_id" class="form-control" required></select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Sumber Listrik</label>
                                        <select name="sumber_listrik_id" id="sumber_listrik_id" class="form-control" required></select>
                                    </div>
                                </div>
                            </form>                            
                        </div>

                        <div id="step-5" class="tab-pane" role="tabpanel">
                            <form id="form-step-5">
                                <div class="row">
                                    <div class="col-lg-8 mb-3 row">
                                        <div class="col-sm-5 mb-3">
                                            <label class="form-label">Tahun Masuk</label>
                                            <input name="tahun_masuk" id="tahun_masuk" type="number" class="form-control" value="{{ date('Y') }}" required>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">NIM</label>
                                            <input name="nim" id="nim" type="text" class="form-control" required>
                                        </div>
                                        <div class="col-sm-12 mb-3">
                                            <label class="form-label">Program Studi</label>
                                            <select name="program_studi_id" id="program_studi_id" class="form-control" required></select>
                                        </div>
                                        <div class="col-sm-5 mb-3">
                                            <label class="form-label">Sumber Biaya</label>
                                            <select name="sumber_biaya_id" id="sumber_biaya_id" class="form-control" required></select>
                                        </div>
                                        </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="form-label">Kartu Mahasiswa</label>
                                        <input type="file" id="kartu_mahasiswa" name="kartu_mahasiswa" accept="image/png, image/jpeg, image/jpg, image/gif" required>
                                        ukuran foto maksimal 750kb
                                        <br>
                                        <img id="previewKartuMahasiswa" class="preview" width="100%">                
                                    </div>
                                </div>
                            </form>
                    
                        </div>

                    </div>
                </div>  

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-5">
        <p class="mb-0">© 2024 Seleksi Beasiswa - Powered by Bootstrap 5.3</p>
    </footer>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>    
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SmartWizard -->
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js"></script>    

    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
  
    <script>
      

        $(document).ready(function() {
            var data;

            initPage();
            async function initPage() {
                await loadDataReferensi();
                await loadOptionSelect(".pekerjaan_id", "Pekerjaan", data);
                await loadOptionSelect(".pendidikan_id", "Pendidikan", data);
                await loadOptionSelect(".pendapatan_id", "Pendapatan", data);
                await loadOptionSelect("#sumber_air_id", "Sumber Air", data);
                await loadOptionSelect("#sumber_listrik_id", "Sumber Listrik", data);
                await loadOptionSelect("#bayar_listrik_id", "Listrik", data);
                await loadOptionSelect("#mck_id", "MCK", data);
                await loadOptionSelect("#status_id", "Kepemilikan Rumah", data);
                await loadOptionSelect("#sumber_biaya_id", "Sumber Biaya", data);
                await loadProgramStudi('#program_studi_id');
            }

            let btnSubmit = $('<button></button>')
                            .text('Buat Akun Sekarang')
                            .addClass('btn btn-success sw-btn-submit')
                            .hide()
                            .on("click", function() {
                                let form1 = $("#form-step-1"); 
                                let form2 = $("#form-step-2"); 
                                let form3 = $("#form-step-3"); 
                                let form4 = $("#form-step-4"); 
                                let form5 = $("#form-step-5"); 
                                if (!form1.valid()) { 
                                    alert("cek inputan pada data akun");
                                }else if (!form2.valid()) { 
                                    alert("cek inputan pada data asal sekolah");
                                }else if (!form3.valid()) { 
                                    alert("cek inputan pada data orang tua");
                                }else if (!form4.valid()) { 
                                    alert("cek inputan pada data rumah");
                                }else if (!form5.valid()) { 
                                    // alert("cek inputan pada data mahasiswa");
                                }else{
                                    if(confirm("apakah anda yakin akan membuat akun sekarang? jangan lupa catat email dan password anda ya!"))
                                        daftar(); 
                                }                             
                            });

            $('#smartwizard').smartWizard({
                theme: 'arrows',
                selected: 0,
                transition: {
                    animation: 'slideHorizontal'
                }                
            });
            $(".toolbar-bottom").append(btnSubmit);

            async function loadProgramStudi(select_id) {
                var url = base_url + '/api/data-program-studi?limit=200';
                let $select = $(select_id);
                $select.empty();
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const result = await response.json();

                    $.each(result.data.data, function (grup, items) {
                        let $option = $("<option>", { value: items.id, text: items.nama });
                        $select.append($option);
                    });

                } catch (error) {
                    console.error('Error:', error);
                }
            } 

            async function loadDataReferensi() {
                var url = base_url + '/api/data-referensi?limit=200';
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const result = await response.json();
                    // console.log(result);
                    data=result.data.data;
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            
            function daftar() {
                let formData = new FormData();
                $("#form-step-1, #form-step-2, #form-step-3, #form-step-4, #form-step-5").find("input, select, textarea").each(function() {
                    if ($(this).attr("type") === "file") {
                        if (this.files.length > 0) {
                            formData.append($(this).attr("name"), this.files[0]);
                        }
                    } else {
                        formData.append($(this).attr("name"), $(this).val());
                    }
                });
                formData.append("wilayah_desa_id", $('#wilayah_desa').data("id"));

                $.ajax({
                    url: base_url+'/api/simpan-pendaftaran-mahasiswa',
                    type: 'POST',
                    data: formData,
                    processData: false,  
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        // appShowNotification(true,['berhasil dilakukan! silahkan login menggunakan akun anda']);
                        alert('pendaftaran berhasil dilakukan! silahkan login menggunakan akun anda');
                        window.location.replace(base_url+'/login');
                    },
                    error: function(xhr, status, error) {
                        let notif="Terjadi kesalahan. Silakan coba lagi!";
                        if (xhr.responseJSON) {
                            let errorMessage = xhr.responseJSON.message || notif;
                            appShowNotification(false, [errorMessage]);
                        } else {
                            appShowNotification(false, [notif]);
                        }
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

            $("#form-step-1").validate({
                rules:{
                    email:{
                        remote:{
                            url: base_url + "/api/cek-email", // Ganti dengan API pengecekan email
                            type: "GET",
                            data: { 
                                email: function() {
                                    return $("#email").val();
                                } 
                            },
                            dataFilter: function (response) {
                                let res = JSON.parse(response);
                                if (res.status) {
                                    return JSON.stringify(res.message);
                                }
                                return "true";                            
                            }
                        }
                    }
                },
                messages: {
                    email: {
                        remote: "Email sudah terdaftar"
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });
            $("#form-step-2").validate();
            $("#form-step-3").validate();
            $("#form-step-4").validate();
            $("#form-step-5").validate({
                rules:{
                    nim:{
                        remote:{
                            url: base_url + "/api/cek-nim", // Ganti dengan API pengecekan email
                            type: "GET",
                            data: { 
                                nim: function() {
                                    return $("#nim").val();
                                } 
                            },
                            dataFilter: function (response) {
                                let res = JSON.parse(response);
                                if (res.status) {
                                    return JSON.stringify(res.message);
                                }
                                return "true";                            
                            }
                        }
                    }
                },
                messages: {
                    nim: {
                        remote: "NIM sudah terdaftar"
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });

            $('#smartwizard').on("leaveStep", function(e, anchorObject, stepIndex, stepDirection) {
                // console.log("dari step:", stepIndex, "ke:", stepDirection);
                if (stepDirection > stepIndex) {
                    let form = $("#form-step-" + (stepIndex + 1));
                    if (form.length > 0) {
                        form.validate(); 
                        if (!form.valid()) {
                            return false;
                        }
                    }
                }
                return true;
            });

            //------------------ js untuk form 1

            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd",
            });

            $("#wilayah_kabupaten").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: base_url+"/api/data-kabupaten",
                        type: "GET",
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function (respon) {
                            response($.map(respon.data.data, function (item) {
                                return {
                                    label: item.nama, 
                                    value: item.nama, 
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                minLength: 3,
                select: function (event, ui) {
                    $(this).val(ui.item.value); 
                    $(this).attr("data-id", ui.item.id);
                    return false;
                }
            });

            $("#wilayah_desa").autocomplete({
                source: function (request, response) {
                    let kabupaten_id = $('#wilayah_kabupaten').data("id");
                    // console.log(kabupaten_id);
                    if (!kabupaten_id) {
                        return;
                    }

                    $.ajax({
                        url: base_url+"/api/data-desa",
                        type: "GET",
                        dataType: "json",
                        data: {
                            search: request.term,
                            wilayah_kabupaten_id: kabupaten_id,
                        },
                        success: function (respon) {
                            response($.map(respon.data.data, function (item) {
                                return {
                                    label: item.desa, 
                                    value: item.desa, 
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                minLength: 3,
                select: function (event, ui) {
                    $(this).val(ui.item.value); 
                    $(this).attr("data-id", ui.item.id);
                    return false;
                }
            });

            $('#foto').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            //------------------ js untuk form 2

            $('#nama_sekolah').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            //------------------ js untuk form mahasiswa

            $('#kartu_mahasiswa').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewKartuMahasiswa').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#smartwizard').on("showStep", function(e, anchorObject, stepIndex) {
                if (stepIndex == 0) {
                    $(".sw-btn-next").show(); 
                    $(".sw-btn-prev").hide(); 
                    $(".sw-btn-submit").hide();
                }else if(stepIndex >= 1 && stepIndex <= 3) {
                    $(".sw-btn-next").show(); 
                    $(".sw-btn-prev").show();  
                    $(".sw-btn-submit").hide();
                }else{
                    $(".sw-btn-next").hide(); 
                    $(".sw-btn-prev").show(); 
                    $(".sw-btn-submit").show(); 
                }
            });

        });
    </script>

</body>
</html>
