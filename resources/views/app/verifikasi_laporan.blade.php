@extends('template')

@section('scriptHead')
<title>Verifikasi Laporan</title>
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
    #daftar-verifikator {
        list-style-type: disc;
        padding-left: 1.5rem; /* atau sesuaikan */
        margin-left: 0;        /* opsional */
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
    <div class="modal-dialog">
        <form id="form-sk">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >SK <span class="judul-modal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Perihal</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-7 mb-3">
                            <label class="form-label">Nomor SK</label>
                            <input name="nomor_sk" id="nomor_sk" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-5 mb-3">
                            <label class="form-label">Tangal SK</label>
                            <input name="tanggal_sk" id="tanggal_sk" type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <h5>Pejabat Penandatangan</h5>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="ttd_nama" id="ttd_nama" type="text" class="form-control" >
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input name="ttd_jabatan" id="ttd_jabatan" type="text" class="form-control" >
                        </div>
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Monitoring Evaluasi</label>
                            <select name="monitoring_id" id="monitoring_id" class="form-control">
                            </select>
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

@endsection

@section('scriptJs')
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    var sk_penerima_id;
    $(document).ready(function() {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });
    });
</script>
<script>
    var page_sk = 1;

    async function loadDataSK(set_default=true) {
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

                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.tanggal_sk.substring(0, 4)}</td>
                            <td>${dt.nama}</td>
                            <td>${dt.nomor_sk}/ ${dt.tanggal_sk}</td>
                            <td>${dt.penerima_count}</td>
                            <td>${dt.laporan_pending_count}</td>
                            <td>
                                <button class="btn btn-success btn-verifikasi" data-id="${dt.id}" ><iconify-icon icon="solar:archive-check-outline"></iconify-icon> Verifikasi</button>
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

    //untuk show modal form
    function showModalFormSK() {
        var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
            keyboard: false
        });
        fModalForm.show();
    }


    $(document).ready(function() {
        initPage();
        async function initPage() { // agar di load secara berurutan
            await loadDataSK();
        }

        $('#search-input').on('keypress', async function(e) {
            if (e.which === 13) {       // 13 = Enter
                e.preventDefault();      
                await loadDataSK(); 
            }
        });
        
        // Handle page change
        $(document).on('click', '.nav-sk .page-link', function() {
            page_sk = $(this).data('page');
            loadDataSK();
        });

        $('#btn-refresh-sk').click(function() {
            loadDataSK();
        });     

        function formSkReset(){
            $('#form-sk').trigger('reset');
            $('#form-sk input[type="hidden"]').val('');
        }

        $('#btn-tambah-sk').click(function() {
            formSkReset();
            showModalFormSK();    
        });    

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-sk").validate({
            submitHandler: function(form) {
                const id = $('#form-sk #id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? '/api/sk-penerima' : '/api/sk-penerima/' + id;

                console.log(id,type,url)

                saveData(base_url+url, type, $(form).serialize(), function(response) {                    
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(type=='POST'){
                        formSkReset();
                    }           
                    loadDataSK();

                });

            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti-sk', function() {
            const id = $(this).attr('data-id');
            showDataById(base_url+'/api/sk-penerima', id, function(response) {
                $('#form-sk #id').val(response.data.id);
                $('#form-sk #nomor_sk').val(response.data.nomor_sk);
                $('#form-sk #tanggal_sk').val(response.data.tanggal_sk);
                $('#form-sk #ttd_jabatan').val(response.data.ttd_jabatan);
                $('#form-sk #ttd_nama').val(response.data.ttd_nama);
                $('#form-sk #monitoring_id').val(response.data.monitoring_id);
                $('#form-sk #nama').val(response.data.nama);
                
                showModalFormSK();
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus-sk', function() {
            const id = $(this).attr('data-id');
                if(id!=="")
                deleteData(base_url+'/api/sk-penerima', id, function() {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataSK();
                });
        });
            
    });
</script>

@endsection