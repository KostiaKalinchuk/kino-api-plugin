var $ = jQuery.noConflict();

$(document).ready(function () {

    $('#kap_plugin_save_button').on('click', function () {
        var data = {
            action: 'save_settings',
            my_message: $('.my_message').val(),
            my_page: $('.my_page').val(),
            my_select: $('#select').val(),
            kinoid: $("#kinoid").prop("checked"),
            my_films_id: $('.my_films_id').val()
        };

        jQuery.post(ajaxurl, data, function (response) {
            if (parseInt(response) === 1) alert('Сообщение сохранено!');
            if (parseInt(response) === 0) alert('Что-то не так! Повторите попытку');
        });

    });

    $('.video-api').on('click', function () {
        var data = {
            action: 'start_videocdn',
            start: 'start',
        };
        jQuery.post(ajaxurl, data, function (response) {
            console.log(response);
            if (response === 'videocdn') {
                $('#progressbar').val(33);
                var data = {
                    action: 'start_videocdn2',
                    start: 'start2',
                };
                jQuery.post(ajaxurl, data, function (response) {
                    console.log(response);
                    if (response === 'kinopoisk') {
                        $('#progressbar').val(66);
                        var data = {
                            action: 'start_videocdn3',
                            start: 'start3',
                        };
                        jQuery.post(ajaxurl, data, function (response) {
                            $('#progressbar').val(100);
                            console.log(response);

                        });

                    }

                });

            }

        });

    });

});