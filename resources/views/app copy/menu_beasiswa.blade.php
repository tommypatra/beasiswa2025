      <?php 
      $id=$beasiswa_id;
      $segmel_url=request()->segment(1);
      $menu=[
        ['color'=>'primary','label'=>'Dashboard','icon'=>'solar:book-2-linear','url'=>'dashboard-beasiswa'],
        ['color'=>'primary','label'=>'Pendaftar','icon'=>'solar:users-group-rounded-outline','url'=>'pendaftar'],
        ['color'=>'primary','label'=>'Syarat','icon'=>'solar:checklist-linear','url'=>'syarat'],
        ['color'=>'primary','label'=>'Verifikator','icon'=>'solar:user-check-linear','url'=>'verifikator'],
        ['color'=>'primary','label'=>'Surveyor','icon'=>'solar:user-hands-outline','url'=>'surveyor'],
        ['color'=>'primary','label'=>'Pewawancara','icon'=>'solar:user-speak-rounded-outline','url'=>'pewawancara'],
        ['color'=>'primary','label'=>'Soal Wawancara','icon'=>'solar:notebook-minimalistic-outline','url'=>'soal-wawancara'],
        ['color'=>'primary','label'=>'Kelulusan','icon'=>'solar:notebook-linear','url'=>'kelulusan'],
      ];
      ?>
      <div class="card w-100">
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <?php foreach($menu as $i => $list){ 
              $color='secondary';
              $style='';
              if($segmel_url==$list['url']){
                $color='primary';
                $style='style="font-weight:bold;"';
              }  
            ?>
            <li class="d-flex align-items-center justify-content-between py-10 border-bottom" id="menu-web-dashboard">
              <div class="d-flex align-items-center">
                <div class="rounded-circle-shape bg-{{ $color }}-subtle me-3 rounded-pill d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="{{ $list['icon'] }}" class="fs-7 text-body-color"></iconify-icon>
                </div>
                <div>
                    <a href="{{ url($list['url'].'/'.$id) }}">
                        <span class="mb-1 fs-3" {!! $style !!}>{{ $list['label'] }}</span>
                    </a>
                </div>
              </div>
            </li>
            <?php } ?>
        </div>
      </div>
