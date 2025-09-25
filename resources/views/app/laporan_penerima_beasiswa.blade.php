@extends('template')

@section('scriptHead')
<title>Laporan Penerima Beasiswa</title>
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

<h4 id="nama-sk">SK Penerima Beasiswa</h4>
<div id="detail-sk" class="mb-2"></div>
<div id="daftar-tombol" class="mb-3"></div>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Laporan Penerima Beasiswa</h5>
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
                        <th width="20%">Item</th>
                        <th width="20%">Bukti Dokumen</th>
                        <th width="15%">Keterangan</th>
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
    var sk_penerima_id = "{{ $sk_penerima_id }}";
    var kegiatan_id;

    async function loadDataSK() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/get-data-sk-beasiswa/${sk_penerima_id}`);
        if(response.status){
            data=response.data;
            $('#nama-sk').html(`${data.nama}`);
            $('#detail-sk').html(`Nomor SK : ${data.nomor_sk} / Tanggal : ${data.tanggal_sk}`);
            renderTombolKegiatan(data.monitoring.kegiatan);
        }
    }

    function renderTombolKegiatan(tombol){
        let html=``;
        if(tombol.length>0){
            html+=`<div class="btn-group" role="group" aria-label="Basic radio toggle button group">`;
            $.each(tombol, function(index, dt) {
                let active ='';    
                if(index==0){
                    active='checked';
                    kegiatan_id=dt.id;
                }
                html+=` <input type="radio" class="btn-check daftar-kegiatan" name="kegiatan-btn" id="btnradio-${index}" value="${dt.id}" ${active}>
                        <label class="btn btn-outline-primary" for="btnradio-${index}">${dt.nama}</label>`;
            });
            html+=`</div>`;
        }
        $('#daftar-tombol').html(html);
    }

    $(document).on('change','.daftar-kegiatan', function() {
        kegiatan_id = $('.daftar-kegiatan:checked').val();
    });

    async function loadSubKegiatan() {
        let search = $('#search-input').val();
        let response = await asyncFunction(`${base_url}/api/laporan-mahasiswa/${kegiatan_id}?search=${search}`);
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
                const monitoring=(sk_penerima.monitoring)?`<span class="badge rounded-pill bg-primary fs-2">${sk_penerima.monitoring.nama}</span>`:"";

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
                            <td><a href="${base_url}/laporan-penerima-beasiswa/${sk_penerima.id}" class="btn btn-secondary btn-sm"><iconify-icon icon="solar:notebook-linear" class="fs-5"></iconify-icon> Laporan</a></td>
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
            await loadDataSK();
            await loadSubKegiatan();
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