@extends('template')

@section('scriptHead')
<title>Peserta Wawancara</title>
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
<div id="info-beasiswa" class="mb-2"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Peserta Wawancara</h5>
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
                        <th width="25%">Nama/ Nim / Program Studi</th>
                        <th width="25%">Pewawancara</th>
                        <th width="5%" class="text-center">Status/ Aksi</th>
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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Nama/ Nim</th>
                                        <th width="25%">Fakultas/ Program Studi</th>
                                        <th width="15%">Status Verifikasi </th>
                                    </tr>
                                </thead>
                                <tbody id="data-list-peserta">
                                </tbody>
                            </table>
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
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/peserta-wawancara';
    var page = 1;

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
                    let tombol_aktif = (dt.is_registrasi_wawancara!==1)?`disabled`:``;
                    let hadir = dt.is_registrasi_wawancara?`<span class="badge fs-2 bg-success">Sudah Registrasi</span>`:`<span class="badge fs-2 bg-danger">belum Registrasi</span>`;
                    let pewawancara = ``;
                    if(dt.wawancara.length>0){
                        pewawancara = `<ul class="list">`;
                        $.each(dt.wawancara, function(index, item) {
                            pewawancara += `<li>${item.pewawancara.user.name}</li>`;
                        });
                        pewawancara += `<ul>`;
                    }
                    const row = `<tr>
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
                                        ${hadir}
                                        <div class="d-flex flex-column align-items-center gap-2 mt-2">
                                            <button class="btn btn-secondary btn-peserta" data-beasiswa_id="${dt.id}" type="button" ${tombol_aktif}>Mulai Wawancara</button>
                                        </div>
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

        async function dataLoad() {
            var search = $('#search-input').val();
            var url = `${base_url}/api/peserta-wawancara?search=${search}`;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });      

        $(document).on('click','#btn-peserta',function(){
            window.location.href = `${base_url}/`;
        });

    });
</script>
@endsection