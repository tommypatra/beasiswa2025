

<div class="d-sm-flex d-block align-items-center justify-content-between mb-3">

    <div></div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control" id="search-input-tab6" placeholder="Cari..." style="max-width: 200px;">
        <button class="btn btn-primary" id="btn-search-tab6">
            <i class="ti ti-search"></i>
        </button>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <iconify-icon icon="solar:settings-linear" class="fs-5"></iconify-icon>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#" id="btn-cetak-absen-tab6">
                        <i class="ti ti-printer"></i> Cetak Absen Peserta
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" id="btn-export-tab6">
                        <i class="ti ti-arrow-up"></i> Export Data
                    </a>
                </li>
            </ul>
        </div>
        <button class="btn btn-success" id="btn-refresh-tab6">
            <i class="ti ti-reload"></i>
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="40%">Nama/ Nim/ Prodi</th>
                <th width="40%">Waktu Ujian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list-tab6">
        </tbody>
    </table>
</div>


<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination-tab6"></ul>
</nav>

@push('scriptJs')
<script type="text/javascript">
    var page_tab6=1;
    var jadwal_ujian_id;
    var data_peserta_generate=null;

    function loadDataTab6() {
        const search_tab6 = $('#search-input-tab6').val();
        const url = `${endpoint_tab6}?page=${page_tab6}&search=${search_tab6}`;

        fetchData(url, function(response) {
            renderDatatab6(response);
        },true);
    }

    function renderDatatab6(response) {
        const dataList = $('#data-list-tab6');
        const pagination = $('#pagination-tab6');
        const data=response.data.data;
        let no = (response.data.current_page - 1) * response.data.per_page + 1;
        dataList.empty();
        pagination.empty();
        if (data.length > 0) {
            $.each(data, function(index, dt) {
                const row = `<tr>
                            <td>${no++}</td>
                            <td>
                                ${dt.name}/ 
                                <div>NIM. ${dt.nim}</div>
                                <i>${dt.fakultas} - ${dt.program_studi}</i>
                            </td>
                            <td>
                                SESI - ${dt.jadwal_ujian.sesi} 
                                <div>
                                    ${formatTanggal(dt.jadwal_ujian.tanggal)}
                                </div>
                                <div>
                                    ${dt.jadwal_ujian.nama}
                                </div>
                                <div>
                                    ${dt.sesi_ujian.jam_mulai} s/d ${dt.sesi_ujian.jam_selesai}
                                </div>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-print-kartu-ujian-tab4" data-url_id="${dt.url_id}" data-id="${dt.beasiswa_id}" href="javascript:;"><i class="far fa-print"></i> Cetak Kartu Ujian</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>`;
                dataList.append(row);
            });
            renderPagination(response.data, pagination);
        }else{
            const row = `<tr>
                            <td colspan="7">data tidak ditemukan</td>
                        </tr>`;
            dataList.append(row);                
        }
    }    

    $(document).ready(function() {

        $('#btn-refresh-tab6').click(function(){
            loadDataTab6();
        });
    
        $('#btn-cetak-absen-tab6').click(function(){
            const url = `${base_url}/cetak-absen-ujian/${beasiswa_id}`;
            window.open(url, '_blank');
        });

        $('#btn-export-tab6').click(function(){
            const url = `${base_url}/cetak-absen-ujian/${beasiswa_id}/null/export`;
            window.open(url, '_blank');
        });

        $('#btn-search-tab6').click(function(){
            page_tab6=1;
            loadDataTab6();
        });

       // Handle page change
        $(document).on('click', '#pagination-tab6 .page-link', function() {
            page_tab6 = $(this).data('page');
            loadDataTab6();
        }); 
                

    });
</script>
@endpush