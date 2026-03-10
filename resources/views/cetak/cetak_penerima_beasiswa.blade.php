<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penerima Beasiswa</title>
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
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
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
            min-width: 1200px; /* optional, bisa disesuaikan */
        }

        .content th,
        .content td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            white-space: normal; /* ganti nowrap supaya teks wrap */
        }

        @media print {
            @page {
                size: landscape;
                margin: 10mm; /* bisa sesuaikan */
            }

            body {
                background: white;
                zoom: 75%; /* optional */
                margin-top: 10px;
            }

            /* Contoh tambahan: pastikan tabel tidak melebar keluar */
            /* table {
                width: 100%;
                table-layout: fixed; 
                word-wrap: break-word;
            } */

            .card {
                border: none;
                box-shadow: none;
                page-break-after: always;
            }
        }        
    </style>
    <script>
        const base_url="{{ url('/') }}";
        const sk_penerima_id="{{ $sk_penerima_id }}";    
    </script>
</head>
<body>

    <div class="card">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="SNPMB Logo">
            <h1>DATA PENERIMA BEASISWA</span></h1>
            <h4 style="margin-top:1px;" id="nama-beasiswa"></h4>
            <hr>    
        </div>

        <button id="copyTableBtn" onclick="copyTable2()">Copy ke Excel</button>

        <div class="content">
            <table id="mytable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="13%">NIM</th>
                        <th width="20%">Nama</th>
                        <th width="10%">Jenis Kelamin</th>
                        <th width="13%">HP</th>
                        <th width="15%">Fakultas</th>
                        <th width="15%">Program Studi</th>
                        <th width="10%">Bank</th>
                        <th width="10%">Nomor Rekening</th>
                        <th width="20%">Rekening Atas Nama</th>
                        <th width="10%">Status Rekening</th>
                        <th width="20%">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="12">tidak ditemukan</td>
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
            loadDataBeasiswa();
            $('#loadingProgress').show().text("0%");
            await dataLoad();
            $('#loadingProgress').text("Selesai").fadeOut(1000);
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-sk-penerima/${sk_penerima_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            // $('#tahun-beasiswa').text(`${beasiswa.tahun}`);
            $('#nama-beasiswa').html(`${beasiswa.nama}<br>Nomor SK : ${beasiswa.nomor_sk} Tanggal : ${beasiswa.tanggal_sk}`);
        }


        async function dataLoad() {
            let page = 1;
            let hasNext = true;
            g_nomor = 1;
            const dataList = $('#data-list');
            dataList.empty();
            while (hasNext) {
                let url = `${base_url}/api/penerima?sk_penerima_id=${sk_penerima_id}&limit=${g_limit}&page=${page}`;
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
                    const row = `<tr>
                                    <td>${g_nomor++}</td>
                                    <td>${dt.nim}</td>
                                    <td>${dt.name}</td>
                                    <td>${dt.jenis_kelamin}</td>
                                    <td>${dt.no_hp}</td>
                                    <td>${dt.fakultas}</td>
                                    <td>${dt.program_studi}</td>
                                    <td>${label(dt.terupload_bank)}</td>
                                    <td>${label(dt.terupload_nomor)}</td>
                                    <td>${label(dt.terupload_nama_pemilik)}</td>
                                    <td>${dt.terupload_bank?"sudah-lengkap":"belum-lengkap"}</td>
                                    <td>${label(dt.keterangan)}</td>
                                </tr>`;
                    dataList.append(row);
                });                        
            }
        }

    });
    </script>
</body>
</html>
