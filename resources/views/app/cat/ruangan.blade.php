

<form id="form-ruangan" class="row g-2 align-items-center mb-3">
    <input type="hidden" id="ruangan_ujian_id" name="id">
    <div class="col-auto">
        <label for="ruangan_id" class="col-form-label">Ruangan</label>
    </div>
    <div class="col-auto">
        <select name="ruangan_id" id="ruangan_id" class="form-control" required></select>
    </div>
    <div class="col">
        <input type="number" name="urut" id="urut" class="form-control" required placeholder="urutan">
    </div>
    <div class="col">
        <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" required placeholder="jumlah">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary" id="btn-simpan-tab2" placeholder="simpan"><iconify-icon icon="solar:diskette-outline" class="fs-3"></iconify-icon></button>
        <button type="reset" class="btn btn-danger" id="btn-batal-tab2" placeholder="batal"><iconify-icon icon="solar:close-circle-outline" class="fs-3"></iconify-icon></button>
        <div class="btn btn-success" id="btn-refresh-tab2" placeholder="refresh"><iconify-icon icon="solar:refresh-outline" class="fs-3"></iconify-icon></div>
    </div>
</form>


<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Ruangan</th>
                <th width="35%">Gedung/ Lantai/ Kapasitas</th>
                <th width="10%">Jumlah Peserta</th>
                <th width="10%">Urut</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list-tab2">
        </tbody>
    </table>
</div>
<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination-tab2"></ul>
</nav>

@push('scriptJs')
<script type="text/javascript">
    var page_tab2=1;

    function loadDataTab2(){
        var search_tab2 = $('#search-input').val();
        var url = `${base_url}/api/ruangan-ujian?beasiswa_id=${beasiswa_id}&page=${page_tab2}&search=${search_tab2}`;

        fetchData(url, function(response) {
            renderDataTab2(response);
        },true);
    }

    function renderDataTab2(response) {
        const dataList = $('#data-list-tab2');
        const pagination = $('#pagination-tab2');
        const data=response.data.data;
        let no = (response.data.current_page - 1) * response.data.per_page + 1;
        dataList.empty();
        pagination.empty();
        if (data.length > 0) {
            $.each(data, function(index, dt) {
                const gedung = (dt.gedung)?`${dt.gedung}/ ${dt.lantai} / ${dt.kapasitas}`:``;
                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.ruangan}</td>
                            <td>${gedung}</td>
                            <td>${dt.jumlah_peserta}</td>
                            <td>${dt.urut}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-ganti-tab2" data-id="${dt.id}" href="javascript:;"><i class="far fa-edit"></i> Ganti</a></li>
                                        <li><a class="dropdown-item btn-hapus-tab2" data-id="${dt.id}" href="javascript:;"><i class="fas fa-trash-alt"></i> Hapus</a></li>
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
        const endpoint=`${base_url}/api/ruangan-ujian`;


        // tambahkan event change
        $('#ruangan_id').on("change", function () {
            let kapasitas = $(this).find(":selected").data("kapasitas");
            if (kapasitas) {
                $("#jumlah_peserta").val(kapasitas);
            } else {
                $("#jumlah_peserta").val("");
            }
        });

        // validasi jumlah peserta
        $('#jumlah_peserta').on("input", function () {
            let kapasitas = $('#ruangan_id').find(":selected").data("kapasitas") || 0;
            let val = parseInt($(this).val(), 10) || 0;
            
            if(kapasitas>0){
                if (val > kapasitas) {
                    alert(`Jumlah peserta tidak boleh lebih dari ${kapasitas}`);
                    $(this).val(kapasitas);
                } else if (val < 0) {
                    $(this).val(0);
                }
            }
        });        

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form-ruangan").validate({
            submitHandler: function(form) {
                const id = $('#ruangan_ujian_id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                const dataForm = $(form).serialize() + '&beasiswa_id=' + beasiswa_id;

                saveData(url, type, dataForm, function(response) {
                    appShowNotification(true,['berhasil dilakukan!']);
                    formReset();
                    loadDataTab2();
                });
            }
        });        

        // Handle page change
        $(document).on('click', '#pagination-tab2 .page-link', function() {
            page_tab2 = $(this).data('page');
            loadDataTab2();
        });

        // Handle page change
        $('#btn-refresh-tab2').click(function() {
            loadDataTab2();
        });

        function formReset(){
            $('#form-ruangan').trigger('reset');
            $('#form-ruangan input[type="hidden"]').val('');
        }

        $('#btn-batal-tab2').click(function() {
            formReset();
        });

        //ganti data
        $(document).on('click', '.btn-ganti-tab2', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#form-ruangan #ruangan_ujian_id').val(response.data.id);
                $('#ruangan_id').val(response.data.ruangan_id);
                $('#urut').val(response.data.urut);
                $('#jumlah_peserta').val(response.data.jumlah_peserta);
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus-tab2', function() {
            const id = $(this).data('id');
            deleteData(endpoint, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataTab2();
            });
        });


    });
</script>
@endpush