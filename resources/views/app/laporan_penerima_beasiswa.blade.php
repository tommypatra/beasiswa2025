@extends('template')

@section('scriptHead')
<title>Laporan Penerima Beasiswa</title>
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
<style>
    #daftar-verifikator {
        list-style-type: disc;
        padding-left: 1.5rem; /* atau sesuaikan */
        margin-left: 0;        /* opsional */
    }
</style>
@endsection

@section('container')

<h4 id="nama-sk">SK Penerima Beasiswa</h4>
<div id="detail-sk" class="mb-2"></div>
<div id="daftar-tombol" class="mb-3"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Laporan Penerima Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;" >
                <button class="btn btn-success" id="btn-refresh-sk" >
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>
        
        <input type="file" name="path" id="path" style="display:none" accept="application/pdf,image/*">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Item</th>
                        <th width="20%">Bukti Dokumen</th>
                        <th width="30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="4">data tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation" class="nav-sk">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>

    </div>
</div>



@endsection

@section('scriptJs')
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = `${base_url}/api/laporan`;
    var page_sk = 1;
    var sk_penerima_id = "{{ $sk_penerima_id }}";
    var penerima_id;
    var kegiatan_id;

    async function loadDataSK() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/get-data-sk-beasiswa/${sk_penerima_id}`);
        if(response.status){
            data=response.data;
            penerima_id=data.penerima[0].id;
            $('#nama-sk').html(`${data.nama}`);
            $('#detail-sk').html(`Nomor SK : ${data.nomor_sk} / Tanggal : ${data.tanggal_sk}`);
            renderTombolKegiatan(data.monitoring.kegiatan);
        }
    }

    function renderTombolKegiatan(tombol){
        let html=``;
        if(tombol.length>0){
            html+=`<div class="btn-group" role="group" aria-label="Basic radio toggle button group">`;
            $.each(tombol, function(index, dt) {
                let active ='';    
                if(index==0){
                    active='checked';
                    kegiatan_id=dt.id;
                }
                html+=` <input type="radio" class="btn-check daftar-kegiatan" name="kegiatan-btn" id="btnradio-${index}" value="${dt.id}" ${active}>
                        <label class="btn btn-outline-primary" for="btnradio-${index}">${dt.nama}</label>`;
            });
            html+=`</div>`;
        }
        $('#daftar-tombol').html(html);
    }

    $(document).on('change','.daftar-kegiatan', function() {
        kegiatan_id = $('.daftar-kegiatan:checked').val();
        loadSubKegiatan();
    });

    async function loadSubKegiatan() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/laporan-mahasiswa/${kegiatan_id}?search=${search}`);
        if(response.status){
            renderData(response);
        }
    }

    function renderData(response) {
        const dataList = $('#data-list');
        const pagination = $('#pagination');
        const data=response.data.data;
        let no = (response.data.current_page - 1) * response.data.per_page + 1;
        dataList.empty();
        pagination.empty();
        if (data.length > 0) {
            $.each(data, function(index, dt) {
                var laporan_upload = `<span class="badge text-bg-danger fs-2">belum upload</span>`;
                if(dt.laporan.length>0){
                    laporan_upload=`<h4>Daftar Dokumen</h4>`;
                    laporan_upload+=`<ul>`;
                    $.each(dt.laporan, function(index, dr) {
                        const filename = dr.path.split('/').pop();
                        var tombol =`<span class="btn-group" role="group" >
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-hapus-laporan" data-id="${dr.id}"><i class="ti ti-trash"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-finalisasi-laporan" data-id="${dr.id}"><i class="ti ti-key"></i></button>
                                    </span>
                                    <span class="badge rounded-pill text-bg-danger fs-2">belum finalisasi</span>`;
                        if(dr.is_kirim){
                            var verifikasi_hasil = '<span class="badge rounded-pill text-bg-warning fs-2">belum diverifikasi</span>';
                            if(dr.verifikasi_hasil){
                                verifikasi_hasil=(dr.verifikasi_hasil==1)?`<span class="badge rounded-pill text-bg-success fs-2">MS</span>`:`<span class="badge rounded-pill text-bg-info fs-2">Final</span>`;
                            }
                            tombol = verifikasi_hasil;
                        }

                        laporan_upload+=`
                            <li class="mb-2">
                                ${dr.keterangan}
                                <div>
                                    <a href="${base_url}/${dr.path}" target="_blank"><i class="ti ti-download"></i> ${filename}</a>
                                    ${tombol}
                                </div>
                            </li>`;                    
                    });
                    laporan_upload+=`</ul>`;
                }

                const contoh_format = (dt.path_format)?`<a href="${base_url}/${dt.path_format}" target="_blank" class="btn btn-sm btn-outline-primary">download contoh format</a>`:``;

                const row = `<tr>
                                <td>${no++}</td>
                                <td>${dt.nama}</td>
                                <td>${dt.bukti}</td>
                                <td>
                                    ${dt.keterangan}
                                    <div>${contoh_format}</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="mb-2">                                        
                                        <button class="btn btn-outline-primary btn-sm btn-upload-file" data-sub_kegiatan_id="${dt.id}" data-penerima_id="${penerima_id}">
                                            <i class="ti ti-upload"></i> Upload ${dt.nama}  
                                        </button>   
                                    </div>
                                    <div>${laporan_upload}</div>
                                </td>
                            </tr>`;
                dataList.append(row);
            });
            renderPagination(response.data, pagination);
        }else{
            const row = `<tr>
                            <td colspan="8">data tidak ditemukan</td>
                        </tr>`;
            dataList.append(row);                
        }
    }    

    $(document).ready(function() {
        initPage();

        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        async function initPage() { // agar di load secara berurutan
            await loadDataSK();
            await loadSubKegiatan();
        }

        $('#search-input').on('keypress', async function(e) {
            if (e.which === 13) {       // 13 = Enter
                e.preventDefault();      
                await loadDataSK(); 
            }
        });
        
        // Handle page change
        $(document).on('click', '.nav-sk .page-link', function() {
            page_sk = $(this).data('page');
            loadDataSK();
        });

        $(document).on('click','.btn-hapus-laporan', function(){
            const id=$(this).attr('data-id');
            deleteData(`${endpoint}`, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadSubKegiatan();
            });
        });

        $(document).on('click','.btn-finalisasi-laporan', async function(){
            const id=$(this).attr('data-id');
            if(confirm("yakin finalisasi upload dokumen ini ?")){
                const response = await execAsync(`${base_url}/api/finalisasi-laporan/${id}`, 'GET', token);
                if(response.status){
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadSubKegiatan();
                }
            }
        });

        $('#btn-refresh-sk').click(function() {
            loadDataSK();
        });

        $(document).on("click", ".btn-upload-file", function () {
            const subKegiatanId = $(this).data("sub_kegiatan_id");
            const penerimaId = $(this).data("penerima_id");

            $("#path")
                .off("change")
                .on("change", function (e) {
                    let path = e.target.files[0];
                    if (!path) return;

                    let keterangan = "";
                    while (!keterangan) {
                        keterangan = prompt("Masukkan keterangan (wajib diisi):");
                        if (keterangan === null) return;
                    }

                    let formData = new FormData();
                    formData.append("path", path);
                    formData.append("sub_kegiatan_id", subKegiatanId);
                    formData.append("penerima_id", penerimaId);
                    formData.append("keterangan", keterangan);

                    saveData(endpoint, 'POST', formData, function(response) {
                        appShowNotification(true, ['Upload berhasil!']);
                        loadSubKegiatan();
                    });

                })
                .click();
        });




    });

</script>
@endsection