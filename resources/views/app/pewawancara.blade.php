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
<div id="info-beasiswa" class="mb-2"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Pewawancara Seleksi Beasiswa</h5>
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
                        <th width="5%"></th>
                        <th width="5%">No</th>
                        <th width="35%">Nama Surveyor</th>
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


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="form">
            <input type="hidden" name="id" id="id" >
            <input type="hidden" name="beasiswa_id" id="beasiswa_id" value="{{ $id }}">
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
    <div class="modal-dialog modal-lg">
        <form id="pembagian">
            <input type="hidden" name="pewawancara_id" id="pewawancara_id" >
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
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Program Studi</label>
                            <select id="program_studi_id" class="form-control"></select>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Cari</label>
                            <input id="cari" type="text" class="form-control">
                        </div>
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <td><input type="checkbox" id="pilihsemua" ></td>
                            <td>Data Mahasiswa</td>
                            <td>Pewawancara</td>
                        </tr>
                        </thead>
                        <tbody id="daftar-peserta"></tbody>
                    </table>                    
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
    const endpoint = base_url+'/api/pewawancara';
    var id = "{{ $id }}";
    var page = 1;
    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
            await dataLoad();
            await loadDataSelect('#program_studi_id', `data-program-studi`);
        }

        async function loadDataBeasiswa() {
            var url = base_url + '/api/get-data-beasiswa/'+id;
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`, 
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                // console.log(result);
                
                $('#info-beasiswa').html(`<h3 id="nama-beasiswa">${result.data.nama}</h3>
                    Jumlah Pendaftar ${result.data.jumlah_finalisasi}
                `);
                // data_referensi=result.data.data;
            } catch (error) {
                console.error('Error:', error);
            }
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
                    let peserta="";
                    console.log(dt);
                    if(dt.peserta_wawancara.length>0){
                        peserta=`<ul class="list">`;
                        $.each(dt.peserta_wawancara, function(index, item) {
                            let mahasiswa = item.pendaftar.mahasiswa;
                            peserta += `<li>
                                            <div class="nama">
                                                ${mahasiswa.user.name}
                                                <a href="javascript:;" class="hapus-peserta-wawancara" data-id="${item.id}">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-outline" class=""></iconify-icon>
                                                </a>
                                            </div>
                                            <div class="nim">${mahasiswa.nim}</div>
                                        </li>`;
                        });
                        peserta+=`</ul>`;

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
            const program_studi_id = $('#program_studi_id').val();
            var url = `${base_url}/api/peserta-wawancara?search=${cari}&prodi=${program_studi_id}&pewawancara=0&beasiswa_id=${id}&limit=${limit}`;
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`, 
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                const daftar_peserta = $('#daftar-peserta');

                daftar_peserta.empty();
                if(result.data.data.length>0){
                    $.each(result.data.data, function(index, dt) {
                        console.log(dt);
                        let data_pewawancara=``;
                        if(dt.wawancara.length>0){
                            data_pewawancara=`<ul class="list">`;
                            $.each(dt.wawancara, function(index, dt) {
                                data_pewawancara+=`<li>${dt.pewawancara.user.name}</li>`;
                            });
                            data_pewawancara+=`</ul>`;
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
                                                    <span class="prodi">${dt.mahasiswa.program_studi.nama}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${data_pewawancara}</td>
                                    </tr>`;
                        daftar_peserta.append(row);
                    });                    
                }
            } catch (error) {
                console.error('Error:', error);
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
                        role: "surveyor",
                        search: request.term
                    },
                    success: function (respon) {
                        $("#user_id").val("");
                        response($.map(respon.data.data, function (item) {
                            return {
                                label: item.name, 
                                value: item.name, 
                                id: item.id
                            };
                        }));
                    }
                });
            },
            appendTo: "#modal-form",
            minLength: 3,
            select: function (event, ui) {
                $(this).val(ui.item.value); 
                $("#user_id").val(ui.item.id);
                return false;
            }
        });        

        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });

        $(document).on('click', '.hapus-peserta-wawancara', function() {
            const id = $(this).data('id');
            deleteData(`${base_url}/api/peserta-wawancara`, id, function() {
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

        $('#cari').on('keyup', function() {
            let keyword = $(this).val().trim();
            if (keyword.length >= 3) {
                loadDataPeserta();
            }else if(keyword.length < 1){
                loadDataPeserta();
            }
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


        $("#pembagian").validate({
            submitHandler: function(form) {
                const type = 'POST'
                const url = `${base_url}/api/peserta-wawancara`;
                saveData(url, type, $(form).serialize(), function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataPeserta();
                    dataLoad();
                });
            }
        });


        $(document).on('click','.btn-tambah-peserta',function(){
            $('#pewawancara_id').val($(this).data('id'));
            loadDataPeserta();
            showModal('modal-pembagian');
        })

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