@extends('template')

@section('scriptHead')
<title>Verifikasi Laporan</title>
<style>
    #daftar-penerima .list-group-item {
        border: 1px solid #dee2e6; /* kasih border */
        margin-bottom: 5px;        /* jarak antar kotak */
        border-radius: 6px;        /* biar agak rounded */
    }
</style>
@endsection

@section('container')

<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Verifikasi Laporan</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;" >
                <button class="btn btn-primary" id="btn-search-sk">
                    <i class="ti ti-search"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh-sk" >
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter-sk " >
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
                        <th width="30%">Perihal/ Monitorin Beasiswa</th>
                        <th width="20%">Nomor/ Tanggal SK</th>
                        <th width="15%">Jumlah Penerima</th>
                        <th width="20%">Jumlah Yang Belum Diverifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="7">data tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation" class="nav-sk">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>

    </div>
</div>

<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Verifikasi Laporan <span class="judul-modal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="search-penerima" placeholder="Cari...">
                                            <button class="btn btn-primary" id="btn-search-penerima">
                                                <i class="ti ti-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="list-group" id="daftar-penerima"></div>

                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation" class="nav-penerima">
                                        <ul class="pagination justify-content-center" id="pagination-penerima"></ul>
                                    </nav>

                                </div>
                                <div class="col-lg-8 mb-3">
                                    <div id="laporan-detail" class="mb-3"></div>
                                    
                                    <form id="form-penilaian">
                                        <input type="hidden" id="laporan_id" name="id">  
                                        <h6>Penilaian:</h6>
                                        <div class="mb-2">
                                            <select class="form-select" id="verifikasi_hasil" name="verifikasi_hasil" required>
                                                <option value="">- pilih -</option>
                                                <option value="3">Sangat Sesuai</option>
                                                <option value="2">Sesuai</option>
                                                <option value="1">Kurang Sesuai</option>
                                                <option value="0">Tidak Sesuai</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <textarea class="form-control" 
                                                    rows="3" id="verifikasi_catatan" name="verifikasi_catatan"
                                                    placeholder="Tambahkan keterangan (opsional)"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-simpan-nilai">
                                            Simpan
                                        </button>
                                    </form>

                                </div>
                            </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
    </div>
</div>
<!-- AKHIR MODAL -->

@endsection

@section('scriptJs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js"></script>

<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/gambar.js') }}"></script>

<script type="text/javascript">
    var sk_penerima_id;
    var page_sk = 1;
    var page_penerima = 1;
    var daftar_penerima;

    async function loadDataSK() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/verifikasi-laporan/daftar?page=${page_sk}&search=${search}`);
        renderData(response);
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
                const disabled=(dt.penerima_count>0)?"":"disabled";
                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.tanggal_sk.substring(0, 4)}</td>
                            <td>${dt.nama}</td>
                            <td>${dt.nomor_sk}/ ${dt.tanggal_sk}</td>
                            <td>${dt.penerima_count}</td>
                            <td>${dt.laporan_pending_count}</td>
                            <td>
                                <button class="btn btn-primary btn-verifikasi" data-id="${dt.id}" ${disabled}><iconify-icon icon="solar:archive-check-outline"></iconify-icon> Verifikasi</button>
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


    $(document).ready(function() {
        initPage();
        async function initPage() { // agar di load secara berurutan
            await loadDataSK();
        }

        //untuk show modal form
        function showModalVerifikasi() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        $('#btn-search-sk').click(function(){
            page_sk=1;
            loadDataSK();
        });


        async function loadDetailSKPenerima() {
            let response = await asyncFunction(`${base_url}/api/get-data-sk-penerima/${sk_penerima_id}`);
            if(response.status){
                $('.judul-modal').text(response.data.nama);
            }
        }
        
        async function loadDataPesertaVerifikasi() {
            const search = $('#search-penerima').val();
            const response = await asyncFunction(`${base_url}/api/verifikasi-laporan/penerima/${sk_penerima_id}?page=${page_penerima}&limit=10&search=${search}`);
            daftar_penerima=[];
            if(response.status){
                daftar_penerima=response.data.data;
                renderPesertaVerifikasi(response);
            }
        }

        $('#btn-search-penerima').click(function(){
            page_penerima=1;
            loadDataPesertaVerifikasi();
        })

        function renderPesertaVerifikasi(response) {
            const dataList = $('#daftar-penerima');
            const pagination = $('#pagination-penerima');
            const data = response.data.data;

            dataList.empty();
            pagination.empty();

            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const foto = dt.foto ? `${base_url}/${dt.foto}` : url('images/user-avatar.png');
                    const row = `
                        <a href="#" 
                        class="list-group-item list-group-item-action peserta-item d-flex align-items-center" 
                        data-id="${dt.penerima_id}" 
                        data-index="${index}">
                            
                            <img src="${foto}" alt="${dt.nama}" 
                                class="rounded me-2" 
                                style="width:60px; height:60px; object-fit:cover;">
                            <div>
                                <div class="fw-bold">${dt.nama}</div>
                                <small class="text-muted d-block">${dt.nim}</small>
                                <small class="text-muted d-block">${dt.program_studi}</small>
                                <small class="text-muted d-block">${dt.sub_kegiatan}</small>
                            </div>
                        </a>
                    `;        
                    dataList.append(row);
                });

                renderPagination(response.data, pagination);
                const peserta = getPesertaByIndex(0);
                if (peserta) {
                    renderLaporanDetail(peserta);
                }
            } else {
                const row = `
                    <div class="list-group-item text-center text-muted">
                        Data tidak ditemukan
                    </div>`;
                dataList.append(row);
            }
        }


        function getPesertaByIndex(index) {
            if (daftar_penerima && daftar_penerima.length > index) {
                return daftar_penerima[index];
            }
            return null;
        }

        $(document).on('click', '.peserta-item', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const peserta = getPesertaByIndex(index);

            if (peserta) {
                renderLaporanDetail(peserta); // tampilkan laporan di kanan
            }
        });

        function renderLaporanDetail(peserta) {
            const container = $('#laporan-detail');
            container.empty();
            
            $('#verifikasi_hasil').val('');
            $('#verifikasi_catatan').val('');
            $('#laporan_id').val('');


            let html = `
                <div class="d-flex align-items-center mb-3">
                    <img src="${peserta.foto || 'https://via.placeholder.com/70x70?text=No+Img'}" 
                        class="rounded-circle me-3" 
                        style="width:70px; height:70px; object-fit:cover;">
                    <div>
                        <h5 class="mb-0">${peserta.nama}</h5>
                        <small>${peserta.nim} - ${peserta.program_studi}</small>
                    </div>
                </div>
                <div><h5>${peserta.sub_kegiatan}</h5></div>
                <div>${peserta.keterangan_sub_kegiatan}</div>
                <div class="mt-2"><h6>Preview Dokumen:</h6></div>
                <div id="kontrol-gambar" style="text-align:center; margin-top:10px; display:none;">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="rotateImage('preview-img',-90)">⟲ Putar Kiri</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="rotateImage('preview-img',90)">⟳ Putar Kanan</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="zoomImage('preview-img',1.2)">🔍 Zoom In</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="zoomImage('preview-img',0.8)">🔎 Zoom Out</button>
                </div>

                <div id="dokumen-embed" 
                    style="margin-top:10px; height:500px; width:100%; border:1px solid #ccc; overflow:auto;">
                </div>

                <div>${peserta.keterangan}</div>

                <div id="download-dokumen" class="mt-2"></div>
            `;

            container.html(html);
            
            if (peserta.path) {
                setFormEnabled('#form-penilaian',true);
                const url = `${base_url}/${peserta.path}`;
                $('#laporan_id').val(peserta.laporan_id);
                $('#download-dokumen').html(
                    `<a href="${url}" class="btn btn-success" target="_blank">Download Manual</a>`
                );

                rotation = 0;
                scale = 1;
                if (peserta.path.endsWith('.pdf')) {
                    openPdf(document.getElementById('dokumen-embed'), url);
                    $('#kontrol-gambar').hide();
                } else {
                    $('#dokumen-embed').html(`
                        <div style="text-align:center;">
                            <img id="preview-img" src="${url}" 
                            style="max-width:95%; display:block; margin:0 auto; transition: transform 0.3s;"
                        >
                        </div>                    
                    `);
                    $('#kontrol-gambar').show();
                }
            } else {
                setFormEnabled('#form-penilaian',false);
                $('#dokumen-embed').html('<p class="text-muted">Tidak ada dokumen</p>');
            }
        }

        

        // aksi verifikasi
        $(document).on('click', '.btn-nilai', async function () {
            const laporanId = $(this).data('id');
            const nilai = $(this).data('nilai');

            try {
                let response = await asyncFunction(`${base_url}/api/verifikasi-laporan/${laporanId}`, 'PUT', { verifikasi_hasil: nilai });
                if (response.status) {
                    alert(`Laporan berhasil dinilai: ${nilai}`);
                    // hapus dari daftar global
                    daftar_penerima = daftar_penerima.filter(item => item.laporan_id !== laporanId);
                    // refresh ulang list kiri
                    renderPesertaVerifikasi({ data: { data: daftar_penerima } });
                    // kosongkan detail kanan
                    $('#laporan-detail').html('<p class="text-muted">Pilih peserta untuk melihat laporan.</p>');
                } else {
                    alert('Gagal menyimpan penilaian');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat simpan penilaian');
            }
        });

        // Handle page change
        $(document).on('click', '.nav-sk .page-link', function() {
            page_sk = $(this).data('page');
            loadDataSK();
        });

        // Handle page change
        $(document).on('click', '.nav-penerima .page-link', function() {
            page_penerima = $(this).data('page');
            loadDataPesertaVerifikasi();
        });


        $('#btn-refresh-sk').click(function() {
            loadDataSK();
        });     

        //validasi dan save mode edit semua
        $("#form-penilaian").validate({
            rules: {
                verifikasi_catatan: {
                    required: function () {
                        return $("#verifikasi_hasil").val() === "0";
                    }                
                }
            },
            messages: {
                verifikasi_catatan: {
                    required: "Catatan wajib diisi jika hasil Tidak Sesuai"
                }
            },            
            submitHandler: function(form) {
                const id = $('#laporan_id').val();
                const type = 'PUT';
                const url = '/api/verifikasi-laporan/simpan/' + id;
                saveData(base_url+url, type, $(form).serialize(), function(response) {                    
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataPesertaVerifikasi();
                });
            }
        });


        //ganti data
        $(document).on('click', '.btn-verifikasi', async function() {
            sk_penerima_id = $(this).attr('data-id');
            $('#search-penerima').val('');
            await loadDetailSKPenerima();
            await loadDataPesertaVerifikasi();
            showModalVerifikasi();
        });
            
    });
</script>

@endsection