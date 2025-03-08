
    // Fungsi fetchData
    function fetchData(endpoint, callback) {
        ajaxRequest(endpoint, 'GET', null, 
            function(response) {
                callback(response);
            }
        );
    }

    // Fungsi showDataById
    function showDataById(endpoint, id, callback) {
        ajaxRequest(endpoint + '/' + id, 'GET', null, 
            function(response) {
                callback(response);
            }
        );
    }

    // Fungsi saveData baik post maupun put
    function saveData(endpoint, type, data, callback) {
        ajaxRequest(endpoint, type, data, 
            function(response) {
                callback(response);
            }
        );
    }


    // Fungsi deleteData
    function deleteData(endpoint, id, callback) {
        if(confirm('apakah anda yakin menghapus data ini?'))
            ajaxRequest(endpoint + '/' + id, 'DELETE', null, 
                function(response) {
                    callback(response);
                }
            );
    }