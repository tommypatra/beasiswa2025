var page_jadwal = 1;

async function loadDataJadwalMonitoring() {
    let search = $('#search-input-jadwal-monitoring').val();
    let response = await asyncFunction(`${base_url}/api/jadwal-monitoring?page=${page_jadwal}&sk_penerima_id=${sk_penerima_id}&search=${search}`);
    renderDataJadwalMonitoring(response);
}

function renderDataJadwalMonitoring(response) {
    const dataList = $('#data-list-jadwal-monitoring');
    const pagination = $('#pagination-jadwal-monitoring');
    const data=response.data.data;
    let no = (response.data.current_page - 1) * response.data.per_page + 1;
    dataList.empty();
    pagination.empty();
    if (data.length > 0) {
        $.each(data, function(index, dt) {
            const row = `<tr>
                        <td>${no++}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3 p-2">
                                <img src="${base_url}/${dt.foto}" style="width: 70px; height: auto; border-radius: 2px;">
                                <div>
                                    <div class="fw-bold">${dt.name}/ ${dt.nim}</div>
                                    <div style="font-size:12px;font-style:italic;">Alamat: ${dt.alamat} Kel/Desa ${dt.desa}, Kec. ${dt.kecamatan}, Kota/Kab. ${dt.kabupaten}, Prov. ${dt.provinsi}</div>
                                </div>
                            </div>
                        </td>
                        <td>${dt.program_studi}</td>
                        <td>${dt.fakultas}</td>
                        <td>${showText(dt.keterangan)}</td>
                        <td>
                            <button class="btn btn-danger btn-hapus-jadwal-monitoring" data-id="${dt.penerima_id}" type="button"><iconify-icon icon="solar:trash-bin-2-outline"></iconify-icon></button>
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
function showModalFormJadwalPenerima() {
    var fModalForm = new bootstrap.Modal(document.getElementById('modal-jadwal-monitoring'), {
        keyboard: false
    });
    fModalForm.show();
    loadDataJadwalMonitoring();
}

$(document).ready(function() {
    
    $('#search-input-jadwal-monitoring').on('keypress', async function(e) {
        if (e.which === 13) {       // 13 = Enter
            e.preventDefault();      
            await loadDataJadwalMonitoring(); 
        }
    });

    // Handle page change
    $(document).on('click', '.nav-jadwal-monitoring .page-link', function() {
        page_jadwal = $(this).data('page');
        loadDataJadwalMonitoring();
    });

    function formPenerimaReset(){
        $('#form-jadwal-monitoring').trigger('reset');
        $('#form-jadwal-monitoring input[type="hidden"]').val('');
    }

    $('#btn-tambah').click(function() {
        formPenerimaReset();
        showModalFormJadwalPenerima();
    });
    
    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $("#form-jadwal-monitoring").validate({
        submitHandler: function(form) {
            const id = $('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? '/api/butir-kegiatan' : '/api/butir-kegiatan/' + id;
            let dataArr = $(form).serializeArray();
            dataArr.push({
                name: 'kegiatan_id',
                value: $('#label_kegiatan').attr('data-id')
            });
            let dataPayload = $.param(dataArr);

            saveData(base_url+url, type, dataPayload, function(response) {                    
                appShowNotification(true,['berhasil dilakukan!']);
                if(type=='POST'){
                    formPenerimaReset();
                }
                loadDataSK();
            });
        }
    });
    
    //hapus data
    $(document).on('click', '.btn-hapus-jadwal-monitoring', function() {
        const id = $(this).attr('data-id');
            if(id!=="")
            deleteData(base_url+'/api/jadwal-monitoring', id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataJadwalMonitoring();
            });
    });
    
    $('#btn-refresh-jadwal-monitoring').click(function() {
        loadDataJadwalMonitoring();
    });

    $(document).on('click', '.btn-jadwal-monitoring', function() {
        const id = $(this).attr('data-id');
        const perihal = $(this).attr('data-perihal');
        sk_penerima_id=id;
        $('#modal-jadwal-monitoring .judul-modal').text(perihal);
        showModalFormJadwalPenerima();
    });


    $("#cari_jadwal").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: `${base_url}/api/cari-mahasiswa?limit=5`,
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
                                name: item.name,
                                nim: item.nim,
                                prodi: item.program_studi,
                                fakultas: item.fakultas,
                                foto_url: `${base_url}/${item.foto}`, // pastikan path lengkap
                                user_id: item.user_id
                            }
                        };
                    }));
                    
                }
            });
        },
        minLength: 3,
        appendTo: "#modal-jadwal-monitoring",
        select: function(event, ui) {
            const mhs = ui.item.data;

            if (confirm(`Tambah mahasiswa atas nama ${mhs.name} NIM: ${mhs.nim} sebagai penerima beasiswa?`)) {
                const dataPost = {
                    user_id: mhs.user_id,
                    sk_penerima_id: sk_penerima_id,
                    keterangan: ''
                };
                const urlPost=base_url+'/api/jadwal-jadwal-monitoring';

                console.log(dataPost,urlPost);
                saveData(base_url + '/api/jadwal-jadwal-monitoring', 'POST', $.param(dataPost), function(response) {
                    appShowNotification(true, ['berhasil dilakukan!']);
                    loadDataJadwalMonitoring();
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
                        <div class="fw-bold">${m.name}</div>
                        <div class="text-muted">NIM: ${m.nim}</div>
                        <div class="text-muted">${m.prodi}</div>
                        <div class="text-muted">${m.fakultas}</div>
                    </div>
                </div>
            `)
            .appendTo(ul);
    };
});