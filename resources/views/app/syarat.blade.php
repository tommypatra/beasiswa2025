@extends('template')

@section('scriptHead')
<title>Syarat Dokumen Upload</title>
<style>
.form-instrumen-opsi ul {
    list-style-type: disc;   /* pakai bullet titik */
    padding-left: 1.5rem;    /* kasih indentasi biar rapi */
    margin: 0.5rem 0;
}

.form-instrumen-opsi li {
    margin-bottom: 0.25rem; /* spasi antar list */
}

</style>
@endsection

@section('container')
<div class="d-flex align-items-center mb-2">
    <iconify-icon icon="solar:checklist-linear" class="fs-9"></iconify-icon> <h2 class="mb-0 ms-2">Syarat</h2>
</div>

<div id="label-beasiswa" class="mb-2"></div>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Syarat Dokumen Upload</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                        <button class="btn btn-primary" id="btn-tambah">
                            <i class="ti ti-plus"></i>
                        </button>
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
                                <th width="25%">Nama/Jenis</th>
                                <th width="25%">Deskripsi</th>
                                {{-- <th width="10%">Wajib</th> --}}
                                {{-- <th width="20%">Beasiswa</th> --}}
                                <th width="10%">Status Isian</th>
                                <th width="10%">Skor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-list">
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        @include('app/menu_beasiswa')
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="form">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Beasiswa</label>
                            <select name="beasiswa_id" id="beasiswa_id"  class="form-control" required></select>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Bobot Skor</label>
                            <input name="bobot" id="bobot" type="number" class="form-control" required>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" id="jenis"  class="form-control" required>
                                <option value="pdf">PDF</option>
                                <option value="image">Gambar</option>
                            </select>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Wajib</label>
                            <select name="is_wajib" id="is_wajib"  class="form-control" required>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Instrumen Opsi</label>
                            <textarea name="instrumen_opsi" id="instrumen_opsi" rows="3" class="form-control"></textarea>
                            <small class="text-muted d-block mt-1 form-instrumen-opsi">
                                <ul class="mb-0 ps-3 ">
                                    <li>Pemisah pilihan gunakan tanda koma (<code>,</code>).</li>
                                    <li>Skor tertinggi diberikan pada pilihan pertama.</li>
                                    <li>Skor menurun sesuai urutan penulisan.</li>
                                    <li>
                                        Contoh:
                                        <code>Sangat Sesuai, Sesuai, Cukup Sesuai, Kurang Sesuai, Tidak Sesuai</code>
                                        <code>Ada, Tidak Ada</code>
                                    </li>
                                </ul>
                            </small>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" required></textarea>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Contoh</label>
                            <input class="form-control" type="file" id="contoh" name="contoh">
                        </div>
						<div class="col-lg-5 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_aktif" id="is_aktif"  class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
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
    const beasiswa_id = '{{ $beasiswa_id }}';
    const endpoint = base_url+'/api/syarat';
    const tahun = "{{ date('Y') }}";
    var page = 1;
    
    async function initPage() { // agar di load secara berurutan
    }

    $(document).ready(function() {
        initPage();
        dataLoad();

        async function initPage() {
            await loadDataSelect('#beasiswa_id', `data-beasiswa?tahun=${tahun}&limit=100`);
            await loadDataBeasiswa();
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);
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
                    const contoh=(dt.contoh)?`<a href="${base_url}/${dt.contoh}" target="_blank"><span class="badge rounded-pill border border-muted fw-bold text-muted fs-2 py-1">Contoh</span></a>`:"";
                    const row = `<tr>
                                <td>${no++}</td>
                                <td>
                                    ${dt.nama}/ ${dt.jenis}
                                    <div>${contoh}</div>
                                </td>
                                <td>${dt.deskripsi}</td>
                                <td>${(dt.is_wajib)?'Wajib':'Tidak Wajib'}</td>
                                <td>${showText(dt.bobot)}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-ganti" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:clapperboard-edit-outline" class=""></iconify-icon> Ganti</a></li>
                                            <li><a class="dropdown-item btn-hapus" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:trash-bin-trash-outline" class=""></iconify-icon> Hapus</a></li>
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

        function dataLoad() {
            var search = $('#search-input').val();
            var url = endpoint + '?beasiswa_id='+beasiswa_id+'&page=' + page + '&search=' + search + '&limit=' + vLimit;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            dataLoad();
        });

        // Handle search-input
        $(document).on('input', '#search-input', function() {
            console.log('Event input berjalan');
            dataLoad();
        });        

        //untuk show modal form
        function showModalForm() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        function formReset(){
            $('#form').trigger('reset');
            $('#form input[type="hidden"]').val('');
            $('#contoh').val('');
            $('#instrumen_opsi').val('');
            $('#beasiswa_id').val(beasiswa_id);
        }

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const url = (id === '') ? endpoint : endpoint + '/' + id;

                var formData = new FormData(form);
                if((id !== '')){
                    formData.append("_method", "put");
                }

                saveData(url, 'POST', formData, function(response) {
                    //jika berhasil
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(id===''){
                        formReset();
                    }
                    dataLoad();
                });
            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#beasiswa_id').val(response.data.beasiswa_id);
                $('#nama').val(response.data.nama);
                $('#jenis').val(response.data.jenis);
                $('#is_wajib').val(response.data.is_wajib);
                $('#bobot').val(response.data.bobot);
                $('#instrumen_opsi').val(response.data.instrumen_opsi);

                $('#is_aktif').val(response.data.is_aktif);
                $('#deskripsi').val(response.data.deskripsi);
                showModalForm();
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            deleteData(endpoint, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                dataLoad();
            });
        });

    });
</script>
@endsection