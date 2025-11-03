<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hasil Wawancara Beasiswa</title>
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
                overflow: visible !important;  /* Hapus scroll saat print */
                width: 100%;                   /* Lebar maksimal untuk print */
                padding: 10px 5px;             /* Bisa disesuaikan */
            }
            /* .card {
                border: none;
                box-shadow: none;
                page-break-after: always;
            } */
        }        
    </style>

    <script>
        const base_url="{{ url('/') }}";
        const beasiswa_id="{{ $beasiswa_id }}";
        const pewawancara_id="{{ $pewawancara_id }}";
        const pendaftar_id="{{ $pendaftar_id }}";
    </script>
</head>
<body>

    <div id="data-list"></div>
    <div id="loadingProgress">0%</div>
    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        var beasiswa;
        const g_limit = 50;

        // Helper
        function label(str){
            return str ? str : "";
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
                    let responHeader = xhr.getResponseHeader('Authorization');
                    if (responHeader) {
                        let newToken = responHeader.replace('Bearer ', '').trim();
                        localStorage.setItem('access_token', newToken);
                    }
                    if (xhr.status === 401) forceLogout();
                }
            });

            cekAkses();
            initPage();

            async function initPage() {
                await loadDataBeasiswa();
                $('#loadingProgress').show().text("0%");
                await dataLoad();
                $('#loadingProgress').text("Selesai").fadeOut(1000);
            }

            async function loadDataBeasiswa() {
                let url = `${base_url}/api/get-data-beasiswa/${beasiswa_id}`;
                const response = await execAsync(url,'GET',token);
                beasiswa = response.data;
            }

            async function dataLoad() {
                let page = 1;
                let hasNext = true;
                const pewawancaraGroups = {}; // global untuk gabung data per pewawancara

                while(hasNext){
                    let url = `${base_url}/api/cetak-wawancara/${beasiswa_id}?pewawancara_id=${pewawancara_id}&sort=2&limit=${g_limit}&page=${page}&pendaftar_id=${pendaftar_id}`;
                    try {
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        const result = await response.json();

                        // Gabung data per pewawancara
                        result.data.data.forEach(dt => {
                            const id = dt.pewawancara.pewawancara_id;
                            if (!pewawancaraGroups[id]) pewawancaraGroups[id] = { pewawancara: dt.pewawancara, data: [] };
                            pewawancaraGroups[id].data.push(dt);
                        });

                        const progress = Math.round((result.data.current_page / result.data.last_page) * 100);
                        $('#loadingProgress').text(progress + "%");

                        if(result.data.current_page < result.data.last_page) page++;
                        else hasNext = false;

                    } catch (err){
                        console.error("Error load data:", err);
                        hasNext = false;
                    }
                }

                renderAllGroups(pewawancaraGroups);
            }

            function renderAllGroups(groups){
                const container = $('#data-list');
                container.empty();

                Object.values(groups).forEach(group => {
                    const pewawancara = group.pewawancara;
                    let nomor = 1;

                    const rows = group.data.map(dt => {
                        let rincian_wawancara = '';
                        if(dt.hasil_wawancara && dt.hasil_wawancara.length>0){
                            rincian_wawancara = '<ul>';
                            dt.hasil_wawancara.forEach(rw => {
                                rincian_wawancara += `<li>
                                    <div>${rw.soal} (bobot: ${rw.persentase_nilai})</div>
                                    <div>Nilai: ${rw.nilai}</div>
                                    <div>Catatan: ${rw.catatan}</div>
                                </li>`;
                            });
                            rincian_wawancara += '</ul>';
                        }

                        return `<tr>
                            <td>${nomor++}</td>
                            <td>${dt.mahasiswa.nama} / ${dt.mahasiswa.nim}</td>
                            <td>${dt.mahasiswa.jenis_kelamin}</td>
                            <td>${dt.mahasiswa.fakultas} / ${dt.mahasiswa.program_studi}</td>
                            <td>${rincian_wawancara}</td>
                            <td>${label(dt.nilai)}</td>
                        </tr>`;
                    }).join('');

                    const cardHtml = `
                    <div class="card">
                        <div class="header">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:80px;">
                            <h1>HASIL WAWANCARA SELEKSI BEASISWA TAHUN ${beasiswa.tahun}</h1>
                            <h3>${beasiswa.nama.toUpperCase()}</h3>
                            <hr>
                        </div>

                        <div class="content">
                            <div>PEWAWANCARA : ${pewawancara.nama.toUpperCase()}</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Nama/ NIM</th>
                                        <th width="5%">Jenis Kelamin</th>
                                        <th width="10%">Fakultas/ Program Studi</th>
                                        <th width="30%">Rincian Wawancara</th>
                                        <th width="10%">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>

                            <div style="
                                margin-top: 20px;
                                width: 350px;
                                height: 120px;
                                float: right;
                                text-align: left;
                            ">
                                Kendari, .................................<br>
                                Pewawancara,<br><br><br><br><br>
                                <span style="text-decoration: underline; font-weight: bold;">
                                    ${pewawancara.nama.toUpperCase()}
                                </span>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>`;

                    container.append(cardHtml);
                });
            }

        });
    </script>

</body>
</html>
