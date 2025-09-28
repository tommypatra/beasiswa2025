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

<div class="alert alert-info" role="alert">
    Halaman ini untuk mengatur nomor rekening yang digunakan saat pencairan bantuan beasiswa, serta untuk mengupload laporan <b>bagi mahasiswa yang lulus seleksi beasiswa</b>
</div>


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
                        <th width="20%">Perihal/ Monitoring Beasiswa</th>
                        <th width="15%">Nomor/ Tanggal SK</th>
                        <th width="20%">Nomor Rekening</th>
                        <th width="5%"></th>
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
        let select = `<select class="form-control w-100 pilih_nomor_rekening" data-old="${dt.buku_rekening_id}" data-id="${dt.id}">`;
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
                const sk_penerima = dt.sk_penerima;

                let monitoring="";
                let btn_laporan="";
                if(sk_penerima.monitoring){
                    monitoring=`<span class="badge rounded-pill bg-primary fs-2">${sk_penerima.monitoring.nama}</span>`;
                    btn_laporan=`<a href="${base_url}/laporan-penerima-beasiswa/${sk_penerima.id}" class="btn btn-secondary btn-sm"><iconify-icon icon="solar:notebook-linear" class="fs-5"></iconify-icon> Laporan</a>`;
                }

                let verifikator_laporan = '';
                if (sk_penerima.verifikator_laporan?.length > 0) {
                    verifikator_laporan = `<ul id="daftar-verifikator">${sk_penerima.verifikator_laporan.map(v => `<li>${v.user.name}</li>`).join('')}</ul>`;
                }
                const row = `<tr>
                            <td>${no++}</td>
                            <td>${sk_penerima.tanggal_sk.substring(0, 4)}</td>
                            <td>${sk_penerima.nama} <div>${monitoring}</div></td>
                            <td>${sk_penerima.nomor_sk}/ ${sk_penerima.tanggal_sk}</td>
                            <td>${renderSelectRekening(dt)}</td>
                            <td>${btn_laporan}</td>
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
                const dataForm = { buku_rekening_id: val };

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