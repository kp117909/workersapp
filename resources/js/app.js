
import './bootstrap';

$(document).ready(function () {
    $('#filter').on('change', function () {
        $('#filter-form').submit();
    });

    $('#filterMale').on('change', function () {
        $('#filter-form').submit();
    });


    $('#filterFemale').on('change', function () {
        $('#filter-form').submit();
    });


    $('.departments_select').select2();

});

