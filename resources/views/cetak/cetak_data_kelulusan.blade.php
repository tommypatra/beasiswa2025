<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Data Kelulusan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        const beasiswa_id="{{ $beasiswa_id }}";
    </script>
</head>
<body>
    <div id="loadingProgress">0%</div>
    <div class="card">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="SNPMB Logo">
            <h1>DATA KELULUSAN BEASISWA TAHUN <span id="tahun-beasiswa"></span></h1>
            <h4 style="margin-top:1px;" id="nama-beasiswa"></h4>
            <hr>    
        </div>
        <button id="copyTableBtn" onclick="copyTable2()">Copy ke Excel</button>
        <div class="content">
            <table id="mytable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama/ NIM</th>
                        <th width="5%">Jenis Kelamin</th>
                        <th width="10%">Fakultas/ Program Studi</th>
                        <th width="5%">Nilai Ekonomi</th>
                        <th width="5%">Nilai Pendidikan</th>
                        <th width="5%">Nilai Berkas</th>
                        <th width="5%">Nilai CBT</th>
                        <th width="5%">Nilai Survei</th>
                        <th width="20%">Catatan Survei</th>
                        <th width="30%">Hasil Wawancara</th>
                        <th width="5%">Nilai Akhir Wawancara</th>
                        <th width="10%">Status Lulus</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="13">tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MULAI MODAL -->
    <div class="modal fade modal" id="modal-dokumentasi" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Dokumentasi Survei</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3" id="dokumentasi-survei"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>
    <!-- AKHIR MODAL -->

    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    const g_limit = 50;
    let g_nomor = 1;
    let pendaftar_id;
        
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
            let url = `${base_url}/api/get-data-beasiswa/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#tahun-beasiswa').text(`${beasiswa.tahun}`);
            $('#nama-beasiswa').text(`${beasiswa.nama}`);
        }

        async function loadDokumentasi() {
            let url = `${base_url}/api/get-data-dokumentasi-survei/${pendaftar_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            if (response.data.length > 0) {
                let dokumentasi_survei = $('#dokumentasi-survei');
                let dok_html = `<div class="row row-cols-2 row-cols-md-3 g-3">`;

                dokumentasi_survei.empty();

                $.each(response.data, function(index, dtdok) {
                    let path_dok = `${base_url}/${dtdok.path}`;
                    dok_html += `
                        <div class="col text-center">
                            <a href="${path_dok}" target="_blank" class="d-block">
                                <img src="${path_dok}" class="img-fluid rounded shadow-sm" alt="Dokumentasi ${index + 1}">
                            </a>
                        </div>
                    `;
                });

                dok_html += `</div>`;
                dokumentasi_survei.html(dok_html);
            }
        }

        async function dataLoad() {
            let page = 1;
            let hasNext = true;
            g_nomor = 1;
            const dataList = $('#data-list');
            dataList.empty();
            while (hasNext) {
                let url = `${base_url}/api/kelulusan?beasiswa_id=${beasiswa_id}&limit=${g_limit}&page=${page}`;
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

        $(document).on('click','.cek-dokumentasi-survei',function(){
            pendaftar_id=$(this).attr('data-pendaftar_id');
            const fModal = new bootstrap.Modal(document.getElementById('modal-dokumentasi'), {
                keyboard: false
            });
            fModal.show();
            loadDokumentasi();
        })

        function renderData(dataRespon,dataList){
            if(dataRespon.length>0){
                $.each(dataRespon, function(data, dt) {
                    const status_kelulusan = dt.status.is_lulus == 1 ? "Lulus"  : dt.status.is_lulus == 0  ? "Tidak Lulus" : "";
                    let daftar_nilai=``;
                    if(dt.wawancara.length>0){
                        let listTag = dt.wawancara.length > 1 ? "ol" : "ul";

                        daftar_nilai=`<${listTag}>`;
                        $.each(dt.wawancara, function(data, dw) {
                            daftar_nilai+=`<li>${dw.pewawancara}: ${showText(dw.nilai)}</li>`;
                        });
                        daftar_nilai+=`</${listTag}>`;
                    }
    

                    let hasil_survei='';
                    if(dt.survei.hasil!==null){
                        let catatan_survei = dt.survei.catatan!==null?`,<div>${dt.survei.catatan}</div>`:'';
                        if(dt.survei.hasil==4)
                            hasil_survei='Sangat Layak'+catatan_survei;
                        else if(dt.survei.hasil==3)
                            hasil_survei='Layak'+catatan_survei;
                        else if(dt.survei.hasil==2)
                            hasil_survei='Cukup Layak'+catatan_survei;
                        else if(dt.survei.hasil==1)
                            hasil_survei='Kurang Layak'+catatan_survei;
                        else
                            hasil_survei='Tidak Layak'+catatan_survei;
                        hasil_survei += `<div><a href="javascript:;" data-pendaftar_id="${dt.pendaftar_id}" class="cek-dokumentasi-survei">dokumentasi survei</a></div>`;
                    }
                    const row = `<tr>
                                    <td>${g_nomor++}</td>
                                    <td>${dt.mahasiswa.nama}/ ${dt.mahasiswa.nim}</td>
                                    <td>${dt.mahasiswa.jenis_kelamin}</td>
                                    <td>${dt.mahasiswa.fakultas}/ ${dt.mahasiswa.program_studi}</td>
                                    <td>${label(dt.nilai.ekonomi)}</td>
                                    <td>${label(dt.nilai.pendidikan)}</td>
                                    <td>${label(dt.nilai.berkas)}</td>
                                    <td>${label(dt.nilai.cbt)}</td>
                                    <td>${label(dt.nilai.survei)}</td>
                                    <td>${hasil_survei}</td>
                                    <td>${daftar_nilai}</td>
                                    <td>${label(dt.nilai.wawancara)}</td>
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
