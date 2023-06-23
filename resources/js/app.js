
import './bootstrap';

$(document).ready(function () {
    $('#filter-employed').on('change', function () {
        $('#filter-form').submit();
    });

    $('#filter-unemployed').on('change', function () {
        $('#filter-form').submit();
    });

    $('#filterMale').on('change', function () {
        $('#filter-form').submit();
    });

    $('#filterFemale').on('change', function () {
        $('#filter-form').submit();
    });


    $('.departments_select').select2();

    $('#export-button').click(function() {
        var selectedExports = $('.export-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        var url = $(this).data('url');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                selectedExports: selectedExports
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var downloadLink = document.createElement('a');
                var blobUrl = URL.createObjectURL(response);
                downloadLink.href = blobUrl;
                downloadLink.download = 'exported_employees.pdf';

                downloadLink.click();

                URL.revokeObjectURL(blobUrl);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

    $(document).on('change', 'input[name="export[]"]', function() {
        var selectedExports = [];
        var selectedExportsCount = parseInt($('#selectedExportsCount').text());

        $('input[name="export[]"]:checked').each(function() {
            selectedExports.push($(this).val());
        });

        var url =  $(this).data('url');

        var isChecked = $(this).prop('checked');
        var value = $(this).val();
        if (isChecked) {
            selectedExportsCount++;
            $.ajax({
                url: url,
                type: 'GET',
                data: { selectedExports: selectedExports, action: 'add' },
                success: function(response) {
                    console.log('Wartości zaznaczonych checkboxów zostały zapisane w sesji.');
                },
                error: function(xhr) {
                    console.log('Wystąpił błąd podczas zapisywania wartości zaznaczonych checkboxów w sesji.');
                }
            });} else{
            selectedExportsCount--;
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: { value: value, action: 'delete' },
                    success: function(response) {
                        console.log('Checkbox został odznaczony i wartość została usunięta z sesji.');
                    },
                    error: function(xhr) {
                        console.log('Wystąpił błąd podczas usuwania wartości z sesji.');
                    }
                });
            }
        $('#selectedExportsCount').text(selectedExportsCount)
    });

    $(document).on('click', '#clear-selects-btn', function() {
        var selectedExports = [];

        $('input[name="export[]"]:checked').each(function() {
            $(this).prop('checked', false);
        });
        $('#selectedExportsCount').text(0)
        var url = $(this).data('url');

        $.ajax({
            url: url,
            type: 'GET',
            data: { selectedExports: selectedExports },
            success: function(response) {

            },
            error: function(xhr) {

            }
        });
    });

});

