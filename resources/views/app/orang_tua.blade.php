    @extends('template')

    @section('scriptHead')
    <title>Data Orang Tua Kandung</title>
    @endsection

    @section('container')
    <h4 id="nama-pengguna"></h4>
    <div class="card">
        <div class="card-body">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                <h4 class="card-title fw-semibold">Data Orang Tua Kandung</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" id="btn-refresh">
                        <i class="ti ti-reload"></i>
                    </button>
                </div>
            </div>

            <form id="form">
                <input type="hidden" id="id" name="id">

                <h5>Bapak Kandung</h5>
                <hr>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input name="bapak_nama" id="bapak_nama" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <select name="pekerjaan_bapak_id" id="pekerjaan_bapak_id" class="form-control pekerjaan_id" required></select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Pendidikan</label>
                        <select name="pendidikan_bapak_id" id="pendidikan_bapak_id" class="form-control pendidikan_id" required></select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Pendapatan</label>
                        <select name="pendapatan_bapak_id" id="pendapatan_bapak_id" class="form-control pendapatan_id" required></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Status bapak kandung</label>
                        <select name="status_hidup_bapak_kandung" id="status_hidup_bapak_kandung" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="1">Hidup</option>
                            <option value="0">Meninggal</option>
                        </select>
                    </div>
                </div>

                <h5>Ibu Kandung</h5>
                <hr>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input name="ibu_nama" id="ibu_nama" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <select name="pekerjaan_ibu_id" id="pekerjaan_ibu_id" class="form-control pekerjaan_id" required></select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Pendidikan</label>
                        <select name="pendidikan_ibu_id" id="pendidikan_ibu_id" class="form-control pendidikan_id" required></select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Pendapatan</label>
                        <select name="pendapatan_ibu_id" id="pendapatan_ibu_id" class="form-control pendapatan_id" required></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label class="form-label">Status ibu Kandung</label>
                        <select name="status_hidup_ibu_kandung" id="status_hidup_ibu_kandung" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="1">Hidup</option>
                            <option value="0">Meninggal</option>
                        </select>
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
        const endpoint = base_url + '/api/orang-tua';
        var page = 1;
        var data_referensi;

        $(document).ready(function() {
            async function initPage() { // agar di load secara berurutan
                await loadDataReferensi();
                await loadOptionSelect(".pekerjaan_id", "Pekerjaan", data_referensi);
                await loadOptionSelect(".pendidikan_id", "Pendidikan", data_referensi);
                await loadOptionSelect(".pendapatan_id", "Pendapatan", data_referensi);
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
                    data_referensi=result.data.data;
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            function renderData(data){
                console.log(data);
                if (data) {
                    $('#id').val(data.id);
                    $('#bapak_nama').val(data.bapak_nama);
                    $('#status_hidup_bapak_kandung').val(data.status_hidup_bapak_kandung);
                    $('#pekerjaan_bapak_id').val(data.pekerjaan_bapak_id);
                    $('#pendidikan_bapak_id').val(data.pendidikan_bapak_id);
                    $('#pendapatan_bapak_id').val(data.pendapatan_bapak_id);

                    $('#ibu_nama').val(data.ibu_nama);
                    $('#status_hidup_ibu_kandung').val(data.status_hidup_ibu_kandung);
                    $('#pekerjaan_ibu_id').val(data.pekerjaan_ibu_id);
                    $('#pendidikan_ibu_id').val(data.pendidikan_ibu_id);
                    $('#pendapatan_ibu_id').val(data.pendapatan_ibu_id);
                }else{
                    $('#id').val("");
                    $('#bapak_nama').val("");
                    $('#status_hidup_bapak_kandung').val("");
                    $('#pekerjaan_bapak_id').val("");
                    $('#pendidikan_bapak_id').val("");
                    $('#pendapatan_bapak_id').val("");

                    $('#ibu_nama').val("");
                    $('#status_hidup_ibu_kandung').val("");
                    $('#pekerjaan_ibu_id').val("");
                    $('#pendidikan_ibu_id').val("");
                    $('#pendapatan_ibu_id').val("");
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