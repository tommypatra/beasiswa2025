@extends('template')

@section('scriptHead')
<title>Daftar Buku Rekening</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
    .preview {
        margin-top: 10px;
        max-width: 300px;
    }
</style>

@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Daftar Buku Rekening</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-primary" id="btn-tambah">
                    <i class="ti ti-plus"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>


        <div class="row" id="data-list"></div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="form">
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Nama Bank</label>
                            <input name="bank" id="bank" type="text" class="form-control huruf-kapital" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Tanggal Pembuatan Rekening</label>
                            <input name="tanggal_pembuatan" id="tanggal_pembuatan" type="text" class="form-control datepicker" vaue="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 mb-3">
                            <label class="form-label">Nomor Rekening</label>
                            <input name="nomor" id="nomor" type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8 mb-3">
                            <label class="form-label">Nama Pemilik Rekening</label>
                            <input name="nama_pemilik" id="nama_pemilik" type="text" class="form-control huruf-kapital" required>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Foto Buku Rekening</label>
                        <input type="file" id="foto_buku" name="foto_buku" class="form-control" accept="image/png, image/jpeg, image/jpg, image/gif">
                        <br>
                        <img id="previewImage" class="preview" >                
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Status Aktif Rekening</label>
                            <select name="is_aktif" id="is_aktif" class="form-control" >
                                <option value="1">Aktif</option>
                                <option value="">Tidak Aktif</option>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>

<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const logo_iain = "{{ asset('images/logo.png') }}";
    const endpoint = base_url + '/api/buku-rekening';
    const tanggal_hari_ini = "{{ date('Y-m-d') }}";
    var page = 1;
    $(document).ready(function() {
        dataLoad();

        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        function labelKostum(data, label) {
            return (data) ? `<span class='badge text-bg-success fs-1'>${label}</span>` : ""
        }

        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data = response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, rekening) {
                    const gambar_beasiswa=base_url+'/'+rekening.foto_buku;
                    const status_aktif=(rekening.is_aktif==1)?`<span class="badge text-bg-success">AKTIF</span>`:`<span class="badge text-bg-danger">TIDAK AKTIF</span>`;
                    const row = `<div class="col-sm-6">
                                    <div class="card">
                                        <div class="position-relative">
                                            <a href="javascript:void(0)">
                                                <img src="${gambar_beasiswa}" class="card-img-top" alt="materialM-img">
                                            </a>
                                            <span class="badge text-bg-light text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">
                                               ${rekening.bank}
                                            </span>
                                        </div>      

                                        <div class="card-body">
                                            <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm mb-3">Tanggal Pembuatan : ${rekening.tanggal_pembuatan}</span>
                                            <h5>${rekening.nama_pemilik}</h5>                                        
                                            Bank/ Nomor Rekening : ${rekening.bank} / ${rekening.nomor}
                                            <div class="mt-2">${status_aktif}</div>

                                            <div class="d-flex align-items-center gap-4 mt-2">
                                                <div class="d-flex align-items-center gap-2">&nbsp;</div>
                                                <div class="d-flex align-items-center fs-3 ms-auto">
                                                    <button class="btn btn-sm btn-primary btn-aktifkan" data-id="${rekening.id}">
                                                        Aktifkan
                                                    </button>
                                                    <button class="btn btn-sm btn-primary btn-ganti" data-id="${rekening.id}">
                                                        Ganti
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-hapus" data-id="${rekening.id}">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>                                                
                                        </div>
                                    </div>
                                </div>`;
                    dataList.append(row);


                });
                renderPagination(response.data, pagination);
            } else {
                const row = `Data tidak ditemukan`;
                dataList.append(row);
            }
        }

        function dataLoad() {
            var search = $('#search-input').val();
            var url = `${endpoint}?page=${page}&search=${search}`;

            fetchData(url, function(response) {
                renderData(response);
            }, true);
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


        $('#foto_buku').on('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle search-input
        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });

                // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
        });

        function showModalForm() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        function formReset(){
            $('#form').trigger('reset');
            $('#previewImage').hide("");
            $('#foto_buku').val("");
            $('#form input[type="hidden"]').val('');
            $('#tanggal_pembuatan').val(tanggal_hari_ini);
        }

                //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#tanggal_pembuatan').val(response.data.tanggal_pembuatan);
                $('#nomor').val(response.data.nomor);
                $('#nama_pemilik').val(response.data.nama_pemilik);
                $('#bank').val(response.data.bank);
                $('#is_aktif').val(response.data.is_aktif);
                $('#previewImage').show("");
                $('#previewImage').attr("src",`${base_url}/${response.data.foto_buku}`);
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


        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            rules: {
                foto_buku: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                }
            },
            messages: {
                foto_buku: {
                    required: "Foto rekening wajib diupload.",
                }
            },
            submitHandler: function(form,event) {
                event.preventDefault();
                const id = $('#id').val();
                const url = (id === '') ? endpoint : endpoint + '/' + id;

                var formData = new FormData(form);
                if((id !== '')){
                    formData.append("_method", "put");
                }

                saveData(url, 'POST', formData, function(response) {
                    appShowNotification(true, ['berhasil dilakukan!']);
                    dataLoad();
                    if(!id){
                        formReset();
                    }
                    $('#foto_buku').val("");
                });
            }
        });


        $(document).on('click', '.btn-aktifkan', function() {
            const id = $(this).data('id');
            if(confirm('Yakin aktifkan rekening ini ?')){
                const url_referensi = base_url + '/api/aktifkan-nomor-rekening/'+id;
                const respon = execAsync(url_referensi,"GET", token);
                // if(respon.status){
                    dataLoad();
                // }
            }
        });


    });
</script>
@endsection