Number.prototype.round = function(p) {
    p = p || 10;
    return parseFloat(this.toFixed(p));
};

$(function() {
    // VARS -------------------------------------------------------------------
    //

    var $window = $(window);
    var $html = $('html');
    var $body = $('body');
    var $scroll_container = $('.scroll-container');
    var $scroll_wrap = $('.scroll-wrap');
    var $scroll_section = $('.js-scroll-section');
    var $dotted_nav = $('.dotted-nav');
    var $before_after_slide = $('.js-before-after-slide');
    var $before_after_drag = $('.js-before-after-drag');
    var $before_after_slide_wrap = $('.js-before-after-slide-wrap');
    var $sticky_nav = $('.sticky-nav-container');
    var $footer = $('#footer');
    var $section__last = $('#section-11');
    var hasTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 1));
    var $open_menu = $('.open-sticky-nav');
    var $main_section = $('.main-section');
    var $main__img = $('.main-img');
    var $tooltip = $('.tooltip');
    var $gallery_img = $('.gallery-img');

    // ACTIONS ----------------------------------------------------------------
    //

    if (hasTouch) {
        $body.addClass('has-touch');
    } else {
        $body.addClass('no-touch');
    }


    if ($(window).outerHeight < 600 && $(window).outerWidth >= 1024) {
        $section__last.find('.section-11-inner').css({
            height: 600 + $footer.outerHeight()
        });
    } else {
        $section__last.find('.section-11-inner').css({
            height: $(window).outerHeight() + $footer.outerHeight()
        });
    }

    $(window).on('load', function(event) {
        if ($(window).outerWidth() === 1024) {
            if ($body.hasClass('has-touch')) {
                TweenLite.set($gallery_img, {
                    marginTop: -$gallery_img.outerHeight() / 2 + 30,
                    marginLeft: -$gallery_img.outerWidth() / 2
                });
            }
        } else if ($(window).outerWidth() < 1024) {
            TweenLite.set($gallery_img, {
                marginTop: -$gallery_img.outerHeight() / 2,
                marginLeft: -$gallery_img.outerWidth() / 2
            });
        } else if ($(window).outerWidth() > 1024) {
            TweenLite.set($gallery_img, {
                marginTop: -$gallery_img.outerHeight() / 2 + 30,
                marginLeft: -$gallery_img.outerWidth() / 2
            });
        }
    });


    $('.section-11-content-wrap').css('height', $(window).outerHeight());

    var timer;
    $(window).on('resize', function(event) {
        var $left_img = $('#dark-slide-1').find('img');
        var $left_img_2 = $('#dark-slide-2').find('img');

        clearTimeout(timer);
        timer = setTimeout(function() {
            if ($(window).outerWidth() < 768) {
                if ($body.hasClass('resized')) {
                    $.fn.fullpage.setAutoScrolling(false);
                } else {
                    $main_section.css('height', $(window).outerHeight());
                }
            } else {
                if ($body.hasClass('resized')) {
                    $.fn.fullpage.setAutoScrolling(true);
                } else {
                    $body.addClass('resized');
                    fp();
                }
            }

            if ($(window).outerWidth() === 1024) {
                if ($body.hasClass('has-touch')) {
                    TweenLite.set($gallery_img, {
                        marginTop: -$gallery_img.outerHeight() / 2 + 30,
                        marginLeft: -$gallery_img.outerWidth() / 2
                    });
                }
            } else if ($(window).outerWidth() < 1024) {
                TweenLite.set($gallery_img, {
                    marginTop: -$gallery_img.outerHeight() / 2,
                    marginLeft: -$gallery_img.outerWidth() / 2
                });
            } else if ($(window).outerWidth() > 1024) {
                TweenLite.set($gallery_img, {
                    marginTop: -$gallery_img.outerHeight() / 2 + 30,
                    marginLeft: -$gallery_img.outerWidth() / 2
                });
            }

            if ($(window).outerHeight < 600 && $(window).outerWidth >= 1024) {
                $section__last.find('.section-11-inner').css({
                    height: 600 + $footer.outerHeight()
                });
            } else {
                $section__last.find('.section-11-inner').css({
                    height: $(window).outerHeight() + $footer.outerHeight()
                });
            }

            $('.section-11-content-wrap').css('height', $(window).outerHeight());

            Draggable.get($before_after_drag).applyBounds({
                minX: 0,
                maxX: $(window).outerWidth()
            });

            TweenLite.to("#drag-1", 0.5, {
                x: $left_img.offset().left + $left_img.outerWidth() * 0.57,
                force3D: true
            });
            TweenLite.to('#dark-slide-1', 0.5, {
                width: $left_img.offset().left + $left_img.outerWidth() * 0.57,
                force3D: true
            });

            TweenLite.to("#drag-2", 0.5, {
                x: $left_img_2.offset().left + $left_img_2.outerWidth() * 0.5,
                force3D: true
            });
            TweenLite.to('#dark-slide-2', 0.5, {
                width: $left_img_2.offset().left + $left_img_2.outerWidth() * 0.5,
                force3D: true
            });

            TweenLite.set($before_after_slide, {
                width: $(window).outerWidth(),
                height: $(window).outerHeight()
            });

            if ($(window).outerWidth() < 768) {
                $('.scroll').slimscroll({
                    destroy: true
                });
            } else {
                $('.scroll').slimScroll({
                    height: '100%'
                });
            }
        }, 300);
    });

    TweenLite.fromTo($main__img, 1.5, {
        scale: 0.8,
        delay: 1
    }, {
        scale: 1,
        force3D: true,
        onComplete: function() {
            TweenLite.to($main__img.find('.main-img__text'), 0.5, {
                alpha: 1
            });
        }
    });

    TweenLite.set($before_after_slide, {
        width: $(window).outerWidth(),
        height: $(window).outerHeight()
    });

    TweenLite.set($before_after_slide_wrap, {
        width: 0
    });

    var dragable = Draggable.create($before_after_drag, {
        bounds: {
            minX: 0,
            maxX: $(window).outerWidth()
        },
        type: "x",
        force3D: true,
        onDrag: function() {
            var $dark_img = $(this.target).siblings('.js-before-after-slide-wrap');
            var distance = (this.x / $(window).outerWidth()).round(4) * 100 + '%';
            TweenLite.set($dark_img, {
                width: distance
            });
        }
    });

    if ($(window).outerWidth() >= 768) {
        $body.addClass('resized');
        fp();
        $('.scroll').slimScroll();
    } else {
        $main_section.css('height', $(window).outerHeight());
        $(window).trigger('resize');
    }



    // scroll to section on click
    $(document).on('click', '.js-scroll-to', function(event) {
        event.preventDefault();
        var $self = $(this);

        mixpanel.track('Grace_Buy_now_button', {}, function() {
            if ($(window).outerWidth() < 768) {
                var $target = $self.attr('href').split('#')[1];
                $('html, body').animate({
                    scrollTop: $('#' + $target).offset().top
                }, 400);
            } else {
                var $scroll_distance = $self.data('sctoll-to');
                $.fn.fullpage.moveTo($scroll_distance);
            }
        });
    });

    // show dependent content 

    $(document).on('click', '.js-show-dependent', function(event) {
        event.preventDefault();
        showDependent($(this));
    });

    // show/hide tooltips
    $(document).on('click', '.js-tooltip', function(event) {
        $(this).toggleClass('active');
    });

    /*$(document).on('touchstart', '.js-tooltip', function(event) {
        $(this).toggleClass('active');
    });*/

    // open menu on mobile devices
    $(document).on('click', '.open-sticky-nav', function(event) {
        event.preventDefault();

        $(this).toggleClass('active');
        $scroll_container.toggleClass('open');
        $sticky_nav.toggleClass('open');
    });

    // show list of features on mobile
    $(document).on('click', '.show-list', function(event) {
        event.preventDefault();
        var oldText = $(this).text();
        var newText = $(this).data('text');

        $(this).toggleClass('active').text(newText).data('text', oldText);
        $(this).parents().siblings('.dotted-list').toggleClass('active');
    });
    //
    // FUNCTIONS --------------------------------------------------------------
    //

    function setActiveSection(section) {
        $scroll_section.removeClass('is-active');
        $scroll_section.eq(section).addClass('is-active');

        $scroll_wrap.data('active-section', section);
    }

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

    function fp() {
        $scroll_container.fullpage({
            sectionSelector: '.js-scroll-section',
            navigation: true,
            scrollingSpeed: 1000,
            navigationPosition: 'right',
            verticalCentered: false,
            easing: 'easeInSine',
            css3: true,
            afterResize: function() {
                if ($(window).outerWidth() < 768) {
                    $.fn.fullpage.setAutoScrolling(false);
                } else {
                    $.fn.fullpage.setAutoScrolling(true);
                }
            },
            'onLeave': function(index, nextIndex, direction) {
                if (index !== 0 || index !== 10) {
                    if ($(window).outerWidth() > 940 && !$body.hasClass('has-touch')) {
                        $sticky_nav.addClass('visible');
                    }
                }

                if (nextIndex == 1 || nextIndex == 11) {
                    if ($(window).outerWidth() > 940 && !$body.hasClass('has-touch')) {
                        $sticky_nav.removeClass('visible');
                    }
                }

                if (nextIndex == 2) {
                    var $left_img = $('#dark-slide-1').find('img');
                    var $left_img_offset = $left_img.offset().left;
                    var $left_img_width = $left_img.outerWidth() * 0.57;
                    var left_offset = $left_img_offset + $left_img_width;

                    TweenLite.killTweensOf('#drag-1');
                    TweenLite.killTweensOf('#dark-slide-1');
                    TweenLite.to("#drag-1", 0.5, {
                        x: left_offset,
                        delay: 1,
                        force3D: true
                    });
                    TweenLite.to('#dark-slide-1', 0.5, {
                        width: left_offset,
                        delay: 1,
                        force3D: true
                    });
                }

                if (nextIndex == 8) {
                    if (!$tooltip.hasClass('visible')) {
                        $tooltip.each(function(index, el) {
                            var $this = $(this);
                            TweenLite.to(el, 0.3, {
                                opacity: 1,
                                force3D: true,
                                delay: 0.1 * index
                            });
                        });
                    }
                }

                if (nextIndex == 10) {
                    var $left_img_2 = $('#dark-slide-2').find('img');
                    var $left_img_offset_2 = $left_img_2.offset().left;
                    var $left_img_width_2 = $left_img_2.outerWidth() * 0.5;
                    var left_offset_2 = $left_img_offset_2 + $left_img_width_2;

                    TweenLite.killTweensOf('#drag-2');
                    TweenLite.killTweensOf('#dark-slide-2');

                    TweenLite.to("#drag-2", 0.5, {
                        x: $(window).outerWidth(),
                        delay: 1,
                        force3D: true,
                        onComplete: function() {
                            TweenLite.to("#drag-2", 0.5, {
                                x: left_offset_2,
                                delay: 0.2,
                                force3D: true
                            });
                        }
                    });

                    TweenLite.to('#dark-slide-2', 0.5, {
                        width: '100%',
                        delay: 1,
                        force3D: true,
                        onComplete: function() {
                            TweenLite.to('#dark-slide-2', 0.5, {
                                width: left_offset_2,
                                delay: 0.2,
                                force3D: true
                            });
                        }
                    });
                }
            }
        });
    }
});
