@extends('template')

@section('scriptHead')
<title>Kelulusan</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>

.mahasiswa-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007bff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.modal-xxl {
    max-width: 95vw;
}

.nim {
    font-weight: bold;
    color: #555;
    margin-top: 5px;
}

.study-program {
    font-size: 14px;
    color: #777;
}

.list {
    list-style-type: decimal;    
    margin-left: 20px;
    padding-left: 20px;
}

.list li {
    line-height: 1.5;
}

</style>
@endsection

@section('container')

<div class="d-flex align-items-center mb-2">
    <iconify-icon icon="solar:notebook-linear" class="fs-9"></iconify-icon> <h2 class="mb-0 ms-2">Kelulusan</h2>
</div>
<div id="label-beasiswa" class="mb-2"></div>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Daftar Mahasiswa</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                        <button class="btn btn-success" id="btn-refresh">
                            <i class="ti ti-reload"></i>
                        </button>
                        <button class="btn btn-success" id="btn-filter">
                            <iconify-icon icon="solar:sort-vertical-outline" class="fs-5"></iconify-icon>
                        </button>
                        <button class="btn btn-primary" id="btn-sinkronisasi">
                            <i class="ti ti-server"></i> Sinkronisasi
                        </button>
                    </div>
                </div>
                
                <div class="progress" style="display: none;">
                    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="vertical-align: middle;" width="5%">No</th>
                                <th style="vertical-align: middle;" width="25%">Nama / Nim / Program Studi</th>
                                <th class="text-center" style="vertical-align: middle;">Alamat</th>
                                <th class="text-center" style="vertical-align: middle;">Status Lulus/ Nilai</th>
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

<!-- MULAI MODAL FILTER-->
<div class="modal fade modal" id="modal-filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Pengaturan Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h5>Filter Data</h5>
                        <hr>
                    </div>
                    <div class="col-lg-5 mb-3">
                        <label class="form-label">Status Lulus</label>
                        <select name="status_lulus" id="status_lulus" class="form-control filter-item">
                            <option value="">-- Pilih --</option>                            
                            <option value="1">Lulus</option>                            
                            <option value="0">Tidak Lulus</option>                            
                            <option value="2">Belum Dinilai</option>                            
                        </select>                
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h5>Urut Data</h5>
                        <hr>
                    </div>
                    <div class="col-lg-5 mb-3">
                        <label class="form-label">Urut Data 1</label>
                        <select name="sort1" id="sort1" class="form-control data-filter" required>                            
                        </select>                
                    </div>
                    <div class="col-lg-5 mb-3">
                        <label class="form-label">Urut Data 2</label>
                        <select name="sort2" id="sort2" class="form-control data-filter" >                            
                        </select>                
                    </div>
                    <div class="col-lg-5 mb-3">
                        <label class="form-label">Urut Data 3</label>
                        <select name="sort3" id="sort3" class="form-control data-filter" >                            
                        </select>                
                    </div>
                    <div class="col-lg-5 mb-3">
                        <label class="form-label">Urut Data 4</label>
                        <select name="sort4" id="sort4" class="form-control data-filter" >                            
                        </select>                
                    </div>
                    <div class="col-lg-5 mb-3">
                        <label class="form-label">Urut Data 5</label>
                        <select name="sort5" id="sort5" class="form-control data-filter" >                            
                        </select>                
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" id="terapkan-filter" class="btn btn-primary " data-bs-dismiss="modal">Terapkan</button>
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- AKHIR MODAL -->

@endsection

@section('scriptJs')
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/kelulusan';
    const beasiswa_id="{{ $beasiswa_id }}";
    var page = 1;
    var pewawancara;
    var pendaftar_id;
    var last_page;
    var data_init;

    const opsiSort = [
        { value: "", text: "-- Pilih --" },
        { value: "nilai_wawancara", text: "Wawancara" },
        { value: "nilai_berkas", text: "Verifikasi Berkas" },
        { value: "nilai_ekonomi", text: "Ekonomi Orang Tua" },
        { value: "nilai_pendidikan", text: "Pendidikan" },
        { value: "nilai_cbt", text: "CBT" },
    ];
    
    const opsiFilter = {
        status_lulus: [
            { value: "", text: "-- Pilih --" },
            { value: "1", text: "Lulus" },
            { value: "0", text: "Tidak Lulus" },
            { value: "2", text: "Belum Dinilai" }
        ],
        // tanggal_mulai: { type: "date" },
        // tanggal_selesai: { type: "date" }
    };    

    $(document).ready(function() {
        init();
        
        async function init(){
            setOptionFilter();
            await loadDataBeasiswa();
            await dataLoad();
        }

        function setOptionFilter(){
            // kosongkan dulu semua select.data-filter
            $(".data-filter").empty();

            // loop untuk isi option
            $.each(opsiSort, function(index, item) {
                $(".data-filter").append(
                    $("<option>", {
                        value: item.value,
                        text: item.text
                    })
                );
            });            
        }

                //untuk show modal form
        function showModalFilter() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-filter'), {
                keyboard: false
            });
            fModalForm.show();
        }

        $('#terapkan-filter').click(function(){
            dataLoad();
        })

        $('#btn-filter').click(function(){
            showModalFilter();
        })


        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    let status_lulus = dt.status?.is_lulus; 
                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        <div>
                                            ${dt.mahasiswa.nama}/  
                                            NIM ${dt.mahasiswa.nim}/
                                            ${dt.mahasiswa.program_studi}
                                        </div>
                                    </td>
                                    <td>
                                        ${dt.mahasiswa.kabupaten} - ${dt.mahasiswa.provinsi} 
                                        <div>
                                            <span class="badge bg-secondary fs-2">${dt.mahasiswa.no_hp}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary fs-2">${dt.mahasiswa.email}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="is_lulus" class="form-control w-auto status-kelulusan mb-2" data-id="${dt.id}">
                                            <option value="" ${status_lulus === null || status_lulus === "" ? "selected" : ""}>-PILIH-</option>
                                            <option value="1" ${status_lulus === 1 ? "selected" : ""}>LULUS</option>
                                            <option value="0" ${status_lulus === 0 ? "selected" : ""}>TIDAK LULUS</option>
                                        </select>                                           

                                        <span class="badge rounded-pill fs-2 fw-medium bg-primary">Ekonomi : ${showText(dt.nilai.ekonomi)}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-primary">Pendidikan : ${showText(dt.nilai.pendidikan)}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-primary">Berkas ${showText(dt.nilai.berkas)}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-primary">CBT : ${showText(dt.nilai.cbt)}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-primary">Survei : ${showText(dt.nilai.survei)}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-primary">Wawancara : ${showText(dt.nilai.wawancara)}</span>                                        
                                    </td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="12">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        
        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);

        }

        async function dataLoad() {
            const search = $('#search-input').val();
            const status_lulus = $('#status_lulus').val();
            
            const sort1 = $('#sort1').val();
            const sort2 = $('#sort2').val();
            const sort3 = $('#sort3').val();
            const sort4 = $('#sort4').val();
            const sort5 = $('#sort5').val();

            var url = `${base_url}/api/kelulusan?beasiswa_id=${beasiswa_id}&search=${search}`;
            
            // loop semua filter
            $(".filter-item").each(function () {
                const key = $(this).attr("name");
                const val = $(this).val();
                if (val) {
                    url += `&filter[${key}]=${val}`;
                }
            });

            // ambil semua value dari select yang punya id diawali "sort"
            $('[id^="sort"]').each(function (idx) {
                const val = $(this).val();
                if (val) {
                    url += `&sort[${idx+1}]=${val}`;
                }
            });

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        async function sinkronisasi() {
            await getPeserta();
            // await hapusKelulusan();
            await prosesKelulusan();
            await dataLoad();
        }

        async function getPeserta() {
            const url = `${base_url}/api/peserta-verifikasi/${beasiswa_id}/1`;
            const res = await execNewAsync(url, 'GET', token);
            if (res.error) {
                data_init = null;
                alert(res.message);
            } else {
                data_init = res;
            }
            console.log(data_init);
        }

        async function hapusKelulusan() {
            const url = `${base_url}/api/hapus-kelulusan/${beasiswa_id}`;
            const res = await execNewAsync(url, 'DELETE', token);
            if (res && !res.error) {
                console.log('berhasil terhapus');
            }
        }

        async function prosesKelulusan() {
            const pendaftarIds = data_init.pendaftar_ids;
            const total = data_init.total;

            // Tampilkan progress bar dan reset
            $('.progress').show();
            updateProgressBar(0, total);

            // Disable tombol sinkronisasi selama proses berjalan
            $('#btn-sinkronisasi').prop('disabled', true);

            for (let i = 0; i < pendaftarIds.length; i++) {
                const pendaftarId = pendaftarIds[i];
                try {
                    const url = `${base_url}/api/proses-kelulusan`;
                    const dataPost = {
                        beasiswa: data_init.beasiswa,
                        beasiswa_id:beasiswa_id,
                        pendaftar_id: pendaftarId,
                    };                    
                    const res = await execNewAsync(url, 'POST', token, dataPost);

                    if (res.error) {
                        console.error(`Error proses pendafatar ID ${pendaftarId}: `, res.message);
                        // Bisa tambahkan UI notifikasi error di sini
                    } else {
                        console.log(`Pendaftar ID ${pendaftarId} selesai diproses`);
                    }
                } catch (err) {
                    console.error(`Exception proses pendafatar ID ${pendaftarId}: `, err);
                    // Bisa tambahkan UI notifikasi exception di sini
                }
                // Update progress
                updateProgressBar(i + 1, total);
            }

            alert('Sinkronisasi kelulusan selesai!');
            // Sembunyikan progress bar dan enable tombol lagi
            $('.progress').fadeOut();
            $('#btn-sinkronisasi').prop('disabled', false);
            updateProgressBar(0, total);

        }

        function updateProgressBar(current, total) {
            var percent = Math.round((current / total) * 100);
            var $progressBar = $('#progress-bar');
            $progressBar.css('width', percent + '%');
            $progressBar.text(percent + '% (' + current + '/' + total + ')');
        }

        $('#btn-sinkronisasi').click(async function() {
            if(confirm("apakah anda yakin ? karena semua data kelulsan akan di reset kembali!"))
                await sinkronisasi();
        });


        $('#btn-refresh').click(function() {
            dataLoad();
        });


        $(document).on('change', '.status-kelulusan', function() {
            const id = $(this).data('id');
            const url = endpoint + '/' + id;

            saveData(url, 'PUT', {is_lulus: $(this).val()}, function(response) {
                appShowNotification(true, ['berhasil dilakukan!']);
            });
        });


        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });      

        $('#catatan').summernote({
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });


    });
</script>
@endsection