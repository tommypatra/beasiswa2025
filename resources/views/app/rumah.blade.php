@extends('template')

@section('scriptHead')
<title>Data Rumah</title>
@endsection

@section('container')
<h4 id="nama-pengguna"></h4>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h4 class="card-title fw-semibold">Data Rumah</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>

        <form id="form">
            <input type="hidden" id="id" name="id">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Status Rumah</label>
                    <select name="status_id" id="status_id" class="form-control" required></select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Luas Tanah</label>
                    <input name="luas_tanah" id="luas_tanah" type="text" class="form-control" required>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Luas Bangunan</label>
                    <input name="luas_bangunan" id="luas_bangunan" type="text" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Rata rata bayar Listrik</label>
                    <select name="bayar_listrik_id" id="bayar_listrik_id" class="form-control" required></select>
                    akumulasi dalam satu bulan
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Jumlah Orang Tinggal</label>
                    <input name="jumlah_orang_tinggal" id="jumlah_orang_tinggal" type="number" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label class="form-label">MCK</label>
                    <select name="mck_id" id="mck_id" class="form-control" required></select>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Sumber Air</label>
                    <select name="sumber_air_id" id="sumber_air_id" class="form-control" required></select>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Sumber Listrik</label>
                    <select name="sumber_listrik_id" id="sumber_listrik_id" class="form-control" required></select>
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
    const endpoint = base_url + '/api/rumah';
    var page = 1;
    var data_referensi;

    async function dataReferensi() {
        var url = base_url + '/api/data-referensi?limit=1000';
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
            data_referensi=result.data.data;
        } catch (error) {
            console.error('Error:', error);
        }
    }
    $(document).ready(function() {
        async function initPage() { // agar di load secara berurutan
            await loadDataReferensi();
            await loadOptionSelect("#sumber_air_id", "Sumber Air", data_referensi);
            await loadOptionSelect("#sumber_listrik_id", "Sumber Listrik", data_referensi);
            await loadOptionSelect("#bayar_listrik_id", "Listrik", data_referensi);
            await loadOptionSelect("#mck_id", "MCK", data_referensi);
            await loadOptionSelect("#status_id", "Kepemilikan Rumah", data_referensi);

            // await loadDataSelect('#status_id','data-referensi?grup=kepemilikan rumah&limit=100');
            // await loadDataSelect('#bayar_listrik_id','data-referensi?grup=listrik&limit=100');
            // await loadDataSelect('#mck_id','data-referensi?grup=mck&limit=100');
            // await loadDataSelect('#sumber_air_id','data-referensi?grup=sumber air&limit=100');
            // await loadDataSelect('#bayar_listrik','data-referensi?grup=listrik&limit=100');
            // await loadDataSelect('#sumber_listrik_id','data-referensi?grup=sumber listrik&limit=100');
            await dataLoad();
        }




        initPage();

        async function loadDataReferensi() {
            var url = base_url + '/api/data-referensi?limit=200';
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                // console.log(result);
                data=result.data.data;
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function renderData(data){
            // console.log(data);
            if (data) {
                $('#id').val(data.id);
                $('#jumlah_orang_tinggal').val(data.jumlah_orang_tinggal);
                $('#luas_bangunan').val(data.luas_bangunan);
                $('#luas_tanah').val(data.luas_tanah);
                $('#mck_id').val(data.mck_id);
                $('#status_id').val(data.status_id);

                $('#sumber_air_id').val(data.sumber_air_id);
                $('#sumber_listrik_id').val(data.sumber_listrik_id);
            }else{
                $('#id').val("");
                $('#jumlah_orang_tinggal').val("");
                $('#luas_bangunan').val("");
                $('#luas_tanah').val("");
                $('#mck_id').val("");
                $('#status_id').val("");

                $('#sumber_air_id').val("");
                $('#sumber_listrik_id').val("");
            }
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
                renderData(result.data.data[0]);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Handle page change
        $('#btn-refresh').click(function() {
            dataLoad();
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                saveData(url, type, $(form).serialize(), function(response) {
                    renderData(response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                });
            }
        });

    });
</script>
@endsection