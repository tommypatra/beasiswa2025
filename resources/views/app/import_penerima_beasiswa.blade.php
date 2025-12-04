<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Penerima Beasiswa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    
    <link href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <style>
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

    </style>
    <script>
        const base_url="{{ url('/') }}";
    </script>
</head>
<body class="bg-light">
    <div id="loadingProgress">0%</div>
    <div class="container py-5">
        <h2 class="text-center text-primary">Import Penerima Beasiswa</h2>
        <div class="text-center mb-3" id="info-sk"></div>

        <div class="row g-4">
            <!-- Import dari CSV -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        Import dari File Excel
                    </div>
                    <div class="card-body">
                        <form id="formXls" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="xlsx_file" class="form-label">Upload File Excel</label>
                                <input type="file" class="form-control" id="xlsx_file" name="xlsx_file" accept=".xlsx" required>
                            </div>
                            <div class="mb-2">
                                <a href="{{ asset('format/import_penerima_beasiswa.xlsx') }}" class="btn btn-link p-0" download>
                                    <iconify-icon icon="mdi:file-download-outline" class="me-1"></iconify-icon>
                                    Unduh Contoh Format Excel
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <iconify-icon icon="mdi:upload-outline" class="me-1"></iconify-icon>
                                Import Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Import dari Seleksi -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white fw-bold">
                        Import dari Seleksi Beasiswa
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <label for="beasiswa_id" class="form-label">Pilih Seleksi Beasiswa</label>
                                <select id="beasiswa_id" name="beasiswa_id" class="form-select" required>
                                    <option value="">-- pilih --</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                import dari data kelulusan
                            </div>
                            <button class="btn btn-success w-100" id="btn-import-seleksi">
                                <iconify-icon icon="mdi:database-import-outline" class="me-1"></iconify-icon>
                                Import dari Seleksi
                            </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Hasil Import -->
        <div class="card shadow-sm border-0 mt-5">
            <div class="card-header bg-dark text-white fw-bold">
                Daftar Penerima Beasiswa
            </div>
            <div class="card-body">
                <!-- Tombol -->
                <button type="button" class="btn-scan btn btn-primary mb-2">Scan Data Terpilih</button>
                <button type="button" class="btn-simpan btn btn-primary mb-2">Simpan Status Ready</button>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%;"><input type="checkbox" id="cek-semua"></th>
                                <th style="width: 5%;">No</th>
                                <th>Nama</th>
                                <th style="width: 200px;">NIM/ Program Studi</th>
                                <th>Nomor Rekening</th>
                                <th>Hasil Scan</th>
                                <th style="width: 120px;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="data-list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>




    <script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
    const g_limit=50;
    const sk_penerima_id="{{ $sk_penerima_id }}";
    var g_nomor;
    var g_program_studi;

	$(document).ready(function() {
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const access_token = localStorage.getItem('access_token');
        var tahun;
        if(access_token){
            // alert('run')
            $.ajax({
                headers: {
                    "Accept": "application/json",
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

        $("#cek-semua").on("change", function() {
            $(".cek-baris").prop("checked", $(this).prop("checked"));
        });
        
        async function initPage() {
            await loadSK();
            await loadBeasiswa();
            await dataLoadProdi();
        }

        async function loadBeasiswa() {
            const url = `${base_url}/api/beasiswa?tahun=${tahun}`;
            const response = await execAsync(url,'get',access_token);
            if(response.status){
                let data = response.data.data; // array daftar beasiswa
                let $select = $('#beasiswa_id');

                // reset option
                $select.empty().append('<option value="">-- pilih --</option>');
                $.each(data, function(i, item) {
                    $select.append(
                        $('<option>', {
                            value: item.id,
                            text: '('+item.tahun+') '+item.jenis_beasiswa.nama + ' - ' + item.nama
                        })
                    );
                });                
            }
        }

        async function loadSK(beasiswa_id) {
            const url = `${base_url}/api/sk-penerima/${sk_penerima_id}`;
            const response = await execAsync(url,"get",access_token);
            $('#info-sk').html(``);
            tahun = '{{ date("Y") }}';
            if(response.status){
                let tanggal = response.data.tanggal_sk;
                tahun = tanggal.substring(0, 4);
                $('#info-sk').html(`
                    <h5>${response.data.nama}</h5>
                    <div>Nomor SK : ${response.data.nomor_sk}, Tanggal SK : ${tanggal}</div>
                `);
            }
        }

        async function dataLoadKelulusan(beasiswa_id) {
            let page = 1;
            let hasNext = true;
            g_nomor=1;
            const dataList = $('#data-list');
            dataList.empty();
            while (hasNext) {
                let url = `${base_url}/api/data-peserta-lulus/${beasiswa_id}/${sk_penerima_id}?limit=${g_limit}&page=${page}`;
                // let url = `${base_url}/api/kelulusan?filter[status_lulus]=1&beasiswa_id=${beasiswa_id}&limit=${g_limit}&page=${page}`;
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

        async function dataLoadProdi() {
            const url = `${base_url}/api/get-data-prodi?limit=0`;
            const response = await execAsync(url,"get",access_token);
            g_program_studi=null;
            if(response.status){
                g_program_studi=response.data;
            }
        }

        function renderData(data,dataList){
            let no = 1;
            if(data.length>0){
                $.each(data, function(index, dt) {
                    checkbox=`<input type="checkbox" class="cek-baris" data-user_id="" data-buku_rekening_id="" data-penerima_id="" data-mahasiswa="">`;
                    let sudah_terdata=``;
                    // if(dt.is_terdata_sk){
                    //     checkbox=``;
                    //     sudah_terdata=`Sudah terdata dalam SK ini`;
                    // } 
                

                    const row = `<tr>
                                    <td>${checkbox}</td>
                                    <td>${g_nomor++}</td>
                                    <td>${dt.mahasiswa.nama}</td>
                                    <td>
                                        <input type="text" class="form-control nim" style="width:180px;" value="${dt.mahasiswa.nim}">
                                        ${dt.mahasiswa.program_studi}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>${sudah_terdata}</td>
                                </tr>`;
                    dataList.append(row);
                });  
            }                      
        }

        $('#btn-import-seleksi').click(async function(){
            const id=$('#beasiswa_id').val();
            if(id){
                $('#loadingProgress').show().text("0%");
                await dataLoadKelulusan(id);
                $('#loadingProgress').text("Selesai").fadeOut(1000);
            }
        });

        $(".btn-scan").on("click", async function() {
            let rows = $(".cek-baris:checked").closest("tr");

            if (rows.length === 0) {
                alert("Tidak ada data yang dipilih!");
                return;
            }

            let total = rows.length;
            let done = 0;

            $("#loadingProgress").show().text("0%");

            for (let row of rows) {
                const nim = $(row).find("input.nim").val().trim();

                try {
                    // const response = await fetch(`${base_url}/api/cek-nim?sk_penerima_id=${sk_penerima_id}&nim=${nim}`);
                    const response = await fetch(`${base_url}/api/cek-nim?nim=${nim}`);
                    const result = await response.json();

                    $(row).find("input.cek-baris").attr("data-user_id","");
                    // $(row).find("input.cek-baris").attr("data-mahasiswa","");
                    $(row).find("input.cek-baris").attr("data-buku_rekening_id","");
                    $(row).find("input.cek-baris").attr("data-penerima_id");

                    
                    if(result.status){
                        const dataWeb = result.data;
                        const penerima = dataWeb.penerima.length>0 ? dataWeb.penerima[0]:null; 
                        const penerima_id = penerima?.id ?? null;
                        const cek_sk_penerima_id = penerima?.sk_penerima_id ?? null;

                        const buku_rekening = dataWeb.buku_rekening.length>0 ? dataWeb.buku_rekening[0]:null;        
                        const rekening = (buku_rekening)?`${buku_rekening.bank} ${buku_rekening.nomor} ${buku_rekening.nama_pemilik}`:``;
                        const buku_rekening_id = (buku_rekening)?buku_rekening.id:``;

                        $(row).find("input.cek-baris").attr("data-user_id",dataWeb.user_id);
                        $(row).find("input.cek-baris").attr("data-buku_rekening_id",buku_rekening_id);
                        $(row).find("input.cek-baris").attr("data-penerima_id",penerima_id);


                        $(row).find("td:eq(5)").text(`${rekening}`);
                        $(row).find("td:eq(2)").text(`${dataWeb.name}`);
                        $(row).find("td:eq(3)").html(`<input type="text" class="form-control nim" style="width:180px;" value="${dataWeb.nim}">
                                                        <div>${dataWeb.program_studi}</div>`);
                        
                        if(cek_sk_penerima_id!=sk_penerima_id){
                            $(row).find("input.cek-baris").attr("data-user_id",dataWeb.user_id);
                            $(row).find("td:eq(5)").text(`data valid nim ${dataWeb.nim} / ${dataWeb.name} / ${dataWeb.program_studi} dari ioss`);
                            $(row).find("td:last").text("ready");
                        }else{
                            $(row).find("td:eq(5)").text(`sudah terdata dalam SK`);
                            $(row).find("td:last").text(`idle`);
                            if ((!penerima.buku_rekening_id && buku_rekening) || (buku_rekening && buku_rekening.id != penerima.buku_rekening_id)) {
                                $(row).find("td:eq(5)").text("perbaikan nomor rekening");
                                $(row).find("td:last").text("ready");
                            } else {
                                $(row).find("input.cek-baris").remove();
                            }
                        }
                    }else{
                        const responseSIA = await fetch(`https://sia.iainkendari.ac.id/api/kkn/cariNim?iddata=${nim}`);
                        const resultSIA = await responseSIA.json();
                        if(resultSIA.status){
                            const dataSIA=resultSIA.data;
                            $(row).find("input.cek-baris").attr("data-mahasiswa", JSON.stringify(dataSIA));
                            $(row).find("td:eq(2)").text(`${dataSIA.nama}`);
                            $(row).find("td:eq(3)").html(`
                                <input type="text" class="form-control nim" style="width:180px;" value="${nim}">
                                ${loadProdiHtml(dataSIA.prodi,'300px')}
                            `);
                            // $(row).find("td:eq(4)").text(`${dataSIA.prodi}`);
                            $(row).find("td:eq(5)").text(`data baru nim ${dataSIA.nim} / ${dataSIA.nama} / ${dataSIA.prodi} dari SIA`);
                            $(row).find("td:last").text("ready");
                        }else{
                            $(row).find("td:eq(5)").text(`data baru dari Import Excle`);
                            $(row).find("td:last").text("ready");
                        }                    
                    }
                } catch (err) {
                    console.error("Error cek NIM:", nim, err);
                    $(row).find("td:last").text("Gagal cek");
                }

                // update progress
                done++;
                let progress = Math.round((done / total) * 100);
                $("#loadingProgress").text(progress + "%");
            }

            $("#loadingProgress").text("Selesai").fadeOut(1000);
        });


        $(".btn-simpan").on("click", async function() {
            let rows = $(".cek-baris:checked").closest("tr");
            if (rows.length === 0) {
                alert("Tidak ada data yang dipilih!");
                return;
            }
            let total = rows.length;
            let done = 0;
            $("#loadingProgress").show().text("0%");
            for (let row of rows) {
                const status_data = $(row).find("td:last").text();
                const user_id = $(row).find("input.cek-baris").attr("data-user_id");
                const mahasiswa = $(row).find("input.cek-baris").attr("data-mahasiswa");
                const mahasiswa_obj = $(row).find("input.cek-baris").data("mahasiswa");
                const buku_rekening_id = $(row).find("input.cek-baris").attr("data-buku_rekening_id");
                const penerima_id = $(row).find("input.cek-baris").attr("data-penerima_id");
                const prodi_id = $(row).find("select.program_studi").val();

                // console.log(status_data,user_id,mahasiswa);
                if(status_data=='ready'){
                    if(user_id){
                        const simpan_ke_sk = await simpanKeSk(penerima_id, user_id,buku_rekening_id);
                        if(simpan_ke_sk.status){
                            $(row).find("input.cek-baris").remove();
                        }
                        $(row).find("td:last").text(simpan_ke_sk.message);
                    }else{
                        const buat_user = await buatUser(mahasiswa_obj,prodi_id);
                        if(buat_user.status){
                            $(row).find("input.cek-baris").remove();
                            $(row).find("td:last").text('simpan user ke db dan masukan user ke sk');
                        }else{
                            $(row).find("td:last").text('terjadi kesalah, perbaiki data dan ulangi lagi');
                        }
                        // $(row).find("td:last").text(simpan_ke_sk.message);

                    }
                }
                // update progress
                done++;
                let progress = Math.round((done / total) * 100);
                $("#loadingProgress").text(progress + "%");
            }

            $("#loadingProgress").text("Selesai").fadeOut(1000);
        });

        async function simpanKeSk(penerima_id,user_id,buku_rekening_id){
            try {
                let method = "POST";
                let url = `${base_url}/api/penerima`;
                if(penerima_id){
                    method = "PUT";
                    url = `${base_url}/api/penerima/${penerima_id}`;
                }

                let response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Bearer " + access_token
                    },
                    body: JSON.stringify({user_id: user_id, buku_rekening_id:buku_rekening_id, sk_penerima_id:sk_penerima_id })
                });


                const result = await response.json();
                if(result.status){
                    return ({status:true,message:"berhasil tersimpan"})
                }else{
                    return ({status:false,message:"gagal tersimpan"});
                }
            } catch (err) {
                return ({status:false,message:"gagal tersimpan"});
            }
        }

        async function buatUser(mahasiswa,prodi_id){
            try {
                let method = "POST";
                let url = `${base_url}/api/buat-user-import-kelulusan/${sk_penerima_id}`;

                let response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Bearer " + access_token
                    },
                    body: JSON.stringify({
                        name:mahasiswa.nama, 
                        no_hp:mahasiswa.hp,
                        nim:mahasiswa.nim,
                        idprodi:mahasiswa.idprodi,
                        prodi:mahasiswa.prodi,
                        tanggal_lahir:mahasiswa.tgllahir,
                        tempat_lahir:mahasiswa.tmplahir,
                        fakultas:mahasiswa.fakultas,
                        tahun_masuk:mahasiswa.thnmasuk,
                        email:mahasiswa.email,
                        jenis_kelamin:mahasiswa.kelamin,
                        program_studi_id:prodi_id
                    })
                });
                const result = await response.json();
                if(result.status){
                    return ({status:true,message:"berhasil tersimpan"})
                }else{
                    return ({status:false,message:"gagal tersimpan"});
                }
            } catch (err) {
                return ({status:false,message:"gagal tersimpan"});
            }
        }

        function loadProdiHtml(prodi,width){
            let program_studi_html = `<select class="form-select program_studi" style="width:${width};">
                                        <option value=""></option>`;
            let prodi_terpilih=false;
            $.each(g_program_studi, function(index, dpr) {
                let selected="";
                if(dpr.nama.toLowerCase().trim() === prodi.toLowerCase().trim()){
                    selected="selected";
                    prodi_terpilih=true;
                }
                program_studi_html +=`<option value="${dpr.id}" ${selected}>${dpr.nama}</option>`;
            });
            program_studi_html +=`</select>`;
            if(!prodi_terpilih)
                program_studi_html +=`<div>${prodi}</div>`;
            return program_studi_html;
        }

        $('#formXls').on('submit', function (e) {
            e.preventDefault(); // jangan kirim ke backend

            const file = $('#xlsx_file')[0].files[0];
            if (!file) {
                alert("Silakan pilih file Excel terlebih dahulu!");
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);

                // Baca Excel
                const workbook = XLSX.read(data, { type: 'array' });

                // Ambil sheet pertama
                const firstSheet = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheet];
                const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                $('#data-list').empty(); // Clear table body dulu

                rows.forEach((row, index) => {
                    if (index === 0) return; // skip header

                    const no   = (row[0] || '').toString().trim();
                    const nama = (row[1] || '').toString().trim();
                    const nim  = (row[2] || '').toString().trim();
                    const program_studi  = (row[3] || '').toString().trim();

                    // Abaikan baris yang tidak ada data
                    if (!nama && !nim) return;
                    
                    const mahasiswa = {
                        iddata: null,
                        idbayar: null,
                        email:null,
                        nama: nama,
                        nim: nim,
                        idprodi: null,
                        tgllahir: "{{ date('Y-m-d') }}",
                        tmplahir: 'Kendari',
                        kelamin: null,
                        thnmasuk: null,
                        status_awal: null,
                        hp: null,
                        prodi: program_studi,
                        fakultas: null
                    };
                    // Encode JSON agar aman dalam HTML attribute
                    const mahasiswaJson = $('<div>').text(JSON.stringify(mahasiswa)).html();

                    const tr = $(`
                        <tr>
                            <td class="text-center">
                                <input 
                                    type="checkbox" 
                                    class="cek-baris" 
                                    data-user_id="" 
                                    data-buku_rekening_id="" 
                                    data-penerima_id=""
                                >
                            </td>
                            <td class="text-center">${no}</td>
                            <td>${nama}</td>
                            <td>
                                <input type="text" class="form-control nim" style="width:180px;" value="${nim}">
                                ${loadProdiHtml(program_studi,'300px')}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);

                    tr.find('.cek-baris').attr('data-mahasiswa', mahasiswaJson);                    
                    $('#data-list').append(tr);
                });

                alert("Data berhasil dimuat ke tabel!");
            };

            reader.readAsArrayBuffer(file);
        });

    });
    </script>
</body>
</html>
