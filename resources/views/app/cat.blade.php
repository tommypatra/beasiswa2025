@extends('template')

@section('scriptHead')
<title>Pengaturan CAT</title>
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

            <ul class="nav nav-tabs" id="catTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="pengaturan-tab" data-bs-toggle="tab" data-bs-target="#pengaturan" type="button" role="tab">Pengaturan</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="ruangan-tab" data-bs-toggle="tab" data-bs-target="#ruangan" type="button" role="tab">Ruangan</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="sesi-tab" data-bs-toggle="tab" data-bs-target="#sesi" type="button" role="tab">Sesi</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button" role="tab">Jadwal</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="catTabContent">
                <!-- Tab 1 -->
                <div class="tab-pane fade show active" id="pengaturan" role="tabpanel">
                </div>

                <!-- Tab 2 -->
                <div class="tab-pane fade" id="ruangan" role="tabpanel">
                </div>

                <!-- Tab 3 -->
                <div class="tab-pane fade" id="sesi" role="tabpanel">
                </div>

                <!-- Tab 4 -->
                <div class="tab-pane fade" id="jadwal" role="tabpanel">
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
<script type="text/javascript">
    var id = "{{ $beasiswa_id }}";
    var loadedTabs = {};
    $(document).ready(function() {
        // auto load tab pertama
        loadTabContent('#pengaturan');

        $('#catTab button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).data('bs-target'); // misal: #ruangan
            if (!loadedTabs[target]) {
                loadTabContent(target);
                loadedTabs[target] = true;
            }
        });

        function loadTabContent(target) {
            let url = "";
            if (target === "#pengaturan") url = `/pengaturan-cat/${id}`;
            if (target === "#ruangan") url = `/ruangan-cat/${id}`;
            if (target === "#sesi") url = `/sesi-cat/${id}`;
            if (target === "#jadwal") url = `/jadwal-cat/${id}`;

            $(target).html('<div class="text-center p-3">Loading...</div>');
            $(target).load(url);
        }

    })
</script>
@endsection