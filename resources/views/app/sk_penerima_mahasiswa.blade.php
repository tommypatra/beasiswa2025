@extends('template')

@section('scriptHead')
<title>SK Penerima Beasiswa</title>
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

<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Daftar SK Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;" >
                <button class="btn btn-success" id="btn-refresh-sk" >
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tahun</th>
                        <th width="20%">Perihal/ Monitorin Beasiswa</th>
                        <th width="15%">Nomor/ Tanggal SK</th>
                        <th width="20%">Nomor Rekening</th>
                        <th width="5%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="7">data tidak ditemukan</td>
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
    var page_sk = 1;
    var data_buku_rekening = [];
    async function loadDataSK() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/get-data-sk-penerima-mahasiswa?page=${page_sk}&search=${search}`);
        renderData(response);
    }


    async function loadDataBukuRekening() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/buku-rekening?limit=0&search=${search}`);
        data_buku_rekening=[];
        if(response.status){
            data_buku_rekening=response.data;
        }
    }

    function renderSelectRekening(dt) {
        // const penerima = 
        let select = `<select class="form-control w-100 pilih_nomor_rekening" data-old="${dt.buku_rekening_id}" data-id="${dt.sk_penerima_id}">`;
        select+=`<option value="">- pilih -</option>`
        data_buku_rekening.forEach(function(item) {
            if(dt.buku_rekening_id==item.id)
                select+=`<option value="${item.id}" selected>${item.nomor} - ${item.bank}</option>`
            else
                select+=`<option value="${item.id}">${item.nomor} - ${item.bank}</option>`
        });
        select+=`</select>`;
        return select;
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
                const pejabat_ttd=(dt.ttd_nama)?dt.ttd_nama+'/ '+dt.ttd_jabatan:"";
                const monitoring=(dt.monitoring)?`<span class="badge rounded-pill bg-primary fs-2">${dt.monitoring.nama}</span>`:"";

                let verifikator_laporan = '';
                if (dt.verifikator_laporan?.length > 0) {
                    verifikator_laporan = `<ul id="daftar-verifikator">${dt.verifikator_laporan.map(v => `<li>${v.user.name}</li>`).join('')}</ul>`;
                }

                const row = `<tr>
                            <td>${no++}</td>
                            <td>${dt.tanggal_sk.substring(0, 4)}</td>
                            <td>${dt.nama} <div>${monitoring}</div></td>
                            <td>${dt.nomor_sk}/ ${dt.tanggal_sk}</td>
                            <td>${renderSelectRekening(dt)}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-daftar-penerima" data-perihal="${dt.nama}" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:notebook-broken"></iconify-icon> Daftar Penerima</a></li>
                                        <li><a class="dropdown-item btn-jadwal-monitoring" data-perihal="${dt.nama}" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:calendar-linear"></iconify-icon> Jadwal Monitoring</a></li>
                                        <li><a class="dropdown-item btn-daftar-verifikator" data-perihal="${dt.nama}" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:user-check-linear"></iconify-icon> Verifikator Monitoring</a></li>
                                        <li><a class="dropdown-item btn-ganti-sk" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:pen-new-round-outline"></iconify-icon> Ganti</a></li>
                                        <li><a class="dropdown-item btn-hapus-sk" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:trash-bin-2-outline"></iconify-icon> Hapus</a></li>
                                    </ul>
                                </div>
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
            await loadDataBukuRekening();
            await loadDataSK();
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

        $('#btn-refresh-sk').click(function() {
            loadDataSK();
        });

        $(document).on('change','.pilih_nomor_rekening',function(){            
            const select = $(this);

            if(confirm('apakah anda ingin mengganti nomor rekening ?')){
                const id = select.attr('data-id');
                const val = select.val();
                const url = base_url + '/api/ganti-nomor-rekening/' + id;
                const dataForm = { nomor_rekening_id: val };

                saveData(url, "PUT", dataForm, function(response) {
                    appShowNotification(true, ['berhasil dilakukan!']);
                    select.attr('data-old',val);
                });
            } else {
                // reset ke default
                select.val(select.attr('data-old'));
            }
        });

    });

</script>
@endsection