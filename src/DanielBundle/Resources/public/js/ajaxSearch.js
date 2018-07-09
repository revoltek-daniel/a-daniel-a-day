$(function () {
    var $search = $('#js-search');
    $search.select2({
            ajax: {
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        }
    );

    $search.on('change', function () {
        var $elem = $(this),
            targetUrl = $elem.data('redirect-url'),
            selected = $elem.find(':selected').val();

        targetUrl = targetUrl.replace('--param--', selected);
        window.location.href = targetUrl;
    });
});