<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <style>
        body { background:#f5f6f8; }
        .profile-photo { width:100px; height:125px; object-fit:cover; }
        .section-title { font-size:16px; font-weight:600; }
        .info-label { font-size:12px; color:#6c757d; margin-bottom:2px; }
        .info-value { font-weight:500; }
        .score-value { font-size:22px; font-weight:700; }
        .document-item { border:1px solid #dee2e6; border-radius:.5rem; padding:12px; }
        @media print {
            .no-print { display:none!important; }
            body { background:#fff; }
            .card { box-shadow:none!important; }
        }
    </style>

    <script>
        const base_url = "{{ url('/') }}";
        const pendaftar_id = "{{ $pendaftar_id }}";
    </script>
</head>

<body>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h4 class="mb-1">Detail Pendaftar</h4>
            <div class="text-muted small">Informasi lengkap pendaftar beasiswa</div>
        </div>
        <button class="btn btn-outline-primary" onclick="window.print()">🖨️ Cetak</button>
    </div>

    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <div class="text-muted mt-2">Memuat data pendaftar...</div>
    </div>

    <div id="error-container" class="alert alert-danger d-none">
        <strong>Gagal mengambil data.</strong>
        <div id="error-message"></div>
    </div>

    <div id="detail-content" class="d-none">

        <!-- PROFIL -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-primary text-white rounded">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <img id="foto-mahasiswa" src="{{ asset('images/logo.png') }}" class="profile-photo rounded border border-3 border-white" alt="Foto">
                    </div>
                    <div class="col">
                        <h3 id="nama-mahasiswa" class="mb-1"></h3>
                        <div>NIM: <span id="nim-mahasiswa"></span></div>
                        <div class="mt-2">
                            <span id="status-pendaftaran" class="badge"></span>
                            <span id="nomor-pendaftaran" class="badge bg-light text-dark"></span>
                        </div>
                        <div id="nama-beasiswa" class="small mt-2 opacity-75"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AKADEMIK -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">🎓 Data Akademik</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="info-label">NIM</div>
                        <div id="akademik-nim" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Program Studi</div>
                        <div id="program-studi" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Fakultas</div>
                        <div id="fakultas" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Tahun Masuk</div>
                        <div id="tahun-masuk" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">UKT</div>
                        <div id="ukt" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Email</div>
                        <div id="email" class="info-value"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IDENTITAS -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">👤 Identitas Pribadi</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="info-label">Tempat, Tanggal Lahir</div>
                        <div id="ttl" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Jenis Kelamin</div>
                        <div id="jenis-kelamin" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Nomor HP</div>
                        <div id="no-hp" class="info-value"></div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Alamat</div>
                        <div id="alamat-lengkap" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Disabilitas</div>
                        <div id="disabilitas" class="info-value"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ORANG TUA -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">👨‍👩‍👦 Data Orang Tua</div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold">Ayah / Bapak</h6>
                        <table class="table table-sm table-bordered mb-0">
                            <tr><th width="35%">Nama</th><td id="bapak-nama"></td></tr>
                            <tr><th>Status</th><td id="bapak-status"></td></tr>
                            <tr><th>Pekerjaan</th><td id="bapak-pekerjaan"></td></tr>
                            <tr><th>Pendidikan</th><td id="bapak-pendidikan"></td></tr>
                            <tr><th>Pendapatan</th><td id="bapak-pendapatan"></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold">Ibu</h6>
                        <table class="table table-sm table-bordered mb-0">
                            <tr><th width="35%">Nama</th><td id="ibu-nama"></td></tr>
                            <tr><th>Status</th><td id="ibu-status"></td></tr>
                            <tr><th>Pekerjaan</th><td id="ibu-pekerjaan"></td></tr>
                            <tr><th>Pendidikan</th><td id="ibu-pendidikan"></td></tr>
                            <tr><th>Pendapatan</th><td id="ibu-pendapatan"></td></tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-light border mb-0">
                            Jumlah tanggungan keluarga:
                            <strong id="tanggungan"></strong> orang
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RUMAH -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">🏠 Kondisi Rumah</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="info-label">Luas Tanah</div>
                        <div id="luas-tanah" class="info-value"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Luas Bangunan</div>
                        <div id="luas-bangunan" class="info-value"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Jumlah Penghuni</div>
                        <div id="jumlah-penghuni" class="info-value"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Kepemilikan</div>
                        <div id="kepemilikan-rumah" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">MCK</div>
                        <div id="mck" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Biaya Listrik</div>
                        <div id="listrik" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Sumber Listrik</div>
                        <div id="sumber-listrik" class="info-value"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Sumber Air</div>
                        <div id="sumber-air" class="info-value"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PENDIDIKAN -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">📚 Pendidikan Terakhir</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-label">Sekolah</div>
                        <div id="nama-sekolah" class="info-value"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Jenis</div>
                        <div id="jenis-sekolah" class="info-value"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Akreditasi</div>
                        <div id="akreditasi" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Jurusan</div>
                        <div id="jurusan" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Tahun Lulus</div>
                        <div id="tahun-lulus" class="info-value"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Nilai Akhir</div>
                        <div id="nilai-akhir" class="info-value"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RAPORT -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">📊 Nilai Rapor</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Semester</th>
                                <th>Nilai</th>
                                <th>Peringkat</th>
                            </tr>
                        </thead>
                        <tbody id="raport-list"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DOKUMEN -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">📄 Dokumen Persyaratan</div>
            <div class="card-body">
                <div id="dokumen-list" class="vstack gap-2"></div>
            </div>
        </div>

        <!-- NILAI -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">🏆 Rekapitulasi Nilai Seleksi</div>
            <div class="card-body">
                <div id="score-list" class="row g-3"></div>
                <hr>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-label">Status Kelulusan</div>
                        <div id="status-lulus" class="mt-1"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Catatan</div>
                        <div id="catatan-kelulusan" class="info-value"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL DOKUMEN -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalTitle">Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height:80vh">
                <iframe id="documentFrame" src="" class="w-100 h-100 border-0"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('template/materialm/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
$(document).ready(function() {
    const token = localStorage.getItem('access_token');
    loadDetail();

    function forceLogout() {
        localStorage.removeItem('access_token');
        window.location.replace(`${base_url}/login`);
    }

    $.ajaxSetup({
        beforeSend: function(xhr) {
            if (token) xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        },
        complete: function(xhr) {
            const authorization = xhr.getResponseHeader('Authorization');
            if (authorization) localStorage.setItem('access_token', authorization.replace('Bearer ', '').trim());
            if (xhr.status === 401) forceLogout();
        }
    });

    async function loadDetail() {
        const url = `${base_url}/api/get-rincian-detail-pendaftar/${encodeURIComponent(pendaftar_id)}`;
        try {
            const response = await execAsync(url, 'GET', token);
            const detail = response?.data;
            if (!response?.status || !detail) throw new Error(response?.message || 'Data pendaftar tidak ditemukan.');
            renderDetail(detail);
            $('#loading').addClass('d-none');
            $('#detail-content').removeClass('d-none');
        } catch (error) {
            $('#loading').addClass('d-none');
            $('#error-container').removeClass('d-none');
            $('#error-message').text(error?.message || 'Gagal mengambil data pendaftar.');
        }
    }

    function renderDetail(data) {
        const pendaftaran = data.pendaftaran || {};
        const beasiswa = data.beasiswa || {};
        const mahasiswa = data.mahasiswa || {};
        const identitas = data.identitas || {};
        const orangTua = data.orang_tua || {};
        const rumah = data.rumah || {};
        const pendidikan = data.pendidikan_akhir || {};
        const kelulusan = data.kelulusan || {};
        const nilai = kelulusan.nilai || {};

        $('#nama-mahasiswa').text(mahasiswa.nama || '-');
        $('#nim-mahasiswa').text(mahasiswa.nim || '-');
        $('#nomor-pendaftaran').text(`No. ${pendaftaran.no_pendaftaran || '-'}`);
        $('#nama-beasiswa').text(beasiswa.nama || '-');

        let statusText = 'Belum Finalisasi', statusClass = 'bg-warning text-dark';
        if (Number(pendaftaran.is_batal) === 1) {
            statusText = 'Dibatalkan';
            statusClass = 'bg-danger text-white';
        } else if (Number(pendaftaran.is_finalisasi) === 1) {
            statusText = 'Sudah Finalisasi';
            statusClass = 'bg-success text-white';
        }
        $('#status-pendaftaran').removeClass().addClass(`badge ${statusClass}`).text(statusText);
        if (identitas.foto) $('#foto-mahasiswa').attr('src', buildFileUrl(identitas.foto));

        $('#akademik-nim').text(mahasiswa.nim || '-');
        $('#program-studi').text(mahasiswa.program_studi?.nama || '-');
        $('#fakultas').text(mahasiswa.fakultas?.nama || '-');
        $('#tahun-masuk').text(mahasiswa.tahun_masuk || '-');
        $('#ukt').text(formatRupiah(mahasiswa.ukt));
        $('#email').text(mahasiswa.email || '-');

        $('#ttl').text(`${identitas.tempat_lahir || '-'}, ${formatDate(identitas.tanggal_lahir)}`);
        $('#jenis-kelamin').text(identitas.jenis_kelamin === 'L' ? 'Laki-laki' : identitas.jenis_kelamin === 'P' ? 'Perempuan' : '-');
        $('#no-hp').text(identitas.no_hp || '-');
        $('#disabilitas').text(identitas.disabilitas || 'Tidak ada');

        const alamat = [identitas.alamat?.alamat, identitas.alamat?.desa, identitas.alamat?.kecamatan, identitas.alamat?.kabupaten, identitas.alamat?.provinsi].filter(Boolean);
        $('#alamat-lengkap').text(alamat.length ? alamat.join(', ') : '-');

        const bapak = orangTua.bapak || {};
        const ibu = orangTua.ibu || {};

        $('#bapak-nama').text(bapak.nama || '-');
        $('#bapak-status').text(statusHidup(bapak.status_hidup));
        $('#bapak-pekerjaan').text(bapak.pekerjaan || '-');
        $('#bapak-pendidikan').text(bapak.pendidikan || '-');
        $('#bapak-pendapatan').text(bapak.pendapatan || '-');

        $('#ibu-nama').text(ibu.nama || '-');
        $('#ibu-status').text(statusHidup(ibu.status_hidup));
        $('#ibu-pekerjaan').text(ibu.pekerjaan || '-');
        $('#ibu-pendidikan').text(ibu.pendidikan || '-');
        $('#ibu-pendapatan').text(ibu.pendapatan || '-');
        $('#tanggungan').text(orangTua.tanggungan ?? 0);

        $('#luas-tanah').text(rumah.luas_tanah != null ? `${rumah.luas_tanah} m²` : '-');
        $('#luas-bangunan').text(rumah.luas_bangunan != null ? `${rumah.luas_bangunan} m²` : '-');
        $('#jumlah-penghuni').text(rumah.jumlah_orang_tinggal != null ? `${rumah.jumlah_orang_tinggal} orang` : '-');
        $('#kepemilikan-rumah').text(rumah.kepemilikan || '-');
        $('#mck').text(rumah.mck || '-');
        $('#listrik').text(rumah.listrik || '-');
        $('#sumber-listrik').text(rumah.sumber_listrik || '-');
        $('#sumber-air').text(rumah.sumber_air || '-');

        $('#nama-sekolah').text(pendidikan.nama_sekolah || '-');
        $('#jenis-sekolah').text(pendidikan.jenis || '-');
        $('#akreditasi').text(pendidikan.akreditasi || '-');
        $('#jurusan').text(pendidikan.jurusan || '-');
        $('#tahun-lulus').text(pendidikan.tahun_lulus || '-');
        $('#nilai-akhir').text(pendidikan.nilai_akhir || '-');

        renderRaport(data.raport || {});
        renderDokumen(data.dokumen || []);
        renderScores(nilai);
        renderStatusKelulusan(kelulusan.is_lulus, kelulusan.catatan);
    }

    function renderRaport(raport) {
        const tbody = $('#raport-list').empty();
        for (let i = 1; i <= 6; i++) {
            const semester = raport[`semester_${i}`] || {};
            const tr = $('<tr>');
            $('<td>').text(`Semester ${i}`).appendTo(tr);
            $('<td>').text(semester.nilai ?? '-').appendTo(tr);
            $('<td>').text(semester.peringkat ?? '-').appendTo(tr);
            tbody.append(tr);
        }
    }

    function renderDokumen(dokumen) {
        const container = $('#dokumen-list').empty();

        if (!dokumen.length) {
            container.html('<div class="text-muted">Tidak ada dokumen.</div>');
            return;
        }

        dokumen.forEach(function(item) {
            const wrapper = $('<div class="document-item d-flex justify-content-between align-items-center gap-3">');
            const left = $('<div>');
            $('<div class="fw-semibold">').text(item.nama || 'Dokumen').appendTo(left);
            $('<small class="text-muted">').text((item.jenis || '').toUpperCase()).appendTo(left);

            const right = $('<div class="no-print">');

            if (item.dokumen) {
                $('<button>')
                    .addClass('btn btn-sm btn-outline-primary')
                    .text('Lihat Dokumen')
                    .on('click', function() {
                        window.open(buildFileUrl(item.dokumen), '_blank', 'noopener,noreferrer');
                    })
                    .appendTo(right);
            } else {
                right.html('<span class="text-muted">Belum ada file</span>');
            }

            wrapper.append(left).append(right);
            container.append(wrapper);
        });
    }

    function renderScores(nilai) {
        const container = $('#score-list').empty();
        const scores = [
            ['Survei', nilai.survei], ['CBT', nilai.cbt], ['Berkas', nilai.berkas],
            ['Orang Tua', nilai.orang_tua], ['Rapor', nilai.raport], ['Pendidikan Akhir', nilai.pendidikan_akhir],
            ['Rumah', nilai.rumah], ['Wawancara', nilai.wawancara], ['Ekonomi', nilai.ekonomi], ['Pendidikan', nilai.pendidikan]
        ];

        scores.forEach(([label, value]) => {
            const col = $('<div class="col-6 col-md-3">');
            const box = $('<div class="border rounded p-3 text-center h-100">');
            $('<div class="small text-muted">').text(label).appendTo(box);
            $('<div class="score-value">').text(value ?? '-').appendTo(box);
            col.append(box);
            container.append(col);
        });
    }

    function renderStatusKelulusan(status, catatan) {
        let html = '';

        if (status !== null) {
            html = Number(status) === 1
                ? '<span class="badge bg-success">LULUS</span>'
                : '<span class="badge bg-danger">TIDAK LULUS</span>';
        }

        $('#status-lulus').html(html);
        $('#catatan-kelulusan').text(catatan || '');
    }

    window.openDocument = function(nama, path) {
        if (!path) return;
        $('#documentModalTitle').text(nama || 'Dokumen');
        $('#documentFrame').attr('src', buildFileUrl(path));
        bootstrap.Modal.getOrCreateInstance(document.getElementById('documentModal')).show();
    };

    function buildFileUrl(path) {
        if (!path) return '';
        if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('/')) return path;
        return `${base_url}/${path}`;
    }

    function formatRupiah(value) {
        if (value === null || value === undefined || value === '') return '-';
        return new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(value);
    }

    function formatDate(value) {
        if (!value) return '-';
        const date = new Date(value);
        if (isNaN(date.getTime())) return value;
        return date.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
    }

    function statusHidup(value) {
        if (Number(value) === 1) return 'Masih Hidup';
        if (Number(value) === 0) return 'Meninggal';
        return '-';
    }
});
</script>
</body>
</html>
