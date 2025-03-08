@extends('template')

@section('scriptHead')
<title>Nilai Raport</title>
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
            <h5 class="card-title fw-semibold">Nilai Raport</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>

        <form id="form">
            <input type="hidden" id="id" name="id">
            <h5>Kelas X</h5>
            <div class="row">
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 1</label>
                    <div>Nilai</div>
                    <input name="smt_1_nilai" id="smt_1_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_1_peringkat" id="smt_1_peringkat" type="number" class="form-control" >
                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 2</label>
                    <div>Nilai</div>
                    <input name="smt_2_nilai" id="smt_2_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_2_peringkat" id="smt_2_peringkat" type="number" class="form-control" >
                </div>
            </div>
            <hr>
            <h5>Kelas XI</h5>
            <div class="row">
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 3</label>
                    <div>Nilai</div>
                    <input name="smt_3_nilai" id="smt_3_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_3_peringkat" id="smt_3_peringkat" type="number" class="form-control" >
                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 4</label>
                    <div>Nilai</div>
                    <input name="smt_4_nilai" id="smt_4_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_4_peringkat" id="smt_4_peringkat" type="number" class="form-control" >
                </div>
            </div>
            <hr>

            <h5>Kelas XII</h5>
            <div class="row">
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 5</label>
                    <div>Nilai</div>
                    <input name="smt_5_nilai" id="smt_5_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_5_peringkat" id="smt_5_peringkat" type="number" class="form-control" >
                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 6</label>
                    <div>Nilai</div>
                    <input name="smt_6_nilai" id="smt_6_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_6_peringkat" id="smt_6_peringkat" type="number" class="form-control" >
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
    const endpoint = base_url + '/api/nilai-raport';
    var page = 1;

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
            $('#smt_1_nilai').val(data.smt_1_nilai);
            $('#smt_1_peringkat').val(data.smt_1_peringkat);
            $('#smt_2_nilai').val(data.smt_2_nilai);
            $('#smt_2_peringkat').val(data.smt_2_peringkat);
            $('#smt_3_nilai').val(data.smt_3_nilai);
            $('#smt_3_peringkat').val(data.smt_3_peringkat);
            $('#smt_4_nilai').val(data.smt_4_nilai);
            $('#smt_4_peringkat').val(data.smt_4_peringkat);
            $('#smt_5_nilai').val(data.smt_5_nilai);
            $('#smt_5_peringkat').val(data.smt_5_peringkat);
            $('#smt_6_nilai').val(data.smt_6_nilai);
            $('#smt_6_peringkat').val(data.smt_6_peringkat);
        }else{
            $('#id').val("");
            $('#smt_1_nilai').val("");
            $('#smt_1_peringkat').val("");
            $('#smt_2_nilai').val("");
            $('#smt_2_peringkat').val("");
            $('#smt_3_nilai').val("");
            $('#smt_3_peringkat').val("");
            $('#smt_4_nilai').val("");
            $('#smt_4_peringkat').val("");
            $('#smt_5_nilai').val("");
            $('#smt_5_peringkat').val("");
            $('#smt_6_nilai').val("");
            $('#smt_6_peringkat').val("");
        }
    }

    $(document).ready(function() {
        dataLoad();

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
                smt_3_nilai: {
                    number: true // Hanya angka dan desimal yang valid
                }
            },
            messages: {
                smt_3_nilai: {
                    number: "Hanya diperbolehkan angka bulat atau desimal (gunakan titik sebagai pemisah)."
                }
            },            
            submitHandler: function(form) {
                const id = $('#id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                saveData(url, type, $(form).serialize(), function(response) {
                    //jika berhasil
                    appShowNotification(true,['operasi berhasil dilakukan!']);
                    // if(type=='POST'){
                    //     formReset();
                    // }
                    dataLoad();
                });
            }
        });

    });
</script>
@endsection