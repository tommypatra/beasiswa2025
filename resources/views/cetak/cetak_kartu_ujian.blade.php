<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Kartu Ujian IOSS IAIN Kendari</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <style>

        @page {
            size: A4;
            /* margin: 5mm; */
            margin-top:10px;
        }        

        .ujian-box {
            display: flex;
            justify-content: center;
            gap: 40px;
            text-align: center;
            border: 1px solid #000;
            padding: 12px;
            border-radius: 8px;
            background: #fff;
            margin: 15px auto;
            width: 80%;
        }

        .ujian-item .label {
            font-weight: bold;
        }

        #loadingProgress {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(51, 51, 51, 0.9);
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            z-index: 9999;
            display: none; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            transition: opacity 0.3s ease;
        }

        
        body {
            font-family: Arial, sans-serif;
        }

        .card {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 10px;
            background-color: #f9f9f9;
            overflow-x: auto; /* Tambahan penting: scroll horizontal kalau tabel lebar */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            height: auto;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .row {
            display: flex;
            align-items: flex-start; /* rata atas */
            justify-content: flex-start; /* rata kiri */
            gap: 20px;
            margin-top: 10px;
        }

        .data-peserta {
            flex: 1;             /* tabel mengambil sisa ruang di row */
        }

        .data-peserta table {
            width: 100%;         /* pastikan tabel penuh */
            text-align: left;    /* teks rata kiri */
        }


        .data-peserta table th,
        .data-peserta table td {
            text-align: left;
            vertical-align: top;
        }

        .content {
            margin-top: 20px;
            /* overflow-x: auto;   /* scroll horizontal kalau tidak muat */
        }

       .content table {
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* membuat kolom tidak melebar otomatis */
            word-wrap: break-word; /* teks panjang otomatis wrap */
        }

        .content th,
        .content td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            white-space: normal; /* ganti nowrap supaya teks wrap */
        }

        .footer {
            display: flex;            /* jadikan flex container */
            justify-content: space-between;  /* memberi jarak antar kolom */
            align-items: flex-start;  /* atas rata atas */
        }

        .footer > div {
            width: 30%;               /* sesuaikan lebar kolom */
            text-align: left;         /* rata kiri */
        }

        @media print {
            @page {
                size: legal;
                margin: 5mm;     /* margin cetak */
            }

            body {
                background: white;
                zoom: 80%;       /* pastikan zoom normal untuk print */
                margin: 0;
            }

            .card {
                overflow: visible !important;  /* hilangkan scroll */
                width: 90% !important;
            }

            .content {
                overflow: visible !important;  /* hilangkan scroll horizontal */
            }

            button {
                display: none;                 /* hilangkan tombol copy saat print */
            }
        }        
    </style>
    <script>
        const base_url="{{ url('/') }}";
        const url_id="{{ $url_id }}";
        const beasiswa_id="{{ $beasiswa_id }}";
    </script>
</head>
<body>

    <div class="card">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="SNPMB Logo">
            <h1>KARTU KARTU UJIAN TAHUN <span class="tahun"></span></h1>
            <h4 style="margin-top:1px;" class="nama-beasiswa"></h4>
            <hr>

            <div class="ujian-box">
                <div class="ujian-item">
                    <div class="label">Tanggal Ujian</div>
                    <div class="tanggal-ujian"></div>
                </div>
                <div class="ujian-item">
                    <div class="label">Sesi/ Waktu</div>
                    <div class="waktu-ujian"></div>
                </div>
                <div class="ujian-item">
                    <div class="label">Lokasi</div>
                    <div class="lokasi-ujian"></div>
                </div>
            </div>

            <div class="row">
                <div class="foto-peserta">
                    <img src="{{ asset('images/user-avatar.png') }}" class="user-foto">  
                </div>
                <div class="data-peserta">               
                    <table>
                        <tr>
                            <th width="30%">Nomor Peserta</th>
                            <td width="1%">:</td>
                            <td class="nomor-peserta"></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>:</td>
                            <td class="nama-lengkap"></td>
                        </tr>
                        <tr>
                            <th>TTL</th>
                            <td>:</td>
                            <td class="tempat-tanggal-lahir"></td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>:</td>
                            <td class="nim"></td>
                        </tr>
                        <tr>
                            <th>Fakultas/ Program Studi</th>
                            <td>:</td>
                            <td class="program-studi"></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>:</td>
                            <td class="alamat"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="55%">Materi Ujian</th>
                        <th width="40%">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                </tbody>
            </table>

        </div>

       
        <div class="footer">
            <div id="qrcode_label1"></div>
            <div id="ttd1"></div>           
            <div id="ttd2">
                <p>Kendari, <span class="tanggal"></span></p>
                Peserta Ujian,
                <p style="margin-top:75px;" class="nama-lengkap">.....</p>    
            </div>           
        </div>
        <div id="inisial" style="font-size:12px;font-style:italic;"></div>
    </div>

    <div class="card" id="info-cetak-kartu-ujian" style="display:none;"></div>

    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script script src="{{ asset('js/app.js?v=25') }}"></script>

    <script>
	$(document).ready(function() {
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const access_token = localStorage.getItem('access_token');

        if(access_token){
            $.ajax({
                headers: {
                    'Authorization': 'Bearer ' + access_token
                },
                type: 'GET',
                url: `${base_url}/api/cek-akses`,
                async: false,
                success: function(response) {
                    console.log(response)
                },
                complete: function(xhr) {
                    let responHeader = xhr.getResponseHeader('Authorization');
                    if (responHeader) {
                        let newToken = responHeader.replace('Bearer ', '').trim();
                        localStorage.setItem('access_token', newToken);
                    }
                    if (xhr.status === 401) {
                        localStorage.clear();
                    }
                },
                error: function(xhr, status, error) {
                    localStorage.clear();
                }
            });
        }
        
        initPage();

        async function initPage() {
            await dataLoad();
            await dataUjian();
            await dataInfoKartu();
        }

        async function dataLoad() {
            var url = `${base_url}/api/get-data-peserta-ujian/${beasiswa_id}?url_id=${url_id}`;
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${access_token}`, 
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                renderData(result.data.data[0]);
            } catch (error) {
                // window.location.replace(`${base_url}/pendaftar`);
            }
        }

        function renderData(data){
            let foto_src=base_url+'/'+data.foto;
            let tanggal_update = formatTanggal(data.waktu_daftar.split('T')[0]);
            $('.tanggal-ujian').text(formatTanggal(data.jadwal_ujian.tanggal));
            $('.waktu-ujian').text('SESI '+data.jadwal_ujian.sesi+' / '+data.sesi_ujian.jam_mulai+' s.d '+data.sesi_ujian.jam_selesai);
            $('.lokasi-ujian').text(data.jadwal_ujian.gedung+' / '+data.jadwal_ujian.nama);
            $('.user-foto').attr('src',foto_src);
            $('.tahun').text(data.tahun);
            $('.tanggal').text(tanggal_update);
            $('.nama-beasiswa').text(data.beasiswa.nama);
            $('.nomor-peserta').text(data.no_pendaftaran);
            $('.nama-lengkap').text(data.name);
            $('.tempat-tanggal-lahir').text(data.tempat_lahir+'/ '+formatTanggal(data.tanggal_lahir));
            $('.password').text(data.tanggal_lahir);
            $('.nim').text(data.nim);
            $('.program-studi').text(`${data.fakultas}/ ${data.program_studi}`);

            let alamat = data.alamat; 
            if(data.desa){
                alamat +=` ${data.desa} / ${data.kecamatan} / ${data.kabupaten} / ${data.provinsi}`;
            }
            $('.alamat').text(alamat);

        }

        async function dataUjian() {
            var url = `${base_url}/api/get-data-materi-ujian/${beasiswa_id}`;
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${access_token}`, 
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                renderDataUjian(result.data);
            } catch (error) {
                // window.location.replace(`${base_url}/pendaftar`);
            }
        }

        function renderDataUjian(data){
            const ujian=data.data;
            if(ujian.length>0){
                const dataList = $('#data-list');
                dataList.empty();
                let no = 1;
                $.each(ujian, function(index, dt) {
                    const row = `<tr>
                                    <td>${no++}</td>
                                    <td>${dt.ujian}</td>
                                    <td>${showText(dt.keterangan)}</td>
                                </tr>`;
                    dataList.append(row);
                });                        

            }
        }


        async function dataInfoKartu() {
            var url = `${base_url}/api/get-data-pengaturan-ujian/${beasiswa_id}`;
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${access_token}`, 
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                if(result.data.total>0){
                    const info_cetak_kartu = result.data.data[0].cetak_kartu_ujian;
                    // console.log(info_cetak_kartu);
                    if(info_cetak_kartu !== null){
                        $('#info-cetak-kartu-ujian').show();
                        $('#info-cetak-kartu-ujian').html(info_cetak_kartu);
                    }
                }
            } catch (error) {
                // window.location.replace(`${base_url}/pendaftar`);
            }
        }


    });
    </script>
</body>
</html>
