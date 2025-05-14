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
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-pendidikan-akhir" style="display:none">Pendidikan Akhir</div>
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-raport" style="display:none">Raport</div>
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-kondisi-rumah" style="display:none">Kondisi Rumah</div>
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-orang-tua" style="display:none">Orang Tua</div>
                                    <div class="btn btn-sm btn-outline-primary mb-1" id="data-dokumen-upload">Dokumen Upload</div>
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
                                                <option value="2">Sesuai</option>
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
                                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
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
                                                <option value="3">Sangat Layak</option>
                                                <option value="2">Layak</option>
                                                <option value="1">Dipertimbangkan</option>
                                                <option value="0">Tidak Layak</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mb-3"> 
                                            <input type="number" class="form-control" id="total_skor" placeholder="total_skor" name="total_skor" required>
                                            <i>wajib di isi 0 - 100</i>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                            <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                                        </div>
                                    </div>
                
                                    <div class="mt-1">
                                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
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
    
    $(document).ready(function() {
        dataLoad();

        function pembalik(nilai, jumlah_pilihan){
            return (jumlah_pilihan > 1) ? (1 - ((nilai - 1) / (jumlah_pilihan - 1))) : 0;
        }

        function faktorVerifikasi(){
            let verifikasi = parseInt($('#verifikasi_lapangan_hasil').val());
            return faktor_verifikasi = verifikasi / 2;            
        }

        $('#verifikasi_lapangan_hasil').on('change', function() {
            let judul_komponen = $('#judul-komponen').text().trim();
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
            data_pendidikan_akhir();
        });

        async function data_pendidikan_akhir(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-pendidikan-akhir/${data_survei.user_id}`);
            vId = vRespon.data.pendidikan_akhir_id;
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
            let data=vRespon.data;

            let akreditasi = data.akreditasi;
            let nilai_akhir_lulus = parseFloat(data.nilai_akhir_lulus);

            let faktor_verifikasi = faktorVerifikasi();
            let skor_akreditasi = (akreditasi=='A')?3:(akreditasi=='B')?2:1; 
            let skor_nilai = (nilai_akhir_lulus/100);
            let skor_pendidikan = (0.7 * skor_nilai) + (0.3 * (skor_akreditasi / 3));

            let skor_final = (skor_pendidikan * faktor_verifikasi)*100;

            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));            
        }

        //data raport
        $(document).on('click','#data-raport', function(){
            data_raport();
        });

        async function data_raport(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-raport/${data_survei.user_id}`);
            vId = vRespon.data.raport.raport_id;
            $('#verifikasi_lapangan_skor').val(vRespon.data.raport.verifikasi_lapangan_skor);
            $('#verifikasi_lapangan_hasil').val(vRespon.data.raport.verifikasi_lapangan_hasil);
            $('#verifikasi_lapangan_catatan').val(vRespon.data.raport.verifikasi_lapangan_catatan);
            $('#konten-komponen').html(`
                <h5 id="judul-komponen">Raport</h5>
                <div>

                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester I (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_1_nilai">${vRespon.data.raport.smt_1_nilai}</span>/ ${vRespon.data.raport.smt_1_peringkat}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester II (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_2_nilai">${vRespon.data.raport.smt_2_nilai}</span>/ ${vRespon.data.raport.smt_2_peringkat}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester III (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_3_nilai">${vRespon.data.raport.smt_3_nilai}</span>/ ${vRespon.data.raport.smt_3_peringkat}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester IV (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_4_nilai">${vRespon.data.raport.smt_4_nilai}</span>/ ${vRespon.data.raport.smt_4_peringkat}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester V (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_5_nilai">${vRespon.data.raport.smt_5_nilai}</span>/ ${vRespon.data.raport.smt_5_peringkat}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 fw-bold">Semester VI (Nilai/ Peringkat)</div>
                        <div class="col-sm-1">:</div>
                        <div class="col-sm-5"><span id="smt_6_nilai">${vRespon.data.raport.smt_6_nilai}</span>/ ${vRespon.data.raport.smt_6_peringkat}</div>
                    </div>
                </div>            
            `);        
        }

        function hitung_skor_raport() {
            let data=vRespon.data;
            let akreditasi = data.akreditasi;
            let faktor_verifikasi = faktorVerifikasi();


            // Faktor akreditasi: A = 3, B = 2, C = 1
            let faktor_akreditasi = ((akreditasi == 'A') ? 3 : (akreditasi == 'B') ? 2 : 1)/3;

            // Ambil nilai raport dari semester 1 sampai semester 6
            let nilai_smt1 = parseFloat(data.raport.smt_1_nilai);
            let nilai_smt2 = parseFloat(data.raport.smt_2_nilai);
            let nilai_smt3 = parseFloat(data.raport.smt_3_nilai);
            let nilai_smt4 = parseFloat(data.raport.smt_4_nilai);
            let nilai_smt5 = parseFloat(data.raport.smt_5_nilai);
            let nilai_smt6 = parseFloat(data.raport.smt_6_nilai);

            // Hitung rata-rata nilai raport
            let rata_rata_raport = (nilai_smt1 + nilai_smt2 + nilai_smt3 + nilai_smt4 + nilai_smt5 + nilai_smt6) / 6;
            // Faktor raport: Skala 0 - 1, jadi bagi dengan 100
            let faktor_raport = rata_rata_raport / 100;

            // Skor akhir untuk raport (70%)
            let skor_raport = faktor_raport * 0.7;

            // Skor akhir untuk akreditasi (30%)
            let skor_akreditasi = faktor_akreditasi * 0.3;

            // Total skor: 70% dari skor raport + 30% dari skor akreditasi
            let skor_final = ((skor_raport + skor_akreditasi)*faktor_verifikasi) * 100;

            // Tampilkan skor akhir pada elemen tertentu
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        //data kondisi rumah
        $(document).on('click','#data-kondisi-rumah', function(){
            data_kondisi_rumah();
        });

        async function data_kondisi_rumah(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-kondisi-rumah/${data_survei.user_id}`);
            vId = vRespon.data.rumah_id;
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
            let data = vRespon.data;
            // Ambil nilai verifikasi hasil lapangan

            let faktor_verifikasi = faktorVerifikasi();

            // Menghitung faktor berdasarkan inputan jumlah orang tinggal
            let faktor_jumlah_orang_tinggal = ((data.jumlah_orang_tinggal <= 3) ? 1 : (data.jumlah_orang_tinggal <= 6) ? 2 : 3)/3;

            // Menghitung faktor berdasarkan luas tanah
            let faktor_luas_tanah = ((data.luas_tanah <= 90) ? 3 : (data.luas_tanah <= 120) ? 2 : 1)/3;

            // Menghitung faktor berdasarkan luas bangunan
            let faktor_luas_bangunan = ((data.luas_bangunan <= 36) ? 3 : (data.luas_bangunan <= 90) ? 2 : 1)/3;

            // Menghitung faktor berdasarkan pilihan lainnya yang diambil dari jumlah pilihan
            let faktor_sumber_listrik = pembalik(data.sumber_listrik_nilai , data.jumlah_pilihan.sumber_listrik);
            let faktor_sumber_air = pembalik(data.sumber_air_nilai, data.jumlah_pilihan.sumber_air);
            let faktor_mck = pembalik(data.mck_nilai, data.jumlah_pilihan.mck);
            let faktor_listrik = pembalik(data.listrik_nilai, data.jumlah_pilihan.listrik);
            let faktor_kepemilikan_rumah = pembalik(data.kepemilikan_rumah_nilai, data.jumlah_pilihan.kepemilikan_rumah);

            // Kalkulasi skor berdasarkan faktor dan bobot masing-masing
            let skor = 0;
            skor += (faktor_luas_tanah * 0.15);  // Bobot 10% untuk luas tanah
            skor += (faktor_luas_bangunan * 0.15);  // Bobot 15% untuk luas bangunan
            skor += (faktor_kepemilikan_rumah * 0.15);  // Bobot 15% untuk kepemilikan rumah
            skor += (faktor_jumlah_orang_tinggal * 0.1);  // Bobot 10% untuk jumlah orang tinggal
            skor += (faktor_sumber_listrik * 0.15);  // Bobot 10% untuk sumber listrik
            skor += (faktor_sumber_air * 0.05);  // Bobot 5% untuk sumber air
            skor += (faktor_mck * 0.05);  // Bobot 5% untuk MCK
            skor += (faktor_listrik * 0.2);  // Bobot 20% untuk listrik

            // Menghitung skor final (dikalikan faktor verifikasi dan dikali 100 untuk skala 0-100)
            let skor_final = (skor * faktor_verifikasi) * 100;

            // Menampilkan skor akhir dalam elemen input
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        //data orang tua
        $(document).on('click','#data-orang-tua', function(){
            data_orang_tua();
        });

        async function data_orang_tua(){
            vRespon = await asyncFunction(`${base_url}/api/get-data-orang-tua/${data_survei.user_id}`);
            vId = vRespon.data.orang_tua_id;
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
            let data = vRespon.data;
            // Ambil nilai verifikasi hasil lapangan
            let faktor_verifikasi = faktorVerifikasi();

            let faktor_tanggunan = ((data.tanggungan <= 2) ? 3 : (data.tanggungan <= 4) ? 2 : 1)/3;
            if(!data.bapak.status || !data.ibu.status){
                faktor_tanggunan=1;
            }

            // Normalisasi bapak
            let faktor_bapak_pekerjaan = (data.bapak.status)?pembalik(data.bapak.pekerjaan_nilai,data.jumlah_pilihan.pekerjaan):1;
            let faktor_bapak_pendidikan = (data.bapak.status)?pembalik(data.bapak.pendidikan_nilai,data.jumlah_pilihan.pendidikan):1;
            let faktor_bapak_pendapatan = (data.bapak.status)?pembalik(data.bapak.pendapatan_nilai,data.jumlah_pilihan.pendapatan):1;

            // Normalisasi Ibu
            let faktor_ibu_pekerjaan = (data.ibu.status)?pembalik(data.ibu.pekerjaan_nilai,data.jumlah_pilihan.pekerjaan):1;
            let faktor_ibu_pendidikan = (data.ibu.status)?pembalik(data.ibu.pendidikan_nilai,data.jumlah_pilihan.pendidikan):1;
            let faktor_ibu_pendapatan = (data.ibu.status)?pembalik(data.ibu.pendapatan_nilai,data.jumlah_pilihan.pendapatan):1;

            // console.log(faktor_ibu_pekerjaan,faktor_ibu_pendidikan,faktor_ibu_pendapatan)

            // Kalkulasi skor berdasarkan faktor dan bobot masing-masing
            let skor = 0;
            skor += (faktor_bapak_pekerjaan * 0.20);  // Bobot 20% untuk pekerjaan bapak
            skor += (faktor_bapak_pendidikan * 0.05);  // Bobot 5% untuk pendidikan bapak
            skor += (faktor_bapak_pendapatan * 0.20);  // Bobot 20% untuk pendapatan bapak
            skor += (faktor_ibu_pekerjaan * 0.20);  // Bobot 20% untuk pekerjaan ibu
            skor += (faktor_ibu_pendidikan * 0.05);  // Bobot 5% untuk pendidikan ibu
            skor += (faktor_ibu_pendapatan * 0.20);  // Bobot 20% untuk pendapatan ibu
            skor += (faktor_tanggunan * 0.10);  // Bobot 10% untuk tanggungan

            // console.log(
            //     faktor_bapak_pekerjaan,
            //     faktor_bapak_pendidikan,
            //     faktor_bapak_pendapatan,
            //     faktor_ibu_pekerjaan,
            //     faktor_ibu_pendidikan,
            //     faktor_ibu_pendapatan,
            //     faktor_tanggunan,
            // );

            // Menghitung skor final (dikalikan faktor verifikasi dan dikali 100 untuk skala 0-100)
            let skor_final = (skor * faktor_verifikasi) * 100;

            // Menampilkan skor akhir dalam elemen input
            $('#verifikasi_lapangan_skor').val(skor_final.toFixed(2));
        }

        //data dokumen upload
        $(document).on('click','#data-dokumen-upload', function(){
            data_dokumen_upload();
        });

        async function data_dokumen_upload(){
            let html = ``;
            vRespon = await asyncFunction(`${base_url}/api/get-data-dokumen-upload/${data_survei.pendaftar_id}`);
            if (vRespon.status && Array.isArray(vRespon.data.upload)) {
                html=`<div class="accordion" id="accordionDokumen">`;
                vRespon.data.upload.forEach((item, index) => {
                    const idHeader = `heading${item.upload_syarat_id}`;
                    const idCollapse = `collapse${item.upload_syarat_id}`;
                    const dokumen_show_id = `dokumen-show-${item.upload_syarat_id}`;

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
                                <div id="${dokumen_show_id}" style="margin-top:10px; height:400px; width:100%; border:1px solid #ccc; overflow:auto;"></div>
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

        $(document).on('shown.bs.collapse', '.accordion-collapse', function () {
            const accordionItem = $(this).closest('.accordion-item');
            const jenis = accordionItem.data('jenis');
            const url = '/' + accordionItem.data('url');
            const dokumen_show_id = accordionItem.data('dokumen_show_id');
            const container = document.getElementById(dokumen_show_id);

            // Jika sudah terisi, jangan render ulang
            if (container.innerHTML.trim() !== '') return;

            if (jenis === 'image') {
                container.innerHTML = `<img src="${url}" class="img-fluid" alt="Dokumen">`;
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
            $('.mahasiswa-no_hp').text(data_survei.no_hp);
            $('.mahasiswa-no-pendaftaran').text(`Nomor Pendaftaran : ${data_survei.no_pendaftaran}`);
            $('.mahasiswa-email').text(`${data_survei.email}`);
            $('.mahasiswa-photo').attr('src',base_url+'/'+data_survei.foto);

            
            if(vBeasiswa.perlu_data_pendidikan_akhir){
                data_pendidikan_akhir();            
            }
            else if(vBeasiswa.perlu_data_nilai_raport){
                data_raport();            
            }
            else if(vBeasiswa.perlu_data_rumah){
                data_kondisi_rumah();            
            }
            else if(vBeasiswa.perlu_data_orang_tua){
                data_orang_tua();            
            }else{
                data_dokumen_upload();            
            }
            // renderSurvei(response.data);
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
                                    </td>
                                    <td class="text-center">
                                        ${status_survei}
                                        <div class="d-flex flex-column align-items-center gap-2 mt-2">
                                            <button class="btn btn-secondary btn-instrumen-survei" data-pendaftar_id="${dt.pendaftar_id}" type="button" data-surveyor_id="${dt.survei.surveyor_id}" data-survei_peserta_id="${dt.survei.survei_peserta_id}" >Instrumen Survei</button>
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
            var url = `${base_url}/api/peserta-survei?beasiswa_id=${beasiswa_id}&search=${search}`;

            fetchData(url, function(response) {
                vBeasiswa=response.data.data[0].beasiswa;

                if(vBeasiswa.perlu_data_nilai_raport){
                    $("#data-raport").show();
                }
                if(vBeasiswa.perlu_data_orang_tua){
                    $("#data-orang-tua").show();
                }
                if(vBeasiswa.perlu_data_pendidikan_akhir){
                    $("#data-pendidikan-akhir").show();
                }
                if(vBeasiswa.perlu_data_rumah){
                    $("#data-kondisi-rumah").show();
                }

                renderData(response);

            },true);
        }


        async function dataWawancara() {
            let response = await asyncFunction(`${base_url}/api/proses-survei/${pendaftar_id}?page=${page}&beasiswa_id=${beasiswa_id}`);
            renderSurvei(response.data);
        }        

        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $(document).on('input', '#search-input', function() {
            // console.log('Event input berjalan');
            dataLoad();
        });      


        $(document).on('click','.btn-instrumen-survei', async function(){
            data_survei=JSON.parse($(this).closest('tr').attr('data-survei'));
            page = 1;

            $('#komponen').show();
            $('#konten').show();
            $('#survei-komponen').show();
            $('#survei-akhir').hide();

            survei_mulai();
            showModal('modal-survei');
            // console.log(data_survei);
        });


        //validasi dan simpan
        $("#form").validate({
            submitHandler: function(form) {
                let judul_komponen = $('#judul-komponen').text().trim().toLowerCase().replace(/\s+/g, '-');
                let url=`${base_url}/api/${judul_komponen}/update-survei/${vId}`;
                saveData(url, 'PUT', $(form).serialize(), function(response) {
                    // renderData(response.status,response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                });
            }
        });

        //validasi dan simpan
        $("#form-survei-akhir").validate({
            submitHandler: function(form) {
                let url=`${base_url}/api/peserta-survei/${vId}`;
                saveData(url, 'PUT', $(form).serialize(), function(response) {
                    // renderData(response.status,response.data);
                    appShowNotification(true, ['berhasil dilakukan!']);
                });
            }
        });


        $('.akhiri-survei').click(async function() {
            let cek_raport;
            let cek_orangtua;
            let cek_pendidikan;
            let cek_rumah;
            let cek_upload;
            let cek_peserta;
            let total_nilai = 0;
            let skor_akhir = 0;
            let pembagi=1;

            if(vBeasiswa.perlu_data_pendidikan_akhir){
                cek_pendidikan = await asyncFunction(`${base_url}/api/get-data-pendidikan-akhir/${data_survei.user_id}`);
                if(cek_pendidikan.data.verifikasi_lapangan_hasil==null){
                    appShowNotification(false, ['data pendidikan akhir wajib di survei terlebih dahulu!']);
                    return;
                }
                pembagi++;
                total_nilai+=parseFloat(cek_pendidikan.data.verifikasi_lapangan_skor || 0);
            }
            if(vBeasiswa.perlu_data_nilai_raport){
                cek_raport = await asyncFunction(`${base_url}/api/get-data-raport/${data_survei.user_id}`);
                if(cek_raport.data.raport.verifikasi_lapangan_hasil==null){
                    appShowNotification(false, ['data nilai raport wajib di survei terlebih dahulu!']);
                    return;
                }
                pembagi++;
                total_nilai+=parseFloat(cek_raport.data.raport.verifikasi_lapangan_skor || 0);
            }
            if(vBeasiswa.perlu_data_rumah){
                cek_rumah = await asyncFunction(`${base_url}/api/get-data-kondisi-rumah/${data_survei.user_id}`);
                if(cek_rumah.data.verifikasi_lapangan_hasil==null){
                    appShowNotification(false, ['data kondisi rumah wajib di survei terlebih dahulu!']);
                    return;
                }
                pembagi++;
                total_nilai+=parseFloat(cek_rumah.data.verifikasi_lapangan_skor || 0);
            }
            if(vBeasiswa.perlu_data_orang_tua){
                cek_orangtua = await asyncFunction(`${base_url}/api/get-data-orang-tua/${data_survei.user_id}`);
                if(cek_orangtua.data.verifikasi_lapangan_hasil==null){
                    appShowNotification(false, ['data orang tua wajib di survei terlebih dahulu!']);
                    return;
                }
                pembagi++;
                total_nilai+=parseFloat(cek_orangtua.data.verifikasi_lapangan_skor || 0);
            }
            cek_upload = await asyncFunction(`${base_url}/api/get-data-dokumen-upload/${data_survei.pendaftar_id}`);
            if(cek_upload.data.verifikasi_berkas.verifikasi_lapangan_hasil==null){
                appShowNotification(false, ['data upload dokumen wajib di survei terlebih dahulu!']);
                return;
            }

            cek_peserta = await asyncFunction(`${base_url}/api/peserta-survei/${data_survei.pendaftar_id}`);

            total_nilai+=parseFloat(cek_upload.data.verifikasi_berkas.verifikasi_lapangan_skor || 0);
            skor_akhir=(total_nilai/pembagi);
            
            let pilihan_hasil=0;
            if(skor_akhir<45){
                pilihan_hasil=0;
            }else if(skor_akhir>=45 && skor_akhir<=69.99){
                pilihan_hasil=1;
            }else if(skor_akhir>=70 && skor_akhir<=84.99){
                pilihan_hasil=2;                
            }else if(skor_akhir>=85 && skor_akhir<=100){
                pilihan_hasil=3;                
            }


            let tmp_survei = cek_peserta.data.survei_peserta[0];
            vId=tmp_survei.id;

            if(parseFloat(tmp_survei.hasil)>=0){
                $('#hasil').val(tmp_survei.hasil);
                $('#total_skor').val(tmp_survei.total_skor);
                $('#catatan').val(tmp_survei.catatan);
            }else{
                $('#hasil').val(pilihan_hasil);
                $('#total_skor').val(skor_akhir.toFixed(2));
                $('#catatan').val("");
            }


            $('#komponen').hide();
            $('#konten').hide();
            $('#survei-komponen').hide();
            $('#survei-akhir').show();

        });


    });
</script>
@endsection