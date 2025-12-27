

<div class="d-sm-flex d-block align-items-center justify-content-between mb-3">

    <div></div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control" id="search-input-tab5" placeholder="Cari..." style="max-width: 200px;">
        <button class="btn btn-primary" id="btn-search-tab5">
            <i class="ti ti-search"></i>
        </button>

        <button class="btn btn-success" id="btn-tambah-tab5">
            <i class="ti ti-plus"></i>
        </button>
        <button class="btn btn-success" id="btn-refresh-tab5">
            <i class="ti ti-reload"></i>
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Ujian/ Nomor Urut</th>
                <th width="40%">Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list-tab5">
        </tbody>
    </table>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form-tab5" role="dialog">
    <div class="modal-dialog">
        <form id="form-tab5">
            <input type="hidden" name="id" id="id-tab5" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Urut</label>
                            <input name="urut" id="urut-tab5" type="number" class="form-control" value="1" required>
                        </div>
						<div class="col-lg-9 mb-3">
                            <label class="form-label">Materi Ujian</label>
                            <input name="ujian" id="ujian-tab5" type="text" class="form-control" value="" required>
                        </div>
                    </div>
                    <div class="row">
						<div class="col-lg-9 mb-12">
                            <label class="form-label">Keterangan</label>
                            <input name="keterangan" id="keterangan-tab5" type="text" class="form-control" value="">
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
    <ul class="pagination justify-content-center" id="pagination-tab5"></ul>
</nav>

@push('scriptJs')
<script type="text/javascript">
    var page_tab5=1;

    function loadDataTab5() {
        const search_tab5 = $('#search-input-tab5').val();
        const url = `${endpoint_tab5}?page=${page_tab5}&search=${search_tab5}`;

        fetchData(url, function(response) {
            renderDatatab5(response);
        },true);
    }

    function renderDatatab5(response) {
        const dataList = $('#data-list-tab5');
        const pagination = $('#pagination-tab5');
        const data=response.data.data;
        let no = (response.data.current_page - 1) * response.data.per_page + 1;
        dataList.empty();
        pagination.empty();
        if (data.length > 0) {
            $.each(data, function(index, dt) {
                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.ujian} (${dt.urut})</td>
                            <td>${showText(dt.keterangan)}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-ganti-tab5" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                        <li><a class="dropdown-item btn-hapus-tab5" data-id="${dt.id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>`;
                dataList.append(row);
            });
            renderPagination(response.data, pagination);
        }else{
            const row = `<tr>
                            <td colspan="4">data tidak ditemukan</td>
                        </tr>`;
            dataList.append(row);                
        }
    }    

    $(document).ready(function() {

        $('#btn-refresh-tab5').click(function(){
            loadDataTab5();
        });

        $('#btn-tambah-tab5').click(function(){
            formReset5();            
            showModalForm5();
        });

        $('#btn-search-tab5').click(function(){
            page_tab5=1;
            loadDataTab5();
        });

       // Handle page change
        $(document).on('click', '#pagination-tab5 .page-link', function() {
            page_tab5 = $(this).data('page');
            loadDataTab5();
        }); 
                
        $('#btn-search-tab5').click(function(){
            loadDataTab5();
        })

        //hapus data
        $(document).on('click', '.btn-hapus-tab5', async function() {            
            const id = $(this).data('id');
            const url = `${endpoint_tab5}/${id}`;
            const response = await execAsync(`${url}`, 'DELETE', token);
            if(confirm('apakah anda yakin hapus data?')){
                if (!response) {
                    appShowNotification(true, ['Berhasil dilakukan!']);
                    loadDataTab5();
                    return;
                }
                if(!response.status){
                    appShowNotification(false,['terjadi kesalahan saat hapus jadwal']);
                    return;
                }
            }
        });
        
        $(document).on('click', '.btn-ganti-tab5', function() {
            const id = $(this).data('id');
            showDataById(endpoint_tab5, id, function(response) {
                $('#id-tab5').val(response.data.id);
                $('#ujian-tab5').val(response.data.ujian);
                $('#keterangan-tab5').val(response.data.keterangan);
                $('#urut-tab5').val(response.data.urut);
                showModalForm5();
            });
        });

        $(document).on('click', '.btn-print-kartu-ujian-tab5', function() {
            const url_id = $(this).data('url_id');
            const url=`${base_url}/cetak-kartu-ujian/${beasiswa_id}/${url_id}`;
            window.open(url, '_blank');

        });


        // $(document).on('click', '.btn-hapus-tab5', function() {
        //     const id = $(this).data('id');
        //     deleteData(`${base_url}/api/beasiswa/${beasiswa_id}/peserta-ujian`, id, function() {
        //         appShowNotification(true,['berhasil dilakukan!']);
        //         loadDaftarPeserta();
        //         loadDataTab5();
        //     });

        // });
        
        //form reset
        function formReset5(){
            $('#form-tab5').trigger('reset');
            $('#form input[type="hidden"]').val('');
            $('#tanggal-tab5').val(tgl_hari_ini);
            $('#sesi-tab5').val('1');
        }

        //untuk show modal form
        function showModalForm5() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form-tab5'), {
                keyboard: false
            });
            fModalForm.show();
        }

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-tab5").validate({
            submitHandler: function(form) {
                const id = $('#id-tab5').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint_tab5 : endpoint_tab5 + '/' + id;
                const formData = $(form).serialize() + `&beasiswa_id=${beasiswa_id}`;
                saveData(url, type, formData, function(response) {
                    //jika berhasil
                    appShowNotification(true,['berhasil dilakukan!']);
                    // if(type=='POST'){
                    //     formReset5();
                    // }
                    loadDataTab5();
                });
            }
        });

    });
</script>
@endpush