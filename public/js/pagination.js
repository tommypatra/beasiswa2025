var vLimit = 30;
var vSelectPaging = [2,25,50,125,250,500];

$(function(){
    $.each(vSelectPaging, function(index, value) {
        // var listItem = $('<li>').text(value);
        var listItem = $('<li>').html(`<a class="dropdown-item item-paging" href="javascript:;" data-nilai="${value}">${value}</a>`);
        $('#list-select-paging').append(listItem);
    });
});

function renderPagination(response, paginationElement) {
    var pagination = paginationElement;
    $('#btn-paging').html('<i class="bi bi-collection"></i> Paging '+vLimit+' ');

    paginationElement.empty();
    // console.log(response.last_page);
    if (response.last_page > 1) {
        var currentPage = response.current_page;
        var lastPage = response.last_page;

        var startPage = Math.max(1, currentPage - 2);
        var endPage = Math.min(lastPage, currentPage + 2);

        if (startPage > 1) {
            pagination.append('<li class="page-item"><a class="page-link" href="javascript:;" data-page="1">1</a></li>');
            if (startPage > 2) {
                pagination.append('<li class="page-item"><span class="page-link">...</span></li>');
            }
        }

        for (var i = startPage; i <= endPage; i++) {
            var activeClass = i === currentPage ? 'active' : '';
            pagination.append('<li class="page-item ' + activeClass + '"><a class="page-link" href="javascript:;" data-page="' + i + '">' + i + '</a></li>');
        }

        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                pagination.append('<li class="page-item"><span class="page-link">...</span></li>');
            }
            pagination.append('<li class="page-item"><a class="page-link" href="javascript:;" data-page="' + lastPage + '">' + lastPage + '</a></li>');
        }
        
        // Menampilkan jumlah data
        var totalData = response.total;
        pagination.append('<li class="page-item disabled"><span class="page-link">Total Data: ' + totalData + '</span></li>');
    }
}

