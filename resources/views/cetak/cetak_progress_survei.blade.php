<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Survei</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            /* margin: 5mm; */
            margin-top: 10px;
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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
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
            overflow-x: auto;
            /* Tambahan penting: scroll horizontal kalau tabel lebar */
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
            table-layout: fixed;
            /* membuat kolom tidak melebar otomatis */
            word-wrap: break-word;
            /* teks panjang otomatis wrap */
            min-width: 1200px;
            /* optional, bisa disesuaikan */
        }

        .content th,
        .content td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            white-space: normal;
            /* ganti nowrap supaya teks wrap */
        }

        @media print {
            @page {
                size: landscape;
                margin: 10mm;
                /* bisa sesuaikan */
            }

            body {
                background: white;
                zoom: 75%;
                /* optional */
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
        const base_url = "{{ url('/') }}";
        const beasiswa_id = "{{ $beasiswa_id }}";
    </script>
</head>

<body>

    <div class="card">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="SNPMB Logo">
            <h1>PROGRESS SURVEI BEASISWA TAHUN <span id="tahun-beasiswa"></span></h1>
            <h4 style="margin-top:1px;" id="nama-beasiswa"></h4>
            <hr>
        </div>


        <div class="content">
            <table id="mytable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama</th>
                        <th width="20%">Kecamatan</th>
                        <th width="15%">Kabupaten</th>
                        <th width="5%">Jumlah Peserta</th>
                        <th width="5%">Selesai Survei</th>
                        <th width="15%">Progress</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="7">tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>


    <div id="loadingProgress">0%</div>
    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const g_limit = 50;
        let g_nomor = 1;

        function label($string) {
            return ($string) ? $string : "";
        }


        $(document).ready(function() {
            const token = localStorage.getItem('access_token');

            function forceLogout() {
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
                await loadDataBeasiswa();
                $('#loadingProgress').show().text("0%");
                await dataLoad();
                $('#loadingProgress').text("Selesai").fadeOut(1000);
            }

            async function loadDataBeasiswa() {
                let url = `${base_url}/api/get-data-beasiswa/${beasiswa_id}`;
                const response = await execAsync(`${url}`, 'GET', token);
                let beasiswa = response.data;
                $('#tahun-beasiswa').text(`${beasiswa.tahun}`);
                $('#nama-beasiswa').text(`${beasiswa.nama}`);
            }

            async function dataLoad() {
                const dataList = $('#data-list');
                const url = window.location.href;
                const queryString = url.split('?')[1];
                let page = 1;
                let hasNext = true;
                g_nomor = 1;

                dataList.empty();
                while (hasNext) {
                    let url = `${base_url}/api/progress-survei/${beasiswa_id}?limit=${g_limit}&page=${page}&${queryString}`;
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
                        renderData(result.data.data, dataList);

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

            function renderData(dataRespon, dataList) {
                if (dataRespon.length > 0) {
                    let total_keseluruhan = 0;
                    let valid_keseluruhan = 0;
                    let persen_keseluruhan = 0;
                    let color="black";

                    $.each(dataRespon, function(data, dt) {
                        let kabupaten = '';
                        if (Array.isArray(dt.daftar_kabupaten) && dt.daftar_kabupaten.length > 0) {
                            kabupaten = dt.daftar_kabupaten.join(', ');
                        }

                        let kecamatan = '';
                        if (Array.isArray(dt.daftar_kecamatan) && dt.daftar_kecamatan.length > 0) {
                            kecamatan = dt.daftar_kecamatan.join(', ');
                        }

                        let total = parseInt(dt.total_pendaftar) || 0;
                        let valid = parseInt(dt.peserta_valid) || 0;
                        let persen = total > 0 ? Math.round((valid / total) * 100) : 0;

                        let bgClass = 'bg-danger'; // default untuk 0%
                        if (persen >= 1 && persen <= 25) bgClass = 'bg-warning';
                        else if (persen >= 26 && persen <= 50) bgClass = 'bg-secondary';
                        else if (persen >= 51 && persen <= 99) bgClass = 'bg-primary';
                        else if (persen > 99) bgClass = 'bg-success';

                        total_keseluruhan+=total;
                        valid_keseluruhan+=valid;

                        // Buat progress bar Bootstrap
                        let progressBar = `<div class="progress position-relative" style="height: 20px;">
                                                <div class="progress-bar ${bgClass}" role="progressbar"
                                                    style="width: ${persen}%;" 
                                                    aria-valuenow="${persen}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                                <span class="position-absolute w-100 text-center fw-bold" 
                                                    style="color:black; font-size:12px;">
                                                    ${persen}%
                                                </span>
                                            </div>`;
                            
                            const row = `<tr>
                                    <td>${g_nomor++}</td>
                                    <td>${dt.verifikator}</td>
                                    <td>${kecamatan}</td>
                                    <td>${kabupaten}</td>
                                    <td>${dt.total_pendaftar}</td>
                                    <td>${dt.peserta_valid}</td>
                                    <td>${progressBar}</td>
                                </tr>`;
                        dataList.append(row);
                    });

                    persen_keseluruhan = total_keseluruhan > 0 ? Math.round((valid_keseluruhan / total_keseluruhan) * 100) : 0;
                    let bgClass = 'bg-danger'; // default untuk 0%
                    if (persen_keseluruhan >= 1 && persen_keseluruhan <= 25) bgClass = 'bg-warning';
                    else if (persen_keseluruhan >= 26 && persen_keseluruhan <= 50) bgClass = 'bg-secondary';
                    else if (persen_keseluruhan >= 51 && persen_keseluruhan <= 99) bgClass = 'bg-primary';
                    else if (persen_keseluruhan > 99) bgClass = 'bg-success';

                    // Buat progress bar Bootstrap
                    let progressBar = `<div class="progress position-relative" style="height: 20px;">
                                            <div class="progress-bar ${bgClass}" role="progressbar"
                                                style="width: ${persen_keseluruhan}%;" 
                                                aria-valuenow="${persen_keseluruhan}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                            <span class="position-absolute w-100 text-center fw-bold" 
                                                style="color:black; font-size:12px;">
                                                ${persen_keseluruhan}%
                                            </span>
                                        </div>`;
                        
                    const row = `<tr>
                            <td colspan="4"></td>
                            <td>${total_keseluruhan}</td>
                            <td>${valid_keseluruhan}</td>
                            <td>${progressBar}</td>
                        </tr>`;
                    dataList.append(row);                                        
                }
            }

        });
    </script>
</body>

</html>