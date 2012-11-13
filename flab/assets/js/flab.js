//(function ($) {
jQuery(document).ready(function ($) {
    $.fn.cattr = function (key, value, attribute) {
        if(typeof attribute == 'undefined') {
            attribute = 'className'
        }
        var $object = $(this).eq(0);
        var class_name = '';
        if(key != null) {
            var classes = $object[0][attribute].split(' ');
            for(i = 0; i < classes.length; i++) {
                if(classes[i].substr(0, key.length) == key) {
                    class_name = classes[i]
                }
            }
        }
        if(typeof value == 'undefined' || value == null) {
            return class_name.substr(key.length + 1)
        } else {
            if(class_name != '') {
                $object[0][attribute] = $object[0][attribute].replace(class_name, key + '-' + value)
            } else {
                $object[0][attribute] = $object[0][attribute] + ' ' + key + '-' + value
            }
        }
        return this
    };
    $(function () {
        function flab_farbtastic_prepare() {
            var color = $(this).children('input').eq(0).val();
            if($(this).children('.flab-farbtastic').length == 0) {
                $(this).children('input').before('<span class="flab-farbtastic-trigger" style="background-color: ' + (color == '' ? '#ffffff' : color) + ';"></span>');
                $(this).children('input').before('<div class="flab-farbtastic" style="display: none; position: absolute; z-index: 50;"></div>')
            }
        }

        function flab_farbtastic_init() {
            var $trigger = $(this).prev('.flab-farbtastic-trigger');
            var $input = $(this).next('input');
            if(typeof $(this).get(0).farbtastic == 'undefined') {
                $(this).farbtastic($trigger)
            }
            $(this).get(0).farbtastic.setColor($input.val())
        }
        $('label.flab-color').each(flab_farbtastic_prepare);
        $('.flab-farbtastic-trigger').live('click', function () {
            var picker = $(this).next('.flab-farbtastic').get(0).farbtastic;
            if(typeof picker == 'undefined') {
                $(this).next('.flab-farbtastic').farbtastic($(this))
            }
            $(this).next('.flab-farbtastic').fadeIn();
            return false
        });
        $('.widget-inside .builder-widget .builder-widget-actions .edit').live('click', function () {
            var $parent = $(this).closest('.builder-widget');
            var $color_fields = $parent.find('.flab-color');
            if($color_fields.length > 0) {
                $color_fields.each(flab_farbtastic_prepare);
                $color_fields.children('.flab-farbtastic').each(flab_farbtastic_init)
            }
        });
        $('.flab-farbtastic').each(flab_farbtastic_init);
        var farbtastic_no_update = false;
        $('.flab-color input').change(function () {
            if(!farbtastic_no_update) {
                var picker = $(this).prevAll('.flab-farbtastic').get(0).farbtastic;
                picker.setColor($(this).val());
                var color = $(this).val();
                if(color.substring(0, 3) == 'rgb') {
                    color = to_hex(color.replace('rgb(', '').replace(')', '').split(', '))
                }
            }
            farbtastic_no_update = false
        });
        $('.flab-farbtastic').live('mousemove', function () {
            var $trigger = $(this).prev('.flab-farbtastic-trigger');
            var $input = $(this).next('input');
            var picker = $(this).get(0).farbtastic;
            var val = $input.val();
            var color = picker.color;
            if(color != val) {
                $input.val(color);
                farbtastic_no_update = true;
                $input.change()
            }
        });
        $(document).mousedown(function () {
            $('.flab-farbtastic:visible').each(function () {
                var picker = $(this).get(0).farbtastic;
                $(this).next('input').val(picker.color);
                $(this).fadeOut()
            })
        });
        $('.flab-color-picker').hide();
        $('.flab-color-picker').each(function () {
            var $span = $(this).prev('.color-picker-trig');
            var $input = $(this).next('.flab-color');
            $(this).farbtastic($span);
            $(this).get(0).farbtastic.setColor($input.val());
            $span.click(function () {
                $(this).next('div').fadeIn()
            })
        });
        var color_no_update = false;
        $('input.flab-color').change(function () {
            if(!color_no_update) {
                var picker = $(this).prevAll('.flab-color-picker').get(0).farbtastic;
                picker.setColor($(this).val());
                var color = $(this).val();
                if(color.substring(0, 3) == 'rgb') {
                    color = to_hex(color.replace('rgb(', '').replace(')', '').split(', '))
                }
            }
            color_no_update = false
        });
        $('.flab-color-picker').live('mousemove', function () {
            var $span = $(this).prev('.color-picker-trig');
            var $input = $(this).next('.flab-color');
            var picker = $(this).get(0).farbtastic;
            var val = $input.val();
            var color = picker.color;
            if(color != val) {
                $input.val(color);
                color_no_update = true;
                $input.change()
            }
        });
        $(document).mousedown(function () {
            $('.flab-color-picker:visible').each(function () {
                var picker = $(this).get(0).farbtastic;
                $(this).next('.flab-color').val(picker.color);
                $(this).fadeOut()
            })
        });
        $('input[name=reset]').click(function () {
            if(!confirm('Are you sure you want to reset settings on this page? \'Cancel\' to stop, \'OK\' to reset.')) {
                return false
            }
        });
        $('.confirm').live('click', function () {
            if(!confirm('Are you sure?')) {
                return false
            }
        });
        $('.flab-form img').each(function () {
            var src = $(this).attr('src');
            if(src.length > 0 && src.match(/^(?:.*?)\.?(youtube|vimeo)\.com\/(watch\?[^#]*v=(\w+)|(\d+)).+$/)) {
                $(this).attr('src', flab.placeholder.video)
            }
        });
        $('.flab-form img').error(function () {
            var src = $(this).attr('src');
            if(src.length > 0 && src.match(/^(?:.*?)\.?(youtube|vimeo)\.com\/(watch\?[^#]*v=(\w+)|(\d+)).+$/)) {
                $(this).attr('src', flab.placeholder.video)
            } else {
                $(this).attr('src', flab.placeholder.img)
            }
        });
        $('.flab img').load(function () {
            $(this).show()
        });
        $('.flab .hidden').hide();
        window.wp_send_to_editor = window.send_to_editor;
        window.send_to_editor = function (html) {
            if(typeof html == "object" || typeof html == "array") {
                if(flab.upload_callback != '') {
                    for(var i = 0; i < html.length; i++) {
                        eval(flab.upload_callback + '(\'' + html[i] + '\');')
                    }
                }
                if(flab.upload_dst != null) {
                    if(html.length > 0) {
                        var src = html[0];
                        var $dst = $(flab.upload_dst);
                        $dst.each(function () {
                            if($(this).is("img")) {
                                $(this).attr("src", src).show()
                            } else if($(this).is("a")) {
                                $(this).attr("href", src)
                            } else if($(this).is("input")) {
                                $(this).val(src).change()
                            }
                        })
                    }
                }
                tb_remove();
                flab.upload_dst = null;
                flab.upload_callback = '';
                flab.upload_caller = null
            } else {
                if(flab.upload_dst != null) {
                    var url = '';
                    var alt = '';
                    var title = '';
                    if($('img', html).length > 0) {
                        url = $('img', html).attr('src');
                        alt = $('img', html).attr('alt');
                        title = $('img', html).attr('title')
                    } else {
                        url = html
                    }
                    $dst = $(flab.upload_dst);
                    $alt = $(flab.upload_dst + '_alt');
                    $title = $(flab.upload_dst + '_title');
                    $dst.each(function () {
                        if($(this).is('img')) {
                            $(this).attr('src', url).show()
                        } else if($(this).is('a')) {
                            $(this).attr('href', url)
                        } else if($(this).is('input')) {
                            $(this).val(url).change()
                        }
                    });
                    $alt.each(function () {
                        if($(this).is('img')) {
                            $(this).attr('alt', alt).show()
                        } else if($(this).is('a')) {
                            $(this).text(alt)
                        } else if($(this).is('input')) {
                            $(this).val(alt)
                        }
                    });
                    $title.each(function () {
                        if($(this).is('img')) {
                            $(this).attr('title', title).show()
                        } else if($(this).is('a')) {
                            $(this).attr('title', title)
                        } else if($(this).is('input')) {
                            $(this).val(title)
                        }
                    });
                    tb_remove();
                    flab.upload_dst = null
                } else {
                    window.wp_send_to_editor(html)
                }
            }
        };
        $('.remove_image').click(function () {
            var name = $(this).attr('name').replace('#', '').replace(flab.prefix + 'remove_', '');
            $('input.upload_' + name).val('').change();
            $('input.upload_' + name + '_alt').val('');
            $('input.upload_' + name + '_title').val('');
            $('img.upload_' + name).attr('src', flab.placeholder.img);
            return false
        });
        $('.upload_image').live('click', function () {
            if($(this).is('button')) {
                var name = $(this).attr('name');
                var width = $(this).cattr('width');
                var height = $(this).cattr('height');
                var single = $(this).hasClass('single');
                var tab = $(this).cattr('tab');
                var callback = $(this).cattr('callback');
                if(tab == '') {
                    tab = 'images'
                }
                if(!callback) {
                    flab.upload_dst = '.' + name.replace(flab.prefix, '').replace('[', '\[').replace(']', '\]');
                    flab.upload_callback = ''
                } else {
                    flab.upload_dst = null;
                    flab.upload_callback = callback
                }
                flab.upload_caller = $(this);
                tb_show('', 'media-upload.php?&type=image&post_id=0&flab=true&output=html&width=' + width + '&height=' + height + '&tab=' + tab + '&single=' + single + '&TB_iframe=true');
                return false
            }
        });
        $('.upload_media').live('click', function () {
            if($(this).is('button')) {
                var name = $(this).attr('name');
                flab.upload_dst = '.' + name.replace(flab.prefix, '');
                tb_show('', 'media-upload.php?&post_id=0&flab=true&TB_iframe=true');
                return false
            }
        });
        var old_tb_showIframe = tb_showIframe;
        tb_showIframe = function () {
            old_tb_showIframe();
            tb_position();
            setTimeout(function () {
                tb_position()
            }, 10)
        }
    })
    //})(jQuery);
});