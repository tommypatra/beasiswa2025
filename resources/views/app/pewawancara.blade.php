@extends('template')

@section('scriptHead')
<title>Pewawancara Seleksi Beasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
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
    <iconify-icon icon="solar:user-speak-rounded-outline" class="fs-9"></iconify-icon> <h2 class="mb-0 ms-2">Pewawancara</h2>
</div>
<div id="label-beasiswa" class="mb-2"></div>

<div class="row">
    <div class="col-lg-9">

        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Pewawancara Seleksi Beasiswa</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                        <button class="btn btn-success" id="btn-cari-data">
                            <i class="ti ti-search"></i>
                        </button>
                        <button class="btn btn-primary" id="btn-tambah">
                            <i class="ti ti-plus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="solar:settings-linear" class="fs-5"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-cetak-peserta-wawancara">
                                        <iconify-icon icon="solar:printer-outline" class="me-2"></iconify-icon> Cetak Peserta Wawancara
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-cetak-absen">
                                        <iconify-icon icon="solar:printer-outline" class="me-2"></iconify-icon> Cetak Absen Wawancara
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-cetak-wawancara">
                                        <iconify-icon icon="solar:printer-outline" class="me-2"></iconify-icon> Cetak Hasil Wawancara
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-cetak-rekap">
                                        <iconify-icon icon="solar:printer-outline" class="me-2"></iconify-icon> Cetak Rekap Wawancara
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-generate">
                                        <iconify-icon icon="mdi:refresh-circle" class="me-2"></iconify-icon> Generate Nilai Akhir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="5%">No</th>
                                <th width="35%">Nama Pewawancara</th>
                                <th width="35%">Daftar Peserta (Nama/ Nim/ Program Studi)</th>
                                <th width="5%">Aksi</th>
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
                            <label class="form-label">pewawancara</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-simpan-pewawancara">Simpan</button>
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-pembagian" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="pembagian">
            <input type="hidden" name="pewawancara_id" id="pembagian_pewawancara_id" >
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
                            <input type="checkbox" class="mt-2" id="pewawancara" name="pewawancara" value="1"> Ada Pewawancara
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Prodi</label>
                            <select id="program_studi_id" class="form-control"></select>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Cari</label>
                            <input id="cari" type="text" class="form-control">
                        </div>
                        <div class="col-sm-2 mb-3 d-flex">
                            <button type="button" class="btn btn-primary" id="btn-cari">Cari Data</button>
                        </div>

                    </div>
                    <hr>
                    <table>
                        <thead>
                        <tr>
                            <td><input type="checkbox" id="pilihsemua" ></td>
                            <td>Data Mahasiswa</td>
                            <td>Pewawancara</td>
                        </tr>
                        </thead>
                        <tbody id="daftar-pembagian"></tbody>
                    </table>     
                    
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center" id="pagination-pembagian"></ul>
                    </nav>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-simpan-pembagian">Simpan</button>
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-tukar" role="dialog">
    <div class="modal-dialog">
        <form id="form-tukar">
            
            <input type="hidden" name="peserta_nim_asal" id="peserta_nim_asal" >
            <input type="hidden" name="peserta_wawancara_id_asal" id="peserta_wawancara_id_asal" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Tukar Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <input type="text" id="nama-mahasiswa-asal" class="form-control">
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="form-label">Pilih Pewawancara</label>
                            <select id="data-pewawancara" class="form-control"></select>
                        </div>
                        <div class="col-sm-12 mb-3" id="list-peserta-tukar">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-simpan-tukar">Simpan</button>
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
    const endpoint = base_url+'/api/pewawancara';
    var id = "{{ $beasiswa_id }}";
    var page = 1;
    var page_pembagian = 1;
    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
            await dataLoad();
            await loadDataSelect('#program_studi_id', `data-program-studi`);
            await loadPewawancara();
        }

        async function loadDataBeasiswa() {
            const url = `${base_url}/api/get-data-beasiswa/${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);
        }

        async function loadPewawancara() {
            const url = `${base_url}/api/daftar-pewawancara/${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let select = $('#data-pewawancara');
            select.empty();
            if (response.status) {
                select.append('<option value="">-- Pilih Pewawancara --</option>');
                response.data.forEach(item => {
                    select.append(`<option value="${item.id}">${item.user.name}</option>`);
                });
            } else {
                console.error('Gagal load pewawancara:', response.message);
            }        
        }

        $('#data-pewawancara').change(function(){
            let $list = $('#list-peserta-tukar');
            $list.empty(); // bersihkan dulu setiap kali ganti pewawancara
            $list.append('<p class="text-muted">Peserta tidak ditemukan.</p>');

            if($(this).val()!=='')
                getPesertaWawancara();
        });

        async function getPesertaWawancara(){
            const pewawancara_id = $('#data-pewawancara').val();
            const url = `${base_url}/api/peserta-ujian-wawancara?pewawancara_id=${pewawancara_id}&limit=0`;
            const response = await execAsync(url, 'GET', token);
            const peserta_wawancara_id_asal = $('#peserta_wawancara_id_asal').val();
            const peserta_nim_asal = $('#peserta_nim_asal').val();

            let $list = $('#list-peserta-tukar');
            $list.empty(); // bersihkan dulu setiap kali ganti pewawancara

            if (response.status && response.data.length > 0) {
                response.data.forEach(item => {
                    const pesertaId = item.pendaftar.id;
                    const noPendaftaran = item.pendaftar.no_pendaftaran;
                    const nama = item.pendaftar.mahasiswa.user.name;
                    const nim = item.pendaftar.mahasiswa.nim;
                    const program_studi = item.pendaftar.mahasiswa.program_studi.nama;
                    let check = `<input class="form-check-input" type="radio" disabled>`;
                    if(peserta_wawancara_id_asal!=item.id && nim!=peserta_nim_asal)
                        check =`<input class="form-check-input peserta_wawancara_id_tujuan" type="radio" 
                                    name="peserta_wawancara_id_tujuan" id="peserta-${item.id}" 
                                    value="${item.id}">`;

                    if(item.pendaftar.tag)
                        check=`<input class="form-check-input" type="radio" disabled>`;

                    $list.append(`
                        <div class="form-check">
                            ${check}
                            <label class="form-check-label" for="peserta-${item.id}">
                                ${nama} (${nim} / ${program_studi})
                            </label>
                        </div>
                    `);
                });
            } else {
                $list.append('<p class="text-muted">Peserta tidak ditemukan.</p>');
            }
        };

        $('#btn-cetak-rekap').click(function(){
            const url = `${base_url}/cetak-rekap-wawancara/${id}`;
            window.open(url, '_blank');
        });


        $('#btn-cetak-peserta-wawancara').click(function(){
            const url = `${base_url}/cetak-peserta-wawancara/${id}`;
            window.open(url, '_blank');
        });

        $('#btn-cetak-absen').click(function(){
            const url = `${base_url}/cetak-absen-wawancara/${id}`;
            window.open(url, '_blank');
        });

        $('#btn-cetak-wawancara').click(function(){
            const url = `${base_url}/cetak-hasil-wawancara/${id}`;
            window.open(url, '_blank');
        });

        $('#btn-generate').click(function(){
            if(confirm('Generate ulang nilai akhir dari peserta wawancara?')){
                const url = `${base_url}/api/generate-nilai-akhir-wawancara/${id}`;
                fetchData(url, function(response) {
                    if(response.status){
                        alert('generate ulang nilai akhir berhasil dilakukan');
                    }        
                },true);
            }
        });

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    let peserta="";
                    // console.log(dt);
                    if(dt.peserta_wawancara.length>0){
                        let listTag = dt.peserta_wawancara.length > 1 ? "ol" : "ul";

                        peserta=`<${listTag} class="list">`;
                        $.each(dt.peserta_wawancara, function(index, item) {
                            let mahasiswa = item.pendaftar.mahasiswa;
                            let icon = item.pendaftar.tag ?"solar:star-fall-minimalistic-2-bold":"solar:star-fall-minimalistic-2-linear";
                            peserta += `<li>
                                            <div class="nama">
                                                ${mahasiswa.user.name}
                                                <a href="javascript:;" class="tandai-peserta-wawancara" data-pewawancara="${dt.user.name}" data-program_studi="${mahasiswa.program_studi.nama}" data-pendaftar_id="${item.pendaftar_id}" data-nim="${mahasiswa.nim}" data-nama="${mahasiswa.user.name}" >
                                                    <iconify-icon icon="${icon}" class=""></iconify-icon>
                                                </a>

                                                <a href="javascript:;" class="tukar-peserta-wawancara" data-pewawancara="${dt.user.name}" data-program_studi="${mahasiswa.program_studi.nama}" data-peserta_wawancara_id="${item.id}" data-nim="${mahasiswa.nim}" data-nama="${mahasiswa.user.name}" >
                                                    <iconify-icon icon="solar:maximize-square-broken" class=""></iconify-icon>
                                                </a>

                                                <a href="javascript:;" class="hapus-peserta-wawancara" data-id="${item.id}">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-outline" class=""></iconify-icon>
                                                </a>
                                            </div>
                                            <div class="nim">${mahasiswa.nim}</div>
                                        </li>`;
                        });
                        peserta+=`</${listTag}>`;

                    }
                    const row = `<tr>
                                    <td><input type="checkbox" name="cek_pewawancara[]" value="${dt.id}"></td>
                                    <td>${no++}</td>
                                    <td>
                                        ${dt.user.name}
                                        <div style="font-size:italic;">${dt.user.email}</div>
                                    </td>
                                    <td>${peserta}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item btn-tambah-peserta" data-jumlah_peserta="0" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Tambah Peserta</a></li>
                                                <li><a class="dropdown-item" target="_blank" href="${base_url}/cetak-absen-wawancara/${dt.beasiswa_id}/${dt.id}"><i class="far fa-edit"></i> Cetak Absen Wawancara</a></li>
                                                <li><a class="dropdown-item" target="_blank" href="${base_url}/cetak-hasil-wawancara/${dt.beasiswa_id}/${dt.id}"><i class="far fa-edit"></i> Cetak Hasil Wawancara</a></li>
                                                <li><a class="dropdown-item btn-ganti" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                                <li><a class="dropdown-item btn-hapus" data-id="${dt.id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
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

        async function loadDataPeserta() {
            const limit = $('#jumlah').val();
            const cari = $('#cari').val();
            const pewawancara = $('#pewawancara').is(':checked') ? $('#pewawancara').val() : '0';
            const program_studi_id = $('#program_studi_id').val();
            const url = `${base_url}/api/get-data-peserta-wawancara?page=${page_pembagian}&is_admin=1&search=${cari}&prodi=${program_studi_id}&pewawancara=${pewawancara}&beasiswa_id=${id}&limit=${limit}`;
            const response = await execAsync(`${url}`, 'GET', token);
            renderPilihPeserta(response);
        }

        function renderPilihPeserta(response) {
            const dataList = $('#daftar-pembagian');
            const pagination = $('#pagination-pembagian');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    // console.log(dt);
                    let data_pewawancara=``;
                    if(dt.wawancara.length>0){
                        let listTag = dt.wawancara.length > 1 ? "ol" : "ul";                        
                        data_pewawancara=`<${listTag} class="list">`;
                        $.each(dt.wawancara, function(index, dt) {
                            data_pewawancara+=`<li>${dt.pewawancara.user.name}</li>`;
                        });
                        data_pewawancara+=`</${listTag}>`;
                    }

                    const row = `<tr>
                                    <td>
                                        <input type="checkbox" class="pilih" name="pendaftar_id[]" value="${dt.id}">                                    
                                    </td>
                                    <td>
                                        <div class="pendaftar-row">
                                            <img class="foto" src="${base_url}/${dt.mahasiswa.user.identitas.foto}" alt="Foto Pendaftar">
                                            <div class="pendaftar-info">
                                                <span class="nama">${dt.mahasiswa.user.name}</span>
                                                <span class="nim">${dt.mahasiswa.nim}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${data_pewawancara}</td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="3">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        async function dataLoad() {
            var search = $('#search-input').val();
            var url = `${endpoint}/${id}?page=${page}&search=${search}&limit=${vLimit}`;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        $("#nama").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url+"/api/pengguna",
                    type: "GET",
                    dataType: "json",
                    data: {
                        role: "pewawancara",
                        search: request.term
                    },
                    success: function (respon) {
                        $("#user_id").val("");
                        response($.map(respon.data.data, function (item) {
                            return {
                                label: item.name, 
                                value: item.name, 
                                user_id: item.user_id
                            };
                        }));
                    }
                });
            },
            appendTo: "#modal-form",
            minLength: 3,
            select: function (event, ui) {
                $(this).val(ui.item.value); 
                $("#user_id").val(ui.item.user_id);
                return false;
            }
        });        

        // Handle page change
        $(document).on('click', '#pagination .page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });

        // Handle page change
        $(document).on('click', '#pagination-pembagian .page-link', function() {
            page_pembagian = $(this).data('page');
            loadDataPeserta();
        });

        $("#nama-mahasiswa-asal").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: `${base_url}/api/peserta-ujian-wawancara`,
                    type: "GET",
                    dataType: "json",
                    data: {
                        beasiswa_id: id,
                        is_admin:1,
                        search: request.term
                    },
                    success: function (respon) {

                        //reset data pewawancara dan list peserta
                        $('#data-pewawancara').val('');
                        $('#list-peserta-tukar').html(`<p class="text-muted">Peserta tidak ditemukan.</p>`);
                        $('#peserta_wawancara_id_asal').val("");
                        $('#peserta_nim_asal').val("");
                        response($.map(respon.data.data, function (item) {
                            let mahasiswa = item.pendaftar.mahasiswa;
                            return {
                                label: `${mahasiswa.user.name} (${mahasiswa.nim} / ${mahasiswa.program_studi.nama}) - ${item.pewawancara.user.name}`, 
                                value: item.id,
                                user_id: mahasiswa.user.id,
                                nim: mahasiswa.nim,
                            };
                        }));
                    }
                });
            },
            appendTo: "#modal-tukar",
            minLength: 3,
            select: function (event, ui) {
                $(this).val(ui.item.label);  

                // simpan value lain ke hidden field
                $("#peserta_wawancara_id_asal").val(ui.item.value); 
                $("#peserta_nim_asal").val(ui.item.nim);
                return false;
            }
        }); 

        $(document).on('click', '.tandai-peserta-wawancara', async function() {
            const id = $(this).attr('data-pendaftar_id');
            const nim = $(this).attr('data-nim');
            const nama = $(this).attr('data-nama');

            if(confirm('beri tanda pada mahasiswa atas nama '+nama+'?')){
                const url = `${base_url}/api/tandai-pendaftar/${id}`;
                const response = await execAsync(url, 'GET', token);
                if(response.status){
                    appShowNotification(true,['berhasil dilakukan!']);
                    dataLoad();                
                }
            }
        }); 


        $(document).on('click', '.tukar-peserta-wawancara', function() {
            const id = $(this).attr('data-id');
            const nim = $(this).attr('data-nim');
            const program_studi = $(this).attr('data-program_studi');
            const peserta_wawancara_id = $(this).attr('data-peserta_wawancara_id');
            const nama = $(this).attr('data-nama');
            const pewawancara = $(this).attr('data-pewawancara');

            $('#peserta_wawancara_id_asal').val(peserta_wawancara_id);
            $('#peserta_nim_asal').val(nim);
            $('#nama-mahasiswa-asal').val(`${nama} (${nim}/ ${program_studi}) - ${pewawancara}`);

            //reset data pewawancara dan list peserta
            $('#data-pewawancara').val('');
            $('#list-peserta-tukar').html(`<p class="text-muted">Peserta tidak ditemukan.</p>`);

            showModal('modal-tukar');
        }); 

        $(document).on('click', '.hapus-peserta-wawancara', function() {
            const id = $(this).data('id');
            deleteData(`${base_url}/api/hapus-peserta-wawancara`, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                dataLoad();
            });
        }); 

        $('#program_studi_id').change(function(){
            loadDataPeserta();
        })

        $('#jumlah').blur(function(){
            loadDataPeserta();
        })

        // $('#cari').on('keyup', function() {
        //     let keyword = $(this).val().trim();
        //     if (keyword.length >= 3) {
        //         loadDataPeserta();
        //     }else if(keyword.length < 1){
        //         loadDataPeserta();
        //     }
        // });

        $(document).on('click', '#btn-cari', function() {
            loadDataPeserta();
        });

        // Handle page change
        $('#btn-cari-data').click(function() {
            cari=1;
            dataLoad();
        });

        // Handle search-input
        // $(document).on('input', '#search-input', function() {
            // console.log('Event input berjalan');
        //     dataLoad();
        // });      

        $('#modal-form').on('shown.bs.modal', function () {
            $(this).removeAttr('aria-hidden');
        });

        function formReset(){
            $('#form').trigger('reset');
            $('#id').val('');
            $('#user_id').val('');
        }

        $('#pilihsemua').on('change', function() {
            $('.pilih').prop('checked', this.checked);
        });

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModal('modal-form');    
            
        });

        $(document).on('click','.btn-tambah-peserta',function(){
            $('#pembagian_pewawancara_id').val($(this).data('id'));
            loadDataPeserta();
            showModal('modal-pembagian');
        })


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

        $("#btn-simpan-tukar").click(async function(){
            const peserta_wawancara_id_tujuan = $('.peserta_wawancara_id_tujuan:checked').val();
            const peserta_wawancara_id_asal = $('#peserta_wawancara_id_asal').val();
            const pendaftar_id_tujuan = $('.pendaftar_id_tujuan').val();
            if(peserta_wawancara_id_asal && peserta_wawancara_id_tujuan){
                const url = `${base_url}/api/tukar-peserta-wawancara/${peserta_wawancara_id_asal}/${peserta_wawancara_id_tujuan}`;
                const response = await execAsync(url, 'GET', token);
                if(response.status){
                    appShowNotification(true,['berhasil dilakukan!']);
                    getPesertaWawancara();
                    dataLoad();
                    $('#peserta_wawancara_id_asal').val(peserta_wawancara_id_tujuan);
                }
            }else{
                appShowNotification(false,['peserta wawancara asal atau tujuan harus dipilih terlebh dahulu!']);
            }
        });


        $("#pembagian").validate({
            submitHandler: function(form) {
                const type = 'POST'
                const url = `${base_url}/api/simpan-peserta-wawancara`;

                saveData(url, type, $(form).serialize(), function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataPeserta();
                    dataLoad();

                });
            }
        });


        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            formReset();
            showDataById(endpoint+'/show', id, function(response) {
                $('#id').val(response.data.id);                
                $('#user_id').val(response.data.user_id);                
                $('#beasiswa_id').val(response.data.beasiswa_id);                
                $('#nama').val(response.data.user.name);                
                showModal('modal-form');
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