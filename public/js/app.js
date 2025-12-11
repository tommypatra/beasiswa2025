$(document).ajaxSend(function (event, jqxhr, settings) {
    if (settings.headers && settings.headers['X-Form-Submit']) {
        $('form').find('input, select, textarea, button').prop('disabled', true);
    }
});

$(document).ajaxComplete(function (event, jqxhr, settings) {
    if (settings.headers && settings.headers['X-Form-Submit']) {
        $('form').find('input, select, textarea, button').prop('disabled', false);
    }
});


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

$(document).on('input', '.uppercase', function() {
    $(this).val($(this).val().toUpperCase());
});

$(document).on('input', '.numberonly', function() {
    this.value = this.value.replace(/[^0-9]/g, ''); 
});

function rangeNilai(element, nilai_minimal, nilai_maksimal) {
    $(element).on('input', function () {
        let val = parseInt($(this).val(), 10);

        if (isNaN(val)) return;

        if (val > nilai_maksimal) {
            $(this).val(nilai_maksimal);
        } else if (val < nilai_minimal) {
            $(this).val(nilai_minimal);
        }
    });
}


function setFormEnabled(selector, state) {
    $(selector).find(':input, button').prop('disabled', !state);
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

function getFileType(fileUrl) {
    let ext = fileUrl.split('.').pop().toLowerCase();

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
        return 'image';
    } else if (ext === 'pdf') {
        return 'pdf';
    }
    return 'image';
}

function showText(param=null,def=""){
    return (param)?param:def;
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

async function execAsync(vapi_url, vmethod = "GET", vtoken = null, vbody = null) {
    try {
        let headers = {};
        if (vtoken) {
            headers['Authorization'] = `Bearer ${vtoken}`;
        }

        let options = {
            method: vmethod,
            headers: headers
        };

        if (vbody) {
            if (vbody instanceof FormData) {
                options.body = vbody;
            } else {
                headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(vbody);
            }
        }

        const response = await fetch(vapi_url, options);

        // tetap ambil JSON dari server, walau status bukan 200-299
        const json = await response.json().catch(() => null);

        // kalau server kirim status 400/500, response.ok = false
        if (!response.ok) {
            console.warn('API Warning:', json || `HTTP error! Status ${response.status}`);
            return json; // <-- tetap return JSON ke pemanggil
        }

        return json;

    } catch (error) {
        console.error('Network/API Error:', error);
        return {
            status: false,
            message: "Terjadi kesalahan pada jaringan atau server",
            data: null
        };
    }
}

async function execNewAsync(vapi_url, vmethod = "GET", vtoken = null, vbody = null) {
    try {
        let headers = {};
        if (vtoken) {
            headers['Authorization'] = `Bearer ${vtoken}`;
        }

        let options = {
            method: vmethod,
            headers: headers
        };

        if (vbody) {
            if (vbody instanceof FormData) {
                options.body = vbody;
            } else {
                headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(vbody);
            }
        }

        const response = await fetch(vapi_url, options);

        if (!response.ok) {
            if(response.status === 401){
                window.location.href = '/login';
                return;
            }
            let errorData;
            try {
                errorData = await response.json();
            } catch {
                errorData = { message: response.statusText };
            }
            throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
        }

        if (response.status === 204) {
            // No Content
            return {};
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        return { error: true, message: error.message || 'Unknown error' };
    }
}


async function loadOptionSelect(select_id, grup_param, data) {
    let select = $(select_id);
    select.empty();
    let groupedData = [];
    $.each(data, function (index, item) {
        if (item.grup === grup_param){
            groupedData.push(item);
        }
    });

    // console.log(data);

    let $option = $("<option>", { value: "", text: "pilih" });
    select.append($option);
    $.each(groupedData, function (grup, items) {
        let $option = $("<option>", { value: items.id, text: items.nama });
        select.append($option);
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


//untuk ubah otomatis format jam
function initTimeInput(selector) {
    $(selector).on("input", function () {
        let val = $(this).val().replace(/\D/g, ""); // hanya angka
        if (val.length >= 6) {
            val = val.substring(0, 2) + ":" + val.substring(2, 4) + ":" + val.substring(4, 6);
        }
        $(this).val(val);
    });

    $(selector).on("blur", function () {
        let val = $(this).val();
        let regex = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/; // HH:MM:SS
        if (val && !regex.test(val)) {
            alert("Format salah! Gunakan HH:MM:SS (contoh 09:30:00 atau 18:45:00).");
            $(this).val("");
        }
    });
}

let currentPdfInstance = null;

async function openPdf(container, urlPdf) {
    // 🔹 Hapus render sebelumnya
    container.innerHTML = '';

    // 🔹 Jika sebelumnya ada PDF instance, destroy dulu
    if (currentPdfInstance) {
        try {
            await currentPdfInstance.destroy();
            currentPdfInstance = null;
            // console.log("PDF sebelumnya dibersihkan");
        } catch (e) {
            console.warn("Gagal destroy PDF sebelumnya:", e);
        }
    }

    if (!urlPdf || urlPdf.trim() === '') {
        container.innerHTML = '<p style="color:red;">Tidak ada file diupload.</p>';
        return;
    }

    pdfjsLib.GlobalWorkerOptions.workerSrc = 
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    try {
        const pdf = await pdfjsLib.getDocument(urlPdf).promise;

        // 🔹 Simpan instance ke variabel global
        currentPdfInstance = pdf;

        const totalPages = pdf.numPages;

        for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
            const page = await pdf.getPage(pageNumber);
            const viewport = page.getViewport({ scale: 1.2 });

            const canvas = document.createElement('canvas');
            canvas.style.width = '100%';
            container.appendChild(canvas);

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const context = canvas.getContext('2d');
            const renderContext = { canvasContext: context, viewport: viewport };

            await page.render(renderContext).promise;
        }
    } catch (error) {
        container.innerHTML = `<p style="color:red;">Gagal memuat dokumen PDF</p>`;
        console.error('PDF load error:', error);
    }
}

function getWhatsAppLink(isMobile, phone, message) {
    const nomorFormatted = formatNoHpIndo(phone); 
    const nomorForUrl = nomorFormatted.replace('+', '');    
    const pesan = encodeURIComponent(message || "");

    let link;
    if (isMobile) {
        link = `whatsapp://send?phone=${nomorForUrl}&text=${pesan}`;
    } else {
        link = `https://web.whatsapp.com/send?phone=${nomorForUrl}&text=${pesan}`;
    }

    return {
        link: link,
        nomor: nomorFormatted
    };
}

function formatNoHpIndo(no_hp) {
    if (!no_hp) return "";
    let hp = no_hp.toString().replace(/[\s\.\-]/g, '');
    if (hp.startsWith("+62")) return hp;
    if (hp.startsWith("62")) return "+" + hp;
    if (hp.startsWith("0")) return "+62" + hp.substring(1);
    return hp;
}

/**
 * Fungsi global untuk toggle area (misal panel filter)
 * @param {string} triggerSelector - tombol pemicu (misal '#btn-filter')
 * @param {string} areaSelector - elemen area yang mau ditampilkan/sembunyikan
 * @param {object} [options] - pengaturan tambahan (opsional)
 *    options.animation: 'fade' | 'slide' | 'none'
 */
function toggleArea(triggerSelector, areaSelector, options = {}) {
    const $trigger = $(triggerSelector);
    const $area = $(areaSelector);
    const animation = options.animation || 'fade';

    // pastikan hanya satu listener per elemen
    $trigger.off('click.toggleArea').on('click.toggleArea', function (e) {
        e.stopPropagation();

        if ($area.is(':visible')) {
            hideArea();
        } else {
            showArea();
        }
    });

    // klik di luar area → sembunyikan
    $(document).off('click.toggleArea').on('click.toggleArea', function (e) {
        if (!$(e.target).closest(areaSelector + ',' + triggerSelector).length) {
            hideArea();
        }
    });

    function showArea() {
        if (animation === 'fade') $area.fadeIn(150);
        else if (animation === 'slide') $area.slideDown(150);
        else $area.show();
    }

    function hideArea() {
        if (animation === 'fade') $area.fadeOut(150);
        else if (animation === 'slide') $area.slideUp(150);
        else $area.hide();
    }
}


function formatTanggal(tanggal) {
    const bulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const [tahun, bulanIndex, hari] = tanggal.split('-');
    return `${parseInt(hari)} ${bulan[parseInt(bulanIndex) - 1]} ${tahun}`;
}
