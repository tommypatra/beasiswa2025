@extends('template')

@section('scriptHead')
<title>Identitas Pengguna</title>
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
    .preview {
        margin-top: 10px;
        max-width: 300px;
    }
</style>
@endsection

@section('container')
<h4 id="nama-pengguna"></h4>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Identitas Pengguna</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>

        <form id="form">
            <input type="hidden" id="user_id" name="user_id">
            <input type="hidden" id="identitas_id" name="identitas_id">
            <div class="row">
                <div class="col-lg-8 mb-3 row">
                    <div class="col-sm-12 mb-3">
                        <div>Email : <span id="email"></span></div>
                    </div>
                    <div class="col-sm-7 mb-3">
                        <label class="form-label">Nama</label>
                        <input name="name" id="name" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-5 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input name="tempat_lahir" id="tempat_lahir" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input name="tanggal_lahir" id="tanggal_lahir" type="text" class="form-control datepicker" required>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" class="form-control" required></textarea>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Kabupaten</label>
                        <input name="wilayah_kabupaten" id="wilayah_kabupaten" data-id="" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Kelurahan/ Desa</label>
                        <input name="wilayah_desa" id="wilayah_desa" data-id="" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Nomor HP/WA</label>
                        <input name="no_hp" id="no_hp" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Foto Pengguna</label>
                    <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg, image/gif">
                    <br>
                    <img id="previewImage" class="preview" >                
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
        </form>
    </div>
</div>


@endsection

@section('scriptJs')
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url + '/api/identitas';
    var page = 1;

    async function initPage() { // agar di load secara berurutan
        await dataLoad();
    }

    async function dataLoad() {
        var url = endpoint + '/' + user_id;
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
            renderData(result.data);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function renderData(data){
        if (data.user) {
            $('#user_id').val(data.user.id);
            $('#email').text(data.user.email);
            $('#name').val(data.user.name);
            $('#tanggal_lahir').val("{{ date('Y-m-d') }}");
            if(data.identitas){
                $('#identitas_id').val(data.identitas.id);
                $('#jenis_kelamin').val(data.identitas.jenis_kelamin);
                $('#tempat_lahir').val(data.identitas.tempat_lahir);
                $('#tanggal_lahir').val(data.identitas.tanggal_lahir);
                $('#no_hp').val(data.identitas.no_hp);
                $('#alamat').val(data.identitas.alamat);

                if(data.identitas.wilayah_desa){
                    let desa = data.identitas.wilayah_desa;
                    let kabupaten = desa.wilayah_kecamatan.wilayah_kabupaten;
                    $('#wilayah_kabupaten').val(kabupaten.nama);
                    $('#wilayah_kabupaten').data('id',kabupaten.id);
    
                    $('#wilayah_desa').val(desa.desa);
                    $('#wilayah_desa').data('id',desa.id);
                }


                $('#previewImage').show("");
                $('#previewImage').attr("src",`${base_url}/${data.identitas.foto}`);
            }
        }else{
            $('#user_id').val("");
            $('#identitas_id').val("");
            $('#nama').val("");
            $('#jenis_kelamin').val("");
            $('#tempat_lahir').val("");
            $('#tanggal_lahir').val("{{ date('Y-m-d') }}");
            $('#no_hp').val("");
            $('#alamat').val("");
            $('#previewImage').hide("");
        }
    }

    $(document).ready(function() {
        initPage();

        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        $('#foto').on('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            dataLoad();
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            rules: {
                foto: {
                    required: function() {
                        return $('#identitas_id').val() === '';
                    }
                }
            },
            messages: {
                foto: {
                    required: "Foto wajib diupload.",
                }
            },
            submitHandler: function(form,event) {
                event.preventDefault();
                const identitas_id = $('#identitas_id').val();
                const url = (identitas_id === '') ? endpoint : endpoint + '/' + identitas_id;

                var formData = new FormData(form);
                if((identitas_id !== '')){
                    formData.append("_method", "put");
                }
                formData.append("wilayah_desa_id", $('#wilayah_desa').data("id"));

                saveData(url, 'POST', formData, function(response) {
                    renderData(response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                    if(response.data.user)
                        localStorage.setItem('nama', response.data.user.name);
                    
                    if(response.data.identitas)
                        localStorage.setItem('foto', `${response.data.identitas.foto}`);

                    $('#user-foto').attr('src',base_url+'/'+localStorage.getItem('foto'));
                    $('#user-name').text(localStorage.getItem('nama'));

                });
            }
        });

        $("#wilayah_kabupaten").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url+"/api/data-kabupaten",
                    type: "GET",
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function (respon) {
                        response($.map(respon.data.data, function (item) {
                            return {
                                label: item.nama, 
                                value: item.nama, 
                                id: item.id
                            };
                        }));
                    }
                });
            },
            minLength: 3,
            select: function (event, ui) {
                $(this).val(ui.item.value); 
                $(this).attr("data-id", ui.item.id);
                return false;
            }
        });

        $("#wilayah_desa").autocomplete({
            source: function (request, response) {
                let kabupaten_id = $('#wilayah_kabupaten').data("id");
                // console.log(kabupaten_id);
                if (!kabupaten_id) {
                    return;
                }

                $.ajax({
                    url: base_url+"/api/data-desa",
                    type: "GET",
                    dataType: "json",
                    data: {
                        search: request.term,
                        wilayah_kabupaten_id: kabupaten_id,
                    },
                    success: function (respon) {
                        response($.map(respon.data.data, function (item) {
                            return {
                                label: item.desa, 
                                value: item.desa, 
                                id: item.id
                            };
                        }));
                    }
                });
            },
            minLength: 3,
            select: function (event, ui) {
                $(this).val(ui.item.value); 
                $(this).attr("data-id", ui.item.id);
                return false;
            }
        });

    });
</script>
@endsection