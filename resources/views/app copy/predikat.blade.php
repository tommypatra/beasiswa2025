@extends('template')

@section('scriptHead')
<title>Predikat</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold">Monitoring Beasiswa</h5>
        <div class="d-flex flex-wrap gap-1 mb-3">

            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="label_monitoring" data-bs-toggle="dropdown" aria-expanded="false" data-id="">
                    Pilih monitoring
                </button>
                <ul class="dropdown-menu p-2" aria-labelledby="label_monitoring" style="max-height:200px; overflow:auto; min-width: 250px;">
                    <!-- Filter input -->
                    <li style="position: sticky; top: 0; background: #fff; z-index:1; padding: .5rem;">
                    <input type="text" id="filter_monitoring" 
                            name="filter_monitoring" 
                            class="form-control" 
                            placeholder="Cari monitoring...">
                    <hr class="dropdown-divider mt-2 mb-0">
                    </li>
                    <div id="list_monitoring">
                    </div>
                </ul>
            </div>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Predikat</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;" disabled>
                <button class="btn btn-primary" id="btn-tambah" disabled>
                    <i class="ti ti-plus"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh" disabled>
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter" disabled>
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="35%">Predikas</th>
                        <th width="15%">Nilai Minimal</th>
                        <th width="15%">Nilai Maksimal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="5">data tidak ditemukan</td>
                    </tr>
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
        <form id="form-modal">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Predikat</label>
                            <input name="predikat" id="predikat" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nilai Minimal</label>
                            <input name="nilai_minimal" id="nilai_minimal" type="number" class="form-control" required>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nilai Maksimal</label>
                            <input name="nilai_maksimal" id="nilai_maksimal" type="number" class="form-control" required>
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
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/predikat';
    var page = 1;
    $(document).ready(function() {
        initPage()

        statusMonitoring(false);
        async function initPage() { // agar di load secara berurutan
            await loadMonitoring();
        }      

        function statusMonitoring(aktif = true) {
            // false = disable, true = enable
            $('#search-input').prop('disabled', !aktif);
            $('#btn-tambah').prop('disabled', !aktif);
            $('#btn-refresh').prop('disabled', !aktif);
            $('#btn-filter').prop('disabled', !aktif);
        }


        async function loadMonitoring() {
            let search = $('#filter_monitoring').val();
            let respon = await asyncFunction(`${base_url}/api/monitoring?limit=10&search=${search}`);
            const list = $('#list_monitoring').empty();

            if(respon.data.total>0)
                $.each(respon.data.data, function(index, item) {
                    list.append(`
                        <li>
                            <a class="dropdown-item" href="#" data-val="${item.id}">
                                ${item.nama}
                            </a>
                        </li>
                    `);
                });
        }

        async function loadData(){
            const search = $('#search-input').val();
            const kegiatan_id = $('#label_monitoring').attr("data-id");
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const response = await asyncFunction(`${base_url}/api/predikat?monitoring_id=${kegiatan_id}&search=${search}`);

            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const row = `<tr>
                                <td>${no++}</td>
                                <td>${dt.predikat}</td>
                                <td>${dt.nilai_minimal}</td>
                                <td>${dt.nilai_maksimal}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
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

        $('#filter_monitoring').on('keypress', async function(e) {
            if (e.which === 13) {       // 13 = Enter
                e.preventDefault();      
                await loadMonitoring(); 
            }
        });

        $(document).on('click', '#list_monitoring .dropdown-item', function(e) {
            e.preventDefault();
            const text = $(this).text();
            const val  = $(this).data('val');

            $('#label_monitoring').text(text).append(' <span class="caret"></span>'); // caret Bootstrap
            $('#label_monitoring').attr("data-id",val);

            statusMonitoring();
            loadData();

        });        


        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            loadData();
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            loadData();
        });

        // Handle search-input
        $(document).on('input', '#search-input', function() {
            // console.log('Event input berjalan');
            loadData();
        });        

        //untuk show modal form
        function showModalForm() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        function formPredikatReset(){
            $('#form-modal').trigger('reset');
            $('#form-modal input[type="hidden"]').val('');
        }

        $('#btn-tambah').click(function() {
            formPredikatReset();
            showModalForm();
        });
        

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-modal").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? '/api/predikat' : '/api/predikat/' + id;
                let dataArr = $(form).serializeArray();
                dataArr.push({
                    name: 'monitoring_id',
                    value: $('#label_monitoring').attr('data-id')
                });
                let dataPayload = $.param(dataArr);

                saveData(base_url+url, type, dataPayload, function(response) {                    
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(type=='POST'){
                        formPredikatReset();
                    }
                    loadData();
                });
            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(base_url+'/api/predikat', id, function(response) {
                $('#id').val(response.data.id);
                $('#nilai_minimal').val(response.data.nilai_minimal);
                $('#nilai_maksimal').val(response.data.nilai_maksimal);
                $('#predikat').val(response.data.predikat);
                showModalForm();
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus', function() {         
            const id = $('#label_monitoring').attr("data-id");
            if(id!=="")
                deleteData(base_url+'/api/kegiatan', id, function() {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadData();
                });
        });

    });
</script>
@endsection