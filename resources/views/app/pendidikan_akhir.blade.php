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
            <div class="alert alert-primary" role="alert">
                Untuk nilai akhir wajib diisi dengan 0 - 100, jika ada koma cara mengisinya ganti dengan titik contoh : 89.78
                <br>ijazah ukuran maksimal 3MB (boleh kosong)
            </div>

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
                <div class="col-lg-9 mb-3">
                    <label class="form-label">Nama Sekolah</label>
                    <input name="nama_sekolah" id="nama_sekolah" type="text" class="form-control" required>
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="form-label">Akreditasi</label>
                    <select name="akreditasi" id="akreditasi" class="form-control" required>
                        <option value="">PILIH</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
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
                    <div style="font-style: italic">rata rata nilai pada ujian nasional di Ijazah/SKHU/SKL, contoh : 93.50</div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Upload Ijazah serta Nilai</label>
                        <input type="file" id="foto_ijazah" name="foto_ijazah" class="form-control" accept="application/pdf">
                        <div>upload pdf ijazah dan nilai ijazah <b>(boleh kosong)</b></div>
                        <div id="download_foto_ijazah"></div>                    
                    </div>
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

        $(document).on('input', 'input[type="text"]', function() {
            $(this).val($(this).val().toUpperCase());
        });

        function renderData(ada,data){
            if (ada) {
                $('#id').val(data.id);
                $('#nisn').val(data.nisn);
                $('#nama_sekolah').val(data.nama_sekolah);
                $('#jenis').val(data.jenis);
                $('#akreditasi').val(data.akreditasi);
                $('#tahun_lulus').val(data.tahun_lulus);
                $('#nilai_akhir_lulus').val(data.nilai_akhir_lulus);
                $('#jurusan').val(data.jurusan);
                if(data.foto_ijazah){
                    $('#download_foto_ijazah').html(`
                        <a href="${base_url}/${data.foto_ijazah}" target="_blank" class="badge text-bg-success mt-2">
                            <iconify-icon icon="solar:download-linear" class=""></iconify-icon> Download Ijazah
                        </a>
                    `);
                }

            }else{
                $('#id').val("");
                $('#nisn').val("");
                $('#nama_sekolah').val("");
                $('#jenis').val("");
                $('#akreditasi').val("");
                $('#tahun_lulus').val("{{ date('Y') }}");
                $('#nilai_akhir_lulus').val("");
                $('#jurusan').val("");
                $('#download_foto_ijazah').html("");

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
            // rules: {
            //     foto_ijazah: {
            //         required: function() {
            //             return $('#id').val() === '';
            //         }
            //     }
            // },
            // messages: {
            //     foto_ijazah: {
            //         required: "Ijazah wajib diupload.",
            //     }
            // },
            submitHandler: function(form,event) {
                event.preventDefault();
                const id = $('#id').val();
                const url = (id === '') ? endpoint : endpoint + '/' + id;

                var formData = new FormData(form);
                if((id !== '')){
                    formData.append("_method", "put");
                }

                saveData(url, 'POST', formData, function(response) {
                    // renderData(response,status, response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                    dataLoad();
                    $('#foto_ijazah').val("");

                });
            }
        });

    });
</script>
@endsection