<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Peserta Wawancara</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
<style>
    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    body {
        font-family: Arial, sans-serif;
        background: #fff;
        margin: 0;
        padding: 0;
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

    /* === CARD (TAMPIL SAAT DI LAYAR) === */
    .card {
        width: 95%;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #000;
        border-radius: 6px;
        background-color: #fff;
        box-shadow: 0 0 3px rgba(0,0,0,0.2);
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
    }

    .header img {
        width: 80px;
        height: auto;
    }

    .header h1 {
        margin: 6px 0 2px;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .header h3 {
        margin: 2px 0 5px;
        font-size: 16px;
        font-weight: normal;
    }

    .header hr {
        border: 0;
        border-top: 2px solid #000;
        margin-top: 8px;
        margin-bottom: 10px;
    }

    /* === TABLE === */
    .content table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 13px;
        margin-top: 10px;
    }

    .content th,
    .content td {
        border: 1px solid #000;
        padding: 6px;
        white-space: normal;
        word-wrap: break-word;
        vertical-align: top;
    }

    .content th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: center;
    }

    .content td img {
        border: 1px solid #aaa;
        background: #fff;
    }

    /* === BAGIAN TANDA TANGAN === */
    .ttd {
        margin-top: 25px;
        width: 350px;
        height: 120px;
        float: right;
        text-align: left;
        font-size: 14px;
        line-height: 1.6;
    }

    .ttd span {
        display: inline-block;
        margin-top: 60px;
        text-decoration: underline;
        font-weight: bold;
    }

    /* === PRINT MODE === */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            background: white;
            margin: 0;
            padding: 0;
        }

        /* Hapus tampilan kartu saat cetak */
        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            page-break-after: always;
        }

        /* Header tetap rapi */
        .header hr {
            border-top: 1px solid #000;
        }

        /* Hilangkan loading progress saat print */
        #loadingProgress {
            display: none !important;
        }
    }
</style>


    <script>
        const base_url="{{ url('/') }}";
        const beasiswa_id="{{ $beasiswa_id }}";
        const pewawancara_id="{{ $pewawancara_id }}";
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
                    let url = `${base_url}/api/cetak-absen-wawancara/${beasiswa_id}?pewawancara_id=${pewawancara_id}&sort=2&limit=${g_limit}&page=${page}`;
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
                    let nomor = 0;

                    const rows = group.data.map(dt => {
                        nomor++;
                        const foto = base_url+'/'+dt.mahasiswa.foto;

                        return `<tr>
                            <td style="vertical-align:top;text-align:center;">${nomor}</td>
                            <td style="vertical-align:top;">

                                <div style="display: flex; gap: 8px; align-items: flex-start;">
                                    <img src="${foto}" height="70" width="65" style="display: block; border-radius: 4px;">
                                    <div style="line-height: 1.4;">
                                        ${dt.mahasiswa.nama.toUpperCase()}<br>  
                                        NIM. ${dt.mahasiswa.nim}<br>
                                        ${dt.mahasiswa.fakultas}
                                    </div>                                    
                                </div>                            
                            </td>
                            <td style="vertical-align:top;text-align:center;">${dt.mahasiswa.jenis_kelamin}</td>
                            <td style="vertical-align:midlle;">${dt.mahasiswa.program_studi}</td>
                            <td>${nomor}.</td>
                        </tr>`;
                    }).join('');

                    const cardHtml = `
                    <div class="card">
                        <div class="header">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:80px;">
                            <h1>DAFTAR HADIR WAWANCARA BEASISWA TAHUN ${beasiswa.tahun}</h1>
                            <h3>${beasiswa.nama.toUpperCase()}</h3>
                            <hr>
                        </div>

                        <div class="content">
                            <div>PEWAWANCARA : ${pewawancara.nama.toUpperCase()}</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" width="5%">No</th>
                                        <th width="45%">Nama/ NIM/ Fakultas</th>
                                        <th style="text-align:center;" width="10%">Jenis Kelamin</th>
                                        <th width="25%">Program Studi</th>
                                        <th style="text-align:center;" width="15%">Tanda Tangan</th>
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
                                Ketua Panitia,<br><br><br><br><br>
                                <span>
                                    ................................................
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
