<!-- ---------------------------------- -->
<!-- Pengelola -->
<!-- ---------------------------------- -->
<li class="nav-small-cap menu-pengelola">
    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
    <span class="hide-menu">Pengelola</span>
</li>
{{-- <li class="sidebar-item menu-pengelola">
    <a class="sidebar-link justify-content-between" 
        href="{{ route('verifikasi-peserta') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
        <span class="d-flex">
            <iconify-icon icon="solar:list-check-linear" class=""></iconify-icon>
        </span>
        <span class="hide-menu">Beasiswa</span>
        </div>
    </a>
</li> --}}
<li class="sidebar-item menu-pengelola">
    <a class="sidebar-link justify-content-between" 
        href="{{ route('registrasi-peserta') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
        <span class="d-flex">
            <iconify-icon icon="solar:user-check-linear" class=""></iconify-icon>
        </span>
        <span class="hide-menu">Registrasi Wawancara</span>
        </div>
    </a>
</li>
<li class="sidebar-item menu-pengelola">
    <a class="sidebar-link justify-content-between" 
        href="{{ route('sk-penerima') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
        <span class="d-flex">
            <iconify-icon icon="solar:notebook-broken" class=""></iconify-icon>
        </span>
        <span class="hide-menu">SK Penerima Beasiswa</span>
        </div>
    </a>
</li>
<li class="sidebar-item menu-pengelola">
    <a class="sidebar-link justify-content-between" 
        href="{{ route('verifikasi-laporan') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
        <span class="d-flex">
            <iconify-icon icon="solar:checklist-minimalistic-outline" class=""></iconify-icon>
        </span>
        <span class="hide-menu">Verifikasi Laporan</span>
        </div>
    </a>
</li>