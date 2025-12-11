

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
                    <a href="javascript:;" class="dropdown-item" id="btn-tambah-tab4">
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
                        <i class="ti ti-trash"></i> Hapus Peserta Ujian
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


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form-tab4" role="dialog">
    <div class="modal-dialog">
        <form id="form-tab4">
            <input type="hidden" name="id" id="id-tab4" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input name="tanggal" id="tanggal-tab4" type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" required>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Sesi</label>
                            <input name="sesi" id="sesi-tab4" type="number" class="form-control" value="1" required>
                        </div>
                    </div>
                    <div class="row">
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Ruangan</label>
                            <select name="sesi_ujian_id" id="sesi_ujian_id-tab4" class="form-control" required></select>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Waktu Ujian</label>
                            <select name="ruangan_ujian_id" id="ruangan_ujian_id-tab4" class="form-control" required></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

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

    // source bisa  URL (otomatis fetch) atau langsung kirim object response
    async function optionSelect(element, source, valueKey, labelCallback) {
        let response;
        //struktur response harus {status:0/1,message:pesan,data:{}}
        if (typeof source === 'string') {
            response = await execAsync(source, 'GET', token);
        } 
        else if (typeof source === 'object') {
            response = source;
        } 
        else {
            console.error('optionSelect: parameter source tidak valid');
            return;
        }
        
        if (response && response.status) {
            loadOptionSelect(element, response, valueKey, labelCallback);
        }
    }

    // fungsi helper render <option>
    function loadOptionSelect(element, response, valueKey, labelCallback) {
        const $select = $(element);
        $select.empty();
        $select.append('<option value="">-- Pilih --</option>');
        const data = response.data || [];
        data.forEach((item) => {
            const value = item[valueKey];
            const label = typeof labelCallback === 'function'
                ? labelCallback(item)
                : item[labelCallback];
            $select.append(`<option value="${value}">${label}</option>`);
        });
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
                            <td>${dt.peserta_ujian_count}</td>
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

        $('#btn-generate-peserta-tab4').click(async function(){
            await simpanPesertaUjian();
        });

        $('#btn-hapus-jadwal-tab4').click(async function () {
            if (confirm('Apakah Anda yakin ingin menghapus semua jadwal?')) {
                const konfirm = prompt('Ketik "hapus" untuk mengonfirmasi penghapusan:');
                if (konfirm && konfirm.trim().toLowerCase() === 'hapus') {
                    await hapusJadwal();
                } else {
                    alert('Penghapusan dibatalkan.');
                }
            }
        });
        

        $('#btn-hapus-peserta-tab4').click(async function () {
            if (confirm('Apakah Anda yakin ingin menghapus semua peserta pada seleksi ini?')) {
                const konfirm = prompt('Ketik "hapus" untuk mengonfirmasi penghapusan:');
                if (konfirm && konfirm.trim().toLowerCase() === 'hapus') {
                    await hapusPeserta();
                } else {
                    alert('Penghapusan dibatalkan.');
                }
            }
        });

        $('#btn-refresh').click(function(){
            loadDataTab4();
        });

        async function generateJadwal() {
            let url = `${base_url}/api/generate-jadwal-ujian/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            if(response.status){
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataTab4();
            }
        }

        async function simpanPesertaUjian(){
            const url = `${base_url}/api/simpan-peserta-ujian`;
            const body = {
                beasiswa_id: beasiswa_id,
                pendaftar_id: 5
            };   

            const response = await execAsync(url, 'POST', token, body);
            if (response && response.status) {
                appShowNotification(true, ['berhasil dilakukan!']);
                loadDataTab4();
            }else{
                appShowNotification(false, [response?.message || 'Gagal dilakukan']);
            }
        }

        async function hapusJadwal() {
            let url = `${base_url}/api/hapus-jadwal-ujian/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            appShowNotification(true,['berhasil dilakukan!']);
            loadDataTab4();
        }

        async function hapusPeserta() {
            let url = `${base_url}/api/hapus-peserta-ujian/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            appShowNotification(true,['berhasil dilakukan!']);
            loadDataTab4();
        }
        
        $('#btn-tambah-tab4').click(function(){
            showModalForm();
        });

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

        $(document).on('click', '.btn-ganti-tab4', function() {
            const id = $(this).data('id');
            showDataById(endpoint_tab4, id, function(response) {
                $('#id-tab4').val(response.data.jadwal_ujian_id);
                $('#tanggal-tab4').val(response.data.tanggal);
                $('#sesi-tab4').val(response.data.sesi);
                $('#sesi_ujian_id-tab4').val(response.data.sesi_ujian_id);
                $('#ruangan_ujian_id-tab4').val(response.data.ruangan_ujian_id);
                showModalForm();
            });
        });
        
        function formReset(){
            $('#form-tab4').trigger('reset');
            $('#form input[type="hidden"]').val('');
            $('#tanggal-tab4').val(tgl_hari_ini);
            $('#sesi-tab4').val('1');
        }

        //untuk show modal form
        function showModalForm() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form-tab4'), {
                keyboard: false
            });
            fModalForm.show();
        }

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-tab4").validate({
            submitHandler: function(form) {
                const id = $('#id-tab4').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint_tab4 : endpoint_tab4 + '/' + id;

                const formData = $(form).serialize() + `&beasiswa_id=${beasiswa_id}`;
                saveData(url, type, formData, function(response) {
                    //jika berhasil
                    appShowNotification(true,['berhasil dilakukan!']);
                    // if(type=='POST'){
                    //     formReset();
                    // }
                    loadDataTab4();
                });
            }
        });


    });
</script>
@endpush