

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
                    <a class="dropdown-item" href="#" id="btn-cetak-absen-tab4">
                        <i class="ti ti-printer"></i> Cetak Absen Peserta
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item" id="btn-generate-tab4">
                        <i class="ti ti-calendar"></i> Generate Jadwal
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item" id="btn-generate-peserta-tab4">
                        <i class="ti ti-user"></i> Generate Peserta Ujian
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

<!-- MULAI MODAL -->
<div class="modal fade modal-xl" id="modal-daftar-peserta-tab4" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form <span class="judul-modal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">


                <div class="accordion mb-2" id="accordionFormPenerima">               
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                Tambah Peserta Ujian
                            </button>
                        </h2>
                        <div id="collapseForm" class="accordion-collapse collapse" data-bs-parent="#accordionFormPenerima">
                            <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <input name="cari_peserta" id="cari_peserta" type="text" class="form-control" placeholder="cari nim nama mahasiswa ...">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Daftar</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input-peserta-tab4" placeholder="Cari..." style="max-width: 200px;" >
                        <button class="btn btn-secondary" id="btn-cari-peserta-tab4">
                            <i class="ti ti-search"></i>
                        </button>
                        <button class="btn btn-success" id="btn-refresh-peserta-tab4" >
                            <i class="ti ti-reload"></i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="solar:settings-linear" class="fs-5"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" id="btn-cetak-peserta-tab4">
                                        <iconify-icon icon="solar:printer-linear" class="me-2 fs-4"></iconify-icon> Cetak Peserta
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Nama/Nim</th>
                                <th width="15%">Fakultas</th>
                                <th width="15%">Program Studi</th>
                                <th width="20%">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-list-peserta">
                            <tr>
                                <td colspan="6">data tidak ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="nav-peserta">
                    <ul class="pagination justify-content-center" id="pagination-peserta"></ul>
                </nav>                            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
<!-- AKHIR MODAL -->

<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination-tab4"></ul>
</nav>

@push('scriptJs')
<script type="text/javascript">
    var page_tab4=1;
    var jadwal_ujian_id;
    var data_peserta_generate=null;

    function loadDataTab4() {
        const search_tab4 = $('#search-input-tab4').val();
        const url = `${endpoint_tab4}?page=${page_tab4}&search=${search_tab4}`;

        fetchData(url, function(response) {
            renderDataTab4(response);
        },true);
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
                                        <li><a class="dropdown-item btn-daftar-peserta-tab4" data-id="${dt.jadwal_ujian_id}" href="javascript:;"><i class="far fa-edit"></i> Daftar Peserta</a></li>
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
            if(confirm('apakah anda yakin generate peserta sesuai jadwal tersedia?')){
                await generatePesertaUjian();
            }
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

        function setProgress(percent) {
            $('.loading-percent').text(percent + '%');
        }

        async function generatePesertaUjian() {
            try {
                $(".loading-progress").fadeIn(200);
                isBatchProcess = true;
                jumlah_error = 0;
                jumlah_sukses = 0;
                const res = await execAsync(
                    `${base_url}/api/cari-peserta-ujian/${beasiswa_id}`,
                    'GET',
                    token
                );

                if (!res?.status || !res.data.length) {
                    throw new Error(res?.message || 'Data kosong');
                }

                const total = res.data.length;
                let done = 0;

                setProgress(0);
                for (const item of res.data) {
                    const res_ujian = await execAsync(`${base_url}/api/simpan-peserta-ujian/${beasiswa_id}`,'POST',token,
                                        {
                                            beasiswa_id: item.beasiswa_id,
                                            pendaftar_id: item.pendaftar_id
                                        }
                                    );
                    if(res_ujian.status)
                        jumlah_sukses++
                    else
                        jumlah_error++;
                    done++;
                    setProgress(Math.round((done / total) * 100));
                }
                setProgress(100);
                loadDataTab4();
                let pesan=`Generate ${jumlah_sukses} peserta ujian selesai`;
                let sukses = true;
                if(jumlah_error>0){
                    sukses = false;
                    pesan=`Terdapat sejumlah ${jumlah_error} peserta yang tidak bisa di generate`;
                }
                appShowNotification(sukses, [pesan]);
            } catch (err) {
                console.error(err);
                appShowNotification(false, [err.message || 'Terjadi kesalahan']);
            } finally {
                isBatchProcess = false;
                $(".loading-progress").fadeOut(300);
                $('.loading-percent').text('');
            }
        }

        async function hapusJadwal() {
            let url = `${base_url}/api/hapus-jadwal-ujian/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);

            if (!response) {
                appShowNotification(true, ['Berhasil dilakukan!']);
                loadDataTab4();
                return;
            }

            if(!response.status){
                appShowNotification(false,['terjadi kesalahan saat hapus jadwal']);
                return;
            }
        }

        async function hapusPeserta() {
            let url = `${base_url}/api/hapus-peserta-ujian/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            appShowNotification(true,['berhasil dilakukan!']);
            loadDataTab4();
        }
    
        $('#btn-cetak-absen-tab4').click(function(){
            const url = `${base_url}/cetak-absen-ujian/${beasiswa_id}`;
            window.open(url, '_blank');
        });

        $('#btn-cetak-peserta-tab4').click(function(){
            const url = `${base_url}/cetak-absen-ujian/${beasiswa_id}/${jadwal_ujian_id}`;
            window.open(url, '_blank');
        });

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
                
        $('#btn-cari-peserta-tab4').click(function(){
            loadDaftarPeserta();
        })

        $('#btn-refresh-peserta-tab4').click(function(){
            loadDaftarPeserta();
        })


        //hapus data
        $(document).on('click', '.btn-hapus-tab4', function() {
            const id = $(this).data('id');
            deleteData(endpoint_tab4, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataTab4();
            });
        });
        
        async function loadDaftarPeserta() {
            const search = $('#search-input-peserta-tab4').val();
            const response = await asyncFunction(`${base_url}/api/beasiswa/${beasiswa_id}/peserta-ujian?jadwal_ujian_id=${jadwal_ujian_id}&search=${search}`);
            renderDataPeserta(response);
        }

        function renderDataPeserta(response) {
            const dataList = $('#data-list-peserta');
            const pagination = $('#pagination-peserta');
            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {

                    // const kirim_wa = getWhatsAppLink(dt.is_mobile_dev, dt.no_hp, `_Bismillah_, ${dt.name.toLowerCase()}`);

                    const row = `<tr>
                                <td>${no++}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3 p-2">
                                        <img src="${base_url}/${dt.foto}" style="width: 70px; height: auto; border-radius: 2px;">
                                        <div>
                                            <div class="fw-bold">${dt.name}/ ${dt.nim}</div>
                                            <div>${dt.email}</div>
                                            <div>${dt.no_hp}</div>
                                            <div style="font-size:12px;font-style:italic;">Kota/Kab. ${dt.kabupaten}, Prov. ${dt.provinsi}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${dt.fakultas}</td>
                                <td>${dt.program_studi}</td>
                                <td></td>
                                <td>
                                    <button class="btn btn-danger btn-hapus-penerima-tab4" data-id="${dt.peserta_ujian_id}" type="button"><iconify-icon icon="solar:trash-bin-2-outline"></iconify-icon></button>
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


        $(document).on('click', '.btn-daftar-peserta-tab4', async function() {
            jadwal_ujian_id = $(this).data('id');
            const url = `${base_url}/api/beasiswa/${beasiswa_id}/peserta-ujian?jadwal_ujian_id=${jadwal_ujian_id}`;

            $('#collapseForm').collapse('hide');
            $('#cari_peserta').val('');
            
            const fModalForm = new bootstrap.Modal(document.getElementById('modal-daftar-peserta-tab4'), {
                keyboard: false
            });
            fModalForm.show();


            await loadDaftarPeserta(); 

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

        $(document).on('click', '.btn-hapus-penerima-tab4', function() {
            const id = $(this).data('id');
            deleteData(`${base_url}/api/beasiswa/${beasiswa_id}/peserta-ujian`, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDaftarPeserta();
                loadDataTab4();
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

        $("#cari_peserta").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: `${base_url}/api/cari-peserta-ujian/${beasiswa_id}`,
                    dataType: "json",
                    data: {
                        search: request.term,
                        limit: 5,
                    },
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    success: function(res) {
                        response($.map(res.data, function(item) {
                            return {
                                label: item.name, // fallback
                                value: item.name, // yang muncul di input setelah dipilih
                                data: {
                                    name: item.name,
                                    nim: item.nim,
                                    prodi: item.program_studi,
                                    fakultas: item.fakultas,
                                    foto_url: `${base_url}/${item.foto}`,
                                    user_id: item.user_id,
                                    pendaftar_id: item.pendaftar_id,
                                }
                            };
                        }));
                        
                    }
                });
            },
            minLength: 3,
            appendTo: "#modal-daftar-peserta-tab4",
            select: function(event, ui) {
                const mhs = ui.item.data;   
                // console.log(mhs);

                if (confirm(`Tambah mahasiswa atas nama ${mhs.name} NIM: ${mhs.nim} sebagai peserta ujian diruangan ini?`)) {
                    const dataPost = {
                        jadwal_ujian_id: jadwal_ujian_id,
                        pendaftar_id: mhs.pendaftar_id,
                    };
                    saveData(`${base_url}/api/beasiswa/${beasiswa_id}/peserta-ujian`, 'POST', $.param(dataPost), function(response) {
                        appShowNotification(true, ['berhasil dilakukan!']);
                        loadDaftarPeserta();
                        loadDataTab4();
                    });
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            const m = item.data;
            return $("<li>")
                .append(`
                    <div class="d-flex align-items-center gap-3 p-2">
                        <img src="${m.foto_url}" style="width: 50px; height: auto; border-radius: 4px;">
                        <div>
                            <div class="fw-bold">${m.name}</div>
                            <div class="text-muted">NIM: ${m.nim}</div>
                            <div class="text-muted">${m.prodi}</div>
                            <div class="text-muted">${m.fakultas}</div>
                        </div>
                    </div>
                `)
                .appendTo(ul);
        };


    });
</script>
@endpush