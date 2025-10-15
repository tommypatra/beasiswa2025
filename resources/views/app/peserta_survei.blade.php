@extends('template')

@section('scriptHead')
<title>Peserta Survei</title>
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
            <h5 class="card-title fw-semibold">Peserta Survei</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-primary" id="btn-search">
                    <i class="ti ti-search"></i>
                </button>
                <button class="btn btn-primary" id="btn-progress">
                    <iconify-icon icon="solar:course-up-outline" class="fs-4"></iconify-icon>
                </button>
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
                        <th width="25%">Alamat</th>
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

<!-- MULAI MODAL SURVEI-->
<div class="modal fade modal" id="modal-survei" role="dialog">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label">Peserta Survei</h5>
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
                                <div class="mahasiswa-alamat">Alamat</div>
                                <div class="mahasiswa-no_hp">Nomor HP</div>
                                <button class="btn btn-danger mt-3 mb-3 akhiri-survei" >Akhiri Survei</button>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-lg-8">
    
                        <div class="card" id="komponen">
                            <div class="card-body">
                                <h5 id="info-nomor-soal">Komponen Survei</h5>
                                <div id="daftar-komponen">
                                    <div class="btn btn-sm btn-outline-primary mb-1 active" id="data-pendidikan-akhir" style="display:none">Pendidikan Akhir</div>
                                    {{-- <div class="btn btn-sm btn-outline-primary mb-1" id="data-raport" style="display:none">Raport</div> --}}
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-kondisi-rumah" style="display:none">Kondisi Rumah</div>
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-orang-tua" style="display:none">Orang Tua</div>
                                    {{-- <div class="btn btn-sm btn-outline-primary mb-1" id="data-dokumen-upload">Dokumen Upload</div> --}}
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="dokumentasi-survei">Dokumentasi Survei</div>
                                </div>
                            </div>
                        </div>

                        <div class="card" id="konten">
                            <div class="card-body" id="konten-komponen">
                            </div>
                        </div>

                        <div class="card" id="survei-komponen">
                            <div class="card-body">
                                <form id="form">
                                    <h5>Hasil Survei</h5>
                                    <div class="row">
                                        <div class="col-lg-8 mb-3">
                                            <select class="form-control" name="verifikasi_lapangan_hasil" id="verifikasi_lapangan_hasil" required>
                                                <option value="">Pilih</option>
                                                <option value="4">Sangat Sesuai</option>
                                                <option value="3">Sesuai</option>
                                                <option value="2">Cukup Sesuai</option>
                                                <option value="1">Kurang Sesuai</option>
                                                <option value="0">Tidak Sesuai</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mb-3"> 
                                            <input type="number" class="form-control" id="verifikasi_lapangan_skor" placeholder="skor" name="verifikasi_lapangan_skor" required>
                                            <i>wajib di isi 0 - 100</i>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                            <textarea class="form-control" name="verifikasi_lapangan_catatan" id="verifikasi_lapangan_catatan" rows="3"></textarea>
                                        </div>
                                    </div>
                
                                    <div class="mt-1">
                                        <button type="submit" class="btn btn-primary" id="btn-simpan1">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                                    

                        <div class="card" id="survei-akhir" style="display: none">
                            <div class="card-body">
                                <form id="form-survei-akhir">
                                    <h5>Survei Akhir</h5>
                                    <div class="row">
                                        <div class="col-lg-8 mb-3">
                                            <select class="form-control" name="hasil" id="hasil" required>
                                                <option value="">Pilih</option>
                                                <option value="4">Sangat Layak</option>
                                                <option value="3">Layak</option>
                                                <option value="2">Cukup Layak</option>
                                                <option value="1">Kurang Layak</option>
                                                <option value="0">Tidak Layak</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                            <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                                        </div>
                                    </div>
                
                                    <div class="mt-1">
                                        <button type="submit" class="btn btn-primary" id="btn-simpan2">Simpan</button>
                                    </div>
                                </form>
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
<script src="{{ asset('js/gambar.js') }}"></script>

<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/peserta-survei';
    const beasiswa_id="{{ $id }}";
    var page = 1;
    var vBeasiswa;
    var pewawancara;
    var last_page;
    var data_survei;
    var vRespon;
    var skor_system;
    var pendaftar_id;
    var surveyor_id;
    var survei_peserta;
    var is_survei_aktif=false;
    
    $(document).ready(function() {
        dataLoad();

        $(document).on("click", "#daftar-komponen .btn", function() {
            $("#daftar-komponen .btn").removeClass("active"); 
            $(this).addClass("active");
        });

        function pembalik(nilai, jumlah_pilihan){
            return (jumlah_pilihan > 1) ? (1 - ((nilai - 1) / (jumlah_pilihan - 1))) : 0;
        }

        function faktorVerifikasi(){
            let verifikasi = parseInt($('#verifikasi_lapangan_hasil').val());
            return faktor_verifikasi = verifikasi / 4;        
        }

        $('#verifikasi_lapangan_hasil').on('change', function() {
            let judul_komponen = $('#judul-komponen').text().trim();
            // alert(judul_komponen);
            if(judul_komponen=='Pendidikan Akhir')
                hitung_skor_pendidikan_akhir();
            else if(judul_komponen=='Raport')
                hitung_skor_raport();
            else if(judul_komponen=='Kondisi Rumah')
                hitung_skor_data_kondisi_rumah();
            else if(judul_komponen=='Orang Tua')
                hitung_skor_data_orang_tua();
            else if(judul_komponen=='Dokumen Upload')
                hitung_skor_dokumen_upload();

        });


        //data pendidikan akhir
        $(document).on('click','#data-pendidikan-akhir', function(){
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();
            data_pendidikan_akhir();
        });

        async function data_pendidikan_akhir(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-pendidikan-akhir/${data_survei.user_id}`);
            vId = vRespon.data.pendidikan_akhir_id;
            skor_system = vRespon.data.skor_akhir;

            $('#verifikasi_lapangan_skor').val(vRespon.data.verifikasi_lapangan_skor);
            $('#verifikasi_lapangan_hasil').val(vRespon.data.verifikasi_lapangan_hasil);
            $('#verifikasi_lapangan_catatan').val(vRespon.data.verifikasi_lapangan_catatan);
            
            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Pendidikan Akhir</h5>
                <div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">NISN</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.nisn}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Tahun Lulus</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.tahun_lulus}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Nama Sekolah</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.nama_sekolah}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Jurusan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.jurusan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Akreditasi</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8" class="akreditasi">${vRespon.data.akreditasi}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Nilai Akhir Lulus</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8" id="nilai_akhir_lulus">${vRespon.data.nilai_akhir_lulus}</div>
                    </div>                                    
                </div>            
            `);
        }

        function hitung_skor_pendidikan_akhir(){    
            let faktor_verifikasi = faktorVerifikasi();
            let skor_final = (skor_system * faktor_verifikasi);
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));            
        }

        //data raport
        $(document).on('click','#data-raport', function(){
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();
            data_raport();
        });

        async function data_raport(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-raport/${data_survei.user_id}`);
            vId = vRespon.data.raport.raport_id;
            skor_system = vRespon.data.skor_akhir;

            $('#verifikasi_lapangan_skor').val(vRespon.data.verifikasi_lapangan_skor);
            $('#verifikasi_lapangan_hasil').val(vRespon.data.verifikasi_lapangan_hasil);
            $('#verifikasi_lapangan_catatan').val(vRespon.data.verifikasi_lapangan_catatan);
            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Raport</h5>
                <div>

                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester I (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_1_nilai">${vRespon.data.raport.smt_1_nilai}</span>/ ${showText(vRespon.data.raport.smt_1_peringkat,"-")}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester II (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_2_nilai">${vRespon.data.raport.smt_2_nilai}</span>/ ${showText(vRespon.data.raport.smt_2_peringkat,"-")}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester III (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_3_nilai">${vRespon.data.raport.smt_3_nilai}</span>/ ${showText(vRespon.data.raport.smt_3_peringkat,"-")}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester IV (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_4_nilai">${vRespon.data.raport.smt_4_nilai}</span>/ ${showText(vRespon.data.raport.smt_4_peringkat,"-")}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester V (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_5_nilai">${vRespon.data.raport.smt_5_nilai}</span>/ ${showText(vRespon.data.raport.smt_5_peringkat,"-")}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester VI (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_6_nilai">${vRespon.data.raport.smt_6_nilai}</span>/ ${showText(vRespon.data.raport.smt_6_peringkat,"-")}</div>
                    </div>
                </div>            
            `);        
        }

        function hitung_skor_raport() {
            let faktor_verifikasi = faktorVerifikasi();
            let skor_final = (skor_system*faktor_verifikasi);
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        //data kondisi rumah
        $(document).on('click','#data-kondisi-rumah', function(){
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();
            data_kondisi_rumah();
        });

        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });

        async function data_kondisi_rumah(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-kondisi-rumah/${data_survei.user_id}`);
            vId = vRespon.data.rumah_id;
            skor_system = vRespon.data.skor_akhir;

            $('#verifikasi_lapangan_skor').val(vRespon.data.verifikasi_lapangan_skor);
            $('#verifikasi_lapangan_hasil').val(vRespon.data.verifikasi_lapangan_hasil);
            $('#verifikasi_lapangan_catatan').val(vRespon.data.verifikasi_lapangan_catatan);
            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Kondisi Rumah</h5>
                <div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Luas Tanah</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.luas_tanah} m2</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Luas Bangunan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.luas_bangunan} m2</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Jumlah Orang Tinggal</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.jumlah_orang_tinggal} orang</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Status Rumah</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.kepemilikan_rumah_nama}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Sumber Listrik</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.sumber_listrik_nama}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Sumber Air</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.sumber_air_nama}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">MCK</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7">${vRespon.data.mck_nama}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold">Biaya Listrik (per bulan)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-7" class="akreditasi">${vRespon.data.listrik_nama}</div>
                    </div>
                </div>            
            `);
        }

        

        function hitung_skor_data_kondisi_rumah() {
            let faktor_verifikasi = faktorVerifikasi();
            let skor_final = (skor_system * faktor_verifikasi);
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        //data orang tua
        $(document).on('click','#data-orang-tua', function(){
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();
            data_orang_tua();
        });

        async function data_orang_tua(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-orang-tua/${data_survei.user_id}`);
            vId = vRespon.data.orang_tua_id;
            skor_system = vRespon.data.skor_akhir;

            $('#verifikasi_lapangan_skor').val(vRespon.data.verifikasi_lapangan_skor);
            $('#verifikasi_lapangan_hasil').val(vRespon.data.verifikasi_lapangan_hasil);
            $('#verifikasi_lapangan_catatan').val(vRespon.data.verifikasi_lapangan_catatan);
            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Orang Tua</h5>
                <div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Jumlah Tanggungan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.tanggungan} orang</div>
                    </div>
                    <h5>Bapak</h5>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Nama</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.bapak.nama}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Pendidikan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.bapak.pendidikan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Pekerjaan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.bapak.pekerjaan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Pendapatan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.bapak.pendapatan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Status</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${(vRespon.data.bapak.status)?"Hidup":"Meninggal"}</div>
                    </div>
                    <hr>
                    <h5>Ibu</h5>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Nama</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.ibu.nama}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Pendidikan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.ibu.pendidikan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Pekerjaan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.ibu.pekerjaan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Pendapatan</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${vRespon.data.ibu.pendapatan}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 fw-bold">Status</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-8">${(vRespon.data.ibu.status)?"Hidup":"Meninggal"}</div>
                    </div>
                </div>            
            `);
        }

        function hitung_skor_data_orang_tua() {
            let faktor_verifikasi = faktorVerifikasi();
            let skor_final = (skor_system * faktor_verifikasi);
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        //data dokumen upload
        $(document).on('click','#data-dokumen-upload', function(){
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();
            data_dokumen_upload();
        });

        async function data_dokumen_upload(){
            let html = ``;
            vRespon = await asyncFunction(`${base_url}/api/get-data-dokumen-upload/${data_survei.pendaftar_id}`);
            if (vRespon.status && Array.isArray(vRespon.data.upload)) {
                html=`<div class="accordion" id="accordionDokumen">`;
                vRespon.data.upload.forEach((item, index) => {
                    console.log(item);
                    const idHeader = `heading${item.upload_syarat_id}`;
                    const idCollapse = `collapse${item.upload_syarat_id}`;
                    const dokumen_show_id = `dokumen-show-${item.upload_syarat_id}`;
                    const display_kontrol_gambar =  (item.jenis!='image')?"display:none;":"";
                    html += `                    
                    <div class="accordion-item" data-jenis="${item.jenis}" data-dokumen_show_id="${dokumen_show_id}" data-url="${item.dokumen}">
                        <h2 class="accordion-header" id="${idHeader}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#${idCollapse}" aria-expanded="${index === 0}" aria-controls="${idCollapse}">
                                ${item.nama}
                            </button>
                        </h2>
                        <div id="${idCollapse}" class="accordion-collapse collapse" aria-labelledby="${idHeader}" data-bs-parent="#accordionDokumen">
                            <div class="accordion-body">
                                <p><strong>Deskripsi:</strong> ${item.deskripsi}</p>
                                <div id="kontrol-gambar-${index}" style="text-align:center; margin-top:10px; ${display_kontrol_gambar}">
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="rotateImage('img-${dokumen_show_id}',-90)">⟲ Putar Kiri</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="rotateImage('img-${dokumen_show_id}',90)">⟳ Putar Kanan</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="zoomImage('img-${dokumen_show_id}',1.2)">🔍 Zoom In</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="zoomImage('img-${dokumen_show_id}',0.8)">🔎 Zoom Out</button>
                                </div>                                        
                                <div id="${dokumen_show_id}" 
                                style="margin-top:10px; height:500px; width:100%; border:1px solid #ccc; overflow:auto;
                                "></div>
                            </div>
                        </div>
                    </div>
                    `;
                });
                html+='</div>';

            } else {
                html='<p>Data tidak ditemukan.</p>';
            }

            vId=vRespon.data.verifikasi_berkas.id;
            $('#verifikasi_lapangan_skor').val(vRespon.data.verifikasi_berkas.verifikasi_lapangan_skor);
            $('#verifikasi_lapangan_hasil').val(vRespon.data.verifikasi_berkas.verifikasi_lapangan_hasil);
            $('#verifikasi_lapangan_catatan').val(vRespon.data.verifikasi_berkas.verifikasi_lapangan_catatan);
            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Dokumen Upload</h5>
                <div>
                    ${html}
                </div>            
            `);
        }


        //data dokumen upload
        $(document).on('click','#dokumentasi-survei', function(){
            $('#konten').show();
            $('#survei-komponen').hide();
            $('#survei-akhir').hide();

            let disabled_form=false;
            if ((survei_peserta && survei_peserta.hasil !== null) || !is_survei_aktif) 
                disabled_form=true;            

            let upload_path='';
            if (!disabled_form) {
                upload_path=`   <div class="mb-3">
                                    <label for="gambar" class="form-label">Pilih Gambar</label>
                                    <input class="form-control" type="file" name="path" id="path" accept="image/*" required>
                                    <div class="form-text">Format: JPG, JPEG, PNG, WEBP — Maksimal 4 MB</div>
                                </div>`;
            }

            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Dokumentasi Survei</h5>
                ${upload_path}               
                <div class="mb-3" id="list-dokumen">
                    <p>Data tidak ditemukan.</p>
                </div>            
            `);

            dokumentasiSurvei(disabled_form);
        });

        async function dokumentasiSurvei(disabled_form){
            vRespon = await asyncFunction(`${base_url}/api/get-data-dokumentasi-survei/${data_survei.pendaftar_id}`);
            
            html=``;
            if (vRespon.status) {


                html=`<div class="row">`;
                vRespon.data.forEach((item, index) => {
                    let link_gambar=base_url+'/'+item.path;
                    let btn_hapus='';
                    if (!disabled_form) {
                        btn_hapus=` <button class="btn btn-danger mt-2 btn-hapus-dokumentasi" data-dokumentasi_survei_id="${item.id}" type="button" >
                                        <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="nav-small-cap-icon fs-4"></iconify-icon>
                                    </button>`;            
                    }
                    html += `                    
                    <div class="col-md-4">                    
                        <a href="${link_gambar}" target="_blank"><img src="${link_gambar}" width="100%"></a>
                        ${btn_hapus}
                    </div>
                    `;
                });
                html+=`</div>`;

            } else {
                html='<p>Data tidak ditemukan.</p>';
            }

            $('#list-dokumen').html(`${html}`);
        }


        $(document).on('click','.btn-hapus-dokumentasi', function () {
            const id=$(this).attr('data-dokumentasi_survei_id');
            deleteData(`${base_url}/api/dokumentasi-survei`, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                dokumentasiSurvei()
            });
        })

        $(document).on('shown.bs.collapse', '.accordion-collapse', function () {
            const accordionItem = $(this).closest('.accordion-item');
            const jenis = accordionItem.data('jenis');
            const url = '/' + accordionItem.data('url');
            const dokumen_show_id = accordionItem.data('dokumen_show_id');
            const container = document.getElementById(dokumen_show_id);

            rotation = 0;
            scale = 1;

            // Jika sudah terisi, jangan render ulang
            if (container.innerHTML.trim() !== '') return;

            if (jenis === 'image') {
                container.innerHTML = `<img src="${url}" id="img-${dokumen_show_id}" class="img-fluid" alt="Dokumen" style="max-width:95%; display:block; margin:0 auto; transition: transform 0.3s;">`;
            } else if (jenis === 'pdf') {
                openPdf(container, url);
            } else {
                container.innerHTML = `<p>Jenis file tidak dikenali</p>`;
            }
        });


        function hitung_skor_dokumen_upload() {
            let data = vRespon.data;
            let faktor_verifikasi = faktorVerifikasi();

            // Menghitung skor final (dikalikan faktor verifikasi dan dikali 100 untuk skala 0-100)
            let skor = parseFloat(data.verifikasi_berkas.total_skor) || 0;
            let skor_final = skor*faktor_verifikasi;

            // Menampilkan skor akhir dalam elemen input
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        async function survei_mulai() {            
            // console.log(data_survei);

            // let orang_tua = await asyncFunction(`${base_url}/api/get-data-orang-tua/${data_survei.user_id}`);
            // let peserta = await asyncFunction(`${base_url}/api/get-data-mahasiswa/${data_survei.mahasiswa_id}`);
            // let pendidikan_akhir = await asyncFunction(`${base_url}/api/get-data-pendidikan-akhir/${data_survei.user_id}`);
            // let data_upload = await asyncFunction(`${base_url}/api/get-data-upload/${data_survei.pendaftar_id}`);
            // let data_raport = await asyncFunction(`${base_url}/api/get-data-raport/${data_survei.user_id}`);
            
            $('#modal-label').text(`Survei ${data_survei.beasiswa.nama}`);
            $('.mahasiswa-nama').text(data_survei.nama);
            $('.mahasiswa-nim').text(`Nim : ${data_survei.nim}`);
            $('.mahasiswa-prodi').text(`${data_survei.fakultas}/ ${data_survei.program_studi}`);
            $('.mahasiswa-alamat').text(`${data_survei.alamat} ${data_survei.desa} ${data_survei.kecamatan} ${data_survei.kabupaten} ${data_survei.provinsi}`);
            $('.mahasiswa-no_hp').html(`<a href="${data_survei.link_wa}bismillah, ${data_survei.nama}" target="_blank">${data_survei.no_hp}</a>`);
            $('.mahasiswa-no-pendaftaran').text(`Nomor Pendaftaran : ${data_survei.no_pendaftaran}`);
            $('.mahasiswa-email').text(`${data_survei.email}`);
            $('.mahasiswa-photo').attr('src',base_url+'/'+data_survei.foto);

            data_pendidikan_akhir();            
            
            // if(vBeasiswa.perlu_data_pendidikan_akhir){
            //     data_pendidikan_akhir();            
            // }
            // else if(vBeasiswa.perlu_data_nilai_raport){
            //     data_raport();            
            // }
            // else if(vBeasiswa.perlu_data_rumah){
            //     data_kondisi_rumah();            
            // }
            // else if(vBeasiswa.perlu_data_orang_tua){
            //     data_orang_tua();            
            // }else{
            //     data_dokumen_upload();            
            // }
            // renderSurvei(response.data);

            // console.log(survei_peserta);
        }

        function cariSurveyor(data, user_id) {
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
                    const status_survei=(dt.survei.hasil==null)?'<span class="badge rounded-pill fs-2 fw-medium bg-danger">belum disurvei</span>':'<span class="badge rounded-pill fs-2 fw-medium bg-success">sudah disurvei</span>';
                    
                    const row = `<tr data-survei='${JSON.stringify(dt)}'>
                                    <td>${no++}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <img src="${base_url}/${dt.foto}" width="90px" >
                                            <div>
                                                ${dt.nama}/  
                                                ${dt.nim}/
                                                ${dt.program_studi}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        ${dt.alamat} (${dt.desa} - ${dt.kecamatan} - ${dt.kabupaten} - ${dt.provinsi})
                                        <a href="${dt.link_wa}bismillah, ${dt.nama}" class="btn btn-secondary btn-sm" target="_blank">WA : ${dt.no_hp}</div>
                                    </td>
                                    <td class="text-center">
                                        ${status_survei}
                                        <div class="d-flex flex-column align-items-center gap-2 mt-2">
                                            <button class="btn btn-secondary btn-instrumen-survei" data-pendaftar_id="${dt.pendaftar_id}" type="button" data-surveyor_id="${dt.survei.surveyor_id}" data-is_survei_aktif="${dt.beasiswa.is_survei_aktif}" data-survei_peserta_id="${dt.survei.survei_peserta_id}" >Instrumen Survei</button>
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
            var url = `${base_url}/api/peserta-survei?beasiswa_id=${beasiswa_id}&page=${page}&search=${search}`;

            fetchData(url, function(response) {
                vBeasiswa=response.data.data[0].beasiswa;

                // if(vBeasiswa.perlu_data_nilai_raport){
                //     $("#data-raport").show();
                // }
                // if(vBeasiswa.perlu_data_orang_tua){
                //     $("#data-orang-tua").show();
                // }
                // if(vBeasiswa.perlu_data_pendidikan_akhir){
                //     $("#data-pendidikan-akhir").show();
                // }
                // if(vBeasiswa.perlu_data_rumah){
                //     $("#data-kondisi-rumah").show();
                // }

                $("#data-raport").show();
                $("#data-orang-tua").show();
                $("#data-pendidikan-akhir").show();
                $("#data-kondisi-rumah").show();

                renderData(response);

            },true);
        }

        $('#btn-progress').click(function() {
            const url = `${base_url}/cetak-progress-survei/${beasiswa_id}`;
            window.open(url, '_blank');
        });

        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $('#btn-search').click(function(){
            page=1;
            dataLoad();
        });

        async function load_data_peserta_survei(){
            let cek_peserta = await asyncFunction(`${base_url}/api/peserta-survei/${pendaftar_id}`);
            survei_peserta = cek_peserta.data.survei_peserta;            
            console.log(survei_peserta);
            if ((survei_peserta && survei_peserta.hasil !== null) || !is_survei_aktif) {
                disabledForm(true);            
            }else{
                disabledForm(false);            
            }
        } 


        $(document).on('click','.btn-instrumen-survei', async function(){
            data_survei=JSON.parse($(this).closest('tr').attr('data-survei'));
            page = 1;

            $('#komponen').show();
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();
         
            $("#daftar-komponen .btn").removeClass("active"); 
            $('#data-pendidikan-akhir').addClass("active"); 

            pendaftar_id=$(this).attr('data-pendaftar_id');
            surveyor_id=$(this).attr('data-surveyor_id');
            is_survei_aktif=$(this).data('is_survei_aktif');
            load_data_peserta_survei();

            survei_mulai();
            showModal('modal-survei');
        });


        //validasi dan simpan
        $("#form").validate({
            submitHandler: function(form) {
                let judul_komponen = $('#judul-komponen').text().trim().toLowerCase().replace(/\s+/g, '-');
                let url=`${base_url}/api/${judul_komponen}/update-survei/${vId}`;
                saveData(url, 'PUT', $(form).serialize(), function(response) {
                    // renderData(response.status,response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                    // kondisiTombol('data-pendidikan-akhir',response.status);
                });
            }
        });

        function kondisiTombol(id_element,hasil=null){
            if(hasil){
                const tombol = $('#'+id_element);            
                if (tombol.hasClass('btn-outline-primary')) {
                    tombol.removeClass('btn-outline-primary').addClass('btn-primary');
                }
            }
        }

        //validasi dan simpan
        $("#form-survei-akhir").validate({
            submitHandler: function(form) {
                let url=`${base_url}/api/peserta-survei/${vId}`;
                saveData(url, 'PUT', $(form).serialize(), function(response) {
                    // renderData(response.status,response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                    dataLoad();
                    load_data_peserta_survei();            
                });
            }
        });

        function disabledForm(status) {
            $('#form, #form-survei-akhir')
                .find('input, select, textarea, button')
                .prop('disabled', status);
            
            $('#btn-simpan1, #btn-simpan2').toggle(!status);
        }

        $('.akhiri-survei').click(async function() {
            cek_dokumentasi = await asyncFunction(`${base_url}/api/get-data-dokumentasi-survei/${pendaftar_id}`);
            if(!cek_dokumentasi.status){
                appShowNotification(false,['Sebelum mengakhiri survei wajib mengupload dokumentasi survei!']);
                return;
            }

            $("#daftar-komponen .btn").removeClass("active"); 

            cek_peserta = await asyncFunction(`${base_url}/api/peserta-survei/${data_survei.pendaftar_id}`);
            // console.log(cek_peserta);
            survei_peserta = cek_peserta.data.survei_peserta;            

            vId=survei_peserta.id;

           
            // if(parseFloat(survei_peserta.hasil)>=0){
                $('#hasil').val(survei_peserta.hasil);
                // $('#total_skor').val(survei_peserta.total_skor);
                $('#catatan').val(survei_peserta.catatan);
            // }else{
            //     $('#hasil').val(pilihan_hasil);
            //     $('#total_skor').val(skor_akhir.toFixed(2));
            //     $('#catatan').val("");
            // }


            $('#komponen').show();
            $('#konten').hide();
            $('#survei-komponen').hide();
            $('#survei-akhir').show();

        });

        // Event delegation — aman walau elemen dibuat via AJAX
        $(document).on('change', '#path', function(e) {
            var formData = new FormData();
            formData.append("surveyor_id", surveyor_id);            
            formData.append("pendaftar_id", pendaftar_id);            
            formData.append("path", $(this)[0].files[0]);   

            saveData(`${base_url}/api/dokumentasi-survei`, 'POST', formData, function(response) {
                appShowNotification(true,['berhasil terupload!']);
                dokumentasiSurvei();
                $('#path').val("");
            });            
        });

    });
</script>
@endsection