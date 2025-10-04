

<div class="d-sm-flex d-block align-items-center justify-content-between mb-3">

    <div></div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control" id="search-input-tab4" placeholder="Cari..." style="max-width: 200px;">
        <button class="btn btn-primary" id="btn-search-tab4">
            <i class="ti ti-search"></i>
        </button>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <iconify-icon icon="solar:settings-linear" class="fs-5"></iconify-icon>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript;;" class="dropdown-item" id="btn-tambah-tab4">
                        <i class="ti ti-plus"></i> Tambah
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item" id="btn-generate-tab4">
                        <i class="ti ti-calendar"></i> Generate Jadwal
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item" id="btn-generate-peserta-tab4">
                        <i class="ti ti-calendar"></i> Generate Peserta Ujian
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item" id="btn-hapus-jadwal-tab4">
                        <i class="ti ti-trash"></i> Hapus Jadwal Ujian
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item" id="btn-hapus-peserta-tab4">
                        <i class="ti ti-trash"></i> Hapus Peseta Ujian
                    </a>
                </li>
            </ul>
        </div>
        <button class="btn btn-success" id="btn-refresh">
            <i class="ti ti-reload"></i>
        </button>
        <button class="btn btn-secondary" id="btn-filter">
            <i class="ti ti-filter"></i>
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Tanggal</th>
                <th width="8%">Sesi</th>
                <th width="20%">Waktu</th>
                <th width="20%">Ruangan</th>
                <th width="15%">Peserta</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list-tab4">
        </tbody>
    </table>
</div>
<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination-tab4"></ul>
</nav>

@push('scriptJs')
<script type="text/javascript">
    const endpoint_tab4 = base_url+'/api/jadwal-ujian'
    var page_tab4=1;

    function loadDataTab4() {
        const search_tab4 = $('#search-input-tab4').val();
        const url = `${endpoint_tab4}?beasiswa_id=${beasiswa_id}&page=${page_tab4}&search=${search_tab4}`;

        fetchData(url, function(response) {
            renderDataTab4(response);
        },true);
    }

    function renderDataTab4(response) {
        const dataList = $('#data-list-tab4');
        const pagination = $('#pagination-tab4');
        const data=response.data.data;
        let no = (response.data.current_page - 1) * response.data.per_page + 1;
        dataList.empty();
        pagination.empty();
        if (data.length > 0) {
            $.each(data, function(index, dt) {
                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.tanggal}</td>
                            <td>Sesi ${dt.sesi}</td>
                            <td>${dt.jam_mulai} sd ${dt.jam_selesai}</td>
                            <td>${dt.ruangan}/ ${dt.gedung}</td>
                            <td>0</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-ganti-tab4" data-id="${dt.jadwal_ujian_id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                        <li><a class="dropdown-item btn-hapus-tab4" data-id="${dt.jadwal_ujian_id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
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
        $('#btn-generate-tab4').click(async function(){
            if(confirm('apakah anda yakin generate jadwal sesuai pengaturan dasar, ruangan dan sesi?')){
                await generateJadwal();
            }
        });

        async function generateJadwal() {
            let url = `${base_url}/api/generate-jadwal-ujian/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            if(response.status){
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataTab4();
            }
        }

        $('#btn-search-tab4').click(function(){
            page_tab4=1;
            loadDataTab4();
        });

       // Handle page change
        $(document).on('click', '#pagination-tab4 .page-link', function() {
            page_tab4 = $(this).data('page');
            loadDataTab4();
        });        


        //hapus data
        $(document).on('click', '.btn-hapus-tab4', function() {
            const id = $(this).data('id');
            deleteData(endpoint_tab4, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataTab4();
            });
        });
    });
</script>
@endpush