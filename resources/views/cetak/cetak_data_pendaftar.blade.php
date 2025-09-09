<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta Seleksi Beasiswa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <style>

        @page {
            size: A4;
            /* margin: 5mm; */
            margin-top:10px;
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

        .content {
            margin-top: 20px;
            overflow-x: auto;   /* scroll horizontal kalau tidak muat */
        }

        .content table {
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px; /* biar kolom tetap punya ruang, bisa diubah sesuai kebutuhan */
        }

        .content th, 
        .content td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            white-space: nowrap; /* cegah teks panjang patah ke bawah */
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
        const beasiswa_id="{{ $beasiswa_id }}";
    </script>
</head>
<body>

    <div class="card">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="SNPMB Logo">
            <h1>DATA PESERTA BEASISWA <span id="tahun"></span></h1>
            <h4 style="margin-top:1px;" class="nama-beasiswa"></h4>
            <hr>    
        </div>

        <button id="copyTableBtn" onclick="copyTable()">Copy ke Excel</button>

        <div class="content">
            <table id="mytable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Nama</th>
                        <th width="10%">NIM</th>
                        <th width="5%">Jenis Kelamin</th>
                        <th width="10%">Tempat Lahir</th>
                        <th width="10%">Tanggal Lahir</th>
                        <th width="10%">Tahun Masuk</th>
                        <th width="10%">Fakultas</th>
                        <th width="10%">Program Studi</th>
                        <th width="20%">Alamat</th>
                        <th width="5%">Kecamatan</th>
                        <th width="5%">Kabupaten</th>
                        <th width="5%">Provinsi</th>
                        <th width="10%">Email</th>
                        <th width="10%">HP</th>
                        <th width="10%">Asal Sekolah</th>
                        <th width="10%">Tahun Lulus Sekolah</th>
                        <th width="10%">Status Pendaftaran</th>
                        <th width="10%">Status Verifikasi</th>
                        <th width="10%">Status Kelulusan</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="14">tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>


    <div id="loadingProgress">0%</div>
    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    const g_limit = 50;
    let g_nomor = 1;
        
    function label($string){
        return ($string)?$string:"";
    }


    function copyTable2() {
        var urlField = document.getElementById('mytable')
        var range = document.createRange()
        range.selectNode(urlField)
        window.getSelection().addRange(range)
        document.execCommand('copy')
        alert("berhasil tersalin");
    }   
    
    function copyTable() {
        let text = "";
        const rows = document.querySelectorAll("#mytable tr");

        rows.forEach(row => {
            let cols = row.querySelectorAll("th, td");
            let rowData = [];
            cols.forEach(col => rowData.push(col.innerText));
            text += rowData.join("\t") + "\n"; // pakai tab untuk Excel
        });

        navigator.clipboard.writeText(text).then(() => {
            alert("Tabel berhasil disalin! Silakan paste di Excel.");
        }).catch(err => {
            console.error("Gagal copy:", err);
        });
    }

	$(document).ready(function() {
        const token = localStorage.getItem('access_token');
        function forceLogout(){
            localStorage.clear();
            window.location.replace(`${base_url}/login`);
        }        


        $.ajaxSetup({
            beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
            },          
            complete: function(xhr) {
                //baca respon jika ada authorization maka refresh token
                let responHeader = xhr.getResponseHeader('Authorization');
                if (responHeader) {
                    let newToken = responHeader.replace('Bearer ', '').trim();
                    access_token = newToken;
                    localStorage.setItem('access_token', newToken);
                }
                //jika token tidak berlaku
                if (xhr.status === 401) {
                    forceLogout();
                }
            }
        });
        cekAkses();
        initPage();

        async function initPage() {
            $('#loadingProgress').show().text("0%");
            await dataLoad();
            $('#loadingProgress').text("Selesai").fadeOut(1000);
        }

        async function dataLoad() {
            let page = 1;
            let hasNext = true;
            g_nomor = 1;
            const dataList = $('#data-list');
            dataList.empty();
            while (hasNext) {
                let url = `${base_url}/api/daftar-pendaftar-beasiswa/${beasiswa_id}?limit=${g_limit}&page=${page}`;
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`, 
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const result = await response.json();
                    renderData(result.data.data,dataList);

                    const progress = Math.round((result.data.current_page / result.data.last_page) * 100);
                    $('#loadingProgress').text(progress + "%");

                    if (result.data.current_page < result.data.last_page) {
                        page++;
                    } else {
                        hasNext = false;
                    }

                } catch (error) {
                    console.error("Error load data:", error);
                    hasNext = false;
                }
            }

        }

        function renderData(dataRespon,dataList){
            if(dataRespon.length>0){
                $.each(dataRespon, function(data, dt) {
                    let status_pendaftaran = (dt.is_finalisasi)?"Selesai":"Proses";
                    if(dt.is_batal==1){
                        status_pendaftaran = 'Batal, '+dt.alasan_batal;
                    }
                    const status_verifikasi = dt.hasil_verifikasi == 1 ? "MS"  : dt.hasil_verifikasi == 0  ? "TMS" : "";
                    const status_kelulusan = dt.is_lulus == 1 ? "Lulus"  : dt.is_lulus == 0  ? "Tidak Lulus" : "";

                    const row = `<tr>
                                    <td>${g_nomor++}</td>
                                    <td>${dt.nama}</td>
                                    <td>${dt.nim}</td>
                                    <td>${dt.jenis_kelamin}</td>
                                    <td>${dt.tempat_lahir}</td>
                                    <td>${dt.tanggal_lahir}</td>
                                    <td>${dt.tahun_masuk}</td>
                                    <td>${dt.fakultas}</td>
                                    <td>${dt.program_studi}</td>
                                    <td>${dt.alamat} ${dt.desa}</td>
                                    <td>${dt.kecamatan}</td>
                                    <td>${dt.kabupaten}</td>
                                    <td>${dt.provinsi}</td>
                                    <td>${dt.email}</td>
                                    <td>${dt.no_hp}</td>
                                    <td>${label(dt.sekolah)}</td>
                                    <td>${dt.tahun_lulus_sekolah}</td>
                                    <td>${status_pendaftaran}</td>
                                    <td>${status_verifikasi}</td>
                                    <td>${status_kelulusan}</td>
                                </tr>`;
                    dataList.append(row);
                });                        
            }
        }

    });
    </script>
</body>
</html>
