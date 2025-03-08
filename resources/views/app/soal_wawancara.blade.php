@extends('template')

@section('scriptHead')
<title>Soal Wawancara</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('container')
<h2 id="nama-beasiswa"></h2>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Soal Wawancara</h5>
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
                        <th width="45%">Soal Wawancara</th>
                        <th width="15%">Bobot Nilai</th>
                        <th width="15%">Nomor Urut</th>
                        <th width="20%">Beasiswa</th>
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
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Nomor</label>
                            <input type="number" name="nomor" id="nomor"  class="form-control" required>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Soal</label>
                            <textarea name="soal" id="soal" rows="4" class="form-control"></textarea>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Bobot Nilai</label>
                            <input type="text" name="bobot_nilai" id="bobot_nilai"  class="form-control" required>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const beasiswa_id = '{{ $beasiswa_id }}';
    const endpoint = base_url+'/api/soal-wawancara';
    var page = 1;
    $(document).ready(function() {
        dataLoad();
        loadBeasiswa('#beasiswa_id',"{{ date('Y') }}");

        detailBeasiswa();
        async function detailBeasiswa() {
            if(beasiswa_id){
                let respon = await asyncFunction(`${base_url}/api/beasiswa/${beasiswa_id}`);
                $('#nama-beasiswa').text(respon.data.nama);
            }
        }

        $('#soal').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],            
            callbacks: {               
                onImageUpload: function(files) {
                    sendFile(files[0], $(this));
                }
            }
        });

        function sendFile(file, editor) {
            var data = new FormData();
            data.append("gambar", file);
            $.ajax({
                data: data,
                type: "POST",
                url: `${base_url}/api/upload-gambar-beasiswa`,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    editor.summernote('insertImage', response.data);
                }
            });
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
                                <td>${dt.soal}</td>
                                <td>${dt.bobot_nilai}</td>
                                <td><input type="number" data-id="${dt.id}" style="width: 80px;" class="form-control ganti-nomor-urut" value="${dt.nomor}"></td>
                                <td>${dt.beasiswa.nama}</td>
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
                                <td colspan="6">data tidak ditemukan</td>
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
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                saveData(url, type, $(form).serialize(), function(response) {
                    //jika berhasil
                    appShowNotification(true,['operasi berhasil dilakukan!']);
                    if(type=='POST'){
                        formReset();
                    }
                    dataLoad();
                });
            }
        });

        
        $(document).on('blur', '.ganti-nomor-urut', function() {
            const id = $(this).data('id');
            const val = $(this).val();
            const data = {
                nomor:val
            };
            if(val)
                saveData(`${base_url}/api/ganti-nomor-soal-wawancara/${id}`, 'PUT', data, function(response) {
                    console.log('berhasil');
                    // appShowNotification(true,['operasi berhasil dilakukan!']);
                });
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