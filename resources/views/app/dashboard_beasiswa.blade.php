@extends('template')

@section('scriptHead')
<title>Dashboard Beasiswa</title>
@endsection

@section('container')

<div class="mb-3">
  <h5 class="card-title" id="label-beasiswa">Dashboard Beasiswa</h5>
</div>

<div class="row">
    <div class="col-lg-8">
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
    <div class="col-lg-4 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body">
          <div class="d-flex mb-3 justify-content-between align-items-center">
            <h4 class="mb-0 card-title">Earning Reports</h4>
            <div class="dropdown">
              <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" class="rounded-circle btn-transparent rounded-circle btn-sm px-1 btn shadow-none">
                <i class="ti ti-dots-vertical fs-6"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="javascript:void(0)">Action</a></li>
                <li>
                  <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                </li>
              </ul>
            </div>
          </div>
          <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center justify-content-between py-10 border-bottom">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:card-line-duotone" class="fs-7 text-primary"></iconify-icon>
                </div>
                <div>
                  <h6 class="mb-1 fs-3">Bank Transfer</h6>
                  <p class="mb-0 fs-2 d-flex align-items-center gap-1">
                    and +1 more<i class="ti ti-info-circle"></i>
                  </p>
                </div>
              </div>
              <span class="badge rounded-pill fw-medium fs-2 d-flex align-items-center bg-success-subtle text-success text-end"><i class="ti ti-caret-up"></i>16.3%</span>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-danger-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-7 text-danger"></iconify-icon>
                </div>
                <div>
                  <h6 class="mb-1 fs-3">Net Profit</h6>
                  <p class="mb-0 fs-2 d-flex align-items-center gap-1">
                    and +4 more<i class="ti ti-info-circle"></i>
                  </p>
                </div>
              </div>
              <span class="badge rounded-pill fw-medium fs-2 d-flex align-items-center bg-success-subtle text-success text-end"><i class="ti ti-caret-up"></i>12.55%</span>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-secondary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:course-up-line-duotone" class="fs-7 text-secondary"></iconify-icon>
                </div>
                <div>
                  <h6 class="mb-1 fs-3">Total Income</h6>
                  <p class="mb-0 fs-2 d-flex align-items-center gap-1">
                    and +4 more<i class="ti ti-info-circle"></i>
                  </p>
                </div>
              </div>
              <span class="badge rounded-pill fw-medium fs-2 d-flex align-items-center bg-success-subtle text-success text-end"><i class="ti ti-caret-up"></i>12.55%</span>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-light me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:waterdrops-line-duotone" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                  <h6 class="mb-1 fs-3">Total Expenses</h6>
                  <p class="mb-0 fs-2 d-flex align-items-center gap-1">
                    and +2 more<i class="ti ti-info-circle"></i>
                  </p>
                </div>
              </div>
              <span class="badge rounded-pill fw-medium fs-2 d-flex align-items-center bg-success-subtle text-success text-end"><i class="ti ti-caret-up"></i>8.28%</span>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-warning-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:waterdrops-line-duotone" class="fs-7 text-warning"></iconify-icon>
                </div>
                <div>
                  <h6 class="mb-1 fs-3">Marketing</h6>
                  <p class="mb-0 fs-2 d-flex align-items-center gap-1">
                    and +3 more<i class="ti ti-info-circle"></i>
                  </p>
                </div>
              </div>
              <span class="badge rounded-pill fw-medium fs-2 d-flex align-items-center bg-success-subtle text-success text-end"><i class="ti ti-caret-up"></i>9.25%</span>
            </li>
            <a href="javascript:void(0)" class="fs-4 mt-7 text-center d-block">View more markets</a>
          </ul>
        </div>
      </div>
    </div>
</div>


@endsection

@section('scriptJs')
<script type="text/javascript">
    var id = "{{ $id }}";
    var page = 1;
    $(document).ready(function() {
        initPage();
        async function initPage() {
            await loadDataBeasiswa();
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/pengelola/peserta-verifikasi?id=${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            console.log(response);

            $('#label-beasiswa').html(`Dashboard `+response.data.data[0].nama);


        }

    })
</script>
@endsection