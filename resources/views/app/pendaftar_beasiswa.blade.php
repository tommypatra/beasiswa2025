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
                        <button class="btn btn-secondary" id="btn-cari-data">
                            <i class="ti ti-search"></i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="solar:settings-linear" class="fs-5"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu" style="">
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-cetak">
                                        <i class="ti ti-printer"></i> Cetak
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ '/daftar-peserta-cat/'.$beasiswa_id }}" class="dropdown-item" id="btn-generate-tab4">
                                        <i class="ti ti-user"></i> Peserta CAT
                                    </a>
                                </li>
                            </ul>
                        </div>

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
<div class="modal fade modal" id="modal-filter" role="dialog">
    <div class="modal-dialog">
        <form id="form-filter">
            <input type="hidden" name="surveyor_id" id="surveyor_id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Pendaftaran</label>                            
                            <select id="status-pendaftaran" name="status-pendaftaran" class="form-control" required>
                                <option value="semua">SEMUA</option>
                                <option value="selesai">SELESAI</option>
                                <option value="belum">BELUM</option>
                                <option value="batal">BATAL</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Verifikasi</label>                            
                            <select id="status-verifikasi" name="status-verifikasi" class="form-control" required>
                                <option value="semua">SEMUA</option>
                                <option value="ms">MEMENUHI</option>
                                <option value="tms">TIDAK MEMENUHI</option>
                                <option value="selesai">SELESAI</option>
                                <option value="belum">BELUM</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Kelulusan</label>
                            <select id="status-kelulusan" name="status-kelulusan" class="form-control" required>
                                <option value="semua">SEMUA</option>
                                <option value="l">LULUS</option>
                                <option value="tl">TIDAK LULUS</option>
                                <option value="selesai">SELESAI</option>
                                <option value="belum">BELUM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn btn-primary" id="btn-terapkan-filter">Terapkan Filter</div>
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
    const id = "{{ $beasiswa_id }}";
    var page = 1;
    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
            await dataLoad();
            // await loadDataSelect('#program_studi_id', `data-program-studi`);
        }

        function initPopover() {
            $('[data-bs-toggle="popover"]').popover({
                html: true,
                trigger: 'manual',
                placement: 'top',
                sanitize: false
            }).on("mouseenter", function () {
                let _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            }).on("mouseleave", function () {
                let _this = this;
                setTimeout(function () {
                    if (!$(".popover:hover").length) {
                        $(_this).popover("hide");
                    }
                }, 200);
            });
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);
        }

        function getStatusPendaftaran(dt) {
            // buat list dokumen yg sudah diupload
            let uploadList = "";
            if (dt.upload_detail && dt.upload_detail.length > 0) {
                uploadList = `<ul class="list-unstyled m-0">`;
                dt.upload_detail.forEach(u => {
                    uploadList += `<li>
                        <a href="${base_url}/${u.dokumen}" target="_blank">${u.nama}</a>
                    </li>`;
                });
                uploadList += `</ul>`;
            } else {
                uploadList = "<em>Belum ada dokumen</em>";
            }

            const popoverAttr = `data-bs-toggle="popover" data-bs-html="true" data-bs-content='${uploadList}'`;

            if (dt.is_batal) {
                return `<div class="badge rounded-pill fs-2 text-bg-danger" ${popoverAttr}>
                            Batal ${dt.progress_upload_syarat}%
                        </div>
                        <div class="fs-2">${showText(dt.alasan_batal)}</div>`;
            }

            if (dt.is_finalisasi == 1) {
                return `<div class="badge rounded-pill fs-2 text-bg-success text-dark" ${popoverAttr}>
                            Selesai ${dt.progress_upload_syarat}%
                            
                        </div>
                        <div class="badge rounded-pill fs-2 text-bg-secondary">${dt.no_pendaftaran} 
                            <a href="${base_url}/cetak-kartu-pendaftaran/${dt.url_id}" target="_blank">
                                <iconify-icon icon="solar:printer-outline"></iconify-icon>
                            </a>
                        </div>`;
            }

            return `<div class="badge rounded-pill fs-2 text-bg-warning" ${popoverAttr}>
                        Proses ${dt.progress_upload_syarat}%
                    </div>`;
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
                status= `<div class="badge rounded-pill fs-2 text-bg-warning text-dark">Belum Diproses <a href="javascript:;" class="btn-batalkan-finalisasi" data-pendaftar_id="${dt.pendaftar_id}" data-nama="${dt.nama}"><iconify-icon icon="solar:close-square-outline" class=""></iconify-icon></a></div>`;
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
            if (response.data.data.length>0) {
                $.each(data, function(index, dt) {

                    const status_pendaftaran=getStatusPendaftaran(dt);
                    const status_lulus=getStatusLulus(dt);
                    const status_verifikasi=getStatusVerifikasi(dt);
                    const cbt=``;

                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        ${dt.nama}/  
                                        ${dt.nim}/
                                        ${dt.program_studi} 
                                    </td>
                                    <td>${status_pendaftaran}</td>
                                    <td><div>${showText(dt.verifikator)}</div> ${status_verifikasi}</td>
                                    <td>${status_lulus}</td>
                                    <td>${cbt}</td>
                                </tr>`;
                    dataList.append(row);
                });
                initPopover();
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
            var pendaftaran = $('#status-pendaftaran').val();
            var verifikasi = $('#status-verifikasi').val();
            var kelulusan = $('#status-kelulusan').val();
            var limit = 30;
            var url = `${base_url}/api/daftar-pendaftar-beasiswa/${id}?page=${page}&search=${search}&limit=${limit}&pendaftaran=${pendaftaran}&verifikasi=${verifikasi}&kelulusan=${kelulusan}`;

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
        $(document).on('click', '.btn-batalkan-finalisasi', async function() {
            let pendaftar_id = $(this).attr('data-pendaftar_id');
            let nama = $(this).attr('data-nama');
            let url = `${base_url}/api/batalkan-finalisasi/${id}/${pendaftar_id}`;

            if (confirm(`Yakin batalkan finalisasi atas nama ${nama} ?`)) {
                // prompt untuk konfirmasi terakhir
                let input = prompt(`Ketik "BATAL" untuk membatalkan finalisasi pendaftaran atas nama ${nama}:`);

                if (input.trim().toUpperCase() === 'BATAL') {
                    const response = await execAsync(url, 'GET', token);
                    dataLoad();
                } else {
                    alert('Finalisasi tidak jadi dibatalkan. Anda tidak mengetik "BATAL".');
                }
            }
        });


        $(document).on('click', '#btn-cari', function() {
            loadDataPeserta();
        });

        $('#btn-cetak').click(function(){
            const params = new URLSearchParams();
            const pendaftaran = $('#status-pendaftaran').val();
            const verifikasi   = $('#status-verifikasi').val();
            const kelulusan    = $('#status-kelulusan').val();

            if (pendaftaran) params.append('pendaftaran', pendaftaran);
            if (verifikasi)  params.append('verifikasi', verifikasi);
            if (kelulusan)   params.append('kelulusan', kelulusan);

            const url = `${base_url}/cetak-data-pendaftar/${id}?${params.toString()}`;
            window.open(url, '_blank');
        });

        // Handle page change
        $('#btn-terapkan-filter').click(function() {
            page=1;
            dataLoad();
        });

        // Handle page change
        $('#btn-cari-data').click(function() {
            page=1;
            dataLoad();
        });

        // Handle search-input
        // $(document).on('input', '#search-input', function() {
        //     console.log('Event input berjalan');
        //     dataLoad();
        // });      

        $('#btn-filter').click(function(){
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-filter'), {
                keyboard: false
            });
            fModalForm.show();
        })

    });
</script>
@endsection