@extends('template')

@section('scriptHead')
<title>Butir Kegiatan</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold">Monitoring Beasiswa</h5>
        <div class="d-flex flex-wrap gap-1 mb-3">

            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="label_monitoring" data-bs-toggle="dropdown" aria-expanded="false" data-id="">
                    Pilih monitoring
                </button>
                <ul class="dropdown-menu p-2" aria-labelledby="label_monitoring" style="max-height:200px; overflow:auto; min-width: 250px;">
                    <!-- Filter input -->
                    <li style="position: sticky; top: 0; background: #fff; z-index:1; padding: .5rem;">
                    <input type="text" id="filter_monitoring" 
                            name="filter_monitoring" 
                            class="form-control" 
                            placeholder="Cari monitoring...">
                    <hr class="dropdown-divider mt-2 mb-0">
                    </li>
                    <div id="list_monitoring">
                    </div>
                </ul>
            </div>


            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="label_kegiatan" data-bs-toggle="dropdown" aria-expanded="false" data-id="">
                    Pilih kegiatan
                </button>
                <ul class="dropdown-menu p-2" aria-labelledby="label_kegiatan" style="max-height:200px; overflow:auto; min-width: 250px;">
                    <div id="list_kegiatan"></div>
                </ul>
            </div>

            <div>
                <button class="btn btn-primary" id="btn-tambah-kegiatan" disabled>
                    <i class="ti ti-plus"></i>
                </button>
                <button class="btn btn-primary" id="btn-ganti-kegiatan" disabled>
                    <i class="ti ti-edit"></i>
                </button>
                <button class="btn btn-primary" id="btn-hapus-kegiatan" disabled>
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Butir Kegiatan</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;" disabled>
                <button class="btn btn-primary" id="btn-search" disabled>
                    <i class="ti ti-search"></i>
                </button>
                <button class="btn btn-primary" id="btn-tambah" disabled>
                    <i class="ti ti-plus"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh" disabled>
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter" disabled>
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="45%">Nama</th>
                        <th width="15%">Tingkat/ Partisipasi/ Jabatan/ Prestasi</th>
                        <th width="15%">Bukti</th>
                        <th width="10%">Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="8">data tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>

    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="form-kegiatan">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Urut</label>
                            <input name="urut" id="urut" type="number" class="form-control">
                        </div>
						<div class="col-lg-8 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Skor Minimal</label>
                            <input name="nilai_minimal" id="nilai_minimal" type="number" class="form-control" required>
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
<div class="modal fade modal" id="modal-butir-kegiatan" role="dialog">
    <div class="modal-dialog">
        <form id="form-butir-kegiatan">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Butir Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Urut</label>
                            <input name="urut" id="urut" type="number" class="form-control">
                        </div>
						<div class="col-lg-8 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						

						<div class="col-lg-6 mb-3">
                            <label class="form-label">Tingkat</label>
                            <select name="tingkat_id" id="tingkat_id" class="form-control">
                            </select>
                        </div>

    

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Partisipasi/ Jabatan/ Prestasi</label>
                            <select name="pjp_id" id="pjp_id" class="form-control">
                            </select>
                        </div>


						<div class="col-lg-5 mb-3">
                            <label class="form-label">Bukti</label>
                            <input name="bukti" id="bukti" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Nilai</label>
                            <input name="nilai" id="nilai" type="number" class="form-control" required>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Contoh format</label>
                            <input type="file" name="path_format" id="path_format" class="form-control" >
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control" ></textarea>
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
@endsection

@section('scriptJs')
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/fakultas';
    var page = 1;
    var data_referensi;
    var kegiatan_id;
    $(document).ready(function() {
        initPage()

        statusKegiatan(false);
        async function initPage() { // agar di load secara berurutan
            await loadReferensi();
            await loadMonitoring();
            await loadOptionSelect("#tingkat_id", "Tingkat", data_referensi);
            await loadOptionSelect("#pjp_id", "Partisipasi/ Jabatan/ Prestasi", data_referensi);
        }

        async function loadReferensi() {
            const url_referensi = base_url + '/api/data-referensi?limit=300&grup[]=Partisipasi/ Jabatan/ Prestasi&grup[]=Tingkat';
            const respon = await execAsync(url_referensi,"GET", token);
            data_referensi=respon.data.data;
        }        

        function statusKegiatan(aktif = true) {
            // false = disable, true = enable
            $('#btn-tambah-kegiatan').prop('disabled', !aktif);
            $('#btn-ganti-kegiatan').prop('disabled', !aktif);
            $('#btn-hapus-kegiatan').prop('disabled', !aktif);
        }

        function statusButirKegiatan(aktif = true) {
            // false = disable, true = enable
            $('#btn-search').prop('disabled', !aktif);
            $('#search-input').prop('disabled', !aktif);
            $('#btn-tambah').prop('disabled', !aktif);
            $('#btn-refresh').prop('disabled', !aktif);
            $('#btn-filter').prop('disabled', !aktif);
        }

        $(document).on("click", ".dropdown-item", function (e) {
            e.preventDefault();
            kegiatan_id = $(this).data("val"); // ambil data-val
        });        

        async function loadMonitoring() {
            let search = $('#filter_monitoring').val();
            let respon = await asyncFunction(`${base_url}/api/monitoring?limit=10&search=${search}`);
            const list = $('#list_monitoring').empty();

            if(respon.data.total>0)
                $.each(respon.data.data, function(index, item) {
                    list.append(`
                        <li>
                            <a class="dropdown-item" href="#" data-val="${item.id}">
                                ${item.nama}
                            </a>
                        </li>
                    `);
                });
        }

        async function loadDataKegiatan(set_default=true){
            const monitoring_id = $('#label_monitoring').attr("data-id");
            const respon = await asyncFunction(`${base_url}/api/kegiatan?monitoring_id=${monitoring_id}`);
            const list = $('#list_kegiatan').empty();
            statusButirKegiatan(false);

            if(set_default){
                $('#label_kegiatan').text("Pilih kegiatan").append(' <span class="caret"></span>'); // caret Bootstrap
                $('#label_kegiatan').attr("data-id","");
            }

            if(respon.data.total>0){
                $.each(respon.data.data, function(index, item) {
                    list.append(`
                        <li>
                            <a class="dropdown-item" href="#" data-val="${item.id}">
                                ${item.nama}
                            </a>
                        </li>
                    `);
                });
            }else{
                list.append(`
                    <div class="alert alert-danger" role="alert">
                        Kegiatan tidak ditemukan!
                    </div>                
                `);                
            }
        }

        async function loadDataButirKegiatan(){
            const kegiatan_id = $('#label_kegiatan').attr("data-id");
            const dataList = $('#data-list');
            const pagination = $('#pagination');
            const search = $('#search-input').val();
            const response = await asyncFunction(`${base_url}/api/butir-kegiatan?kegiatan_id=${kegiatan_id}&search=${search}&page=${page}&limit=2`);

            const data=response.data.data;
            let no = (response.data.current_page - 1) * response.data.per_page + 1;
            dataList.empty();
            
            pagination.empty();
            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const tingkat=(dt.tingkat)?dt.tingkat.nama:"";
                    const pjp=(dt.pjp)?dt.pjp.nama:"";
                    const label_urut=(dt.urut)?`<span class="badge text-bg-primary">${dt.urut}</span>`:"";

                    var contoh_format=``;
                    if(dt.path_format){
                        contoh_format=`<div class="mt-1">
                                            <a href="${base_url}/${dt.path_format}" target="_blank"><span class="badge text-bg-info">contoh format</span></a>
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-hapus-contoh" data-id="${dt.id}"><i class="ti ti-trash"></i></button>
                                        </div>`;
                    }
                    const row = `<tr>
                                <td>${no++}</td>
                                <td>${dt.nama} ${label_urut} ${contoh_format}</td>
                                <td>${tingkat} ${pjp}</td>
                                <td>${showText(dt.bukti)}</td>
                                <td>${dt.nilai}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-ganti" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                            <li><a class="dropdown-item btn-hapus" data-id="${dt.id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
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
            statusButirKegiatan(true);
        }

        // Filter opsi saat mengetik
        // $('#filter_monitoring').on('input', async function() {
        //     await loadMonitoring();
        // });

        $('#filter_monitoring').on('keypress', async function(e) {
            if (e.which === 13) {       // 13 = Enter
                e.preventDefault();      
                await loadMonitoring(); 
            }
        });

        $(document).on('click', '#list_monitoring .dropdown-item', function(e) {
            e.preventDefault();
            const text = $(this).text();
            const val  = $(this).data('val');

            $('#label_monitoring').text(text).append(' <span class="caret"></span>'); // caret Bootstrap
            $('#label_monitoring').attr("data-id",val);

            statusKegiatan();
            loadDataKegiatan();

        });        


        $(document).on('click','.btn-hapus-contoh',function(){
            const id=$(this).attr('data-id');
            if(id!=="")
                deleteData(base_url+'/api/hapus-contoh-format-laporan', id, function() {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataButirKegiatan();
                });
        })

        $(document).on('click', '#list_kegiatan .dropdown-item', function(e) {
            e.preventDefault();
            const text = $(this).text();
            const val  = $(this).data('val');
            // Ganti teks tombol
            $('#label_kegiatan').text(text).append(' <span class="caret"></span>'); // caret Bootstrap
            $('#label_kegiatan').attr("data-id",val);

            loadDataButirKegiatan();
        });        


        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            loadDataButirKegiatan();
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            loadDataButirKegiatan();
        });

        $('#btn-search').click(function(){
            page=1;
            loadDataButirKegiatan();
        });


        //untuk show modal form
        function showModalFormKegiatan() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        //untuk show modal form
        function showModalFormButirKegiatan() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-butir-kegiatan'), {
                keyboard: false
            });
            fModalForm.show();
        }


        function formKegiatanReset(){
            $('#form-kegiatan').trigger('reset');
            $('#form-kegiatan input[type="hidden"]').val('');
        }

        function formButirKegiatanReset(){
            $('#form-butir-kegiatan').trigger('reset');
            $('#path_format').val("");
            $('#form-butir-kegiatan input[type="hidden"]').val('');
        }

        // Handle page change
        $('#btn-tambah-kegiatan').click(function() {
            formKegiatanReset();
            showModalFormKegiatan();    
        });

        $('#btn-tambah').click(function() {
            formButirKegiatanReset();
            showModalFormButirKegiatan();
        });
        

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-kegiatan").validate({
            submitHandler: function(form) {
                const id = $('#form-kegiatan #id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? '/api/kegiatan' : '/api/kegiatan/' + id;
                let dataArr = $(form).serializeArray();
                dataArr.push({
                    name: 'monitoring_id',
                    value: $('#label_monitoring').attr('data-id')
                });
                dataArr.push({
                    name: 'kegiatan_id',
                    value: kegiatan_id
                });
                
                let dataPayload = $.param(dataArr);

                saveData(base_url+url, type, dataPayload, function(response) {                    
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(type=='POST'){
                        formKegiatanReset();
                        loadDataKegiatan();
                    }else{
                        loadDataKegiatan(false);
                        $('#label_kegiatan').text(response.data.nama).append(' <span class="caret"></span>');
                        $('#label_kegiatan').attr("data-id",response.data.id);
                    }

                });
            }
        });


        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-butir-kegiatan").validate({
            submitHandler: function(form) {
                // const id = $('#form-butir-kegiatan #id').val();
                // const type = (id === '') ? 'POST' : 'PUT';
                // const url = (id === '') ? '/api/butir-kegiatan' : '/api/butir-kegiatan/' + id;
                // let dataArr = $(form).serializeArray();
                // dataArr.push({
                //     name: 'kegiatan_id',
                //     value: $('#label_kegiatan').attr('data-id')
                // });
                // let dataPayload = $.param(dataArr);

                // saveData(base_url+url, type, dataPayload, function(response) {                    
                //     appShowNotification(true,['berhasil dilakukan!']);
                //     if(type=='POST'){
                //         formButirKegiatanReset();
                //     }
                //     loadDataButirKegiatan();
                // });

                const id = $('#form-butir-kegiatan #id').val();
                const url = (id === '') ? '/api/butir-kegiatan' : '/api/butir-kegiatan/' + id;

                var formData = new FormData(form);
                formData.append("kegiatan_id", $('#label_kegiatan').attr('data-id'));

                var is_insert=true;
                if((id !== '')){
                    is_insert=false;
                    formData.append("_method", "put");
                }

                saveData(url, 'POST', formData, function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(is_insert){
                        formButirKegiatanReset();
                    }
                    loadDataButirKegiatan();
                });

            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            $('#path_format').val("");
            showDataById(base_url+'/api/butir-kegiatan', id, function(response) {
                if(response.status){
                    $('#form-butir-kegiatan #id').val(response.data.id);
                    $('#form-butir-kegiatan #urut').val(response.data.urut);
                    $('#form-butir-kegiatan #nilai').val(response.data.nilai);
                    $('#form-butir-kegiatan #bukti').val(response.data.bukti);
                    $('#form-butir-kegiatan #keterangan').val(response.data.keterangan);
                    $('#form-butir-kegiatan #tingkat_id').val(response.data.tingkat_id);
                    $('#form-butir-kegiatan #pjp_id').val(response.data.pjp_id);
                    $('#form-butir-kegiatan #nama').val(response.data.nama);
                    showModalFormButirKegiatan();
                }
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            if(id!=="")
                deleteData(base_url+'/api/butir-kegiatan', id, function() {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataButirKegiatan();
                });
        });


        $('#btn-ganti-kegiatan').click(function() {            
            const id = $('#label_kegiatan').attr("data-id");
            if(id!==""){
                showDataById(base_url+'/api/kegiatan', id, function(response) {
                    $('#form-kegiatan #id').val(response.data.id);
                    $('#form-kegiatan #urut').val(response.data.urut);
                    $('#form-kegiatan #nama').val(response.data.nama);
                    $('#form-kegiatan #nilai_minimal').val(response.data.nilai_minimal);
                    showModalFormKegiatan();
                });                
            }
        });

        //hapus data
        $('#btn-hapus-kegiatan').click(function() {            
            const id = $('#label_kegiatan').attr("data-id");
            if(id!=="")
                deleteData(base_url+'/api/kegiatan', id, function() {
                    appShowNotification(true,['berhasil dilakukan!']);
                    loadDataKegiatan();
                });
        });

    });
</script>
@endsection