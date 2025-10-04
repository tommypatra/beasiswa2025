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

      <div class="card">
        <div class="card-body pb-0 d-flex justify-content-between align-items-center">
            <h4 class="fs-4 mb-1 card-title">Rekap Berdasarkan Kabupaten</h4>
            <select id="filter-rekap-kabupaten" class="form-select w-auto">
                <option value="">Semua</option>
                <option value="pendaftar">Proses</option>
                <option value="selesai">Selesai</option>
                <option value="lulus_berkas">Lulus Berkas</option>
                <option value="penerima">Penerima</option>
            </select>          
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="fs-4">
                    <tr>
                        <th class="fs-3">NO</th>
                        <th class="fs-3">KABUPATEN/KOTA</th>
                        <th class="fs-3">JUMLAH</th>
                    </tr>
                </thead>
                <tbody id="data-list-rekap"></tbody>
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
            await loadRekapKabupaten();
        }

        async function loadDataBeasiswa() {
            let url = `${base_url}/api/get-data-beasiswa/${id}`;
            const response = await execAsync(`${url}`, 'GET', token);
            let beasiswa=response.data;
            $('#label-beasiswa').html(`<h4>${beasiswa.nama}</h4>`);
        }

        async function loadRekapKabupaten() {
            let url = `${base_url}/api/get-rekap-kabupaten/${id}`;
            let status = $('#filter-rekap-kabupaten').val(); 
            if (status) {
                url += `?status=${status}`;
            }
            const response = await execAsync(`${url}`, 'GET', token);
            renderDataKabupaten(response);
        }

        function renderDataKabupaten(response) {
            const dataList = $('#data-list-rekap');
            const data = response.data;
            dataList.empty();
            if (data.length > 0) {
                let no=1;
                let total=0;
                $.each(data, function(index, dt) {
                    const row = `<tr>
                                <td width="3%">${no++}</td>
                                <td>${dt.kabupaten}</td>
                                <td width="15%">${dt.total}</td>
                            </tr>`;
                    total+=dt.total;
                    dataList.append(row);
                });
                const row = `<tr>
                            <td width="3%"></td>
                            <td>TOTAL</td>
                            <td width="15%">${total}</td>
                        </tr>`;
                dataList.append(row);
            }else{
                const row = `<tr>
                                <td colspan="3">data tidak ditemukan</td>
                            </tr>`;
                dataList.append(row);                
            }
        }    

        $(document).on('change', '#filter-rekap-kabupaten', function() {
            loadRekapKabupaten();
        });
    })
</script>
@endsection