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
            <h5 class="card-title fw-semibold">Daftar Penerima Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;" >
                <button class="btn btn-primary" id="btn-tambah-sk" >
                    <i class="ti ti-plus"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh-sk" >
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter-sk " >
                    <i class="ti ti-filter"></i>
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
                        <th width="20%">Pejabat Tanda Tangan</th>
                        <th width="10%">Jumlah Penerima</th>
                        <th width="20%">Verifikator</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <tr>
                        <td colspan="8">data tidak ditemukan</td>
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


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="form-sk">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >SK <span class="judul-modal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Perihal</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-7 mb-3">
                            <label class="form-label">Nomor SK</label>
                            <input name="nomor_sk" id="nomor_sk" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-5 mb-3">
                            <label class="form-label">Tangal SK</label>
                            <input name="tanggal_sk" id="tanggal_sk" type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <h5>Pejabat Penandatangan</h5>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="ttd_nama" id="ttd_nama" type="text" class="form-control" >
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input name="ttd_jabatan" id="ttd_jabatan" type="text" class="form-control" >
                        </div>
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Monitoring Evaluasi</label>
                            <select name="monitoring_id" id="monitoring_id" class="form-control">
                            </select>
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

<!-- MULAI MODAL -->
<div class="modal fade modal-xl" id="modal-jadwal-monitoring" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Jadwal Monitoring <span class="judul-modal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">


                <div class="accordion mb-2" id="accordionFormJadwal">               
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                Tambah Jadwal
                            </button>
                        </h2>
                        <div id="collapseForm" class="accordion-collapse collapse" data-bs-parent="#accordionFormJadwal">
                            <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <input name="cari_jadwal" id="cari_jadwal" type="text" class="form-control" placeholder="cari nim nama mahasiswa ...">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Daftar Jadwal</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input-jadwal" placeholder="Cari..." style="max-width: 200px;" >
                        <button class="btn btn-success" id="btn-refresh-jadwal" >
                            <i class="ti ti-reload"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="50%">Nama/Nim</th>
                                <th width="20%">Fakultas</th>
                                <th width="20%">Program Studi</th>
                                <th width="20%">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-list-jadwal-monitoring">
                            <tr>
                                <td colspan="6">data tidak ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="nav-jadwal-monitoring">
                    <ul class="pagination justify-content-center" id="pagination-jadwal-monitoring"></ul>
                </nav>                            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
<!-- AKHIR MODAL -->

<!-- MULAI MODAL -->
<div class="modal fade modal-xl" id="modal-penerima" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penerima <span class="judul-modal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">


                <div class="accordion mb-2" id="accordionFormPenerima">               
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                Tambah Penerima
                            </button>
                        </h2>
                        <div id="collapseForm" class="accordion-collapse collapse" data-bs-parent="#accordionFormPenerima">
                            <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <input name="cari_mahasiswa" id="cari_mahasiswa" type="text" class="form-control" placeholder="cari nim nama mahasiswa ...">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Daftar Penerima Beasiswa</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input-penerima" placeholder="Cari..." style="max-width: 200px;" >
                        <button class="btn btn-success" id="btn-refresh-penerima" >
                            <i class="ti ti-reload"></i>
                        </button>
                        <button class="btn btn-primary fs-4" id="btn-import-penerima" >
                            <iconify-icon icon="solar:import-outline" class=""></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="50%">Nama/Nim</th>
                                <th width="20%">Fakultas</th>
                                <th width="20%">Program Studi</th>
                                <th width="20%">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-list-penerima">
                            <tr>
                                <td colspan="6">data tidak ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="nav-penerima">
                    <ul class="pagination justify-content-center" id="pagination-penerima"></ul>
                </nav>                            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
<!-- AKHIR MODAL -->

<!-- MULAI MODAL -->
<div class="modal fade modal-xl" id="modal-verifikator" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikator <span id="judul-modal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">


                <div class="accordion mb-2" id="accordionFormVerifikator">               
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                Tambah Verifikator
                            </button>
                        </h2>
                        <div id="collapseForm" class="accordion-collapse collapse" data-bs-parent="#accordionFormVerifikator">
                            <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <input name="cari_verifikator" id="cari_verifikator" type="text" class="form-control" placeholder="cari nama verifikator ...">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                    <h5 class="card-title fw-semibold">Daftar Verifikator</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="search-input-verifikator" placeholder="Cari..." style="max-width: 200px;" >
                        <button class="btn btn-success" id="btn-refresh-verifikator" >
                            <i class="ti ti-reload"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="50%">Nama</th>
                                <th width="20%">Email</th>
                                <th width="20%">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="data-list-verifikator">
                            <tr>
                                <td colspan="5">data tidak ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="nav-verifikator">
                    <ul class="pagination justify-content-center" id="pagination-verifikator"></ul>
                </nav>                            

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
<!-- AKHIR MODAL -->
@endsection

@section('scriptJs')
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    var sk_penerima_id;
    $(document).ready(function() {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });
    });
</script>

<script src="{{ asset('js/web/sk_penerima.js?v=1') }}"></script>
<script src="{{ asset('js/web/penerima.js?v=1') }}"></script>
<script src="{{ asset('js/web/jadwal_monitoring.js?v=1') }}"></script>
<script src="{{ asset('js/web/verifikator_laporan.js?v=1') }}"></script>

@endsection