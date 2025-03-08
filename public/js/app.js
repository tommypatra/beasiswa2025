function appShowNotification(vStatus, vPesan) {
    let vIcon = "success";
    let vTitle = "Berhasil";
    if (!vStatus) {
        vIcon = "error";
        vTitle = "Terjadi Kesalahan...";
    }
    let pesan = "";
    $.each(vPesan, function (key, value) {
        pesan += value;
        if (key + 1 < vPesan.length)
        pesan += ",";
        pesan += "<br>";
    });

    Swal.fire({
        icon: vIcon,
        title: vTitle,
        html: pesan,
    })
}

function appPilihAkses(hakakses){
    let pilih='<ul>';	
    let link;
    jQuery.each(hakakses, function(index, item) {
        link = "{{ route('akun-set-akses', ['grup_id' => ':grup_id']) }}";
        link = link.replace(':grup_id', item.grup_id);
        pilih+='<li><a href="'+link+'">'+item.grup.grup+'</a></li>';
    });			
    pilih+='</ul>';	
    return pilih;
}

function convertTimestamp(utcTimestamp) {
    var utcDate = new Date(utcTimestamp);
    var formattedDate = utcDate.toISOString().slice(0, 19).replace('T', ' ');
    return formattedDate;
}

var my_date_format = function (input) {
    var d = new Date(Date.parse(input.replace(/-/g, "/")));
    var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 
    'Nov', 'Dec'];
    var date = d.getDay().toString() + " " + month[d.getMonth().toString()] + ", " + 
    d.getFullYear().toString();
    return (date);
}; 

function waktuLalu(timestamp,skrng=null,lbl='lalu') {
    var waktu = "";
    if (timestamp) {
        var phpDate = new Date(timestamp.replace(/-/g, '/')); // Convert MySQL timestamp string to Date object
        
        if(!skrng)
            skrng = Date.now();
        else
            skrng = new Date(skrng.replace(/-/g, '/'));

        var selisih = Math.floor((skrng - phpDate) / 1000);
        var detik = selisih;
        var menit = Math.round(selisih / 60);
        var jam = Math.round(selisih / 3600);
        var hari = Math.round(selisih / 86400);
        var minggu = Math.round(selisih / 604800);
        var bulan = Math.round(selisih / 2419200);
        var tahun = Math.round(selisih / 29030400);

        if (detik <= 60) {
            waktu = detik + ' detik ';
        } else if (menit <= 60) {
            waktu = menit + ' menit ';
        } else if (jam <= 24) {
            waktu = jam + ' jam ';
        } else if (hari <= 7) {
            waktu = hari + ' hari ';
        } else if (minggu <= 4) {
            waktu = minggu + ' minggu ';
        } else if (bulan <= 12) {
            waktu = bulan + ' bulan ';
        } else {
            waktu = tahun + ' tahun ';
        }
    }
    return waktu+lbl;
}

function is_image(fileType) {
    return fileType.startsWith('image/');
}

function cekNilaiArray(dataArray, targetUserId, colName) {
    return dataArray.some(item => item[colName] === targetUserId);
}   

function cariArray(dataArray, targetUserId, colName) {
    return dataArray.filter(item => item[colName] === targetUserId);

}   

function getCurrentDateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    return formattedDateTime;
}

function disableForm(disabled=true,formEl='#myForm'){
    $(formEl + ' input, ' + formEl + ' select, ' + formEl + ' textarea').prop('readonly', disabled);
    $(formEl + ' input[type="submit"], ' + formEl + ' button').prop('disabled', disabled);
}

function showHideModal(el,status=true){
    if(status){
        let myModalForm = new bootstrap.Modal(document.getElementById(el), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }else{
        const cmodal = document.querySelector('#'+el);
        const modal = bootstrap.Modal.getInstance(cmodal);    
        modal.hide();      
    }
}

function showText(param=null){
    return (param)?param:"";
}

function ajaxRequest(url, method, data=null, successCallback, errorCallback) {
    var hasFile = false;

    if (data instanceof FormData) {
        data.forEach(function(value, key) {
            if (value instanceof File) {
                hasFile = true;
                
            }
        });
    }

    var ajaxOptions = {
        url: url,
        type: method,
        data: data,
        contentType: hasFile ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
        processData: !hasFile,        
        success: function(response) {
            if (successCallback) {
                successCallback(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                forceLogout();
            } else {
                appShowNotification(false, [jqXHR.responseJSON.message]);
                // console.log(jqXHR);
                // console.log(textStatus);
                // console.log(errorThrown);
            }
            if (errorCallback) {
                errorCallback(jqXHR, textStatus, errorThrown);
            }
        }
    };
    $.ajax(ajaxOptions);

}

function cekAkses(){
    $.ajax({
        type: 'GET',
        url: `${base_url}/api/cek-akses`,
        async: false, 
        success: function(response) {
            user_id=response.user_id;
            console.log(response);
        }
    });		
}

async function cekAksesNew() {
    try {
        const response = await fetch(`${base_url}/api/cek-akses`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`, 
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        user_id = result.user_id;
        console.log(result);
    } catch (error) {
        console.error('Error:', error);
    }
}

function showAkses(id_attr='#daftar-akses') {
    $(id_attr).html('');
    var daftar_akses = localStorage.getItem('hakakses');
    var nama = localStorage.getItem('nama');
    daftar_akses = JSON.parse(daftar_akses);
    if (daftar_akses.length > 0) {
        var htmlOptions = `<ul>`;
        daftar_akses.forEach(function(akses, index) {
        htmlOptions += `<li><a href="javascript:;" class="set-akses" data-grup_name="${akses.role}" data-grup_id="${akses.role_id}">${akses.role}</a></li>`;
        });
        htmlOptions += '</ul>';
        $(id_attr).html(htmlOptions);
    }else{
        $(id_attr).html('akses tidak ditemukan, hubungi admin');
    }
}

$(document).on('click','.set-akses',function(){
    let base_url = window.location.origin;
    localStorage.setItem('akses', $(this).attr('data-grup_id'));
    window.location.replace(base_url+'/dashboard');
})

async function asyncFunction(url,type='GET',data=null) {
    try {
        let response = await $.ajax({
            url: url,
            type: type,
            data:data,
            dataType: 'json'
        });
        return response;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

async function loadDataSelect(selectElement, api_url) {
    try {
        const response = await fetch(`${base_url}/api/${api_url}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`, 
                'Content-Type': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const result = await response.json();
        let $select = $(selectElement);
        $select.empty();
        if (result.data?.data?.length > 0) {
            $select.append('<option value="">Pilih</option>');
            result.data.data.forEach(item => {
                $select.append(`<option value="${item.id}">${item.nama}</option>`);
            });
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadOptionSelect(select_id, grup_param, data) {
    let $select = $(select_id);
    $select.empty();
    let groupedData = [];
    $.each(data, function (index, item) {
        if (item.grup === grup_param){
            groupedData.push(item);
        }
    });

    $.each(groupedData, function (grup, items) {
        let $option = $("<option>", { value: items.id, text: items.nama });
        $select.append($option);
    });
}  

function loadRole(containerId) {
    $.ajax({
        url: `${base_url}/api/data-role?limit=100`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            const $container = $(containerId);
            $container.empty();

            if (response.data && response.data.data.length > 0) {
                $.each(response.data.data, function(index, item) {
                    $container.append(`
                        <div class="form-check-akses">
                            <input class="form-check-input" type="checkbox" id="role-${item.id}" value="${item.id}">
                            <label class="form-check-label" for="role-${item.id}">
                                ${item.nama}
                            </label>
                        </div>
                    `);
                });
            }        
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function showModal(modalId){
    var tmpModal = new bootstrap.Modal(document.getElementById(modalId), {
        backdrop: 'static',
        keyboard: false  
      });
      tmpModal.show();

}