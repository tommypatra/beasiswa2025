@extends('template')

@section('scriptHead')
<title>Wawancara Peserta</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>

.mahasiswa-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007bff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.modal-xxl {
    max-width: 95vw;
}

.nim {
    font-weight: bold;
    color: #555;
    margin-top: 5px;
}

.study-program {
    font-size: 14px;
    color: #777;
}

.list {
    list-style-type: decimal;    
    margin-left: 20px;
    padding-left: 20px;
}

.list li {
    line-height: 1.5;
}

</style>
@endsection

@section('container')
<div id="info-beasiswa" class="mb-2"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Peserta Wawancara</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama/ Nim / Program Studi</th>
                        <th width="25%">Pewawancara</th>
                        <th width="5%" class="text-center">Status/ Aksi</th>
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

<!-- MULAI MODAL DAFTAR PESERTA-->
<div class="modal fade modal" id="modal-wawancara" role="dialog">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Wawancara Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4 flex-column align-items-center justify-content-center text-center">
                        <div class="card">
                            <div class="card-body">
                                <h5>Identitas Peserta</h5>
                                <img src="{{ asset('images/user-avatar.png') }}" alt="Foto Mahasiswa" class="mahasiswa-photo">
                                <h5 class="mt-2 mahasiswa-nama">Nama</h5>
                                <div class="mahasiswa-nim">NIM</div>
                                <div class="mahasiswa-prodi">Program Studi</div>
                                <div class="mahasiswa-no-pendaftaran">Nomor Pendaftaran</div>
                                <div class="mahasiswa-email">Email</div>
                                <button class="btn btn-danger mt-3 mb-3 akhiri-wawancara" data-peserta_wawancara_id="" >Akhiri Wawancara</button>

                            </div>
                        </div>


                    </div>
                    <form id="form-wawancara" class="col-lg-8">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="pewawancara_id" name="pewawancara_id">
                        <input type="hidden" id="soal_wawancara_id" name="soal_wawancara_id">
                        <input type="hidden" id="pendaftar_id" name="pendaftar_id">
                        <input type="hidden" id="beasiswa_id" name="beasiswa_id" value="{{ $id }}">
    
                        <div>
                            <div class="card">
                                <div class="card-body">
                                    
                                    <div class="d-flex justify-content-between">
                                        <h5 id="info-nomor-soal">Soal Wawancara</h5>
                                        <div>
                                            <div class="btn btn-outline-primary btn-sm soal-sebelumnya"><<</div>
                                            <div class="btn btn-outline-primary btn-sm soal-berikutnya">>></div>
                                        </div>
                                    </div>                                    
                                    <div id="soal-wawancara"></div>
                                    <hr>
                                    <h5>Tanggapan Pewawancara</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">Nilai</label>
                                            <input name="nilai" id="nilai" type="number" class="form-control" required>
                                            <i>Nilai : 0 sd 100</i>
                                        </div>
                
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">Catatan</label>
                                            <textarea name="catatan" id="catatan" rows="4" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-simpan">Simpan</button>
                                        <div>
                                            <div class="btn btn-outline-primary btn-sm soal-sebelumnya"><<</div>
                                            <div class="btn btn-outline-primary btn-sm soal-berikutnya">>></div>
                                        </div>
                                    </div>
                
                                </div>                                            
                            </div>
                        </div>
                    </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- AKHIR MODAL -->

@endsection

@section('scriptJs')
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/peserta-wawancara';
    const beasiswa_id="{{ $id }}";
    var page = 1;
    var pewawancara;
    var pendaftar_id;
    var last_page;
    
    $(document).ready(function() {
        dataLoad();

        async function wawancara_mulai() {            
            const id=$('.akhiri-wawancara').attr('data-peserta_wawancara_id');

            let peserta = await asyncFunction(`${base_url}/api/peserta-wawancara/${id}`);
            let pendaftar = await asyncFunction(`${base_url}/api/get-data-pendaftar/${pendaftar_id}`);
            let response = await asyncFunction(`${base_url}/api/proses-wawancara/${pendaftar_id}?beasiswa_id=${beasiswa_id}`);
            let data = pendaftar.data;
            last_page=response.data.last_page;

            // console.log(peserta.data);

            if (peserta.data.status == 2 || !data.beasiswa.is_wawancara_aktif) {
                $('#form-wawancara').find('input, select, textarea, button').prop('disabled', true);
                $('#catatan').summernote('disable');
                $('.akhiri-wawancara').prop('disabled',true);
                $('.btn-simpan').prop('disabled', true);
            }else{
                $('#form-wawancara').find('input, select, textarea, button').prop('disabled', false);
                $('#catatan').summernote('enable');
                $('.akhiri-wawancara').prop('disabled',false);
                $('.btn-simpan').prop('disabled', false);
            }
            
            $('#modal-label').text(`Wawancara ${data.beasiswa.nama}`);
            $('.mahasiswa-nama').text(data.mahasiswa.user.name);
            $('.mahasiswa-nim').text(`Nim : ${data.mahasiswa.nim}`);
            $('.mahasiswa-prodi').text(data.mahasiswa.program_studi.nama);
            $('.mahasiswa-no-pendaftaran').text(`Nomor Pendaftaran : ${data.no_pendaftaran}`);
            $('.mahasiswa-email').text(`${data.mahasiswa.user.email}`);
            $('.mahasiswa-photo').attr('src',base_url+'/'+data.mahasiswa.user.identitas.foto);


            renderWawancara(response.data);
        }

        function cariPewawancara(data, user_id) {
            return data.filter(item => item.pewawancara.user.id === user_id);
        }


        function renderData(response) {
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    let tombol_aktif = (dt.is_registrasi_wawancara!==1)?`disabled`:``;
                    let hadir = dt.is_registrasi_wawancara?`<span class="badge fs-2 bg-primary">Sudah Registrasi</span>`:`<span class="badge fs-2 bg-danger">belum Registrasi</span>`;
                    let pewawancara = ``;
                    let pewawancara_id;
                    let label_tombol = `Mulai Wawancara`;
                    let status_wawancara = 0;
                    let peserta_wawancara_id;
                    let tombol_wawancara="btn-warning";
                    if(dt.wawancara.length>0){
                        pewawancara = `<ul class="list">`;


                        $.each(dt.wawancara, function(index, item) {

                            let proses_wawancara=``;
                            if(dt.is_registrasi_wawancara){
                                proses_wawancara=`<span class="badge fs-2 bg-danger">belum dimulai</span>`;
                                if(item.status==1){
                                    proses_wawancara=`<span class="badge fs-2 bg-primary">Proses wawancara</span>`;
                                }
                                else if(item.status==2){
                                    proses_wawancara=`<span class="badge fs-2 bg-success">Selesai</span>`;
                                }
                            }

                            pewawancara += `<li>${item.pewawancara.user.name} ${proses_wawancara}</li>`;
                            if(item.pewawancara.user_id==localStorage.getItem('id')){
                                pewawancara_id=item.pewawancara.id;
                                peserta_wawancara_id=item.id;
                                if(item.status==1){
                                    label_tombol=`Lanjutkan Wawancara`;
                                    status_wawancara=1;
                                    tombol_wawancara="btn-secondary"
                                }
                                else if(item.status==2){
                                    label_tombol=`Wawancara Selesai`;
                                    status_wawancara=2;
                                    tombol_wawancara="btn-success"
                                }
                            }
                        });
                        pewawancara += `<ul>`;
                    }
                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <img src="${base_url}/${dt.identitas.foto}" width="90px" >
                                            <div>
                                                ${dt.user.name}/  
                                                ${dt.mahasiswa.nim}/
                                                ${dt.mahasiswa.program_studi.nama}
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        ${pewawancara}
                                    </td>
                                    <td class="text-center">
                                        ${hadir}
                                        <div class="d-flex flex-column align-items-center gap-2 mt-2">
                                            <button class="btn ${tombol_wawancara} btn-mulai-wawancara" data-pendaftar_id="${dt.id}" data-pewawancara_id="${pewawancara_id}" data-peserta_wawancara_id="${peserta_wawancara_id}" data-status_wawancara="${status_wawancara}" type="button" ${tombol_aktif}>${label_tombol}</button>
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

        async function dataLoad() {
            var search = $('#search-input').val();
            var url = `${base_url}/api/daftar-peserta-wawancara?beasiswa_id=${beasiswa_id}&search=${search}`;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        async function dataPesertaWawancara() {
            var url = `${base_url}/api/peserta-wawancara/${search}`;
            let response = await asyncFunction(`${url}`);
            console.log(response)
        }

        async function dataWawancara() {
            let response = await asyncFunction(`${base_url}/api/proses-wawancara/${pendaftar_id}?page=${page}&beasiswa_id=${beasiswa_id}`);
            renderWawancara(response.data);
        }        

        $('.akhiri-wawancara').click(function() {
            let tmpid = $(this).attr('data-peserta_wawancara_id');
            if (!tmpid) return;
            if (!confirm("Apakah anda yakin akan mengakhiri wawancara ini ?")) return;

            let input = prompt("Ketik SELESAI untuk benar-benar mengakhiri sesi wawancara ini dan tidak bisa lagi dilakukan perubahan nilai !");
            if (input !== "SELESAI") 
                return alert("Proses dibatalkan. Ketik 'SELESAI' untuk mengakhiri.");

            const url = `${base_url}/api/akhiri-wawancara/${tmpid}`;
            const dataForm = new URLSearchParams();
            dataForm.append("status", "2");
            saveData(url, 'PUT', dataForm.toString(), function(response) {
                appShowNotification(response.status,[response.message]);
                if(response.status){
                    $('#form-wawancara').find('input, select, textarea, button').prop('disabled', true);
                    $('#catatan').summernote('disable');
                    $('.btn-simpan').prop('disabled', true);
                    $('.akhiri-wawancara').prop('disabled',true);
                    dataLoad();
                }
            });            
        });

        function renderWawancara(data) {
            const wawancara=data.data[0];
            let id="";
            let catatan="";
            let nilai="";
            let sudah_dinilai =`<span class="badge bg-danger">Belum Dinilai</span>`;
            if(data.data[0].wawancara_nilai){
                id=data.data[0].wawancara_nilai.id;
                catatan=data.data[0].wawancara_nilai.catatan;
                nilai=data.data[0].wawancara_nilai.nilai;
                sudah_dinilai =`<span class="badge bg-success">Sudah Dinilai</span>`;
            }
            $('#info-nomor-soal').html(`Soal Wawancara ke ${data.current_page} dari ${data.last_page} ${sudah_dinilai}`);

            $('#id').val(id);
            $('#pewawancara_id').val(pewawancara_id);
            $('#soal_wawancara_id').val(wawancara.id);
            $('#pendaftar_id').val(pendaftar_id);
            $('#nilai').val(nilai);
            $('#catatan').summernote('code', catatan);

            $('#soal-wawancara').html(`<figure>
                                            <blockquote class="blockquote">
                                                <p>${wawancara.soal}</p>
                                            </blockquote>
                                            <figcaption class="blockquote-footer">
                                                <i>Peresentase nilai ${wawancara.persentase_nilai}</i>
                                            </figcaption>
                                        </figure>`);
        }

        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });      

        $('.soal-berikutnya').click(async function() {
            page++;
            if(page>last_page){
                page=1;
            }
            dataWawancara();
        });      

        $('.soal-sebelumnya').click(async function() {
            page--;
            if(page<=0){
                page=last_page;
            }
            dataWawancara();
        });      


        $(document).on('click','.btn-mulai-wawancara', async function(){
            pendaftar_id=$(this).data('pendaftar_id');
            pewawancara_id=$(this).data('pewawancara_id');
            let peserta_wawancara_id=$(this).data('peserta_wawancara_id');
            let status_wawancara=$(this).data('status_wawancara');
            if(status_wawancara==0){
                const url = base_url+'/api/cari-peserta-wawancara/' + peserta_wawancara_id;
                const dataForm = new URLSearchParams();
                dataForm.append("status", "1");
                saveData(url, 'PUT', dataForm.toString(), function(response) {
                    console.log(response);
                    dataLoad();
                });
            }

            $('.akhiri-wawancara').attr('data-peserta_wawancara_id', peserta_wawancara_id);
            page = 1;
            wawancara_mulai();
            showModal('modal-wawancara');
        });


        $('#catatan').summernote({
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

                //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-wawancara").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? base_url+'/api/wawancara-nilai' : base_url+'/api/wawancara-nilai' + '/' + id;

                saveData(url, type, $(form).serialize(), function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    page++;
                    if(page>last_page){
                        page=1;
                    }
                    dataWawancara();
                });
            }
        });


    });
</script>
@endsection