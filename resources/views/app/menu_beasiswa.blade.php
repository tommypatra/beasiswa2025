      <?php $id=$beasiswa_id;?>
      <div class="card w-100">
        <div class="card-body">
          <ul class="list-unstyled mb-0">

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-dashboard">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:book-2-linear" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('dashboard-beasiswa/'.$id) }}">
                        <h6 class="mb-1 fs-3">Dashboard</h6>
                    </a>
                </div>
              </div>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-pendaftar">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:users-group-rounded-outline" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('pendaftar/'.$id) }}">
                        <h6 class="mb-1 fs-3">Pendaftar</h6>
                    </a>
                </div>
              </div>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-syarat">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:checklist-linear" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('syarat/'.$id) }}">
                        <h6 class="mb-1 fs-3">Syarat</h6>
                    </a>
                </div>
              </div>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-verifikator">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:user-check-linear" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('verifikator/'.$id) }}">
                        <h6 class="mb-1 fs-3">Verifikator</h6>
                    </a>
                </div>
              </div>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-surveyor" style="display:none !important">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:user-hands-outline" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('surveyor/'.$id) }}">
                        <h6 class="mb-1 fs-3">Surveyor</h6>
                    </a>
                </div>
              </div>
            </li>
            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-pewawancara" style="display:none !important">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:user-speak-rounded-outline" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('pewawancara/'.$id) }}">
                        <h6 class="mb-1 fs-3">Pewawancara</h6>
                    </a>
                </div>
              </div>
            </li>

            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-soal" style="display:none !important">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:notebook-minimalistic-outline" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('soal-wawancara/'.$id) }}">
                        <h6 class="mb-1 fs-3">Soal Wawancara</h6>
                    </a>
                </div>
              </div>
            </li>
            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-kelulusan">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-primary-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="solar:notebook-linear" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url('kelulusan/'.$id) }}">
                        <h6 class="mb-1 fs-3">Kelulusan</h6>
                    </a>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
