

<div class="d-sm-flex d-block align-items-center justify-content-between mb-3">

    <div></div>
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
                <th width="10%">Tanggal</th>
                <th width="10%">Sesi</th>
                <th width="20%">Waktu</th>
                <th width="20%">Ruangan</th>
                <th width="15%">Peserta</th>
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

@push('scriptJs')
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
@endpush