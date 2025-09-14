

<form id="form-sesi" class="row g-2 align-items-center mb-3">
    <input type="hidden" id="sesi_ujian_id" name="id">
    <div class="col-auto">
        <label for="ruangan_id" class="col-form-label">Sesi</label>
    </div>
    <div class="col-auto">
        <input type="number" name="sesi" id="sesi" class="form-control" required placeholder="sesi">
    </div>
    <div class="col">
        <input type="text" name="jam_mulai" id="jam_mulai" class="form-control time-input" required placeholder="HH:MM:SS">
    </div>
    <div class="col">
        <input type="text" name="jam_selesai" id="jam_selesai" class="form-control time-input" required placeholder="HH:MM:SS">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary" id="btn-simpan-tab3" placeholder="simpan"><iconify-icon icon="solar:diskette-outline" class="fs-3"></iconify-icon></button>
        <button type="reset" class="btn btn-danger" id="btn-batal-tab3" placeholder="batal"><iconify-icon icon="solar:close-circle-outline" class="fs-3"></iconify-icon></button>
        <div class="btn btn-success" id="btn-refresh-tab3" placeholder="refresh"><iconify-icon icon="solar:refresh-outline" class="fs-3"></iconify-icon></div>
    </div>
</form>


<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Jam Mulai</th>
                <th width="25%">Jam Selesai</th>
                <th width="25%">Sesi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list-tab3">
        </tbody>
    </table>
</div>
<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination-tab3"></ul>
</nav>

@push('scriptJs')
<script type="text/javascript">
    var page_tab3=1;

    function loadDataTab3(){
        var search_tab2 = $('#search-input').val();
        var url = `${base_url}/api/sesi-ujian?beasiswa_id=${beasiswa_id}&page=${page_tab3}&search=${search_tab2}`;

        fetchData(url, function(response) {
            renderDataTab3(response);
        },true);
    }

    function renderDataTab3(response) {
        const dataList = $('#data-list-tab3');
        const pagination = $('#pagination-tab3');
        const data=response.data.data;
        let no = (response.data.current_page - 1) * response.data.per_page + 1;
        dataList.empty();
        pagination.empty();
        if (data.length > 0) {
            $.each(data, function(index, dt) {
                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.jam_mulai}</td>
                            <td>${dt.jam_selesai}</td>
                            <td>${dt.sesi}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-ganti-tab3" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                        <li><a class="dropdown-item btn-hapus-tab3" data-id="${dt.id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>`;
                dataList.append(row);
            });
            renderPagination(response.data, pagination);
        }else{
            const row = `<tr>
                            <td colspan="5">data tidak ditemukan</td>
                        </tr>`;
            dataList.append(row);                
        }
    }    

    $(document).ready(function() {
        const endpoint=`${base_url}/api/sesi-ujian`;


        // Panggil untuk semua input jam
        initTimeInput(".time-input");

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-sesi").validate({
            submitHandler: function(form) {
                const id = $('#sesi_ujian_id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                const dataForm = $(form).serialize() + '&beasiswa_id=' + beasiswa_id;

                saveData(url, type, dataForm, function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    formReset();
                    loadDataTab3();
                });
            }
        });        

        // Handle page change
        $(document).on('click', '#pagination-tab3 .page-link', function() {
            page_tab3 = $(this).data('page');
            loadDataTab3();
        });

        // Handle page change
        $('#btn-refresh-tab3').click(function() {
            loadDataTab3();
        });

        function formReset(){
            $('#form-sesi').trigger('reset');
            $('#form-sesi input[type="hidden"]').val('');
        }

        $('#btn-batal-tab3').click(function() {
            formReset();
        });

        //ganti data
        $(document).on('click', '.btn-ganti-tab3', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#form-sesi #sesi_ujian_id').val(response.data.id);
                $('#sesi').val(response.data.sesi);
                $('#jam_mulai').val(response.data.jam_mulai);
                $('#jam_selesai').val(response.data.jam_selesai);
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus-tab3', function() {
            const id = $(this).data('id');
            deleteData(endpoint, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataTab3();
            });
        });


    });
</script>
@endpush