@extends('template')

@section('scriptHead')
<title>Data Beasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">

@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Data Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-primary" id="btn-tambah">
                    <i class="ti ti-plus"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter">
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tahun</th>
                        <th width="30%">Beasiswa</th>
                        <th width="10%">Jenis</th>
                        <th width="10%">Pendaftaran</th>
                        <th width="10%">Pengumuman</th>
                        <th width="30%">Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="form">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Tahun</label>
                            <input name="tahun" id="tahun" type="number" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Jenis Beasiswa</label>
                            <select name="jenis_beasiswa_id" id="jenis_beasiswa_id" class="form-control" required></select>
                        </div>
						<div class="col-sm-8 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
						<div class="col-sm-5 mb-3">
                            <input type="checkbox" name="ada_wawancara" id="ada_wawancara" value="1"> Ada Wawancara
                        </div>
                        <div class="col-sm-5 mb-3">
                            <input type="checkbox" name="ada_survei_lapangan" id="ada_survei_lapangan" value="1"> Ada Survei
                        </div>
                    </div>

                    <h5>Kebutuhan Data</h5>
                    <hr>
                    <div class="row">

                        <div class="col-sm-4 mb-3">
                            <input type="checkbox" name="butuh_data_orang_tua" id="butuh_data_orang_tua" value="1"> Orang Tua
                        </div>
                        <div class="col-sm-4 mb-3">
                            <input type="checkbox" name="butuh_data_pendidikan_akhir" id="butuh_data_pendidikan_akhir" value="1"> Asal Sekolah
                        </div>
                        <div class="col-sm-4 mb-3">
                            <input type="checkbox" name="butuh_data_nilai_raport" id="butuh_data_nilai_raport" value="1"> Nilai Raport
                        </div>
                        <div class="col-sm-4 mb-3">
                            <input type="checkbox" name="butuh_data_rumah" id="butuh_data_rumah" value="1"> Rumah
                        </div>
                    </div>
                        
                    <h5>Jadwal Beasiswa</h5>
                    <hr>
                    <div class="row">
						<div class="col-sm-3 mb-3">
                            <label class="form-label">Pendaftaran</label>
                            <input name="daftar_mulai" id="daftar_mulai" type="text" class="form-control datepicker" required>
                            sd
                            <input name="daftar_selesai" id="daftar_selesai" type="text" class="form-control datepicker" required>
                        </div>
						<div class="col-sm-3 mb-3">
                            <label class="form-label">Verifikasi</label>
                            <input name="verifikasi_berkas_mulai" id="verifikasi_berkas_mulai" type="text" class="form-control datepicker" required>
                            sd
                            <input name="verifikasi_berkas_selesai" id="verifikasi_berkas_selesai" type="text" class="form-control datepicker" required>
                        </div>
						<div class="col-sm-3 mb-3">
                            <label class="form-label">Lapangan</label>
                            <input name="survei_lapangan_mulai" id="survei_lapangan_mulai" type="text" class="form-control datepicker" required>
                            sd
                            <input name="survei_lapangan_selesai" id="survei_lapangan_selesai" type="text" class="form-control datepicker" required>
                        </div>
						<div class="col-sm-3 mb-3">
                            <label class="form-label">Wawancara</label>
                            <input name="wawancara_mulai" id="wawancara_mulai" type="text" class="form-control datepicker" required>
                            sd
                            <input name="wawancara_selesai" id="wawancara_selesai" type="text" class="form-control datepicker" required>
                        </div>
                    </div>

                    <div class="row">
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Pengumuman Verifikasi Verkas</label>
                            <input name="pengumuman_verifikasi_berkas" id="pengumuman_verifikasi_berkas" type="text" class="form-control datepicker" required>
                        </div>
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Pengumuman Kelulusan Beasiswa</label>
                            <input name="pengumuman_akhir" id="pengumuman_akhir" type="text" class="form-control datepicker" required>
                        </div>
                    </div>                    
                    <div class="row">
						<div class="col-sm-6 mb-3">
                            <label class="form-label">Syarat Tahun Lulus SMA</label>
                            <input name="syarat_tahun_lulus_sma" id="syarat_tahun_lulus_sma" type="text" class="form-control">
                            <i>tanda koma (,) untuk memisahkan lebih dari 1 tahun</i>
                        </div>
						<div class="col-sm-6 mb-3">
                            <label class="form-label">Syarat Tahun Angkatan Mahasiswa</label>
                            <input name="syarat_tahun_angkatan_mahasiswa" id="syarat_tahun_angkatan_mahasiswa" type="text" class="form-control">
                            <i>tanda koma (,) untuk memisahkan lebih dari 1 tahun</i>
                        </div>
                    </div>                    

                    <div class="row">
						<div class="col-sm-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control"></textarea>
                        </div>
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_aktif" id="is_aktif" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->
@endsection

@section('scriptJs')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>

<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/beasiswa';
    var page = 1;
    $(document).ready(function() {
        dataLoad();
        loadDataSelect('#jenis_beasiswa_id','data-jenis-beasiswa?limit100');

        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        $('#deskripsi').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],            
            callbacks: {               
                onImageUpload: function(files) {
                    sendFile(files[0], $(this));
                }
            }
        });

        function sendFile(file, editor) {
            var data = new FormData();
            data.append("gambar", file);
            $.ajax({
                data: data,
                type: "POST",
                url: `${base_url}/api/upload-gambar-beasiswa`,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    editor.summernote('insertImage', response.data);
                }
            });
        }

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>${dt.tahun}</td>
                                    <td>${dt.nama}</td>
                                    <td>${dt.jenis_beasiswa.nama}</td>
                                    <td>${dt.daftar_mulai} sd ${dt.daftar_selesai}</td>
                                    <td>${dt.pengumuman_akhir}</td>
                                    <td>${showText(dt.keterangan)}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item btn-ganti" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                                <li><a class="dropdown-item btn-hapus" data-id="${dt.id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="7">
                                        <a href="${base_url}/syarat/${dt.id}">
                                            <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1"> 
                                                Syarat
                                            </span>                                  
                                        </a>      
                                        <a href="{{ route('soal-wawancara') }}/${dt.id}">
                                            <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">
                                                Soal Ujian
                                            </span>                                  
                                        </a>      
                                        <a href="{{ route('soal-wawancara') }}/${dt.id}">
                                            <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">
                                                Soal Wawancara
                                            </span>                                  
                                        </a>      
                                        <a href="${base_url}/verifikator/${dt.id}">
                                            <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">
                                                Verifikator
                                            </span>                                  
                                        </a>      
                                        <a href="${base_url}/surveyor/${dt.id}">
                                            <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">
                                                Surveyor
                                            </span>                                  
                                        </a>      
                                        <a href="${base_url}/pewawancara/${dt.id}">
                                            <span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">
                                                Pewawancara
                                            </span>                                  
                                        </a>      
                                    </td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="8">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        function dataLoad() {
            var search = $('#search-input').val();
            var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            dataLoad();
        });

        // Handle search-input
        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });        

        //untuk show modal form
        function showModalForm() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        function formReset(){
            $('#form').trigger('reset');
            $('#form input[type="hidden"]').val('');
            $('#deskripsi').summernote('code', '');
        }

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
            $('#tahun').val("{{ date('Y') }}");
            $('#syarat_tahun_angkatan_mahasiswa').val("");
            $('#syarat_tahun_lulus_sma').val("");
            $('#daftar_mulai').val("{{ date('Y-m-d') }}");
            $('#daftar_selesai').val("{{ date('Y-m-d') }}");
            $('#verifikasi_berkas_mulai').val("{{ date('Y-m-d') }}");
            $('#verifikasi_berkas_selesai').val("{{ date('Y-m-d') }}");
            $('#survei_lapangan_mulai').val("{{ date('Y-m-d') }}");
            $('#survei_lapangan_selesai').val("{{ date('Y-m-d') }}");
            $('#wawancara_mulai').val("{{ date('Y-m-d') }}");
            $('#wawancara_selesai').val("{{ date('Y-m-d') }}");
            $('#pengumuman_verifikasi_berkas').val("{{ date('Y-m-d') }}");
            $('#pengumuman_akhir').val("{{ date('Y-m-d') }}");
            $('#butuh_data_orang_tua').prop("checked", true);
            $('#butuh_data_pendidikan_akhir').prop("checked", true);
            $('#is_aktif').val(1);
            
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                saveData(url, type, $(form).serialize(), function(response) {
                    //jika berhasil
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(type=='POST'){
                        formReset();
                    }
                    dataLoad();
                });
            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            formReset();
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#jenis_beasiswa_id').val(response.data.jenis_beasiswa_id);
                $('#syarat_tahun_angkatan_mahasiswa').val(response.data.syarat_tahun_angkatan_mahasiswa);
                $('#syarat_tahun_lulus_sma').val(response.data.syarat_tahun_lulus_sma);
                
                $('#nama').val(response.data.nama);
                $('#tahun').val(response.data.tahun);
                $('#daftar_mulai').val(response.data.daftar_mulai);
                $('#daftar_selesai').val(response.data.daftar_selesai);
                $('#verifikasi_berkas_mulai').val(response.data.verifikasi_berkas_mulai);
                $('#verifikasi_berkas_selesai').val(response.data.verifikasi_berkas_selesai);
                $('#survei_lapangan_mulai').val(response.data.survei_lapangan_mulai);
                $('#survei_lapangan_selesai').val(response.data.survei_lapangan_selesai);

                $('#wawancara_mulai').val(response.data.wawancara_mulai);
                $('#wawancara_selesai').val(response.data.wawancara_selesai);
                $('#pengumuman_verifikasi_berkas').val(response.data.pengumuman_verifikasi_berkas);
                $('#pengumuman_akhir').val(response.data.pengumuman_akhir);


                if(response.data.ada_wawancara)
                    $('#ada_wawancara').prop("checked", true);
                if(response.data.ada_survei_lapangan)
                    $('#ada_survei_lapangan').prop("checked", true);

                if(response.data.butuh_data_orang_tua)
                    $('#butuh_data_orang_tua').prop("checked", true);
                if(response.data.butuh_data_nilai_raport)
                    $('#butuh_data_nilai_raport').prop("checked", true);
                if(response.data.butuh_data_orang_tua)
                    $('#butuh_data_pendidikan_akhir').prop("checked", true);
                if(response.data.butuh_data_rumah)
                    $('#butuh_data_rumah').prop("checked", true);
                $('#is_aktif').val(response.data.is_aktif);    
                $('#deskripsi').summernote('code', response.data.deskripsi);

                showModalForm();
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            deleteData(endpoint, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                dataLoad();
            });
        });

    });
</script>
@endsection