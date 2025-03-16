@extends('template')

@section('scriptHead')
<title>Syarat Dokumen Upload</title>
@endsection

@section('container')
<h2 id="nama-beasiswa"></h2>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Syarat Dokumen Upload</h5>
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
                        <th width="5%">No</th>
                        <th width="25%">Nama/Jenis</th>
                        <th width="25%">Deskripsi</th>
                        <th width="10%">Wajib</th>
                        <th width="20%">Beasiswa</th>
                        <th width="10%">Aktif</th>
                        <th>Aksi</th>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Beasiswa</label>
                            <select name="beasiswa_id" id="beasiswa_id"  class="form-control" required></select>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" id="jenis"  class="form-control" required>
                                <option value="pdf">PDF</option>
                                <option value="image">Gambar</option>
                            </select>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Wajib</label>
                            <select name="is_wajib" id="is_wajib"  class="form-control" required>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" required></textarea>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Contoh</label>
                            <input class="form-control" type="file" id="contoh" name="contoh">
                        </div>
						<div class="col-lg-5 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_aktif" id="is_aktif"  class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
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
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const beasiswa_id = '{{ $beasiswa_id }}';
    const endpoint = base_url+'/api/syarat';
    const tahun = "{{ date('Y') }}";
    var page = 1;
    
    async function initPage() { // agar di load secara berurutan
        await loadDataSelect('#beasiswa_id', `data-beasiswa?tahun=${tahun}&limit=100`);
    }

    $(document).ready(function() {
        initPage();
        dataLoad();

        detailBeasiswa();
        async function detailBeasiswa() {
            if(beasiswa_id){
                let respon = await asyncFunction(`${base_url}/api/beasiswa/${beasiswa_id}`);
                $('#nama-beasiswa').text(respon.data.nama);
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
                    const contoh=(dt.contoh)?`<a href="${base_url}/storage/${dt.contoh}" target="_blank"><span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">Contoh</span></a>`:"";
                    const row = `<tr>
                                <td>${no++}</td>
                                <td>
                                    ${dt.nama}/ ${dt.jenis}
                                    <div>${contoh}</div>
                                </td>
                                <td>${dt.deskripsi}</td>
                                <td>${(dt.is_wajib)?'Wajib':'Tidak Wajib'}</td>
                                <td>${dt.beasiswa.nama}</td>
                                <td>${(dt.is_aktif)?'Aktif':'Tidak Aktif'}</td>
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
                                <td colspan="7">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        function dataLoad() {
            var search = $('#search-input').val();
            var url = endpoint + '?beasiswa_id='+beasiswa_id+'&page=' + page + '&search=' + search + '&limit=' + vLimit;

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

        // Handle search-input
        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
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
            $('#contoh').val('');
            $('#beasiswa_id').val(beasiswa_id);
        }

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const url = (id === '') ? endpoint : endpoint + '/' + id;

                var formData = new FormData(form);
                if((id !== '')){
                    formData.append("_method", "put");
                }

                saveData(url, 'POST', formData, function(response) {
                    //jika berhasil
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(id===''){
                        formReset();
                    }
                    dataLoad();
                });
            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#beasiswa_id').val(response.data.beasiswa_id);
                $('#nama').val(response.data.nama);
                $('#jenis').val(response.data.jenis);
                $('#is_wajib').val(response.data.is_wajib);
                $('#is_aktif').val(response.data.is_aktif);
                $('#deskripsi').val(response.data.deskripsi);
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