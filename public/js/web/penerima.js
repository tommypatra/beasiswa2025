var page_penerima = 1;
var penerima_keterangan_default = '';

async function loadDataPenerima() {
    let search = $('#search-input-penerima').val();
    let response = await asyncFunction(`${base_url}/api/penerima?page=${page_penerima}&sk_penerima_id=${sk_penerima_id}&search=${search}`);
    renderDataPenerima(response);
}

function renderDataPenerima(response) {
    const dataList = $('#data-list-penerima');
    const pagination = $('#pagination-penerima');
    const data=response.data.data;
    let no = (response.data.current_page - 1) * response.data.per_page + 1;
    dataList.empty();
    pagination.empty();
    if (data.length > 0) {
        $.each(data, function(index, dt) {

            let buku_rekening=`<span class="badge text-bg-danger fs-2">belum terupload</span>`;
            if(dt.terupload_buku_rekening_id){
                buku_rekening=`
                <a href="${base_url}/${dt.terupload_foto_buku}" target="_blank">
                    ${dt.terupload_bank} ${dt.terupload_nomor} ${dt.terupload_nama_pemilik}
                </a> 
                <span class="badge text-bg-success fs-2">sudah sinkron</span>
            `;
            }else{
                if(dt.tersedia_buku_rekening_id){
                    buku_rekening=`
                    <a href="${base_url}/${dt.tersedia_foto_buku}" target="_blank">
                        ${dt.tersedia_bank} ${dt.tersedia_nomor} ${dt.tersedia_nama_pemilik} 
                    </a>
                    <span class="badge text-bg-warning fs-2">belum sinkron</span>
                    `;
                }
            }
            const kirim_wa = getWhatsAppLink(dt.is_mobile_dev, dt.no_hp, `_Bismillah_, ${dt.name.toLowerCase()}`);

            const row = `<tr>
                        <td>${no++}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3 p-2">
                                <img src="${base_url}/${dt.foto}" style="width: 70px; height: auto; border-radius: 2px;">
                                <div>
                                    <div class="fw-bold">${dt.name}/ ${dt.nim}</div>
                                    <div>${dt.email}</div>
                                    <div><a href="${kirim_wa.link}" target="_blank">${kirim_wa.nomor}</a></div>
                                    <div style="font-size:12px;font-style:italic;">Kota/Kab. ${dt.kabupaten}, Prov. ${dt.provinsi}</div>
                                </div>
                            </div>
                        </td>
                        <td>${dt.program_studi}</td>
                        <td>${dt.fakultas}</td>
                        <td>${buku_rekening}</td>
                        <td>
                            <textarea class="form-control keterangan_penerima" rows="2" 
                            data-id="${dt.penerima_id}" data-user_id="${dt.user_id}">${showText(dt.keterangan)}</textarea>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-hapus-penerima" data-id="${dt.penerima_id}" type="button"><iconify-icon icon="solar:trash-bin-2-outline"></iconify-icon></button>
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
async function showModalFormPenerima() {
    var fModalForm = new bootstrap.Modal(document.getElementById('modal-penerima'), {
        keyboard: false
    });
    fModalForm.show();
    loadDataPenerima();
    await statistikPenerima();
}

$(document).ready(function() {
    
    // $('#search-input-penerima').on('keypress', async function(e) {
    //     if (e.which === 13) {       // 13 = Enter
    //         e.preventDefault();      
    //         await loadDataPenerima(); 
    //     }
    // });

    $('#btn-cari-penerima').click(function(){
        page_penerima=1;
        loadDataPenerima();
    })

    // Handle page change
    $(document).on('click', '.nav-penerima .page-link', function() {
        page_penerima = $(this).data('page');
        loadDataPenerima();
    });

    function formPenerimaReset(){
        $('#form-penerima').trigger('reset');
        $('#form-penerima input[type="hidden"]').val('');
    }

    $('#btn-tambah').click(function() {
        formPenerimaReset();
        showModalFormPenerima();
    });
    
    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $("#form-penerima").validate({
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


    // Handle on focus keterangan penerima to save default value before change
    $(document).on('focus', '.keterangan_penerima', function() {
        penerima_keterangan_default = $(this).val();
    });

    // Handle on change/blur keterangan penerima
    $(document).on('blur', '.keterangan_penerima', function() {
        const id = $(this).attr('data-id');
        const user_id = $(this).attr('data-user_id');
        const keterangan = $(this).val();
        const url = `${base_url}/api/penerima/${id}`;

        if (keterangan.trim() !== "" && keterangan !== penerima_keterangan_default){
            saveData(url, 'PUT', {keterangan,sk_penerima_id,user_id}, function(response) {
                appShowNotification(true,['Berhasil dilakukan!']);
            });

        }
    });
    
    //hapus data
    $(document).on('click', '.btn-hapus-penerima', function() {
        const id = $(this).attr('data-id');
            if(id!=="")
            deleteData(base_url+'/api/penerima', id, function() {
                appShowNotification(true,['berhasil dilakukan!']);
                loadDataPenerima();
            });
    });
    
    $('#btn-refresh-penerima').click(function() {
        loadDataPenerima();
    });

    $(document).on('click', '.btn-daftar-penerima', function() {
        const id = $(this).attr('data-id');
        const perihal = $(this).attr('data-perihal');
        sk_penerima_id=id;
        page_penerima=1;
        $('#modal-penerima .judul-modal').text(perihal);

        showModalFormPenerima();
    });


    $("#cari_mahasiswa").autocomplete({
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
        appendTo: "#modal-penerima",
        select: function(event, ui) {
            const mhs = ui.item.data;

            if (confirm(`Tambah mahasiswa atas nama ${mhs.name} NIM: ${mhs.nim} sebagai penerima beasiswa?`)) {
                const dataPost = {
                    user_id: mhs.user_id,
                    sk_penerima_id: sk_penerima_id,
                    keterangan: ''
                };
                const urlPost=base_url+'/api/penerima';

                // console.log(dataPost,urlPost);
                saveData(base_url + '/api/penerima', 'POST', $.param(dataPost), function(response) {
                    appShowNotification(true, ['berhasil dilakukan!']);
                    loadDataPenerima();
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

    $('#btn-import-penerima').click(function(){
        const url = `${base_url}/import-penerima-beasiswa/${sk_penerima_id}`;
        window.open(url, '_blank');
    })

    $('#btn-cetak-penerima').click(function(){
        const url = `${base_url}/cetak-penerima-mahasiswa/${sk_penerima_id}`;
        window.open(url, '_blank');
    })

    $('#btn-sinkron-rekening').click(async function(){
        if(confirm('apakah anda yakin sinkron rekening sekarang?')){
            let response = await asyncFunction(`${base_url}/api/sinkron-rekening/${sk_penerima_id}`);
            if(response.status){
                if(response.data>0){
                    appShowNotification(true,[`sinkron ${response.data} nomor rekening berhasil dilakukan`]);
                    loadDataPenerima();
                    await statistikPenerima();
                }else{
                    alert("tidak ada proses sinkron nomor rekening");
                }
            }
        }
    })

});