var page_verifikator = 1;

async function loadDataVerifikator() {
    let search = $('#search-input-verifikator').val();
    let response = await asyncFunction(`${base_url}/api/verifikator-laporan?page=${page_verifikator}&sk_penerima_id=${sk_penerima_id}&search=${search}`);
    renderDataVerifikator(response);
}


function renderDataVerifikator(response) {
    const dataList = $('#data-list-verifikator');
    const pagination = $('#pagination-verifikator');
    const data=response.data.data;
    let no = (response.data.current_page - 1) * response.data.per_page + 1;
    dataList.empty();
    pagination.empty();
    if (data.length > 0) {
        $.each(data, function(index, dt) {
            const row = `<tr>
                        <td>${no++}</td>
                        <td>${dt.name}</td>
                        <td>${dt.email}</td>
                        <td></td>
                        <td>
                            <button class="btn btn-danger btn-hapus-verifikator" data-id="${dt.verifikator_laporan_id}" type="button"><iconify-icon icon="solar:trash-bin-2-outline"></iconify-icon></button>
                        </td>
                    </tr>`;
            dataList.append(row);
        });
        renderPagination(response.data, pagination);
    }else{
        const row = `<tr>
                        <td colspan="7">data tidak ditemukan</td>
                    </tr>`;
        dataList.append(row);                
    }
}    

//untuk show modal form
function showModalFormVerifikator() {
    var fModalForm = new bootstrap.Modal(document.getElementById('modal-verifikator'), {
        keyboard: false
    });
    fModalForm.show();
    loadDataVerifikator();
}

$(document).ready(function() {
    
    $('#search-input-verifikator').on('keypress', async function(e) {
        if (e.which === 13) {       // 13 = Enter
            e.preventDefault();      
            loadDataVerifikator(); 
        }
    });

    $('#btn-import-penerima').click(function(){
        const url = `${base_url}/import-penerima-beasiswa/${sk_penerima_id}`;
        window.open(url, '_blank');
    })

    // Handle page change
    $(document).on('click', '.nav-verifikator .page-link', function() {
        page_verifikator = $(this).data('page');
        loadDataVerifikator();
    });

    function formVerifikatorReset(){
        $('#form-verifikator').trigger('reset');
        $('#form-verifikator input[type="hidden"]').val('');
    }

    $('#btn-tambah').click(function() {
        formVerifikatorReset();
        showModalFormVerifikator();
    });

    $('#btn-refresh-verifikator').click(function() {
        loadDataVerifikator();
    });
    
    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $("#form-verifikator").validate({
        submitHandler: function(form) {
            const id = $('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? '/api/verifikator-laporan' : '/api/verifikator-laporan/' + id;
            let dataArr = $(form).serializeArray();
            dataArr.push({
                name: 'sk_penerima_id',
                value: sk_penerima_id
            });
            let dataPayload = $.param(dataArr);

            saveData(base_url+url, type, dataPayload, function(response) {                    
                appShowNotification(true,['berhasil dilakukan!']);
                if(type=='POST'){
                    formVerifikatorReset();
                }
                loadDataVerifikator();
            });
        }
    });
    
    //hapus data
    $(document).on('click', '.btn-hapus-verifikator', function() {
        const id = $(this).attr('data-id');
            if(id!=="")
            deleteData(base_url+'/api/verifikator-laporan', id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataVerifikator();
            });
    });
    

    $(document).on('click', '.btn-daftar-verifikator', function() {
        const id = $(this).attr('data-id');
        const perihal = $(this).attr('data-perihal');
        sk_penerima_id=id;
        $('#modal-verifikator .judul-modal').text(perihal);
        showModalFormVerifikator();
    });


    $("#cari_verifikator").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: `${base_url}/api/pengguna?role=verifikator&limit=5`,
                dataType: "json",
                data: {
                    search: request.term,
                },
                headers: {
                    Authorization: `Bearer ${token}`
                },
                success: function(res) {
                    response($.map(res.data.data, function(item) {
                        return {
                            label: item.name, // fallback
                            value: item.name, // yang muncul di input setelah dipilih
                            data: {
                                nama: item.name,
                                hp: item.hp,
                                email: item.email,
                                foto_url: `${base_url}/${item.foto}`, // pastikan path lengkap
                                user_id: item.user_id
                            }
                        };
                    }));
                    
                }
            });
        },
        minLength: 3,
        appendTo: "#modal-verifikator",
        select: function(event, ui) {
            const dt = ui.item.data;

            if (confirm(`Tambah verifikator atas nama ${dt.nama} ?`)) {
                const dataPost = {
                    user_id: dt.user_id,
                    sk_penerima_id: sk_penerima_id,
                };
                const urlPost=base_url+'/api/verifikator-laporan';

                saveData(urlPost, 'POST', $.param(dataPost), function(response) {
                    appShowNotification(true, ['berhasil dilakukan!']);
                    loadDataVerifikator();
                });
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        const m = item.data;
        return $("<li>")
            .append(`
                <div class="d-flex align-items-center gap-3 p-2">
                    <img src="${m.foto_url}" style="width: 50px; height: auto; border-radius: 4px;">
                    <div>
                        <div class="fw-bold">${m.nama}</div>
                        <div class="text-muted">Email : ${m.email}</div>
                    </div>
                </div>
            `)
            .appendTo(ul);
    };
});