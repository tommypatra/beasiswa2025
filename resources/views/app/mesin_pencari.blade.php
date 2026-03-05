@extends('template')

@section('scriptHead')
<title>Mesin Pencari</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body mb-3">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
                <h5 class="card-title fw-semibold">Mesin Pencari</h5>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="file" id="file-excel" accept=".xls,.xlsx" style="display:none;">
            <select class="form-select" id="filter-tahun" name="tahun" style="max-width:120px;">
                <option value="">Semua</option>
                <?php
                    $tahunSekarang = date('Y');
                    for ($i = 0; $i < 10; $i++) {
                        $tahun = $tahunSekarang - $i;
                        echo "<option value='$tahun'>$tahun</option>";
                    }
                ?>
            </select>
            <input type="text" 
                class="form-control" 
                id="search-input" 
                placeholder="Cari...">

            <button class="btn btn-primary"
                id="btn-search"
                data-bs-toggle="tooltip"
                title="Cari Data">
                <i class="ti ti-search"></i>
            </button>

            <button class="btn btn-warning"
                id="btn-download-format"
                data-bs-toggle="tooltip"
                title="Download Format">
                <i class="ti ti-download"></i>
            </button>

            <button class="btn btn-success"
                id="btn-upload"
                data-bs-toggle="tooltip"
                title="Upload Data">
                <i class="ti ti-upload"></i>
            </button>
        </div>


        <div class="mt-4 mb-4" id="hasil-pencarian" style="display: none;">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Hasil Pencarian</h5>
                </div>
                <div class="dropdown dropstart">
                    <a href="javascript:;" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-dots-vertical fs-6"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item d-flex align-items-center gap-3" href="javascript:;" id="copy-pencarian"><i class="fs-4 ti ti-copy"></i>salin hasil pencarian</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-3" href="javascript:;" id="clear-pencarian"><i class="fs-4 ti ti-refresh"></i>bersihkan hasil pencarian</a></li>
                    </ul>
                </div>                    
            </div>            
            <hr> 
            <div class="table-responsive">
                <table class="table" id="table-pencarian">
                    <tbody id="data-list"></tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>

    </div>
</div>
@endsection

@section('scriptJs')
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {
    const endpoint = base_url+'/api/';
    var page = 1;

    $('#hasil-pencarian').hide();

    $('[data-bs-toggle="tooltip"]').each(function () {
        new bootstrap.Tooltip(this);
    });

    function renderData(response) {
        const dataList = $('#data-list');
        const tableList = $('#table-list');
        const pagination = $('#pagination');
        const data = response.data.data;

        dataList.empty();
        pagination.empty();
        $('#hasil-pencarian').show();

        if (data.length > 0) {
            let nomor = ((response.data.current_page - 1) * response.data.per_page) + 1;
            $.each(data, function(index, dt) {
                let beasiswaHtml = '';
                
                if (dt.penerima && dt.penerima.length > 0) {
                    $.each(dt.penerima, function(i, p) {
                        beasiswaHtml += `
                            <div class="border rounded p-2 mb-2 bg-light">
                                <div><strong>${p.nama}</strong></div>
                                <div>No SK: ${p.nomor_sk}</div>
                            </div>
                        `;
                    });
                } else {
                    beasiswaHtml = `
                        <div class="text-danger">
                            Belum menerima beasiswa
                        </div>
                    `;
                }

                const card = `
                <tr>
                    <td width="50px">
                        ${nomor++}
                    </td>

                    <td width="50%">
                        <h5 class="card-title mb-1">
                            ${dt.nama}
                        </h5>
                        <div class="text-muted mb-2">
                            NIM: ${dt.nim}
                        </div>
                        <div>
                            (${dt.program_studi})
                        </div>
                    </td>
                    <td width="50%">
                        <div class="fw-bold mb-2">
                            Riwayat Beasiswa
                        </div>
                        ${beasiswaHtml}
                    </td>
                </tr>
                `;
                dataList.append(card);
            });
            renderPagination(response.data, pagination);
        } else {
            const empty = `
                <tr>
                    <td>
                        <div class="alert alert-warning">
                            Data mahasiswa tidak ditemukan
                        </div>
                    </td>
                </tr>
            `;
            dataList.append(empty);
        }
    }

    function resetPencarian(){
        $('#hasil-pencarian').hide();
        $('#file-excel').val('');
        $('#data-list').empty();
        $('#pagination').empty();
    }

    async function dataSearch() {
        const search = $('#search-input').val();
        const filter_tahun = $('#filter-tahun').val();
        if(search!==''){
            const res = await execAsync(
                `${base_url}/api/cari-beasiswa-mahasiswa`,
                "POST",
                token,
                {
                    page: page,
                    search: search,
                    filter_tahun: filter_tahun
                }
            );
            renderData(res);
        }else{
            resetPencarian();
        }
    }   

    $('#btn-search').click(function(){
        dataSearch(); 
    });

    $('#search-input').on('keydown', function(e){
        if(e.key === 'Enter'){
            e.preventDefault();
            dataSearch();
        }
    });    

    // Handle page change
    $(document).on('click', '.page-link', function() {
        page = $(this).data('page');
        dataSearch();
    });

    $('#btn-download-format').click(function(){
        window.location.replace(base_url+'/format/cek_penerima_beasiswa.xlsx');
    });

    $('#btn-upload').click(function(){
        $('#file-excel').val('');
        $('#file-excel').click();
        resetPencarian();
    });

    $('#file-excel').on('change', function(e){
        startLoading();
        const file = e.target.files[0];
        if(!file) return;
        const reader = new FileReader();
        reader.onload = function(e){
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type:'array'});
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const rows = XLSX.utils.sheet_to_json(sheet, {header:1});
            renderExcelPreview(rows);
            loadRiwayatBeasiswa();

            stopLoading();
            alert("Proses selesai");            
        };
        reader.readAsArrayBuffer(file);
    });

    function renderExcelPreview(rows){
        const dataList = $('#data-list');
        const hasil = $('#hasil-pencarian');
        dataList.empty();
        if(rows.length === 0) return;
        let nomor = 1;

        rows.slice(1).forEach(function(row){

            const nama = (row[1] ?? '').toString().trim();
            const nim = (row[2] ?? '').toString().trim();
            const program_studi = (row[3] ?? '').toString().trim();

            // skip jika salah satu kosong
            if(!nama || !nim || !program_studi){
                return;
            }

            const card = `
            <tr data-nim="${nim}">
                <td width="50px">
                    ${nomor++}
                </td>

                <td width="50%">
                    <h5 class="card-title mb-1 nama-mahasiswa">
                        ${nama}
                    </h5>
                    <div class="text-muted mb-2">
                        NIM: <span class="nim-mahasiswa">${nim}</span>
                    </div>
                    <div class="prodi-mahasiswa">
                        (${program_studi})
                    </div>
                </td>

                <td width="50%">
                    <div class="fw-bold mb-2">
                        Riwayat Beasiswa
                    </div>
                    <div class="text-muted riwayat-beasiswa">Memuat...</div>
                </td>
            </tr>
            `;

            dataList.append(card);
        });

        hasil.show();
    }


    function ambilSemuaNim(){
        const nims = [];
        $('#data-list tr').each(function(){
            const nim = $(this).data('nim');
            if(nim) nims.push(nim);
        });
        return nims;
    }

    function chunkArray(array, size){
        const result = [];
        for(let i = 0; i < array.length; i += size){
            result.push(array.slice(i, i + size));
        }
        return result;
    }

    async function loadRiwayatBeasiswa(){
        const filter_tahun = $('#filter-tahun').val();
        const nims = ambilSemuaNim();
        const batches = chunkArray(nims, 30);
        for(const batch of batches){
            const res = await execAsync(
                `${base_url}/api/cari-beasiswa-mahasiswa`,
                "POST",
                token,
                {
                    limit: 0,
                    nim: batch,
                    filter_tahun: filter_tahun
                }
            );
            if(!res?.data) continue;

            const mahasiswaMap = {};

            res.data.forEach(function(m){
                mahasiswaMap[m.nim] = m;
            });

            batch.forEach(function(nim){
                const tr = $(`#data-list tr[data-nim="${nim}"]`);
                const label_nama = tr.find('.nama-mahasiswa');
                const label_nim = tr.find('.nim-mahasiswa');
                const label_prodi = tr.find('.prodi-mahasiswa');
                const target = tr.find('.riwayat-beasiswa');
                const mahasiswa = mahasiswaMap[nim];

                target.empty();
                let html = '';  
                if(mahasiswa && mahasiswa.penerima && mahasiswa.penerima.length){
                    
                    label_nama.text(mahasiswa.nama);
                    label_nim.text(mahasiswa.nim);
                    label_prodi.text(`(${mahasiswa.program_studi})`);

                    html='<ul>';
                    mahasiswa.penerima.forEach(function(b){
                        html += `
                        <li class="border rounded p-2 mb-2 bg-light">
                            <div><strong>${b.nama ?? '-'}</strong></div>
                            <div>No SK: ${b.nomor_sk ?? '-'}</div>
                        </li>
                        `;
                    });
                    html+='</ul>';

                }else{
                    html = `<div class="text-danger">
                                Belum menerima beasiswa
                            </div>`;
                }
                target.append(html);
            });
        }
    }

    $("#copy-pencarian").click(function() {
        copyTable("table-pencarian");
    });

    $("#clear-pencarian").click(function() {
        resetPencarian();
    });

    function copyTable(elmnt) {
        var body = document.body,
            range, sel;
        var el = document.getElementById(elmnt);
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            range.selectNodeContents(el);
            sel.addRange(range);
        }
        document.execCommand("Copy");
        alert('sudah tercopy');
    }    

});

</script>
@endsection