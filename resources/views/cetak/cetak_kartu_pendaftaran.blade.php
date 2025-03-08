<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Tanda Pendaftaran Beasiswa IAIN Kendari</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <style>

        @page {
            size: A4;
            /* margin: 5mm; */
            margin-top:10px;
        }        
        
        body {
            font-family: Arial, sans-serif;
        }

        .card {
            width: 750px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 10px;
            background-color: #f9f9f9;
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
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .foto-peserta {
            width: 120px;
            height: 150px;
            border: 1px solid #000;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .foto-peserta img {
            width: 120px;
            height: 100%;
            object-fit: cover;
        }

        .data-peserta {
            flex-grow: 1;
        }

        .data-peserta table {
            text-align: left;
            margin: 5px 0;
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }

        .data-peserta th, td {
            padding: 3px;
        }

        .data-peserta th {
            text-align: left;
            vertical-align: top;
            font-weight: bold;
        }

        .data-peserta td {
            text-align: left;
            vertical-align: top;
        }        

        .content {
            margin-top: 20px;
        }
        .content table {
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }
        .content table, .content th, .content td {
            border: 1px solid #000;
        }
        .content th, .content td {
            padding: 4px;
            text-align: left;
        }

        .footer {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            margin-top: 20px;
        }

        #qrcode_label1 #qrcode_label2 {
            flex: 1; 
            text-align: left;
        }

        #ttd1 {
            flex: 1; 
            text-align: center;
        }

        #ttd2 {
            flex: 1; 
            text-align: center;
        }

        @media print {
            body {
                background: white;
                zoom: 75%;
                margin-top:10px;
            }

            .card {
                border: none;
                box-shadow: none;
                page-break-after: always;
            }
        }        
    </style>
    <script>
        const base_url="{{ url('/') }}";
        const url_id="{{ $url_id }}";
    </script>
</head>
<body>

    <div class="card">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="SNPMB Logo">
            <h1>KARTU PESERTA BEASISWA TAHUN <span id="tahun"></span></h1>
            <h4 style="margin-top:1px;" class="nama-beasiswa"></h4>
            <hr>
            <div class="row">
                <div class="foto-peserta">
                    <img src="{{ asset('images/user-avatar.png') }}" class="user-foto">  
                </div>
                <div class="data-peserta">               
                    <table>
                        <tr>
                            <th width="40%">Nomor Peserta</th>
                            <td width="1%">:</td>
                            <td class="nomor-peserta"></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>:</td>
                            <td class="nama-lengkap"></td>
                        </tr>
                        <tr>
                            <th>Tempat/ Tanggal Lahir</th>
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
                        <th width="45%">Syarat</th>
                        <th width="10%">Ceklist Upload</th>
                        <th width="10%">Ceklist Fisik</th>
                        <th width="10%">Status</th>
                        <th width="20%">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                </tbody>
            </table>

        </div>

        <p>
            Status Akhir : ......................................................
        </p>
        
        <div class="footer">
            <div id="qrcode_label1"></div>
            <div id="ttd1">
                <p>Kendari, ........ / ........... / {{ date('Y') }}</p>
                Verifikator
                <p style="margin-top:75px;">__________________________</p>    
            </div>           
            <div id="ttd2">
                <p>Kendari, <span class="tanggal"></span></p>
                Pemohon
                <p style="margin-top:75px;" class="nama-lengkap">.....</p>    
            </div>           
        </div>

        <hr>
        <h2>Tanda Terima Dokumen</h2>
        <h4 style="margin-top:1px;" class="nama-beasiswa"></h4>

        <div class="data-peserta">                
            <table>
                <tr>
                    <th width="40%">Nomor Peserta</th>
                    <td width="1%">:</td>
                    <td class="nomor-peserta"></td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>:</td>
                    <td class="nama-lengkap"></td>
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
            </table>

            <div class="footer">
                <div id="qrcode_label2"></div>
                <div id="ttd1">
                    <p>Diterima oleh</p>
                    <p>Kendari, ........ / ........... / {{ date('Y') }}</p>
                    <p style="margin-top:75px;">__________________________</p>    
                </div>           
            </div>
    
        </div>
    </div>
    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
	$(document).ready(function() {
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const access_token = localStorage.getItem('access_token');

        if(access_token){
            // alert('run')
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
        }

        async function dataLoad() {
            var url = `${base_url}/api/cetak-kartu-pendaftaran/${url_id}`;
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
                renderData(result.data);
            } catch (error) {
                // window.location.replace(`${base_url}/pendaftar`);
            }
        }

        function renderData(data){
            if (data.beasiswa && data.pendaftar && data.pendaftar.is_finalisasi) {
                let pendaftar = data.pendaftar;
                let beasiswa = data.beasiswa;
                let syarat = beasiswa.syarat;
                let mahasiswa = pendaftar.mahasiswa;
                let user = mahasiswa.user;
                let identitas = user.identitas;
                let foto_src=base_url+'/'+user.identitas.foto;

                // console.log(pendaftar);
                let tanggal_update = pendaftar.updated_at.split('T')[0];


                $('.user-foto').attr('src',foto_src);
                $('.tahun').text(data.tahun);
                $('.tanggal').text(tanggal_update);
                $('.nama-beasiswa').text(data.nama);
                $('.nomor-peserta').text(pendaftar.no_pendaftaran);
                $('.nama-lengkap').text(user.name);
                $('.tempat-tanggal-lahir').text(identitas.tempat_lahir+'/ '+identitas.tanggal_lahir);
                $('.nim').text(mahasiswa.nim);
                $('.program-studi').text(`${mahasiswa.program_studi.fakultas.nama}/ ${mahasiswa.program_studi.nama}`);

                let alamat = identitas.alamat; 
                if(identitas.wilayah_desa){
                    let desa = identitas.wilayah_desa;
                    let kecamatan = desa.wilayah_kecamatan;
                    let kabupaten = kecamatan.wilayah_kabupaten;
                    let provinsi = kabupaten.wilayah_provinsi;
                    alamat +=` ${desa.desa} / ${kecamatan.nama} / ${kabupaten.nama} / ${provinsi.nama}`;
                }
                $('.alamat').text(alamat);

                if(syarat.length>0){
                    const dataList = $('#data-list');
                    dataList.empty();
                    let no = 1;
                    $.each(syarat, function(index, dt) {
                        const sudah_upload=(dt.upload_syarat)?"Ada":"-";
                        const is_wajib=(dt.is_wajib)?"wajib":"pilihan";
                        const row = `<tr>
                                        <td>${no++}</td>
                                        <td>${dt.nama} (${is_wajib})</td>
                                        <td>${sudah_upload}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>`;
                        dataList.append(row);
                    });                        

                }

                // const qr_text = JSON.stringify({
                //     id: data.id, 
                //     api: "disposisi", 
                // });     
                const qr_text = window.location.href;
                // const qr_ttd = `${data.no_surat} ${data.perihal} ${qr_link}`;
                new QRCode(document.getElementById('qrcode_label1'), {
                    text: qr_text,
                    width: 100,
                    height: 100
                });                

                new QRCode(document.getElementById('qrcode_label2'), {
                    text: qr_text,
                    width: 100,
                    height: 100
                });                

                
                $('#nomor-peserta').val(pendaftar.no_pendaftaran);
            }else{
                // window.location.replace(`${base_url}/pendaftar`);
            }
        }

    });
    </script>
</body>
</html>
