@extends('template')

@section('scriptHead')
<title>Data Mahasiswa</title>
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
            <h5 class="card-title fw-semibold">Data Mahasiswa</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>

        <form id="form">
            <input type="hidden" id="id" name="id">
            <div class="row">
                <div class="col-lg-8 mb-3 row">
                    <div class="col-sm-5 mb-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input name="tahun_masuk" id="tahun_masuk" type="number" class="form-control" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">NIM</label>
                        <input name="nim" id="nim" type="text" class="form-control" required>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <label class="form-label">Program Studi</label>
                        <select name="program_studi_id" id="program_studi_id" class="form-control"></select>
                    </div>
                    <div class="col-sm-5 mb-3">
                        <label class="form-label">Sumber Biaya</label>
                        <select name="sumber_biaya_id" id="sumber_biaya_id" class="form-control"></select>
                    </div>
                    </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Kartu Mahasiswa</label>
                    <input type="file" id="kartu_mahasiswa" name="kartu_mahasiswa" accept="image/png, image/jpeg, image/jpg, image/gif">
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
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url + '/api/mahasiswa';
    var page = 1;

    async function initPage() { // agar di load secara berurutan
        await loadDataSelect('#program_studi_id', 'data-program-studi?limit=100');
        await loadDataSelect('#sumber_biaya_id', 'data-referensi?grup=sumber biaya&limit=100');
        await dataLoad();
    }

    async function dataLoad() {
        var url = endpoint + '?user_id=' + user_id;
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
            renderData(result.data.data.length,result.data.data[0]);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function renderData(ada,data){
        if (ada) {
            $('#id').val(data.id);
            $('#tahun_masuk').val(data.tahun_masuk);
            $('#nim').val(data.nim);
            $('#program_studi_id').val(data.program_studi_id);
            $('#sumber_biaya_id').val(data.sumber_biaya_id);
            $('#previewImage').show("");
            $('#previewImage').attr("src",`${base_url}/${data.kartu_mahasiswa}`);
        }else{
            $('#tahun_masuk').val("{{ date('Y') }}");
            $('#id').val("");
            $('#nim').val("");
            $('#program_studi_id').val("");
            $('#sumber_biaya_id').val("");
            $('#previewImage').hide("");
        }
    }

    $(document).ready(function() {
        initPage();

        $('#kartu_mahasiswa').on('change', function(event) {
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
                kartu_mahasiswa: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                }
            },
            messages: {
                kartu_mahasiswa: {
                    required: "Kartu mahasiswa wajib diupload",
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
                    renderData(response.status,response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                });
            }
        });

    });
</script>
@endsection