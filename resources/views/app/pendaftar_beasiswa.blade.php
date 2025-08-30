@extends('template')

@section('scriptHead')
<title>Pendaftar Beasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
.peserta-verifikasi {
    list-style-type: disc; /* Memastikan bullet point muncul */
    margin-left: 20px;
    padding-left: 20px; /* Pastikan ada padding untuk space bullet */
}

.peserta-verifikasi li {
    margin-bottom: 5px;
    font-size: 13px;
}

/* Mengatur tabel agar lebih rapi */
table {
    width: 100%;
    border-collapse: collapse;
}

/* Mengatur jarak antar sel tabel */
td, th {
    padding: 5px;
    text-align: left;
    vertical-align: middle;
}

/* Mengatur tata letak dalam satu baris */
.pendaftar-row {
    display: flex;
    align-items: center;
    gap: 10px; /* Memberikan jarak antara foto dan informasi */
}

/* Mengatur ukuran foto agar seragam */
.foto {
    width: 80px;
    height: 80px;
    object-fit: cover; /* Memastikan foto tetap proporsional */
    border-radius: 5px; /* Opsional: membuat sudut foto agak membulat */
}

/* Mengatur tampilan informasi mahasiswa */
.pendaftar-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Memberikan sedikit ruang antar teks */
.pendaftar-info span {
    margin-bottom: 3px;
}
</style>
@endsection

@section('container')
<div class="d-flex align-items-center mb-2">
    <iconify-icon icon="solar:users-group-rounded-outline" class="fs-9"></iconify-icon> <h2 class="mb-0 ms-2">Pendaftar Beasiswa</h2>
</div>
<div id="label-beasiswa" class="mb-2"></div>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Peserta Beasiswa</h5>
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
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%">No</th>
                                <th rowspan="2" width="45%">Data Mahasiswa</th>
                                <th colspan="3" class="text-center">Status</th>
                            </tr>
                            <tr>
                                <th width="10%">Pendaftaran</th>
                                <th width="10%">Verifikasi</th>
                                <th width="10%">Kelulusan</th>
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
    </div>
    <div class="col-lg-3">
        @include('app/menu_beasiswa')
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="form">
            <input type="hidden" name="id" id="id" >
            <input type="hidden" name="beasiswa_id" id="beasiswa_id" value="{{ $beasiswa_id }}">
            <input type="hidden" name="user_id" id="user_id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
						<div class="col-sm-12 mb-3">
                            <label class="form-label">Surveyor</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
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

<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-pembagian" role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="pembagian">
            <input type="hidden" name="surveyor_id" id="surveyor_id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Pembagian Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Jumlah</label>                            
                            <input id="jumlah" type="number" class="form-control" value="10">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Filter Wilayah</label>
                            <input id="filter_wilayah" type="text" class="form-control">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Cari</label>
                            <input id="cari" type="text" class="form-control">
                        </div>
                        <div class="col-sm-2 mb-3 d-flex">
                            <button type="button" class="btn btn-primary" id="btn-cari">Cari Data</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                            <tr>
                                <td><input type="checkbox" id="pilihsemua" ></td>
                                <td width="40%">Data Mahasiswa</td>
                                <td>Alamat/ HP</td>
                                <td>Kelurahan/ Desa/ Kecamatan</td>
                                <td>Kabupaten/ Provinsi</td>
                            </tr>
                            </thead>
                            <tbody id="daftar-peserta"></tbody>
                        </table>  
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
    const endpoint = base_url+'/api/surveyor';
    var id = "{{ $beasiswa_id }}";
    var page = 1;
    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
            await dataLoad();
            // await loadDataSelect('#program_studi_id', `data-program-studi`);
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);
        }

        function getStatusPendaftaran(dt) {
            if (dt.is_batal) return `<div class="badge rounded-pill fs-2 text-bg-danger">Batal</div>
                                    <div class="fs-2">${showText(dt.alasan_batal)}</div>`;
            if (dt.is_finalisasi==1) return `<div class="badge rounded-pill fs-2 text-bg-success">Selesai</div>
                                            <div class="badge rounded-pill fs-2 text-bg-secondary">${dt.no_pendaftaran}</div>`;
            return `<div class="badge rounded-pill fs-2 text-bg-warning">Proses</div>`;
        }

        function getStatusLulus(dt) {
            if (!dt.is_finalisasi) return ``;
            if (dt.is_lulus === 1) return `<div class="badge rounded-pill fs-2 text-bg-success">Lulus</div>`;
            if (dt.is_lulus === 0) return `<div class="badge rounded-pill fs-2 text-bg-danger">Tidak Lulus</div>`;
            return ``;
        }

        function getStatusVerifikasi(dt) {
            let status=``;
            let catatan = showText(dt.catatan_verifikasi);
            if (!dt.is_finalisasi) return status;
            
            if (dt.hasil_verifikasi === null){ 
                catatan=``;
                status= `<div class="badge rounded-pill fs-2 text-bg-warning">Belum Diproses</div>`;
            }
            else if (dt.hasil_verifikasi === 1) 
                status= `<div class="badge rounded-pill fs-2 text-bg-success">Memenuhi</div>`;
            else if (dt.hasil_verifikasi === 0) 
                status= `<div class="badge rounded-pill fs-2 text-bg-danger">TMS</div>`;
            return `${status} <div class="fs-2">${catatan}</div>`;
        }

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (response.status) {
                $.each(data, function(index, dt) {

                    const status_pendaftaran=getStatusPendaftaran(dt);
                    const status_lulus=getStatusLulus(dt);
                    const status_verifikasi=getStatusVerifikasi(dt);

                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        ${dt.nama}/  
                                        ${dt.nim}/
                                        ${dt.program_studi} 
                                    </td>
                                    <td>${status_pendaftaran}</td>
                                    <td>${showText(dt.verifikator)} ${status_verifikasi}</td>
                                    <td>${status_lulus}</td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="5">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        async function dataLoad() {
            var search = $('#search-input').val();
            var limit = 30;
            var url = `${base_url}/api/daftar-pendaftar-beasiswa/${id}?page=${page}&search=${search}&limit=${limit}`;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }    

        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });


        $(document).on('click', '#btn-cari', function() {
            loadDataPeserta();
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

    });
</script>
@endsection