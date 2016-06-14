$(function() {
    var $window = $(window);
    var $html = $('html');
    var $body = $('body');
    var $scroll_container = $('.scroll-container');
    var $open_menu = $('.open-sticky-nav');
    var $sticky_nav = $('.sticky-nav-container');

    $window.on('scroll', function(e) {
        if ($window.outerWidth() > 940 && !$body.hasClass('has-touch') && $window.scrollTop() > 0) {
            $sticky_nav.addClass('visible');
        } else {
            $sticky_nav.removeClass('visible');
        }
    });

    $(document).on('click', '.js-show-dependent', function(event) {
        event.preventDefault();
        showDependent($(this));
    });

    $(document).on('click', '.open-sticky-nav', function(event) {
        event.preventDefault();

        $(this).toggleClass('active');
        $scroll_container.toggleClass('open');
        $sticky_nav.toggleClass('open');
    });

    $(document).on('click', '.js-scroll-to', function(event) {
        event.preventDefault();
        var $self = $(this);

        var $target = $self.attr('data-scroll-to');
        $('html, body').animate({
            scrollTop: $('#section-' + $target).offset().top
        }, 400);
    });

    function showDependent(link) {
        var $link = link;
        var $data = $link.data('dependent');
        var $el = $('[data-dependent="' + $data + '"]');

        if (!$link.hasClass('active')) {
            $link.siblings().removeClass('active');
            $link.addClass('active');
            $el.siblings().removeClass('active');
            $el.addClass('active');
        }
    }
});
