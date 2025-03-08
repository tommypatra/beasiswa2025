@extends('template')

@section('scriptHead')
<title>Daftar Beasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">

@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Daftar Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter">
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </div>


        <div class="row" id="data-list"></div>

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
            <input type="hidden" name="id" id="id">
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
    const gambar_beasiswa = "{{ asset('images/beasiswa.webp') }}";
    const logo_iain = "{{ asset('images/logo.png') }}";
    const endpoint = base_url + '/api/pendaftar';

    var page = 1;
    $(document).ready(function() {
        loadDataSelect('#jenis_beasiswa_id', 'data-jenis-beasiswa?limit100');
        dataLoad();

        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        function labelKostum(data, label) {
            return (data) ? `<span class='badge text-bg-success fs-1'>${label}</span>` : ""
        }

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data = response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const pendaftar=(dt.pendaftar[0])?dt.pendaftar[0]:null;
                    let butuh_data_nilai_raport = labelKostum(dt.perlu_data_nilai_raport, 'nilai raport');
                    let butuh_data_orang_tua = labelKostum(dt.perlu_data_orang_tua, 'data orang tua');
                    let butuh_data_pendidikan_akhir = labelKostum(dt.perlu_data_pendidikan_akhir, 'data pendidikan akhir');
                    let butuh_data_rumah = labelKostum(dt.perlu_data_rumah, 'data rumah');
                    let dokumen_upload = '';
                    if (dt.syarat.length > 0) {
                        $.each(dt.syarat, function(index, dt) {
                            const bg=(dt.is_wajib)?"danger":"warning";
                            dokumen_upload += `<span class='badge text-bg-${bg} fs-1'>${dt.nama}</span>`;
                        });

                    }

                    let tombol_aksi = ``;
                    if (!pendaftar) {
                        if(dt.is_pendaftaran_aktif)
                            tombol_aksi = `<a href="javascript:;" data-id="${dt.id}" class="btn btn-primary daftar-baru">Mulai Mendaftar!</a>`;
                    }else {
                        tombol_aksi = ``;
                        if(pendaftar.is_finalisasi){
                            tombol_aksi += `<a href="${base_url}/berkas-pendaftaran/${pendaftar.id}" data-beasiswa_id="${dt.id}" class="btn btn-success dokumen-pendaftaran">Data Pendaftaran</a>`;
                        }else{
                            if(pendaftar.is_batal){
                                if(dt.is_pendaftaran_aktif)
                                    tombol_aksi += `<a href="javascript:;" data-id="${pendaftar.id}" data-beasiswa_id="${pendaftar.beasiswa_id}" class="btn btn-warning daftar-kembali">Lanjutkan Kembali!</a>`;
                            }else{
                                if(dt.is_pendaftaran_aktif)
                                    tombol_aksi += `<a href="${base_url}/berkas-pendaftaran/${pendaftar.id}" data-beasiswa_id="${dt.id}" class="btn btn-primary dokumen-pendaftaran">Lanjutkan Pendaftaran</a>`;
                                tombol_aksi += `<a href="javascript:;" data-beasiswa_id="${dt.id}" data-id="${pendaftar.id}" class="btn btn-danger batalkan-pendaftaran">Batalkan Pendaftaran!</a>`;
                            }
                        }
                    }

                    let syarat_tahun_angkatan_mahasiswa = (dt.syarat_tahun_angkatan_mahasiswa)?`<div>Khusus mahasiswa angkatan : ${dt.syarat_tahun_angkatan_mahasiswa}</div>`:"";
                    let syarat_tahun_lulus_sma = (dt.syarat_tahun_lulus_sma)?`<div>Tahun syarat lulusan SMA : ${dt.syarat_tahun_lulus_sma}</div>`:"";

                    let timestamp = dt.created_at.replace('T', ' ').split('.')[0];
                    let is_pendaftaran_aktif=(dt.is_pendaftaran_aktif)?`<span class="badge text-bg-success fs-1">terbuka</span>`:`<span class="badge text-bg-danger fs-1">tertutup</span>`;

                    const row = `<div class="col-sm-6">
                                    <div class="card">
                                        <div class="position-relative">
                                            <a href="javascript:void(0)">
                                                <img src="${gambar_beasiswa}" class="card-img-top" alt="materialM-img">
                                            </a>
                                            <span class="badge text-bg-light text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">
                                               ${dt.jenis_beasiswa.nama}
                                            </span>
                                            <img src="${logo_iain}" alt="materialM-img" class="img-fluid rounded-circle position-absolute bottom-0 start-0 mb-n9 ms-9" width="40" height="40" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Georgeanna Ramero">
                                        </div>      

                                        <div class="card-body">
                                            <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm mb-3">Tahun ${dt.tahun}</span>
                                            <h5>${showText(dt.nama)}</h5>
                                            ${showText(dt.deskripsi)}
                                            <div>
                                                Pendaftaran : ${dt.daftar_mulai} sd ${dt.daftar_selesai} ${is_pendaftaran_aktif}
                                            </div>
                                            <hr>

                                            ${syarat_tahun_lulus_sma}
                                            ${syarat_tahun_angkatan_mahasiswa}
                                            <div>
                                            dokumen upload : ${dokumen_upload}
                                            </div>
                                            <div>
                                            kebutuhan data : 
                                            ${butuh_data_nilai_raport}
                                            ${butuh_data_orang_tua}
                                            ${butuh_data_pendidikan_akhir}
                                            ${butuh_data_rumah}
                                            </div>
                                            <hr>
                                            <div class="mt-2">
                                                ${tombol_aksi}
                                            </div>

                                            <div class="d-flex align-items-center gap-4 mt-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ti ti-user text-dark fs-3"></i> ${dt.pendaftar_count}
                                                </div>
                                                <div class="d-flex align-items-center fs-3 ms-auto">
                                                    <i class="ti ti-calendar text-dark"></i> ${waktuLalu(timestamp)}
                                                </div>
                                            </div>    

                                        </div>
                                    </div>
                                </div>`;
                    dataList.append(row);


                });
                renderPagination(response.data, pagination);
            } else {
                const row = `Data tidak ditemukan`;
                dataList.append(row);
            }
        }

        function dataLoad() {
            var search = $('#search-input').val();
            var url = `${endpoint}?page=${page}&search=${search}&limit=${vLimit}`;

            fetchData(url, function(response) {
                renderData(response);
            }, true);
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

        $(document).on('click', '.daftar-baru', function() {
            const id = $(this).data('id');
            const dataform = {
                beasiswa_id: id,
            };
            saveData(endpoint, 'POST', dataform, function(response) {
                appShowNotification(true, ['berhasil dilakukan!']);
                dataLoad();
            });
        });

        $(document).on('click', '.daftar-kembali', function() {
            const id = $(this).data('id');
            const beasiswa_id = $(this).data('beasiswa_id');
            $.ajax({
                type: 'PUT',
                url: `${base_url}/api/daftar-kembali/${id}`,
                async: false,
                data:{
                    beasiswa_id:beasiswa_id,
                },
                success: function(response) {
                    dataLoad();
                },
                error: function(xhr){
                    // console.log(xhr.responseJSON.message);
                    appShowNotification(false, [xhr.responseJSON.message]);
                }
            });
        });        

        $(document).on('click', '.batalkan-pendaftaran', function() {
            const id = $(this).data('id');
            const beasiswa_id = $(this).data('beasiswa_id');
            if (confirm('Kami ingatkan, apakah yakin akan membatalkan pendaftaran ini?')) {
                const alasan = prompt('wajib mengisi alasan pembatalan?');
                if (alasan !== "")
                    $.ajax({
                        type: 'PUT',
                        url: `${base_url}/api/batalkan-pendaftaran/${id}`,
                        data:{
                            alasan:alasan,
                            beasiswa_id:beasiswa_id,
                        },
                        async: false,
                        success: function(response) {
                            dataLoad();
                        },
                        error: function(xhr) {
                            appShowNotification(false, [xhr.responseJSON.message]);
                        }
                    });
                else
                    appShowNotification(false, ['gagal dilakukan!']);
            }
        });

    });
</script>
@endsection