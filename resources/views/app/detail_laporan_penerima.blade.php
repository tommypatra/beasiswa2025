@extends('template')

@section('scriptHead')
<title>Detail Laporan Penerima Beasiswa</title>
@endsection

@section('container')
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Detail Laporan Penerima Beasiswa</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" id="search-input" placeholder="Cari..." style="max-width: 200px;">
                <button class="btn btn-primary" id="btn-search">
                    <i class="ti ti-search"></i>
                </button>
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
                <button class="btn btn-secondary" id="btn-filter">
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </div>
        
        <div id="detail-laporan" class="vstack gap-3 "></div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="form">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Nama</label>
                            <input name="nama" id="nama" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Singkatan</label>
                            <input name="singkatan" id="singkatan" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Urut</label>
                            <input name="urut" id="urut" type="number" class="form-control" required>
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
@endsection

@section('scriptJs')
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url+'/api/laporan';
    var sk_penerima_id = "{{ $sk_penerima_id }}";
    var page = 1;
    $(document).ready(function() {
        dataLoad();

        function renderData(response) {
            const dataList = $('#detail-laporan');
            const pagination = $('#pagination');
            const data=response.data;
            let no = (response.current_page - 1) * response.per_page + 1;
            dataList.empty();
            pagination.empty();

            if (data.length > 0) {
                $.each(data, function(index, dt) {
                    const foto = dt.foto ? `${base_url}/${dt.foto}`: null;
                    
                    const img = `<img src="${foto || 'https://via.placeholder.com/64x64?text=IMG'}" class="rounded border" style="width:70px;height:70px;object-fit:cover" alt="foto">`;
                    const row=`
                    <div class="card shadow-sm table-responsive">
                        <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="fs-5 fw-semibold text-secondary">#${no++}</div>
                            ${img}
                            <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                <div class="fw-semibold">${dt.name}</div>
                                <div class="text-muted small">NIM: ${dt.nim}</div>
                                <div class="mt-1">
                                    <span class="badge text-bg-light border me-1">${dt.prodi}</span>
                                    <span class="badge text-bg-light border">${dt.fakultas}</span>
                                </div>
                                </div>
                                <div class="text-end small text-muted">
                                <div>${dt.email}</div>
                                <div>${dt.no_hp}</div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <hr class="my-3">
                            ${daftarDokumen(dt)}
                        </div>
                    </div>`;
                    dataList.append(row);
                });
                renderPagination(response.data, pagination);
            }else{
                const row = `<div>
                                data tidak ditemukan
                            </div>`;
                dataList.append(row);                
            }
        }    

        function daftarDokumen(data){
            let accordion=`<div class="accordion accordion-flush " id="accordion-dokumen-${data.penerima_id}">`;
            if (data.kegiatan.length > 0) {
                $.each(data.kegiatan, function(index, dt) {
                    const item_id = `${data.penerima_id}-${dt.kegiatan_id}`;
                    let totalLaporan = 0;
                    let laporanBaru = 0;
                    let laporan = `<div class="list-group list-group-flush table-responsive">`; 
            
                    if(dt.sub_kegiatans && dt.sub_kegiatans.length){
                        dt.sub_kegiatans.forEach(sk => {

                            laporan += `<div class="list-group-item ">
                                            <div class="fw-semibold">${sk.sub_kegiatan_nama}</div>`;

                            if(sk.laporans && sk.laporans.length){
                                laporan += `<div class="list-group mt-2">`;

                                sk.laporans.forEach(l => {
                                    const filename = l.path.split('/').pop();
                                    totalLaporan++;

                                    let tanda_baru = '';
                                    let is_baru = false;
                                    let btn_verifikasi=`<a href="javascript:;" class="btn btn-sm btn-outline-primary ms-auto">Verifikasi</a>`;
                                    if(l.verifikasi_hasil == null){
                                        laporanBaru++;
                                        is_baru=true;
                                        tanda_baru=`<span class="badge bg-danger position-absolute top-0 end-0 translate-middle p-1"">
                                                        <iconify-icon icon="solar:check-read-outline" class=""></iconify-icon>
                                                    </span>`;
                                    }
                                    btn_verifikasi=``;

                                    laporan += `<div class="list-group-item d-flex align-items-center">
                                                    <span class="small text-muted">
                                                        <a href="${base_url}/${l.path}" target="_blank">${filename}</a>
                                                    </span> 
                                                    ${btn_verifikasi}
                                                    ${tanda_baru}                                                    
                                                </div>`;
                                });
                                laporan += `</div>`;
                            } else {
                                laporan += `<div class="text-muted small mt-1">Belum ada laporan.</div>`;
                            }

                            laporan += `</div>`;
                        });
                    }
                    laporan += `</div>`;

                    accordion+=`    
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#item-${item_id}" aria-expanded="false" aria-controls="item-${item_id}">
                                    <div class="w-100 d-flex align-items-center position-relative">
                                        <span>${dt.kegiatan_nama}</span>
                                        <span class="badge bg-primary position-absolute top-50 end-0 translate-middle-y me-2">
                                            ${totalLaporan}
                                        </span>
                                        ${ laporanBaru > 0 
                                            ? `<span class="badge bg-danger position-absolute top-0 end-0 translate-middle p-1" style="font-size:10px;">
                                                    ${laporanBaru}
                                                </span>`
                                            : '' 
                                        }
                                    </div>
                                </button>
                            </h2>
                            <div id="item-${item_id}" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    ${laporan}
                                </div>
                            </div>
                        </div>`;
                });
            }
            accordion+=`</div>`;
            
            return accordion;   
        }

        function dataLoad() {
            var search = $('#search-input').val();
            var url = `${base_url}/api/detail-laporan/${sk_penerima_id}?&page=${page}&search=${search}`;

            fetchData(url, function(response) {
                renderData(response);
            },true);
        }

        // Handle page change
        $(document).on('click', '.page-link', function() {
            page = $(this).data('page');
            dataLoad();
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $('#btn-search').click(function(){
            page=1;
            dataLoad();
        });

        //untuk show modal form
        function showModalForm() {
            var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
                keyboard: false
            });
            fModalForm.show();
        }

        function formReset(){
            $('#form').trigger('reset');
            $('#form input[type="hidden"]').val('');
        }

        // Handle page change
        $('#btn-tambah').click(function() {
            formReset();
            showModalForm();    
        });

        //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
        $("#form").validate({
            submitHandler: function(form) {
                const id = $('#id').val();
                const type = (id === '') ? 'POST' : 'PUT';
                const url = (id === '') ? endpoint : endpoint + '/' + id;
                saveData(url, type, $(form).serialize(), function(response) {
                    //jika berhasil
                    appShowNotification(true,['berhasil dilakukan!']);
                    if(type=='POST'){
                        formReset();
                    }
                    dataLoad();
                });
            }
        });

        //ganti data
        $(document).on('click', '.btn-ganti', function() {
            const id = $(this).data('id');
            showDataById(endpoint, id, function(response) {
                $('#id').val(response.data.id);
                $('#singkatan').val(response.data.singkatan);
                $('#urut').val(response.data.urut);
                $('#nama').val(response.data.nama);
                showModalForm();
            });
        });

        //hapus data
        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id');
            deleteData(endpoint, id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                dataLoad();
            });
        });

    });
</script>
@endsection