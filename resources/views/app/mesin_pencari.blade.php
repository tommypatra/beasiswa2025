@extends('template')

@section('scriptHead')
<title>Mesin Pencari</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
                <h5 class="card-title fw-semibold">Mesin Pencari</h5>
            </div>
            <div class="dropdown dropstart">
                <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical fs-6"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)"><i class="fs-4 ti ti-plus"></i>Add</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)"><i class="fs-4 ti ti-edit"></i>Edit</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)"><i class="fs-4 ti ti-trash"></i>Delete</a></li>
                </ul>
            </div>                    
        </div>

        <div class="input-group mb-3">
            <select class="form-select" id="filter-tahun" name="tahun" style="max-width:120px;">
                <option value="">Semua</option>
                <?php
                    $tahunSekarang = date('Y');
                    for ($i = 0; $i < 10; $i++) {
                        $tahun = $tahunSekarang - $i;
                        echo "<option value='$tahun'>$tahun</option>";
                    }
                ?>
            </select>
            <input type="text" 
                class="form-control" 
                id="search-input" 
                placeholder="Cari...">

            <button class="btn btn-primary"
                id="btn-search"
                data-bs-toggle="tooltip"
                title="Cari Data">
                <i class="ti ti-search"></i>
            </button>

            <button class="btn btn-warning"
                id="btn-format"
                data-bs-toggle="tooltip"
                title="Download Format">
                <i class="ti ti-download"></i>
            </button>

            <button class="btn btn-success"
                id="btn-upload"
                data-bs-toggle="tooltip"
                title="Upload Data">
                <i class="ti ti-upload"></i>
            </button>
        </div>

    </div>

    <div class="mb-4">
        <div id="data-list"></div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>

</div>
@endsection

@section('scriptJs')
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">

$(document).ready(function () {
    const endpoint = base_url+'/api/';
    var page = 1;

    $('[data-bs-toggle="tooltip"]').each(function () {
        new bootstrap.Tooltip(this);
    });

    function renderData(response) {
        const dataList = $('#data-list');
        const pagination = $('#pagination');
        const data = response.data.data;

        dataList.empty();
        pagination.empty();

        if (data.length > 0) {
            $.each(data, function(index, dt) {
                let beasiswaHtml = '';
                
                if (dt.penerima && dt.penerima.length > 0) {
                    $.each(dt.penerima, function(i, p) {
                        beasiswaHtml += `
                            <div class="border rounded p-2 mb-2 bg-light">
                                <div><strong>${p.nama}</strong></div>
                                <div>No SK: ${p.nomor_sk}</div>
                            </div>
                        `;
                    });
                } else {
                    beasiswaHtml = `
                        <div class="text-danger">
                            Belum menerima beasiswa
                        </div>
                    `;
                }

                const card = `
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-1">
                                        ${dt.nama}
                                    </h5>
                                    <div class="text-muted mb-2">
                                        NIM: ${dt.nim}
                                    </div>
                                    <div>
                                        ${dt.program_studi} - ${dt.fakultas}
                                    </div>
                                    <div>
                                        Tahun Masuk: ${dt.tahun_masuk}
                                    </div>
                                    <div>
                                        UKT: ${dt.ukt}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-bold mb-2">
                                        Riwayat Beasiswa
                                    </div>
                                    ${beasiswaHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                dataList.append(card);
            });
            renderPagination(response.data, pagination);
        } else {
            const empty = `
                <div class="alert alert-warning">
                    Data mahasiswa tidak ditemukan
                </div>
            `;
            dataList.append(empty);
        }
    }

    function dataSearch() {
        const search = $('#search-input').val();
        const filter_tahun = $('#filter-tahun').val();
        if(search!==''){
            const url = endpoint+'cari-beasiswa-mahasiswa?page='+page+'&search='+search+'&filter_tahun='+filter_tahun+'&limit=30';

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }else{
            $('#data-list').empty();
            $('#pagination').empty();
        }
    }   

    $('#btn-search').click(function(){
        dataSearch(); 
    });

    // Handle page change
    $(document).on('click', '.page-link', function() {
        page = $(this).data('page');
        dataSearch();
    });


});

</script>
@endsection