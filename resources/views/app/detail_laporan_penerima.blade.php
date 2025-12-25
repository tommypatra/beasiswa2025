@extends('template')

@section('scriptHead')
<title>Detail Laporan Penerima Beasiswa</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Detail Laporan Penerima Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-primary" id="btn-search">
                    <i class="ti ti-search"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter">
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </div>
        
        <div id="detail-laporan" class="vstack gap-3 "></div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog modal-xl">
        <form id="form">
            <input type="hidden" id="id" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Validasi Laporan Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 mt-2">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3">
                                        <img id="mv_foto" src="" alt="foto mahasiswa" src="{{ asset('images/user-avatar.png') }}"
                                        class="rounded border" style="width:80px;height:80px;object-fit:cover">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0" id="mv_nama">Nama Mahasiswa</h6>
                                            <div class="small text-muted" id="mv_nim">NIM: -</div>
                                            <div class="mt-2">
                                                <span class="badge text-bg-light border" id="mv_prodi">Program Studi</span>
                                                <span class="badge text-bg-light border" id="mv_fakultas">Fakultas</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled small mb-0">
                                        <li><strong>Email:</strong> <span id="mv_email">-</span></li>
                                        <li class="mt-1"><strong>No. HP:</strong> <span id="mv_nohp">-</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 mt-2">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h5>
                                            <span id="mv_kegiatan">Kegiatan</span> /
                                            <span id="mv_subkegiatan">Sub Kegiatan</span>
                                        </h5>
                                        <div id="kontrol-gambar" style="text-align:center; margin-top:10px; display:none;">
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="rotateImage('preview-img',-90)">⟲ Putar Kiri</button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="rotateImage('preview-img',90)">⟳ Putar Kanan</button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="zoomImage('preview-img',1.2)">🔍 Zoom In</button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="zoomImage('preview-img',0.8)">🔎 Zoom Out</button>
                                        </div>                                        
                                        <div id="dokumen-embed" 
                                            style="margin-top:10px; height:500px; width:100%; border:1px solid #ccc; overflow:auto;">
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label for="verifikasi_hasil" class="form-label">Hasil Verifikasi (Skor)</label>
                                            <select id="verifikasi_hasil" name="verifikasi_hasil" data-bobot="0" class="form-select" required>
                                                <option value="" selected disabled>- pilih -</option>
                                                <option value="3">Sangat Sesuai</option>
                                                <option value="2">Sesuai</option>
                                                <option value="1">Kurang Sesuai</option>
                                                <option value="0">Tidak Sesuai</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="verifikasi_skor" class="form-label">Skor</label>
                                            <input type="number" id="verifikasi_skor" name="verifikasi_skor" class="form-control" placeholder="" readonly required>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label for="verifikasi_catatan" class="form-label">Catatan (opsional)</label>
                                        <textarea id="verifikasi_catatan" name="verifikasi_catatan" class="form-control" rows="4" ></textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        
                    </div>
                </div> <!-- modal-body -->

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
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/gambar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/laporan';
    var sk_penerima_id = "{{ $sk_penerima_id }}";
    var page = 1;
    $(document).ready(function() {
        dataLoad();

        function renderData(response) {
            const dataList = $('#detail-laporan');
            const pagination = $('#pagination');
            const data=response.data;
            let no = (data.current_page - 1) * data.per_page + 1;
            dataList.empty();
            pagination.empty();

            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const foto = base_url+'/'+dt.foto;
                    const img = `<img src="${foto}" class="rounded border" style="width:80px;height:80px;object-fit:cover" alt="foto">`;
                    const row=`
                    <div class="card shadow-sm table-responsive">
                        <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="fs-5 fw-semibold text-secondary">#${no++}</div>
                            ${img}
                            <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                <div class="fw-semibold">${dt.name}</div>
                                <div class="text-muted small">NIM: ${dt.nim}</div>
                                <div class="mt-1">
                                    <span class="badge text-bg-light border me-1">${dt.prodi}</span>
                                    <span class="badge text-bg-light border">${dt.fakultas}</span>
                                </div>
                                </div>
                                <div class="text-end small text-muted">
                                <div>${dt.email}</div>
                                <div>${dt.no_hp}</div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <hr class="my-3">
                            ${daftarDokumen(dt)}
                        </div>
                    </div>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<div>
                                data tidak ditemukan
                            </div>`;
                dataList.append(row);                
            }
        }    

        function daftarDokumen(data){
            let accordion=`<div class="accordion accordion-flush " id="accordion-dokumen-${data.penerima_id}">`;
            if (data.kegiatan.length > 0) {
                $.each(data.kegiatan, function(index, dt) {
                    const item_id = `${data.penerima_id}-${dt.kegiatan_id}`;
                    let totalLaporan = 0;
                    let laporanBaru = 0;
                    let laporan = `<div class="list-group list-group-flush table-responsive">`; 
            
                    if(dt.sub_kegiatans && dt.sub_kegiatans.length){
                        dt.sub_kegiatans.forEach(sk => {

                            laporan += `<div class="list-group-item ">
                                            <div class="fw-semibold">${sk.sub_kegiatan_nama}</div>`;

                            if(sk.laporans && sk.laporans.length){
                                laporan += `<div class="list-group mt-2">`;

                                sk.laporans.forEach(l => {
                                    const filename = l.path.split('/').pop();
                                    totalLaporan++;

                                    let tanda_baru = '';
                                    let is_baru = false;
                                    let btn_verifikasi=`<a href="javascript:;" class="btn btn-sm btn-outline-primary ms-auto btn-verifikasi" data-laporan_id="${l.laporan_id}">Verifikasi</a>`;
                                    if(l.verifikasi_hasil == null){
                                        laporanBaru++;
                                        is_baru=true;
                                        tanda_baru=`<span class="badge bg-danger position-absolute top-0 end-0 translate-middle p-1 tanda-baru">
                                                        <iconify-icon icon="solar:danger-circle-bold" class=""></iconify-icon>
                                                    </span>`;
                                    }
                                    // btn_verifikasi=``;

                                    laporan += `<div class="list-group-item d-flex align-items-center">
                                                    <span class="small text-muted">
                                                        <a href="${base_url}/${l.path}" target="_blank">${filename}</a>
                                                    </span> 
                                                    ${btn_verifikasi}
                                                    ${tanda_baru}                                                    
                                                </div>`;
                                });
                                laporan += `</div>`;
                            } else {
                                laporan += `<div class="text-muted small mt-1">Belum ada laporan.</div>`;
                            }

                            laporan += `</div>`;
                        });
                    }
                    laporan += `</div>`;

                    accordion+=`    
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#item-${item_id}" aria-expanded="false" aria-controls="item-${item_id}">
                                    <div class="w-100 d-flex align-items-center position-relative">
                                        <span>${dt.kegiatan_nama}</span>
                                        <span class="badge bg-primary position-absolute top-50 end-0 translate-middle-y me-2">
                                            ${totalLaporan}
                                        </span>
                                        ${ laporanBaru > 0 
                                            ? `<span class="badge bg-danger position-absolute top-0 end-0 translate-middle p-1 jumlah-belum-verifikasi" style="font-size:10px;">
                                                    ${laporanBaru}
                                                </span>`
                                            : '' 
                                        }
                                    </div>
                                </button>
                            </h2>
                            <div id="item-${item_id}" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    ${laporan}
                                </div>
                            </div>
                        </div>`;
                });
            }
            accordion+=`</div>`;
            
            return accordion;   
        }

        function dataLoad() {
            var search = $('#search-input').val();
            var url = `${base_url}/api/detail-laporan/${sk_penerima_id}?&page=${page}&search=${search}`;

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

        $('#btn-search').click(function(){
            page=1;
            dataLoad();
        });

        function loadFormVerifikasi(data){
            // isi informasi student
            const url = base_url+'/'+data.path;
            const jenis = getFileType(url);
            const foto = base_url+'/'+data.foto;

            $('#mv_foto').attr('src', foto);
            $('#mv_nama').text(data.nama);
            $('#mv_nim').text('NIM: ' + data.nim);
            $('#mv_prodi').text(data.program_studi);
            $('#mv_fakultas').text(data.fakultas);
            $('#mv_email').text(data.email);
            $('#mv_nohp').text(data.no_hp);

            $('#mv_kegiatan').text(data.kegiatan);
            $('#mv_subkegiatan').text(data.sub_kegiatan);

            rotation = 0;
            scale = 1;

            $('#dokumen-embed').html('<a href="javascript:;" class="refresh-dokumen" style="color:blue;">klik disini untuk melihat preview dokumen</a>');            
            if (jenis=='pdf') {
                $('#kontrol-gambar').hide();
                openPdf(document.getElementById('dokumen-embed'), url);
            }else{
                $('#dokumen-embed').html(`
                    <div style="text-align:center;">
                        <img id="preview-img" src="${url}" 
                            style="max-width:95%; display:block; margin:0 auto; transition: transform 0.3s;">
                    </div>                    
                `);
                $('#kontrol-gambar').show();
            }

            $('#id').val(data.laporan_id);                  
            $('#verifikasi_catatan').val(data.verifikasi_catatan);                  
            $('#verifikasi_hasil').val(data.verifikasi_hasil);
            $('#verifikasi_hasil').attr('data-bobot',data.sub_kegiatan_skor);
            $('#verifikasi_skor').val(data.verifikasi_skor);

        }

        $('#verifikasi_hasil').on('change', function () {
            let skor = parseInt($(this).val());
            let bobot = parseFloat($(this).attr('data-bobot'));
            let nilai = (skor / 3) * bobot;
            $('#verifikasi_skor').val(nilai.toFixed(2));
        });
        
        $(document).on('click', '.btn-verifikasi', function () {
            const $btn = $(this);
            const $item = $btn.closest('.list-group-item');
            const $accordionItem = $btn.closest('.accordion-item');
            const id = $btn.attr('data-laporan_id');


            const url = `${base_url}/api/get-data-laporan-mahasiswa?limit=0&id=${id}`;
            fetchData(url, function(response) {
                loadFormVerifikasi(response.data[0]);
            },true);
            
            showModalForm();

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
        }

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
        });

        $("#form").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const url = `${base_url}/api/verifikasi-laporan-mahasiswa/${id}`;
                // console.log(url)
                saveData(url, 'PUT', $(form).serialize(), function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    updateDom(response.data.id);
                });
            }
        });


        function updateDom(laporanId) {
            let $btn = $('.btn-verifikasi[data-laporan_id="' + laporanId + '"]');
            if (!$btn.length) return;

            let $row = $btn.closest('.list-group-item.d-flex');
            $row.find('.tanda-baru').remove();

            let $accordionItem = $btn.closest('.accordion-item');
            let $counter = $accordionItem.find('.jumlah-belum-verifikasi').first();

            if ($counter.length) {
                let val = parseInt($counter.text().trim()) || 0;
                let newVal = val - 1;
                if (newVal > 0) {
                    $counter.text(newVal);
                } else {
                    $counter.remove();
                }
            }
        }

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#singkatan').val(response.data.singkatan);
                $('#urut').val(response.data.urut);
                $('#nama').val(response.data.nama);
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