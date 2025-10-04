@extends('template')

@section('scriptHead')
<title>Pengaturan CAT</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
@endsection

@section('container')

<div class="mb-3">
    <div class="d-flex align-items-center mb-2">
        <iconify-icon icon="solar:book-2-linear" class="fs-9"></iconify-icon> <h2 class="mb-0 ms-2">Pengaturan CAT</h2>
    </div>
    <div id="label-beasiswa" class="mb-2"></div>
</div>

<div class="row">
    <div class="col-lg-9">
      <div class="card">
        <div class="card-body pb-0">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-pengaturan">Dasar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-ruangan">Ruangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-sesi">Sesi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-jadwal">Jadwal Ujian</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="catTabContent">
                <!-- Tab 1 -->
                <div class="tab-pane fade show active" id="tab-pengaturan" >
                    @include('app.cat.pengaturan')
                </div>

                <!-- Tab 2 -->
                <div class="tab-pane fade" id="tab-ruangan" >
                    @include('app.cat.ruangan')
                </div>

                <!-- Tab 3 -->
                <div class="tab-pane fade" id="tab-sesi" >
                    @include('app.cat.sesi')
                </div>

                <!-- Tab 4 -->
                <div class="tab-pane fade" id="tab-jadwal" >
                    @include('app.cat.jadwal')
                </div>
            </div>

            </div>
        </div>
    </div>
    <div class="col-lg-3">
      @include('app/menu_beasiswa')
    </div>
</div>


@endsection

@section('scriptJs')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
    var beasiswa_id = "{{ $beasiswa_id }}";

    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
            await loadDataRuangan();
        }

        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${beasiswa_id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);
        }


        async function loadDataRuangan() {
            let url = `${base_url}/api/ruangan?limit=0`;
            const response = await execAsync(`${url}`, 'GET', token);
            if (response.status && response.data) {
                let $select = $('#ruangan_id');
                $select.empty();
                $select.append('<option value="" data-kapasitas="">-- Pilih Ruangan --</option>');
                $.each(response.data, function (index, item) {
                    $select.append(`<option value="${item.id}" data-kapasitas="${item.kapasitas}">${item.nama}</option>`);
                });
            }
        }

        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).attr("href");
            switch (target) {
                case "#tab-pengaturan":
                    loadDataTab1();
                    break;
                case "#tab-ruangan":
                    loadDataTab2();
                    break;
                case "#tab-sesi":
                    loadDataTab3();
                    break;
                case "#tab-jadwal":
                    loadDataTab4();
                    break;
            }
        });

    })
</script>
@endsection