var page_sk = 1;

async function loadDataMonitoring() {
    let respon = await asyncFunction(`${base_url}/api/monitoring`);
    const list = $('#monitoring_id').empty();

    list.append(`<option value="">- pilih -</option>`);

    if(respon.data.total>0)
        $.each(respon.data.data, function(index, item) {
            list.append(`
                <option value="${item.id}">
                    ${item.nama}
                </option>
            `);
        });
}

async function loadDataSK(set_default=true) {
    let search = $('#search-input').val();
    let response = await asyncFunction(`${base_url}/api/sk-penerima?page=${page_sk}&search=${search}`);

    renderData(response);
}

function renderData(response) {
    const dataList = $('#data-list');
    const pagination = $('#pagination');
    const data=response.data.data;
    let no = (response.data.current_page - 1) * response.data.per_page + 1;
    dataList.empty();
    pagination.empty();
    if (data.length > 0) {
        $.each(data, function(index, dt) {
            const pejabat_ttd=(dt.ttd_nama)?dt.ttd_nama+'/ '+dt.ttd_jabatan:"";
            const monitoring=(dt.monitoring)?`<span class="badge rounded-pill bg-primary fs-2">${dt.monitoring.nama}</span>`:"";

            let verifikator_laporan = '';
            if (dt.verifikator_laporan?.length > 0) {
                verifikator_laporan = `<ul id="daftar-verifikator">${dt.verifikator_laporan.map(v => `<li>${v.user.name}</li>`).join('')}</ul>`;
            }

            const row = `<tr>
                        <td>${no++}</td>
                        <td>${dt.tanggal_sk.substring(0, 4)}</td>
                        <td>${dt.nama} <div>${monitoring}</div></td>
                        <td>${dt.nomor_sk}/ ${dt.tanggal_sk}</td>
                        <td>${pejabat_ttd}</td>
                        <td>${dt.penerima_count}</td>
                        <td>${verifikator_laporan}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item btn-daftar-penerima" data-perihal="${dt.nama}" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:notebook-broken"></iconify-icon> Daftar Penerima</a></li>
                                    <li><a class="dropdown-item btn-daftar-verifikator" data-perihal="${dt.nama}" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:user-check-linear"></iconify-icon> Verifikator Monitoring</a></li>
                                    <li><a class="dropdown-item btn-ganti-sk" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:pen-new-round-outline"></iconify-icon> Ganti</a></li>
                                    <li><a class="dropdown-item btn-hapus-sk" data-id="${dt.id}" href="javascript:;"><iconify-icon icon="solar:trash-bin-2-outline"></iconify-icon> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>`;
            dataList.append(row);
        });
        renderPagination(response.data, pagination);
    }else{
        const row = `<tr>
                        <td colspan="8">data tidak ditemukan</td>
                    </tr>`;
        dataList.append(row);                
    }
}    

//untuk show modal form
function showModalFormSK() {
    var fModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
        keyboard: false
    });
    fModalForm.show();
}


$(document).ready(function() {
    initPage();
    async function initPage() { // agar di load secara berurutan
        await loadDataMonitoring();
        await loadDataSK();
    }

    $('#btn-cari-sk').click(function(){
        page_sk=1;
        loadDataSK();
    })

    // $('#search-input').on('keypress', async function(e) {
    //     if (e.which === 13) {       // 13 = Enter
    //         e.preventDefault();      
    //         await loadDataSK(); 
    //     }
    // });
    
    // Handle page change
    $(document).on('click', '.nav-sk .page-link', function() {
        page_sk = $(this).data('page');
        loadDataSK();
    });

    $('#btn-refresh-sk').click(function() {
        loadDataSK();
    });     

    function formSkReset(){
        $('#form-sk').trigger('reset');
        $('#form-sk input[type="hidden"]').val('');
    }

    $('#btn-tambah-sk').click(function() {
        formSkReset();
        showModalFormSK();    
    });    

    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $("#form-sk").validate({
        submitHandler: function(form) {
            const id = $('#form-sk #id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? '/api/sk-penerima' : '/api/sk-penerima/' + id;

            console.log(id,type,url)

            saveData(base_url+url, type, $(form).serialize(), function(response) {                    
                appShowNotification(true,['berhasil dilakukan!']);
                if(type=='POST'){
                    formSkReset();
                }           
                loadDataSK();

            });

        }
    });

    //ganti data
    $(document).on('click', '.btn-ganti-sk', function() {
        const id = $(this).attr('data-id');
        showDataById(base_url+'/api/sk-penerima', id, function(response) {
            $('#form-sk #id').val(response.data.id);
            $('#form-sk #nomor_sk').val(response.data.nomor_sk);
            $('#form-sk #tanggal_sk').val(response.data.tanggal_sk);
            $('#form-sk #ttd_jabatan').val(response.data.ttd_jabatan);
            $('#form-sk #ttd_nama').val(response.data.ttd_nama);
            $('#form-sk #monitoring_id').val(response.data.monitoring_id);
            $('#form-sk #nama').val(response.data.nama);
            
            showModalFormSK();
        });
    });

    //hapus data
    $(document).on('click', '.btn-hapus-sk', function() {
        const id = $(this).attr('data-id');
            if(id!=="")
            deleteData(base_url+'/api/sk-penerima', id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataSK();
            });
    });
        
});