@extends('template')

@section('scriptHead')
<title>Wawancara Seleksi Beasiswa</title>
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

</style>
@endsection

@section('container')
<div id="info-beasiswa" class="mb-2"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Wawancara Seleksi Beasiswa</h5>
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
    const endpoint = base_url+'/api/wawancara';
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
                                        <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle text-success">Sudah Wawancara : ${dt.peserta_valid}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-danger-subtle text-danger">Belum Wawancara : ${dt.total_pendaftar-dt.peserta_valid}</span>
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
            var search = $('#search-input').val();
            var url = `${base_url}/api/wawancara?search=${search}`;

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

        $(document).on('click','.btn-daftar-peserta',function(){
            const id = $(this).data('beasiswa_id');
            window.location.href = `${base_url}/peserta-wawancara/${id}`;
        });

    });
</script>
@endsection