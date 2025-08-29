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

<h2 >Penetapan Kelulusan Beasiswa</h2>
<div id="label-beasiswa" class="mb-2"></div>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Dafftar Mahasiswa</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                        <button class="btn btn-success" id="btn-refresh">
                            <i class="ti ti-reload"></i>
                        </button>
                        <button class="btn btn-success" id="btn-filter">
                            <i class="ti ti-filter"></i>
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
                                <th rowspan="2" style="vertical-align: middle;" width="5%">No</th>
                                <th rowspan="2" style="vertical-align: middle;" width="25%">Nama / Nim / Program Studi</th>
                                <th colspan="6" class="text-center" style="vertical-align: middle;">Nilai</th>
                                <th rowspan="2" style="vertical-align: middle;" width="15%">Status Lulus</th>
                                <th rowspan="2" style="vertical-align: middle;" width="5%" class="text-center">Status / Aksi</th>
                            </tr>
                            <tr>
                                <th style="vertical-align: middle;" width="10%">Ekonomi</th>
                                <th style="vertical-align: middle;" width="10%">Pendidikan</th>
                                <th style="vertical-align: middle;" width="10%">Dokumen Syarat</th>
                                <th style="vertical-align: middle;" width="10%">CBT</th>
                                <th style="vertical-align: middle;" width="10%">Survei</th>
                                <th style="vertical-align: middle;" width="10%">Wawancara</th>
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
                <h5 class="modal-title" id="modal-label">Filter Data Kelulusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Filter 1</label>
                        <select name="filter1" id="filter1" class="form-control data-filter" required>                            
                        </select>                
                    </div>
                </div>
                <div class="row">                
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Filter 2</label>
                        <select name="filter2" id="filter2" class="form-control data-filter" >                            
                        </select>                
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Filter 3</label>
                        <select name="filter3" id="filter3" class="form-control data-filter" >                            
                        </select>                
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Filter 4</label>
                        <select name="filter4" id="filter4" class="form-control data-filter" >                            
                        </select>                
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Filter 5</label>
                        <select name="filter5" id="filter5" class="form-control data-filter" >                            
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
    const endpoint = base_url+'/api/peserta-wawancara';
    const beasiswa_id="{{ $beasiswa_id }}";
    var page = 1;
    var pewawancara;
    var pendaftar_id;
    var last_page;
    var data_init;
    
    $(document).ready(function() {
        init();
        
        async function init(){
            setOptionFilter();
            await loadDataBeasiswa();
            await dataLoad();
        }

        function setOptionFilter(){
            let opsi = [
                { value: "", text: "-- Pilih --" },
                { value: "nilai_wawancara", text: "Wawancara" },
                { value: "nilai_berkas", text: "Verifikasi Berkas" },
                { value: "nilai_ekonomi", text: "Ekonomi Orang Tua" },
                { value: "nilai_pendidikan", text: "Pendidikan" },
                { value: "nilai_cbt", text: "CBT" },
            ];

            // kosongkan dulu semua select.data-filter
            $(".data-filter").empty();

            // loop untuk isi option
            $.each(opsi, function(index, item) {
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

                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        <div>
                                            ${dt.mahasiswa.nama}/  
                                            NIM ${dt.mahasiswa.nim}/
                                            ${dt.mahasiswa.program_studi}
                                        </div>
                                    </td>
                                    <td>${showText(dt.nilai.ekonomi)}</td>
                                    <td>${showText(dt.nilai.pendidikan)}</td>
                                    <td>${showText(dt.nilai.berkas)}</td>
                                    <td>${showText(dt.nilai.cbt)}</td>
                                    <td>${showText(dt.nilai.survei)}</td>
                                    <td>${showText(dt.nilai.wawancara)}</td>
                                    <td></td>
                                    <td></td>
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

            if(beasiswa.ada_verifikasi_lapangan)
              $('#menu-web-surveyor').show();
            if(beasiswa.ada_wawancara)
              $('#menu-web-pewawancara').show();
            if(beasiswa.ada_wawancara)
              $('#menu-web-soal').show();
        }

        async function dataLoad() {
            const search = $('#search-input').val();
            const filter1 = $('#filter1').val();
            const filter2 = $('#filter2').val();
            const filter3 = $('#filter3').val();
            const filter4 = $('#filter4').val();
            const filter5 = $('#filter5').val();

            var url = `${base_url}/api/kelulusan?beasiswa_id=${beasiswa_id}&search=${search}`;

            // tambahkan filter kalau ada yg dipilih
            [filter1, filter2, filter3, filter4, filter5].forEach((f, idx) => {
                if (f) {
                    url += `&sort[${idx+1}]=${f}`;
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