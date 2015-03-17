"use strict";
var header = jQuery('.main_header'),
    html = jQuery('html'),
    body = jQuery('body'),
    footer = jQuery('footer'),
    window_h = jQuery(window).height(),
    window_w = jQuery(window).width(),
    main_wrapper = jQuery('.main_wrapper'),
    site_wrapper = jQuery('.site_wrapper'),
    preloader_block = jQuery('.preloader'),
    fullscreen_block = jQuery('.fullscreen_block'),
    is_masonry = jQuery('.is_masonry'),
    grid_portfolio_item = jQuery('.grid-portfolio-item'),
    pp_block = jQuery('.pp_block'),
    head_border = 1;

jQuery(document).ready(function ($) {
    if (jQuery('.preloader').size() > 0) {
        setTimeout("preloader_block.addClass('la-animate');", 500);
        setTimeout("preloader_block.addClass('load_done')", 2500);
        setTimeout("preloader_block.remove()", 2950);
    }
	if (html.hasClass('sticky_menu') && body.hasClass('admin-bar')) {
		header.css('top', jQuery('#wpadminbar').height());
	}
    content_update();
    if (jQuery('.flickr_widget_wrapper').size() > 0) {
        jQuery('.flickr_badge_image a').each(function () {
            jQuery(this).append('<div class="flickr_fadder"></div>');
        });
    }
    header.find('.header_wrapper').append('<a href="javascript:void(0)" class="menu_toggler"></a>');
    header.append('<div class="mobile_menu_wrapper"><ul class="mobile_menu container"/></div>');
    jQuery('.mobile_menu').html(header.find('.menu').html());
    jQuery('.mobile_menu_wrapper').hide();
    jQuery('.menu_toggler').click(function () {
        jQuery('.mobile_menu_wrapper').slideToggle(300);
        jQuery('.main_header').toggleClass('opened');
    });
    //setTimeout("jQuery('body').animate({'opacity' : '1'}, 500)", 500);
	if (jQuery('.main_wrapper').size() > 0) {
		setTimeout("jQuery('.main_wrapper').animate({'opacity' : '1'}, 500)", 500);
	} else if (jQuery('.fullscreen_block').size() > 0) {
		setTimeout("jQuery('.fullscreen_block').animate({'opacity' : '1'}, 500)", 500);
	}

    jQuery('.search_toggler').click(function (event) {
		event.preventDefault();
        header.toggleClass('search_on');
    });
    if (pp_block.size()) {
        centerWindow404();
    }
});

jQuery(window).resize(function () {
    window_h = jQuery(window).height();
    window_w = jQuery(window).width();
    content_update();
});

jQuery(window).load(function () {
    content_update();
});

function content_update() {
    if (html.hasClass('sticky_menu')) {
        if (html.hasClass('without_border')) {
            head_border = 0;
        }
        jQuery('body').css('padding-top', header.height() + head_border);
    }
    site_wrapper.width('100%').css('min-height', window_h - parseInt(body.css('padding-top')));
    if (jQuery(window).width() > 760) {
        main_wrapper.css('min-height', window_h - header.height() - footer.height() - parseInt(main_wrapper.css('padding-top')) - parseInt(main_wrapper.css('padding-bottom')) - parseInt(footer.css('border-top-width')) - parseInt(header.css('border-bottom-width')) + 'px');
        if (fullscreen_block.size() > 0 && footer.size() > 0) {
            fullscreen_block.css('min-height', window_h - header.height() - footer.height() - parseInt(fullscreen_block.css('padding-top')) - parseInt(fullscreen_block.css('padding-bottom')) - parseInt(footer.css('border-top-width')) - parseInt(header.css('border-bottom-width')) + 'px');
        } else {
            fullscreen_block.css('min-height', window_h - header.height() - parseInt(header.css('border-bottom-width')) + 'px');
        }
    } else {
        //
    }
}

function gt3_get_blog_posts(post_type, posts_count, posts_already_showed, template_name, content_insert_class, categories, author) {
    jQuery.post(gt3_ajaxurl, { action: "gt3_get_blog_posts", post_type: post_type, posts_count: posts_count, posts_already_showed: posts_already_showed, template_name: template_name, content_insert_class: content_insert_class, categories: categories, author: author })
        .done(function (data) {
            jQuery(content_insert_class).append(data);
            if (jQuery('.this_is_blog').size() > 0) {
                jQuery('.pf_output_container').each(function () {
                    if (jQuery(this).html() == '') {
                        jQuery(this).parents('.fw_preview_wrapper').addClass('no_pf');
                    } else {
                        jQuery(this).parents('.fw_preview_wrapper').addClass('has_pf');
                    }
                });
            }
            if (is_masonry.size() > 0) {
                is_masonry.masonry('reloadItems');
                is_masonry.masonry();
            }
            if (jQuery('.fs_grid_portfolio').size() > 0) {
                setupGrid();
                grid_portfolio_item.unbind();
                grid_portfolio_item.bind({
                    mouseover: function () {
                        jQuery(this).removeClass('unhovered');
                        jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height() + jQuery(this).find('.fs-port-cont').height());
                    },
                    mouseout: function () {
                        jQuery(this).addClass('unhovered');
                        jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height());
                    }
                });
            }
            jQuery('.newLoaded').each(function () {
                jQuery(this).find('.gallery_likes_add').click(function () {
                    var gallery_likes_this = jQuery(this);
                    if (!jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'))) {
                        jQuery.post(gt3_ajaxurl, {
                            action: 'add_like_attachment',
                            attach_id: jQuery(this).attr('data-attachid')
                        }, function (response) {
                            jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'), 'true', { expires: 7, path: '/' });
                            gallery_likes_this.addClass('already_liked');
                            gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
                            gallery_likes_this.find('span').text(response);
                        });
                    }
                });
                jQuery(this).removeClass('newLoaded');
            });
            setTimeout("animateList()", 300);
            jQuery(window).on('scroll', scrolling);
        });
}

function gt3_get_isotope_posts(post_type, posts_count, posts_already_showed, template_name, content_insert_class, categories) {
    jQuery.post(gt3_ajaxurl, { action: "gt3_get_blog_posts", post_type: post_type, posts_count: posts_count, posts_already_showed: posts_already_showed, template_name: template_name, content_insert_class: content_insert_class, categories: categories })
        .done(function (data) {
            if (data.length < 1) {
                jQuery(".load_more_works").hide("fast");
            }

            var $newItems = jQuery(data);
            jQuery(content_insert_class).isotope('insert', $newItems, function () {
                jQuery(content_insert_class).ready(function () {
                    jQuery(content_insert_class).isotope('reLayout');
                });
                if (jQuery('.fs-port-cont').size() > 0) {
                    setTimeout("setupGrid()", 500);
                    setTimeout("setupGrid()", 1000);
                    setTimeout('jQuery(".fs_grid_portfolio").isotope("reLayout");', 1500);
                }
            });
        });
}

function animateList() {
    jQuery('.loading:first').removeClass('loading').animate({'z-index': '15'}, 200, function () {
        animateList();
        if (is_masonry.size() > 0) {
            is_masonry.masonry();
        }
    });
};
function workCheck() {
    if (jQuery('.fs_blog_module').height() < parseInt(fullscreen_block.css('min-height'))) {
        get_works();
    } else {
        fullscreen_block.addClass('cheked');
    }
}
function scrolling() {
    var chk_height = jQuery('body').height() - jQuery(this).height() - header.height() - footer.height() - 20;
    if (jQuery(this).scrollTop() >= chk_height) {
        jQuery(this).unbind("scroll");
        get_works();
    }
}
var setTop = 0;
function centerWindow404() {
    setTop = (window_h - pp_block.height() - header.height()) / 2 + header.height();
    if (setTop < header.height() + 50) {
        pp_block.addClass('fixed');
        body.addClass('addPadding');
        pp_block.css('top', header.height() + 50 + 'px');
    } else {
        pp_block.css('top', setTop + 'px');
        pp_block.removeClass('fixed');
        body.removeClass('addPadding');
    }
}

jQuery(window).resize(function () {
    if (pp_block.size()) {
        setTimeout('centerWindow404()', 500);
        setTimeout('centerWindow404()', 1000);
    }
});
