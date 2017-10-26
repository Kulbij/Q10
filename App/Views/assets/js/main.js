$('form input[name="store_url"]').focus();

function getAjax() {
    // var loadind = "<p>Loading...</p>";
    // $('#ajax_put_block').html(loadind);

    var ajax_url = "/index.php?ajax=on";
    var store_url = $('form input[name="store_url"]').val();

    if (store_url == "") {
        var clearInput = "<p>Please specify Store URL address</p>";
        $('#ajax_put_block').html(clearInput);
        $('form input[name="store_url"]').focus();
    }

    if (store_url != "") {

        if (!isUrlValid(store_url)) {
            var clearInput = "<p>Not valid Store URL address</p>";
            $('#ajax_put_block').html(clearInput);
            $('form input[name="store_url"]').focus();
            return;
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: {url: store_url},
            beforeSend: function(){
                $('#ajax_put_block').html('<div class="loader"></div>');
                $('form input').attr('disabled', 'disabled');
                $('form button').attr('disabled', 'disabled');
            }
        }).done(function(data) {
            $('#ajax_put_block').html(data);
            $('form input').removeAttr('disabled');
            $('form button').removeAttr('disabled');

            var ajax_url_field = "/index.php?ajax=on";
            $.ajax({
                type: "POST",
                url: ajax_url_field,
                data: {url: store_url, field: 'likes'},
            }).done(function(data) {
                if (typeof data.field == 'object') {
                    $('#likes').parent().parent().hide();
                }
                $('#likes').replaceWith(data.field);

                var ajax_url_field = "/index.php?ajax=on";
                $.ajax({
                    type: "POST",
                    url: ajax_url_field,
                    data: {url: store_url, field: 'solds'},
                }).done(function(data) {
                    if (typeof data.field == 'object') {
                        $('#solds').parent().parent().hide();
                    }
                    $('#solds').replaceWith(data.field);
                });
            });

            var ajax_url_field = "/index.php?ajax=on";
            $.ajax({
                type: "POST",
                url: ajax_url_field,
                data: {url: store_url, field: 'reviews'},
            }).done(function(data) {
                var reviews = $('#reviews'),
                firstTotal = reviews.find('input').val();

                reviews.replaceWith(Number(firstTotal) + Number(data.field));

                if (typeof data.field == 'object') {
                    $('#reviews').parent().parent().hide();
                }
            });
        });
    }
}

function isUrlValid(userInput) {
    var regexQuery = "^(https?://)?(www\\.)?([-a-z0-9]{1,63}\\.)*?[a-z0-9][-a-z0-9]{0,61}[a-z0-9]\\.[a-z]{2,6}(/[-\\w@\\+\\.~#\\?&/=%]*)?$";
    var url = new RegExp(regexQuery,"i");
    if (url.test(userInput)) {
        return true;
    }
    return false;
}