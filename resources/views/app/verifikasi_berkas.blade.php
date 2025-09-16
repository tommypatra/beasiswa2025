@extends('template')

@section('scriptHead')
<title>Verfikator Berkas</title>
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

</style>
@endsection

@section('container')
<div id="info-beasiswa" class="mb-2"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Verfikator Berkas</h5>
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
                        <th width="8%">Tahun</th>
                        <th width="40%">Beasiswa</th>
                        <th width="20%">Jadwal Verifikasi</th>
                        <th width="15%">Jumlah Peserta </th>
                        <th width="5%" class="text-center">Aksi</th>
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
<div class="modal fade modal" id="modal-peserta" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Daftar Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Nama/ Nim</th>
                                        <th width="25%">Fakultas/ Program Studi</th>
                                        <th width="15%">Status Verifikasi </th>
                                    </tr>
                                </thead>
                                <tbody id="data-list-peserta">
                                </tbody>
                            </table>
                        </div>                                                            
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- AKHIR MODAL -->

<!-- MULAI MODAL VALIDASI-->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog modal-xxl">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Verifikasi Berkas <span class="mahasiswa-nama"></span></h5>
                    &nbsp; <a href="#" target="_blank" class="cetak-kartu-pendaftaran fs-4"><iconify-icon icon="solar:printer-outline" class=""></iconify-icon></a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row"> 
                        <div class="col-lg-4 flex-column align-items-center justify-content-center text-center">
                            <div class="card">
                                <div class="card-body">                                
                                    <h5 id="info-halaman"></h5>
                                    <img src="{{ asset('images/user-avatar.png') }}" alt="Foto Mahasiswa" class="mahasiswa-photo">
                                    <h5 class="mt-2 mahasiswa-nama">Nama</h5>
                                    <div class="mahasiswa-nim">NIM</div>
                                    <div class="mahasiswa-prodi">Program Studi</div>
                                    <div class="mahasiswa-no-pendaftaran">Nomor Pendaftaran</div>
                                    <div class="mahasiswa-email">Email</div>
                                    <div class="d-flex justify-content-between w-100 mt-2">
                                        <div id="peserta-sebelumnya" class="btn btn-outline-primary">Previous</div>
                                        <div id="peserta-berikutnya" class="btn btn-primary">Next</div>
                                    </div>                                
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">                                
                                    <h5>Syarat</h5>
                                    <div id="syarat-list"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-8">
                            <div class="card" id="validasi-syarat">                            
                                <div class="card-body">                                
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Syarat Upload <span id="info-syarat"></span></h5>
                                        <div>
                                            <div class="btn btn-outline-primary btn-sm btn-syarat-sebelumnya"> << </div>
                                            <div class="btn btn-primary btn-sm btn-syarat-berikutnya"> >> </div>
                                        </div>
                                    </div>                                    
                                    <div id="syarat-upload"></div>
                                    <hr>
                                    <div id="syarat-form-validasi" >
                                        <form id="form">
                                            <h5>Validasi Syarat</h5>
                                            <input type="hidden" id="id" name="id">
                                            <div class="row">
                                                <div class="col-lg-8 mb-3">
                                                    <select class="form-control" name="verifikasi_berkas_hasil" id="verifikasi_berkas_hasil" data-bobot="" required>
                                                        <option value="">Pilih</option>
                                                        <option value="1">Memenuhi Syarat</option>
                                                        <option value="0">Tidak Memenuhi Syarat</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-4 mb-3"> 
                                                    <input type="number" class="form-control" id="verifikasi_berkas_skor" placeholder="skor" name="verifikasi_berkas_skor" required>
                                                    <i>wajib di isi 0 - 100</i>
                                                </div>
    
                                                <div class="col-lg-12 mb-3">
                                                    <textarea class="form-control" name="verifikasi_berkas_catatan" id="verifikasi_berkas_catatan" rows="3"></textarea>
                                                </div>
                                            </div>
                        
                                            <div class="mt-1">
                                                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                                                <div class="btn btn-outline-primary btn-syarat-sebelumnya" > << </div>
                                                <div class="btn btn-primary btn-syarat-berikutnya"> >> </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                            <div class="card" id="validasi-final" style="display:none">                            
                                <div class="card-body">                                
                                    <form id="form-validasi-final" >
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Validasi Final</h5>
                                            <div>
                                                <div class="btn btn-outline-primary btn-sm btn-syarat-sebelumnya"> << </div>
                                                <div class="btn btn-primary btn-sm btn-syarat-berikutnya"> >> </div>
                                            </div>
                                        </div>                                    
                                        <input type="hidden" id="verifikator_pendaftar_id" name="verifikator_pendaftar_id">
                                        <div class="row">
                                            <div class="col-lg-8 mb-3">
                                                <select class="form-control" name="hasil" id="hasil" required>
                                                    <option value="">Pilih</option>
                                                    <option value="1">Memenuhi Syarat</option>
                                                    <option value="0">Tidak Memenuhi Syarat</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <input type="number" class="form-control" id="total_skor" placeholder="total skor" name="total_skor" required>
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                                            </div>
                                        </div>
                    
                                        <div class="mt-1">
                                            <button type="submit" class="btn btn-primary" id="btn-simpan-final">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/verifikasi-berkas';
    var page = 1;
    var current_page_verifikasi = 1;
    var total_page_verifikasi = 1;    
    var beasiswa_id;
    var verifikator_id;
    var peserta;
    var data_syarat;
    var lengkap;
    var syarat_index=0;
    var is_verifikasi_berkas_aktif=false;

    $(document).ready(function() {
        dataLoad();


        async function openPdf(container, urlPdf) {
            container.innerHTML = ''; // Bersihkan isi elemen dulu
            // Cek apakah URL PDF tersedia
            if (!urlPdf || urlPdf.trim() === '') {
                container.innerHTML = '<p style="color:red;">Tidak ada file diupload.</p>';
                return;
            }

            // Set worker PDF.js
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

            try {
                const pdf = await pdfjsLib.getDocument(urlPdf).promise;
                const totalPages = pdf.numPages; // Dapatkan jumlah total halaman

                // Buat kontainer untuk menampung canvas per halaman
                for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    const viewport = page.getViewport({
                        scale: 1.5
                    });

                    // Buat canvas untuk setiap halaman
                    const canvas = document.createElement('canvas');
                    canvas.style.width = '100%';
                    container.appendChild(canvas);

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const context = canvas.getContext('2d');
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    // Render halaman
                    await page.render(renderContext).promise;
                }
            } catch (error) {
                container.innerHTML = `<p style="color:red;">Gagal memuat dokumen PDF</p>`;
                console.error('PDF load error:', error);
            }
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
                    let tombol_aktif = (dt.verifikator_pendaftar_count<1)?`disabled`:``;
                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>
                                        ${dt.beasiswa.tahun}
                                    </td>
                                    <td>
                                        ${dt.beasiswa.nama}
                                    </td>
                                    <td>
                                        ${dt.beasiswa.verifikasi_berkas_mulai} sd 
                                        ${dt.beasiswa.verifikasi_berkas_selesai}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary">Jumlah Peserta : ${dt.verifikator_pendaftar_count}</span>
                                        <hr>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle text-success">Sudah Verifikasi : ${dt.verifikator_pendaftar_valid}</span>
                                        <span class="badge rounded-pill fs-2 fw-medium bg-danger-subtle text-danger">Belum Verifikasi : ${dt.verifikator_pendaftar_count-dt.verifikator_pendaftar_valid}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <button class="btn btn-secondary btn-mulai-verifikasi" data-beasiswa_id="${dt.beasiswa.id}" data-is_verifikasi_berkas_aktif="${dt.beasiswa.is_verifikasi_berkas_aktif}" data-verifikator_id="${dt.id}" type="button" ${tombol_aktif}>Verifikasi</button>
                                        </div>
                                    </td>
                                </tr>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<tr>
                                <td colspan="6">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        async function dataLoad() {
            var search = $('#search-input').val();
            var url = `${endpoint}?search=${search}`;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        $(document).on('click', '.btn-mulai-verifikasi', function() {
            showModal('modal-form');
            
            verifikator_id = $(this).data('verifikator_id');
            beasiswa_id = $(this).data('beasiswa_id');
            is_verifikasi_berkas_aktif = $(this).data('is_verifikasi_berkas_aktif');
            pesertaVerifikasi();
        }); 

        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $(document).on('input', '#search-input', function() {
            // console.log('Event input berjalan');
            dataLoad();
        });      


        //hapus data
        $(document).on('click', '.btn-peserta', function() {
            const id = $(this).data('beasiswa_id');
            showModal('modal-peserta');

        });

        function opsi_instrumen(data_opsi){
            //untuk pengaturan opsi instrumen
            let opsi_pilihan_instrumen = [
                {label: "Memenuhi Syarat", skor: 1},
                {label: "Tidak Memenuhi Syarat", skor: 0}
            ];

            if (data_opsi && data_opsi.length > 0) {
                opsi_pilihan_instrumen = data_opsi;
            }
            return opsi_pilihan_instrumen;
        }


        async function pesertaVerifikasi(halaman=1) {
            try {
                let respon_peserta = await execAsync(`${base_url}/api/peserta-verifikasi?page=${halaman}&limit=1&beasiswa_id=${beasiswa_id}`, 'GET', token);
                peserta = respon_peserta.data.data[0];
                let syarat = await execAsync(`${base_url}/api/data-upload-syarat?beasiswa_id=${beasiswa_id}&pendaftar_id=${peserta.pendaftar.id}`, 'GET', token);
                let body = $('#body-verifikasi-berkas');
                let pendaftar_id = peserta.pendaftar.id;

                // console.log(syarat);

                data_syarat=syarat.data;
                $('.cetak-kartu-pendaftaran').attr('href',`${base_url}/cetak-kartu-pendaftaran/${peserta.pendaftar.url_id}`);
                $('.mahasiswa-nama').text(peserta.user.name);
                $('.mahasiswa-nim').text(`Nim : ${peserta.mahasiswa.nim}`);
                $('.mahasiswa-prodi').text(peserta.program_studi.nama);
                $('.mahasiswa-no-pendaftaran').text(`Nomor Pendaftaran : ${peserta.pendaftar.no_pendaftaran}`);
                $('.mahasiswa-email').text(`${peserta.user.email}`);
                $('.mahasiswa-photo').attr('src',base_url+'/'+peserta.foto);


                total_page_verifikasi = respon_peserta.data.last_page; 
                current_page_verifikasi = respon_peserta.data.current_page;

                $("#info-halaman").text(`Peserta ke ${current_page_verifikasi} dari ${total_page_verifikasi}`);
                if (current_page_verifikasi === 1) {
                    $("#peserta-sebelumnya").addClass("disabled").css("pointer-events", "none");
                } else {
                    $("#peserta-sebelumnya").removeClass("disabled").css("pointer-events", "auto");
                }
                if (current_page_verifikasi === total_page_verifikasi) {
                    $("#peserta-berikutnya").addClass("disabled").css("pointer-events", "none");
                } else {
                    $("#peserta-berikutnya").removeClass("disabled").css("pointer-events", "auto");
                }

                //untuk render syarat
                // syarat_index = 0;
                if (syarat.status) {
                    lengkap=true;                    
                    $("#syarat-list").empty();
                    let listHtml = `<ul class="list-group list-group-flush">`;
                    syarat.data.forEach((item, index) => {
                        // let wajib = (item.is_wajib) ? `<span class="badge bg-success fs-2">Wajib</span>` : `<span class="badge bg-warning fs-2">Pilihan</span>`;
                        let wajib=``;

                        if(item.is_wajib && item.upload_syarat){
                            if(item.upload_syarat.verifikasi_berkas_hasil==null)
                                lengkap=false;
                        }

                        let opsi_pilihan_instrumen = opsi_instrumen(item.instrumen_opsi);
                        
                        let opsi_found = opsi_pilihan_instrumen.find(
                            o => o.skor == item.upload_syarat?.verifikasi_berkas_hasil
                        ) ?? null;
                        
                        // console.log(opsi_pilihan_instrumen);

                        let keterangan_upload = (!item.upload_syarat) ? `<span class="badge bg-info fs-2">tidak upload</span>`:
                                                (item.upload_syarat?.verifikasi_berkas_hasil == null) ? `<span class="badge bg-warning fs-2">belum periksa</span>` : 
                                                (item.upload_syarat.verifikasi_berkas_hasil) ? `<span class="badge bg-success fs-2">${opsi_found.label}</span>` : `<span class="badge bg-danger fs-2">TMS</span>`; 
                        listHtml += `<li class="list-group-item syarat-item" data-index="${index}" style="cursor:pointer;">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="d-flex flex-column text-start">
                                                <h6 class="mb-0">${item.nama}</h6>
                                                <small class="text-muted">${wajib} ${keterangan_upload}</small>
                                            </div>
                                            <span class="badge bg-primary ms-auto">${index + 1}</span>
                                        </div>
                                    </li>`;
                    });
                    listHtml += "</ul>";



                    $("#syarat-list").html(listHtml);
                } else {
                    $("#syarat-list").html("<p>Gagal mengambil data.</p>");
                }
                // console.log('lengkap : '+lengkap);
                syaratBerikutnya();

            } catch (error) {
                console.error("Terjadi kesalahan:", error);
            }
        }       

        $('#peserta-sebelumnya').click(function(){
            syarat_index = 0;
            if (current_page_verifikasi > 1) {
                pesertaVerifikasi(current_page_verifikasi - 1);
            }
        });

        $('#peserta-berikutnya').click(function(){
            syarat_index = 0;
            if (current_page_verifikasi < total_page_verifikasi) {
                pesertaVerifikasi(current_page_verifikasi + 1);
            }
        });
        
        $('.btn-syarat-sebelumnya').click(function(){
            syarat_index--;
            syaratSebelumnya();
        });

        function syaratSebelumnya(){
            if (syarat_index == -1) {
                showFinal();
            }else{
                showSyarat();
            }
        }

        function syaratBerikutnya(){
            if (syarat_index == data_syarat.length) {
                showFinal();
            }else{
                showSyarat();
            }
        }

        $('.btn-syarat-berikutnya').click(function(){
            syarat_index++;
            syaratBerikutnya();        
        });

        $(document).on("click",".syarat-item",function() {
            syarat_index = $(this).data("index");
            showSyarat();
        });
        
        function resetFormSyarat(){
            $('#syarat-upload').trigger('reset');
            $('#syarat-upload input[type="hidden"]').val('');
            $("#verifikasi_berkas_hasil").prop("disabled", true);
            $("#verifikasi_berkas_catatan").prop("disabled", true);
            $("#verifikasi_berkas_skor").prop("disabled", true);
            $("#btn-simpan").prop("disabled", true);
        }

        function showFinal(){
            $('#verifikator_pendaftar_id').val(peserta.id); 
            $('#hasil').val(peserta.hasil); 
            $('#total_skor').val(peserta.total_skor); 
            
            $('#catatan').val(peserta.catatan); 
            $('#validasi-final').show();
            $('#validasi-syarat').hide();


            $("#hasil").prop("disabled", true);
            $("#total_skor").prop("disabled", true);
            $("#catatan").prop("disabled", true);
            $("#btn-simpan-final").prop("disabled", true);                
            if(is_verifikasi_berkas_aktif){
                $("#hasil").prop("disabled", false);
                $("#total_skor").prop("disabled", false);
                $("#catatan").prop("disabled", false);
                $("#btn-simpan-final").prop("disabled", false);                
            }

        }

        function showSyarat(){
            resetFormSyarat();
            $('#validasi-final').hide();
            $('#validasi-syarat').show();


            $('#syarat-upload').empty();
            if (syarat_index < 0 || syarat_index >= data_syarat.length) {
                syarat_index = 0;
            }    

            let data = data_syarat[syarat_index];
            let contohPath = data.contoh ? `<a href="${base_url}/${data.contoh}" target="_blank">Contoh Format Dokumen</a>` : "";
            let wajib = (data.is_wajib) ? `Wajib` : `Pilihan`;
            // let dokumenEmbed=`Tidak Mengupload Dokumen`;
            $(`#verifikasi_berkas_skor`).val('');
            $('#info-syarat').text(` ke ${syarat_index+1} dari ${data_syarat.length}`);

            let syarat = `  <div>
                                <h2>${data.nama} (${wajib})</h2>
                                <div >
                                    <div class="accordion-body">                                        
                                        <p>Deskripsi : ${data.deskripsi}</p>
                                        <p>${contohPath}</p>
                                        <div id="dokumen-embed" style="margin-top:10px; height:400px; width:100%; border:1px solid #ccc; overflow:auto;"></div>                                        
                                    </div>
                                </div>
                            </div>`;   
            $('#syarat-upload').html(syarat);

            //untuk pengaturan opsi instrumen
            let opsi_pilihan_instrumen = opsi_instrumen(data.instrumen_opsi);
            let $select = $("#verifikasi_berkas_hasil");
            $select.empty().append('<option value="">Pilih</option>');
            opsi_pilihan_instrumen.forEach(function(opt, idx) {
                // console.log(opt);
                $select.append(`<option value="${opt.skor}">${opt.label}</option>`);
            });
            //untuk akhir untuk pengaturan opsi instrumen

            $("#verifikasi_berkas_hasil").attr('data-bobot',data.bobot);

            if (data.upload_syarat){
                let jenis = data.jenis;
                let url = base_url+'/'+data.upload_syarat.dokumen;
                if(is_verifikasi_berkas_aktif){
                    $("#verifikasi_berkas_hasil").prop("disabled", false);
                    $("#verifikasi_berkas_catatan").prop("disabled", false);
                    $("#verifikasi_berkas_skor").prop("disabled", false);
                    $("#btn-simpan").prop("disabled", false);
                }
                $(`#id`).val(data.upload_syarat.id);
                $(`#verifikasi_berkas_hasil`).val(data.upload_syarat.verifikasi_berkas_hasil);
                $(`#verifikasi_berkas_catatan`).val(data.upload_syarat.verifikasi_berkas_catatan);
                $(`#verifikasi_berkas_skor`).val(data.upload_syarat.verifikasi_berkas_skor);

                if(jenis=='pdf'){
                    openPdf(document.getElementById('dokumen-embed'), url);
                }else{
                    $('#dokumen-embed').html(`<img src="${url}">`)
                }
                // dokumenEmbed = (jenis === "pdf") ?
                //     `<object data="${url}" type="application/pdf" width="100%" height="500px">
                //         <p>Browser Anda tidak mendukung tampilan PDF. <a href="${url}" target="_blank">Cek dokumen PDF disini</a></p>
                //     </object>` :
                //     `<iframe src="${url}" width="100%" height="350px""></iframe>`;
            }else{
                $('#dokumen-embed').html('<p style="color:red;">Tidak ada file diupload.</p>');
            }

            

        }
        
        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            rules: {
                verifikasi_berkas_catatan: {
                    required: function() {
                        return $("#verifikasi_berkas_hasil").val() == "0";
                    }
                }
            },
            messages: {
                verifikasi_berkas_catatan: {
                    required: "Catatan wajib diisi jika tidak memenuhi syarat."
                }
            },            
            submitHandler: function(form) {
                const id = $('#id').val();
                let url = `${base_url}/api/simpan-validasi-syarat/${id}`;

                let data = $(form).serializeArray();
                data.push({ name: 'verifikator_id', value: verifikator_id });

                // konversi ke URL-encoded string kembali
                let postData = $.param(data);                

                saveData(url, 'PUT', postData, function (response) {
                    appShowNotification(true, ["simpan validasi berhasil dilakukan!"]);
                    syarat_index++;
                    pesertaVerifikasi(current_page_verifikasi);
                });
            }
        });

        $(document).on("change","#verifikasi_berkas_hasil",function(){
            let bobot = parseFloat($(this).attr('data-bobot')) || 0;
            let nilai = parseFloat($(this).val()) || 0;
            let total_pilihan = $(this).find("option[value!='']").length;            
            let skor_akhir = (nilai / (total_pilihan - 1)) * bobot;            
            $('#verifikasi_berkas_skor').val(skor_akhir.toFixed(2));
        });


        $("#form-validasi-final").validate({
            rules: {
                catatan: {
                    required: function() {
                        return $("#hasil").val() == "0";
                    }
                }
            },
            messages: {
                catatan: {
                    required: "Catatan wajib diisi jika tidak memenuhi syarat."
                }
            },            
            submitHandler: function(form) {
                const id = $('#verifikator_pendaftar_id').val();   
                
                let data = $(form).serializeArray();
                data.push({ name: 'verifikator_id', value: verifikator_id });

                // konversi ke URL-encoded string kembali
                let postData = $.param(data);                

                if(!lengkap){
                    appShowNotification(false, ["masih ada syarat wajib yang belum divalidasi!"]);
                    syarat_index=0;
                    showSyarat();
                    return;
                }

                let url = `${base_url}/api/simpan-validasi-final/${id}`;
                saveData(url, 'PUT', postData, function (response) {
                    appShowNotification(true, ["berhasil dilakukan!"]);
                    syarat_index=0;

                    current_page_verifikasi++;
                    if(current_page_verifikasi>total_page_verifikasi){
                        current_page_verifikasi=1;
                    }
                    pesertaVerifikasi(current_page_verifikasi);
                    dataLoad();
                });
            }
        });       

    });
</script>
@endsection