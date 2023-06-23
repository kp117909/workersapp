document.getElementById("go-back").addEventListener("click", function() {
    window.history.back();
});
$(document).ready(function () {

    $('#export-data').click(function() {
        var url = $(this).data('url');
        var id = $(this).data('id');

        $.ajax({
            url: url,
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            data : {
                id: id
            },
            success: function(response) {
                var downloadLink = document.createElement('a');
                var blobUrl = URL.createObjectURL(response);
                downloadLink.href = blobUrl;
                downloadLink.download = 'exported_employee.pdf';

                downloadLink.click();

                URL.revokeObjectURL(blobUrl);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

});
