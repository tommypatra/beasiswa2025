@extends('template')

@section('scriptHead')
<title>Registrasi Peserta Seleksi Beasiswa</title>
<style>

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
            <h5 class="card-title fw-semibold">Registrasi Peserta Seleksi Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="8%">Tahun</th>
                        <th width="40%">Beasiswa</th>
                        <th width="20%">Jadwal Wawancara</th>
                        <th width="15%">Jumlah Peserta </th>
                        <th width="5%" class="text-center">Aksi</th>
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

<!-- MULAI MODAL DAFTAR PESERTA-->
<div class="modal fade modal" id="modal-peserta" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Daftar Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                            Registrasi
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" id="search-peserta" placeholder="Cari..." style="max-width: 200px;">
                                <button class="btn btn-success" id="btn-refresh-peserta">
                                    <i class="ti ti-reload"></i>
                                </button>
                            </div>
                        </div>
            
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Nama/ Nim</th>
                                        <th width="25%">Fakultas/ Program Studi</th>
                                        <th width="15%">Status Registrasi </th>
                                    </tr>
                                </thead>
                                <tbody id="data-list-peserta">
                                </tbody>
                            </table>
                        </div>   
                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="pagination-peserta"></ul>
                        </nav>

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
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/wawancara';
    var page = 1;
    var beasiswa_id;

    $(document).ready(function() {
        dataLoad();

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    let tombol_aktif = (dt.verifikator_pendaftar_count<1)?`disabled`:``;
                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        ${dt.beasiswa.tahun}
                                    </td>
                                    <td>
                                        ${dt.beasiswa.nama}
                                    </td>
                                    <td>
                                        ${dt.beasiswa.wawancara_mulai} sd 
                                        ${dt.beasiswa.wawancara_selesai}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary">Jumlah Peserta : ${dt.total_pendaftar}</span>
                                        <hr>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle text-success">Sudah Registrasi : ${dt.peserta_registrasi}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-danger-subtle text-danger">Belum Registrasi : ${dt.total_pendaftar-dt.peserta_registrasi}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <button class="btn btn-secondary btn-daftar-peserta" data-beasiswa_id="${dt.beasiswa.id}" type="button" ${tombol_aktif}>Daftar Peserta</button>
                                        </div>
                                    </td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="6">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        async function dataLoad() {
            let search = $('#search-input').val();
            let url = `${base_url}/api/wawancara?search=${search}`;
            let response = await execAsync(`${url}`, 'GET', token);
            renderData(response);
        }

        async function dataPeserta() {
            let search = $('#search-peserta').val();
            let url = `${base_url}/api/peserta-wawancara?beasiswa_id=${beasiswa_id}&search=${search}`;
            let response = await execAsync(`${url}`, 'GET', token);

            const dataList = $('#data-list-peserta');
            const pagination = $('#pagination-peserta');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    let pewawancara = ``;
                    let peserta_wawancara=dt.wawancara[0];
                    if(dt.wawancara.length>0){                        
                        pewawancara = `<ul class="list">`;
                        $.each(dt.wawancara, function(index, item) {
                            pewawancara += `<li>${item.pewawancara.user.name}</li>`;
                        });
                        pewawancara += `<ul>`;
                    }
                    let vclass="table-warning";
                    let is_registrasi_wawancara="";
                    if(dt.is_registrasi_wawancara){
                        vclass="table-success";
                        is_registrasi_wawancara="selected";
                    }
                    const row = `<tr class="${vclass}">
                                    <td>${no++}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <img src="${base_url}/${dt.identitas.foto}" width="90px" >
                                            <div>
                                                ${dt.user.name}/  
                                                ${dt.mahasiswa.nim}/
                                                ${dt.mahasiswa.program_studi.nama}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        ${pewawancara}
                                    </td>
                                    <td class="text-center">
                                        <select style="min-width: 100px;" class="form-control status-registrasi-peserta" data-id="${dt.id}">
                                            <option value="0">Belum</option>
                                            <option value="1" ${is_registrasi_wawancara}>Sudah</option>
                                        </select>
                                    </td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="4">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }        
        }


        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $('#btn-refresh-peserta').click(function() {
            dataPeserta();
        });

        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });      

        $(document).on('input', '#search-peserta', function() {
            dataPeserta();
        });      

        $(document).on('click','.btn-daftar-peserta',function(){
            beasiswa_id = $(this).data('beasiswa_id');
            showModal('modal-peserta');    
            dataPeserta();
        });


        $(document).on('change','.status-registrasi-peserta',function(){
            const id = $(this).data('id');
            const value = $(this).val();
            if(value!==""){
                if(confirm("Apakah anda yakin akan mengubah status registrasi peserta ini?")){
                    let data_post = {
                        is_registrasi_wawancara : value,
                    };
                    let url = `${base_url}/api/registasi-peserta-wawancara/${id}`;
                    saveData(url, 'PUT', data_post, function(response) {
                        // console.log(response);
                        dataLoad();
                        dataPeserta();
                        appShowNotification(true,['berhasil dilakukan!']);
                    });
                }
            }    
        });

    });
</script>
@endsection