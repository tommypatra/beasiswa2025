@extends('template')

@section('scriptHead')
<title>Berkas Pendaftaran Beasiswa</title>
<style>
.preview {
    margin-top: 10px;
    max-width: 300px;
}
.list-data {
    list-style: disc;  
    padding-left: 20px; 
}
</style>
@endsection

@section('container')

<div class="row">
<div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
    <h5 class="card-title fw-semibold" id="beasiswa-deskripsi">Syarat Dokumen Beasiswa</h5>
    <div class="d-flex gap-2">
        <a href="{{ url('/pendaftar') }}" class="btn btn-primary">
            <i class="ti ti-briefcase"></i>
        </a>
        <button class="btn btn-success" id="btn-refresh">
            <i class="ti ti-reload"></i>
        </button>
        <button class="btn btn-success" id="btn-cetak-pendaftaran">
            <i class="ti ti-printer"></i> Cetak Bukti Pendaftaran
        </button>
    </div>
</div>

<div class="col-lg-12 mb-2" id="identitas-pendaftar"></div>    
</div>

<div class="row">
    <div id="syarat-dokumen"></div>   
</div>
<div class="row" id="area-finalisasi" style="display: none;"> 
    <div class="col-lg-12 mb-3" >
        <h5>Persetujuan Pendaftaran : </h5>
        <ul class="list-data">
            <li>Pastikan seluruh dokumen dan data yang dikirim bersifat final</li> 
            <li>Dengan menekan tombol <b>"Pendaftaran Selesai"</b> maka proses pendaftaran <b>"Dinyatakan Selesai"</b></li>
            <li>Setelah pendaftaran selesai, seluruh data dan dokumen tidak bisa diubah lagi</li> 
            <li>jika suatu saat terbukti melakukan manipulasi data maka saya siap dikenakan sanksi akademik atau pidana atas perbuatan tersebut.</li>
        </ul>                                 
    </div>
    <div class="col-lg-6 mb-3">
        <button type="button" class="btn btn-warning" id="btn-pendaftaran-selesai" disabled>Pendaftaran Selesai</button>
    </div>
</div>

@endsection

@section('scriptJs')
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
const endpoint = base_url + '/api/upload-syarat';
const id = '{{ $id }}';
var beasiswa_id;
var page = 1;
var siap_finaliasi=true;
var is_finalisasi=false;
var url_id;

async function initPage() { // agar di load secara berurutan
    await dataLoad();
}

async function dataLoad() {
    try {
        let result = await execAsync(`${base_url}/api/pendaftar/${id}`, 'GET', token);
        renderData(result.data);
    } catch (error) {
        console.error("Terjadi kesalahan:", error);
    }
}       

function renderData(data){
    if (data.pendaftar.length>0) {
        let beasiswa = data;
        let syarat = data.syarat;
        let pendaftar = data.pendaftar[0];
        let mahasiswa = pendaftar.mahasiswa;
        let konten_syarat ='';
        if(!mahasiswa){
            alert('akses ditolak');
            window.location.replace(`${base_url}/pendaftar`);
        }
        url_id=pendaftar.url_id;
        $('#id').val(pendaftar.id);
        $('#beasiswa-deskripsi').text(beasiswa.nama);
        $('#identitas-pendaftar').text(`Data Mahasiswa : ${localStorage.getItem('nama')}/ ${mahasiswa.nim}/ ${mahasiswa.program_studi.nama}`);
        siap_finaliasi=true;
        is_finalisasi=pendaftar.is_finalisasi;
        $.each(syarat, function(index, dt) {
            var is_wajib=(dt.is_wajib)?`<span class="badge rounded-pill fs-2 bg-danger">wajib</span>`:`<span class="badge rounded-pill fs-2 bg-warning">tidak wajib</span>`;
            const contoh=(dt.contoh)?`<div class="mb-2"><i>Wajib sama dengan format berikut <a href="${base_url}/${dt.contoh}" target="_blank"><span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">Download Contoh Format</span></a></i></div>`:"";

            const jenis_dokumen=(dt.jenis.toLowerCase()==='pdf')?`application/pdf`:`image/png, image/jpeg, image/jpg`;
            var upload_syarat="";
            // console.log(dt.upload_syarat);
            var upload_syarat_id="";


            if(dt.upload_syarat){
                let timestamp = dt.upload_syarat.created_at.replace('T', ' ').split('.')[0];
                upload_syarat_id=dt.upload_syarat.id;
                upload_syarat=` <div class="d-flex align-items-center justify-content-between py-10 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle-shape bg-light me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                                            <iconify-icon icon="solar:cloud-file-outline" class="fs-7 text-body-color"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fs-3">Dokumen Upload ${dt.nama}</h6>
                                            <p class="mb-0 fs-2 d-flex align-items-center gap-1">
                                                <a href="${base_url}/${dt.upload_syarat.dokumen}" target="_blank"><span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1"><i class="ti ti-download"></i> Download</span></a>                                                    
                                                <a href="javascript:;" data-id="${upload_syarat_id}" class="hapus-upload-syarat"><span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1"><i class="ti ti-trash"></i> Hapus</span></a>                                                    
                                            </p>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill fw-medium fs-2 d-flex align-items-center bg-success-subtle text-success text-end"><i class="ti ti-calendar"></i> ${waktuLalu(timestamp)}</span>
                                </div>`;
            }else{
                if(dt.is_wajib)
                    siap_finaliasi=false;
            }
            konten_syarat+=`<div class="card">
                                <div class="card-body">
                                    <div class="d-sm-flex d-block align-items-center justify-content-between">
                                        <div>
                                            <h5 class="card-title fw-semibold">${dt.nama}</h5>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <span class="badge rounded-pill fs-2 bg-secondary-subtle text-secondary">${dt.jenis}</span>
                                            ${is_wajib}                                            
                                        </div>
                                    </div>
                                    <div class="mb-2" style="font-style:italic;">
                                        ${dt.deskripsi}                                        
                                    </div>
                                    ${contoh}
                                    <div class="row mb-2 ">
                                        <div class="col-lg-6 mb-3 input-file">
                                            <input class="form-control upload-syarat" type="file" data-beasiswa_id="${beasiswa.id}" data-upload_syarat_id="${upload_syarat_id}" data-syarat_id="${dt.id}" name="dokumen" accept="${jenis_dokumen}">
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            ${upload_syarat}
                                        </div>                                            
                                    </div>
                                </div>
                            </div>`;
        });
        
        $('#syarat-dokumen').html(konten_syarat);
        if(siap_finaliasi){
            $('#btn-pendaftaran-selesai').attr('disabled',false);
            $('#btn-pendaftaran-selesai').removeClass('btn-warning').addClass('btn-primary');
        }else{
            $('#btn-pendaftaran-selesai').attr('disabled',true);
            $('#btn-pendaftaran-selesai').removeClass('btn-primary').addClass('btn-warning');
        }

        if(!pendaftar.is_finalisasi && data.is_pendaftaran_aktif){
            $('#area-finalisasi').show();
            $('.input-file').show();
            $('.hapus-upload-syarat').show();
        }else{
            $('#area-finalisasi').hide();
            $('.input-file').hide();
            $('.hapus-upload-syarat').hide();
        }
    }else{
        alert('akses ditolak')
        window.location.replace(`${base_url}/pendaftar`);
    }
}

$(document).ready(function() {
    initPage();

    
    // Handle page change
    $('#btn-refresh').click(function() {
        dataLoad();
    });

    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $(document).on('change', '.upload-syarat', function(){
        const syarat_id = $(this).data('syarat_id');
        const beasiswa_id = $(this).data('beasiswa_id');

        var formData = new FormData();
        formData.append("beasiswa_id", beasiswa_id);            
        formData.append("pendaftar_id", id);            
        formData.append("syarat_id", syarat_id);            
        formData.append("dokumen", $(this)[0].files[0]);   

        saveData(`${base_url}/api/upload-syarat`, 'POST', formData, function(response) {
            appShowNotification(true,['berhasil terupload!']);
            dataLoad();
        });

    });

    $(document).on('click', '#btn-cetak-pendaftaran', function(){
        if(!is_finalisasi){
            appShowNotification(false,['Semua syarat yang sifatnya wajib harus diupload setelah itu klik tombol Pendaftaran Selesai pada bagian bawah']);
        }else{
            if(url_id){
                // window.location.replace(`${base_url}/cetak-kartu-pendaftaran/${tmp_id}`);
                window.open(`${base_url}/cetak-kartu-pendaftaran/${url_id}`, '_blank');
            }else{
                alert('init gagal')
            }
        }
    });
    
    $(document).on('click', '.hapus-upload-syarat', function(){
        const id = $(this).data('id');
        deleteData(endpoint, id, function() {
            appShowNotification(true,['berhasil dilakukan!']);
            dataLoad();
        });

    });

    $(document).on('click', '#btn-pendaftaran-selesai', function(){
        const url = `${base_url}/api/pendaftaran-selesai/${id}`;
        if(confirm('Apakah anda yakin data dan dokumen pendaftaran telah lengkap, tidak ada perubahan dan selesai?')){
            const selesai=prompt('Ketik dengan huruf kapital "SELESAI", maka pendaftaran akan dinyatakan selesai, final dan tidak bisa lagi diubah!');            
            if(selesai==='SELESAI')
                saveData(url, 'PUT', null, function(response) {
                    //jika berhasil
                    appShowNotification(true,['Selamat pendaftaran telah selesai, silahkan cetak bukti pendaftaran!']);
                    dataLoad();
                });
        }

    });
    

});
</script>
@endsection