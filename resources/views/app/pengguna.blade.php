@extends('template')

@section('scriptHead')
<title>Akun Pengguna</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Akun Pengguna</h5>
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
                        <th width="25%">Nama</th>
                        <th width="35%">Email</th>
                        <th width="30%">Akses</th>
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
                            <label class="form-label">Nama</label>
                            <input name="name" id="name" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" id="email" type="email" class="form-control" required>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" id="password" type="password" class="form-control">
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
<div class="modal fade" id="modal-akses" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-akses" >
                <input type="hidden" name="user_id" id="user_id">
                <div class="modal-header">
                    <h5 class="modal-title">PILIH AKSES AKUN</h5>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-12 mb-2" id="daftar-akses-akun">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- AKHIR MODAL -->	
@endsection

@section('scriptJs')
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/pengguna';
    var page = 1;
    $(document).ready(function() {
        dataLoad();
        loadRole('#daftar-akses-akun');

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    let akses_list="";
                    if(dt.user_role.length>0){
                        akses_list = '<ul>';
                        $.each(dt.user_role, function(index, item) {
                            akses_list += ` <li>
                                                ${item.role.nama} 
                                                <a href="javascript:;" class="hapus-akses" data-id="${item.id}" data-grup_id="${item.role_id}"><iconify-icon icon="solar:trash-bin-minimalistic-outline" class=""></iconify-icon></a>
                                            </li>`;
                        });
                        akses_list += '</ul>';
                    }

                    const row = `<tr>
                                <td>${no++}</td>
                                <td>${dt.name}</td>
                                <td>${dt.email}</td>
                                <td>${akses_list}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-pilih-akses" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Akses Pengguna</a></li>
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
                                <td colspan="4">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        function dataLoad() {
            var search = $('#search-input').val();
            var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;

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
        }

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            rules: {
                password: {
                    required: function() {
                        return $('#form #id').val().trim() === ''; // Password wajib jika ID kosong
                    },
                    minlength: 8 // Password minimal 8 karakter
                }
            },
            messages: {
                password: {
                    required: "Password wajib diisi",
                    minlength: "Password minimal 8 karakter"
                }
            },
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

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#email').val(response.data.email);
                $('#name').val(response.data.name);
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


        function daftarAkses(id){
            $.ajax({
                url: 'api/pengguna/'+id, // Ganti dengan URL API
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('.form-check-akses input[type="checkbox"]').prop('checked', false).prop('disabled', false);                    
                    if(response.data.user_role.length>0)
                    $.each(response.data.user_role, function(index, item) {
                        $(`.form-check-akses input[value="${item.role_id}"]`).prop('checked', true).prop('disabled', true);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Gagal mengambil data:", error);
                }
            });        
        }        

        //ganti akses
        $(document).on('click', '.btn-pilih-akses', function() {
            const id = $(this).data('id');
            daftarAkses(id);
            $('#form-akses').trigger('reset');
            $('#form-akses #user_id').val(id);
            showModal('modal-akses');
        });
        
        //hapus akses
        $(document).on('click', '.hapus-akses', function() {
            const id = $(this).data('id');
            deleteData(`${base_url}/api/user-role`, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                dataLoad();
            });
        });


        $('#form-akses').on('submit', function (e) {
            e.preventDefault(); // Hindari reload halaman
            let user_id = $('#form-akses #user_id').val();
            let berhasil=false;
            $('.form-check-akses input[type="checkbox"]:checked').each(function () {
                if(!$(this).prop('disabled')){            
                    $.ajax({
                        url: 'api/user-role',
                        type: 'POST',
                        data: JSON.stringify({ user_id:user_id, role_id: $(this).val() }),
                        dataType: 'json',
                        async:false,
                        contentType: 'application/json', // Pastikan dikirim dalam JSON                    
                        success: function (response) {
                            berhasil=true;
                        },
                        error: function (xhr, status, error) {
                            console.error("Gagal menyimpan data:", error);
                        }
                    });
                }
            });  
            if(berhasil){
                appShowNotification(true,['berhasil dilakukan!']);
                daftarAkses(user_id);
                dataLoad();
            }
        });            

    });
</script>
@endsection