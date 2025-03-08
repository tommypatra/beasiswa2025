@extends('template')

@section('scriptHead')
<title>Data Sekolah (SMA)</title>
@endsection

@section('container')
<h4 id="nama-pengguna"></h4>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Data Sekolah (SMA)</h5>
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
                    <label class="form-label">Tahun Lulus</label>
                    <input name="tahun_lulus" id="tahun_lulus" type="number" class="form-control" required>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Jenis Sekolah</label>
                    <select name="jenis" id="jenis" class="form-control">
                        <option value="">PILIH</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                        <option value="MA">MA</option>
                        <option value="PONDOK PESANTREN">PONDOK PESANTREN</option>
                        <option value="LAINNYA">LAINNYA</option>
                    </select>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">NISN</label>
                    <input name="nisn" id="nisn" type="text" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <label class="form-label">Nama Sekolah</label>
                    <input name="nama_sekolah" id="nama_sekolah" type="text" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Jurusan</label>
                    <select name="jurusan" id="jurusan" class="form-control">
                        <option value="">PILIH</option>
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="BAHASA">AGAMA</option>
                        <option value="KEAGAMAAN">KEAGAMAAN</option>
                        <option value="TEKNOLOGI">TEKNOLOGI</option>
                        <option value="BISNIS MANAJEMEN">BISNIS MANAJEMEN</option>
                        <option value="KESEHATAN">KESEHATAN</option>
                        <option value="PARIWISATA">PARIWISATA</option>
                        <option value="SENI">SENI</option>
                        <option value="PERTANIAN">PERTANIAN</option>
                        <option value="LAINNYA">LAINNYA</option>
                    </select>
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="form-label">Nilai Akhir</label>
                    <input name="nilai_akhir_lulus" id="nilai_akhir_lulus" type="text" class="form-control" required>
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
    const endpoint = base_url + '/api/pendidikan-akhir';
    var page = 1;
    $(document).ready(function() {
        dataLoad();

        function renderData(ada,data){
            if (ada) {
                $('#id').val(data.id);
                $('#nisn').val(data.nisn);
                $('#nama_sekolah').val(data.nama_sekolah);
                $('#jenis').val(data.jenis);
                $('#tahun_lulus').val(data.tahun_lulus);
                $('#nilai_akhir_lulus').val(data.nilai_akhir_lulus);
                $('#jurusan').val(data.jurusan);
            }else{
                $('#id').val("");
                $('#nisn').val("");
                $('#nama_sekolah').val("");
                $('#jenis').val("");
                $('#tahun_lulus').val("{{ date('Y') }}");
                $('#nilai_akhir_lulus').val("");
                $('#jurusan').val("");
            }
        }

        function dataLoad() {
            var url = endpoint + '?user_id=' + user_id;
            fetchData(url, function(response) {
                renderData(response.data.data.length,response.data.data[0]);
            }, true);
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
                    renderData(response.status,response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                });
            }
        });

    });
</script>
@endsection