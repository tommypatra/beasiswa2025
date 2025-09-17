<!-- ---------------------------------- -->
<!-- Mahasiswa -->
<!-- ---------------------------------- -->
<li class="nav-small-cap menu-mahasiswa">
    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
    <span class="hide-menu">Beasiswa</span>
</li>
<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('pendaftar') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:documents-outline" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Pendaftaran Beasiswa</span>
    </div>
  </a>
</li>
{{-- <li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('sk-penerima-beasiswa') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:book-2-linear" class=""></iconify-icon>
      </span>
      <span class="hide-menu">SK Penerima Beasiswa</span>
    </div>
  </a>
</li> --}}

<li class="nav-small-cap menu-mahasiswa">
    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
    <span class="hide-menu">Data Mahasiswa</span>
</li>
<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('identitas') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:user-id-linear" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Identitas Pengguna</span>
    </div>
  </a>
</li>

<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('mahasiswa') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:card-2-outline" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Data Mahasiswa</span>
    </div>
  </a>
</li>
<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('orang-tua') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:users-group-rounded-outline" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Data Orang Tua</span>
    </div>
  </a>
</li>
<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('rumah') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:home-outline" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Data Rumah</span>
    </div>
  </a>
</li>
<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{ route('pendidikan-akhir') }}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:square-academic-cap-2-outline" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Data Sekolah (SMA)</span>
    </div>
  </a>
</li>
<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{route('nilai-raport')}}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:notebook-minimalistic-outline" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Nilai Raport</span>
    </div>
  </a>
</li>

<li class="sidebar-item menu-mahasiswa">
  <a class="sidebar-link justify-content-between" 
    href="{{route('buku-rekening')}}" aria-expanded="false">
    <div class="d-flex align-items-center gap-3">
      <span class="d-flex">
        <iconify-icon icon="solar:notebook-line-duotone" class=""></iconify-icon>
      </span>
      <span class="hide-menu">Buku Rekening</span>
    </div>
  </a>
</li>