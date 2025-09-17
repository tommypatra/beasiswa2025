<form id="form-pengaturan" class="mb-3">
    <input type="hidden" id="pengaturan_id" name="id">
    <div class="row">
        <div class="col-lg-4 mb-3">
            <label class="form-label">Jumlah Peserta Per Ruangan</label>
            <input name="peserta_per_ruangan" id="peserta_per_ruangan" type="number" class="form-control">
        </div>
        <div class="col-lg-4 mb-3">
            <label class="form-label">Tanggal Ujian</label>
            <input name="tanggal_mulai" id="tanggal_mulai" type="text" class="form-control datepicker" value="{{ date('Y-m-d')}}" required>
            sd
            <input name="tanggal_selesai" id="tanggal_selesai" type="text" class="form-control datepicker" value="{{ date('Y-m-d')}}" required>
        </div>
        <div class="col-sm-12 mb-3">
            <label class="form-label">Cetak Kartu Ujian</label>
            <textarea name="cetak_kartu_ujian" id="cetak_kartu_ujian" rows="6" class="form-control"></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
</form>


@push('scriptJs')
<script type="text/javascript">

    function loadDataTab1() {
        url=`${base_url}/api/pengaturan-ujian?beasiswa_id=${beasiswa_id}`;
        formResetPengaturan();
        fetchData(url, function(response) {
            const data=response.data.data;
            if(data.length>0){
                $('#pengaturan_id').val(data[0].id);
                $('#peserta_per_ruangan').val(data[0].peserta_per_ruangan);
                $('#tanggal_mulai').val(data[0].tanggal_mulai);
                $('#tanggal_selesai').val(data[0].tanggal_selesai);
                $('#cetak_kartu_ujian').val(data[0].cetak_kartu_ujian);
            }
        },true);

    }

    function formResetPengaturan(){
        $('#form-pengaturan').trigger('reset');
        $('#form-pengaturan input[type="hidden"]').val('');
    }

    $(document).ready(function() {
        const endpoint=`${base_url}/api/pengaturan-ujian`;
        loadDataTab1();

        $('#cetak_kartu_ujian').summernote({
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


        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-pengaturan").validate({
            submitHandler: function(form) {
                const id = $('#pengaturan_id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                const dataForm = $(form).serialize() + '&beasiswa_id=' + beasiswa_id;

                saveData(url, type, dataForm, function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataTab1();
                });
            }
        });        

    });
</script>
@endpush