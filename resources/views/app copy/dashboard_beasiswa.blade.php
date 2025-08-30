@extends('template')

@section('scriptHead')
<title>Dashboard Beasiswa</title>
@endsection

@section('container')

<div class="mb-3">
    <div class="d-flex align-items-center mb-2">
        <iconify-icon icon="solar:book-2-linear" class="fs-9"></iconify-icon> <h2 class="mb-0 ms-2">Dashboard</h2>
    </div>
    <div id="label-beasiswa" class="mb-2"></div>
</div>

<div class="row">
    <div class="col-lg-9">
      <div class="card">
        <div class="card-body pb-0">
          <h4 class="fs-4 mb-1 card-title">Jadwal</h4>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="fs-4">
                    <tr>
                        <th class="fs-3 px-4">Kegiatan</th>
                        <th class="fs-3">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pendaftaran</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-pendaftaran">{{ date('Y-m-d') }} s/d {{ date('Y-m-d') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Verifikasi Berkas</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-verifikasi-berkas">{{ date('Y-m-d') }} s/d {{ date('Y-m-d') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Pengumuman Verifikasi Berkas</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-pengumuman-verifikasi-berkas">{{ date('Y-m-d') }}</span>
                        </td>
                    </tr>
                    <tr id="baris-seleksi-cat">
                        <td>Seleksi CAT</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-seleksi-cat">{{ date('Y-m-d') }} s/d {{ date('Y-m-d') }}</span>
                        </td>
                    </tr>
                    <tr id="baris-survei-lapangan">
                        <td>Survei Lapangan</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-survei-lapangan">{{ date('Y-m-d') }} s/d {{ date('Y-m-d') }}</span>
                        </td>
                    </tr>
                    <tr id="baris-wawancara">
                        <td>Wawancara</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-wawancara">{{ date('Y-m-d') }} s/d {{ date('Y-m-d') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Pengumuman</td>
                        <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary" id="tanggal-pengumuman">{{ date('Y-m-d') }}  </span>
                        </td>
                    </tr>
                </tbody>
            </table>
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
    var page = 1;
    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);

            if(beasiswa.ada_verifikasi_lapangan)
              $('#menu-web-surveyor').show();
            if(beasiswa.ada_wawancara)
              $('#menu-web-pewawancara').show();
            if(beasiswa.ada_wawancara)
              $('#menu-web-soal').show();
        }

    })
</script>
@endsection