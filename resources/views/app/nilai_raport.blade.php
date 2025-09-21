@extends('template')

@section('scriptHead')
<title>Nilai Raport</title>
<style>
    .preview {
        margin-top: 10px;
        max-width: 300px;
    }
</style>
@endsection

@section('container')
<h4 id="nama-pengguna"></h4>
<div class="card">
    <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
            <h5 class="card-title fw-semibold">Nilai Raport</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btn-refresh">
                    <i class="ti ti-reload"></i>
                </button>
            </div>
        </div>

        <form id="form">
            <input type="hidden" id="id" name="id">
            <div class="alert alert-primary" role="alert">
                Untuk nilai rata rata wajib diisi dengan 0 - 100, jika ada koma cara mengisinya ganti dengan titik contoh : 89.78
                <br>Untuk peringkat boleh kosong atau isikan dengan angka contoh 1 atau 2 atau 3 dst
                <br>Wajib mengupload raport pdf yg sudah dilegalisir ukurang maksimal 3MB
            </div>                    

            <h5>Kelas X</h5>
            <div class="row">
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 1</label>
                    <div>Nilai Rata-Rata</div>
                    <input name="smt_1_nilai" id="smt_1_nilai" type="number" class="form-control" required>                    
                    <div>Peringkat</div>
                    <input name="smt_1_peringkat" id="smt_1_peringkat" type="number" class="form-control" >
                    <div>
                        <label class="form-label">Upload Raport Semester 1</label>
                        <input type="file" id="foto_raport_smt_1" name="foto_raport_smt_1" class="form-control" accept="application/pdf">
                        
                        <div id="download_foto_raport_smt_1"></div>                    
                    </div>

                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 2</label>
                    <div>Nilai Rata-Rata</div>
                    <input name="smt_2_nilai" id="smt_2_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_2_peringkat" id="smt_2_peringkat" type="number" class="form-control" >
                    <div>
                        <label class="form-label">Upload Raport Semester 2</label>
                        <input type="file" id="foto_raport_smt_2" name="foto_raport_smt_2" class="form-control" accept="application/pdf">
                        
                        <div id="download_foto_raport_smt_2"></div>                    
                    </div>
                </div>
            </div>
            <hr>
            <h5>Kelas XI</h5>
            <div class="row">
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 3</label>
                    <div>Nilai Rata-Rata</div>
                    <input name="smt_3_nilai" id="smt_3_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_3_peringkat" id="smt_3_peringkat" type="number" class="form-control" >
                    <div>
                        <label class="form-label">Upload Raport Semester 3</label>
                        <input type="file" id="foto_raport_smt_3" name="foto_raport_smt_3" class="form-control" accept="application/pdf">
                        
                        <div id="download_foto_raport_smt_3"></div>                    
                    </div>
                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 4</label>
                    <div>Nilai Rata-Rata</div>
                    <input name="smt_4_nilai" id="smt_4_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_4_peringkat" id="smt_4_peringkat" type="number" class="form-control" >
                    <div>
                        <label class="form-label">Upload Raport Semester 4</label>
                        <input type="file" id="foto_raport_smt_4" name="foto_raport_smt_4" class="form-control" accept="application/pdf">
                        
                        <div id="download_foto_raport_smt_4"></div>                    
                    </div>
                </div>
            </div>
            <hr>

            <h5>Kelas XII</h5>
            <div class="row">
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 5</label>
                    <div>Nilai Rata-Rata</div>
                    <input name="smt_5_nilai" id="smt_5_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_5_peringkat" id="smt_5_peringkat" type="number" class="form-control" >
                    <div>
                        <label class="form-label">Upload Raport Semester 5</label>
                        <input type="file" id="foto_raport_smt_5" name="foto_raport_smt_5" class="form-control" accept="application/pdf">
                        
                        <div id="download_foto_raport_smt_5"></div>                    
                    </div>
                </div>
                <div class="col-sm-5 mb-3">
                    <label class="form-label">Semester 6</label>
                    <div>Nilai Rata-Rata</div>
                    <input name="smt_6_nilai" id="smt_6_nilai" type="number" class="form-control" required>
                    <div>Peringkat</div>
                    <input name="smt_6_peringkat" id="smt_6_peringkat" type="number" class="form-control" >
                    <div>
                        <label class="form-label">Upload Raport Semester 6</label>
                        <input type="file" id="foto_raport_smt_6" name="foto_raport_smt_6" class="form-control" accept="application/pdf">
                        
                        <div id="download_foto_raport_smt_6"></div>                    
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
        </form>
    </div>
</div>


@endsection

@section('scriptJs')
<script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script type="text/javascript">
    const endpoint = base_url + '/api/nilai-raport';
    var page = 1;

    async function dataLoad() {
        var url = endpoint + '?user_id=' + user_id;
        try {
            const response = await fetch(url, {
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
            renderData(result.data.data.length,result.data.data[0]);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function renderData(ada,data){
        $('#id').val(data.id);
        for (let i = 1; i <= 6; i++) {
            $(`#smt_${i}_nilai`).val(ada ? data[`smt_${i}_nilai`] : "");
            $(`#smt_${i}_peringkat`).val(ada ? data[`smt_${i}_peringkat`] : "");

            let field = `foto_raport_smt_${i}`;
            if (ada && data[field]) {
                $(`#download_${field}`).html(`
                    <a href="${base_url}/${data[field]}" target="_blank" class="badge text-bg-success mt-2">
                        <iconify-icon icon="solar:download-linear" class=""></iconify-icon> Download Raport Semester ${i}
                    </a>
                `);
            } else {
                $(`#download_${field}`).html(""); // kosongin kalau ga ada
            }
        }
    }

    $(document).ready(function() {
        dataLoad();

        $('#kartu_mahasiswa').on('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle page change
        $('#btn-refresh').click(function() {
            dataLoad();
        });

        $("#form").validate({
            rules: {
                foto_raport_smt_1: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                },
                foto_raport_smt_2: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                },
                foto_raport_smt_3: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                },
                foto_raport_smt_4: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                },
                foto_raport_smt_5: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                },
                foto_raport_smt_6: {
                    required: function() {
                        return $('#id').val() === '';
                    }
                },
            },
            messages: {
                foto_raport_smt_1: {
                    required: "file raport semester 1 wajib diupload.",
                },
                foto_raport_smt_2: {
                    required: "file raport semester 2 wajib diupload.",
                },
                foto_raport_smt_3: {
                    required: "file raport semester 3 wajib diupload.",
                },
                foto_raport_smt_4: {
                    required: "file raport semester 4 wajib diupload.",
                },
                foto_raport_smt_5: {
                    required: "file raport semester 5 wajib diupload.",
                },
                foto_raport_smt_6: {
                    required: "file raport semester 6 wajib diupload.",
                },
            },
            submitHandler: function(form,event) {
                event.preventDefault();
                const id = $('#id').val();
                const url = (id === '') ? endpoint : endpoint + '/' + id;

                var formData = new FormData(form);
                if((id !== '')){
                    formData.append("_method", "put");
                }

                saveData(url, 'POST', formData, function(response) {
                    appShowNotification(true, ['berhasil dilakukan!']);
                    dataLoad();

                    for (let i = 1; i <= 6; i++) {
                        $(`#foto_raport_smt_${i}`).val("");
                    }

                });
            }
        });        

    });
</script>
@endsection