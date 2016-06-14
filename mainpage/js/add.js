$(function() {
    var $form        = $('.submit__form');

    var $orderWrap   = $('.order-wrap');
    var $orderInner  = $('.order--i');
    var $orderForm   = $('.order-form');
    var $orderNotify = $('.order__notify');

    var $link        = $('.section-link');
    var lang;

    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });

        return o;
    };

    $.ajax({
        type: 'GET',
        url: 'get_country.php',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                lang   = response.lang;
                var domain = response.domain;
                var localizeCollection = $('[data-localize]');

                localizeCollection.localize('app', {
                    language: lang,
                    callback: function (data, defaultCallback) {
                        defaultCallback(data);

                        $('<input>').attr({
                            'type':  'hidden',
                            'name':  'domain',
                            'value': domain
                        }).appendTo($form);

                        switch (domain) {
                            case 'ru':
                                $link.attr('href', 'http://prestigio.ru').html('Перейти на prestigio.ru');
                                break;
                            case 'by':
                                $link.attr('href', 'http://prestigio.by').html('Перейти на prestigio.by');
                                break;
                            default:
                                break;
                        }
                    }
                });
            }
        }
    });

    $(document).on('click', '.order__show', function(event) {
        event.preventDefault();
        if ($(window).outerWidth() < 480) {
            $orderWrap.height(420);
        }

        $orderInner.hide();
        $orderForm.show();
    });

    $(document).on('click', '.order__notify', function(event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url:  'store.php',
            data: $orderForm.serializeObject(),
            success: function(response) {
                $('.order__error').remove();
                if (response.status) {
                    $orderForm.html('Thank you. We will send you and email when this product arrives in stock');
                } else {
                    $orderForm.prepend('<p class="order__error">' + response.error + '</p>');
                }
            }
        });
    });

    $(document).on('click', '.order__sbmt', function(event) {
        event.preventDefault();

        var model = $(this).attr('data-model');
        var id    = $(this).attr('data-id');

        mixpanel.track('Grace_Buy_now_button_' + model, {}, function() {
            $('<input>').attr({
                'type':  'hidden',
                'name':  'sku[]',
                'value': id,
            }).appendTo($form);
        });

        if ('gb' == lang) {
            $form.attr('action', 'http://prestigioplaza.co.uk/cart.php');
        } else {
            $form.attr('action', 'http://prestigioplaza.com/cart.php');
        }

        $form.submit();
    });
});
