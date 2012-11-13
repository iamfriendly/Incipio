(function (a) {
    a.fn.cattr = function (b, c, d) {
        if (typeof d == "undefined") {
            d = "className"
        }
        var e = a(this).eq(0);
        var f = "";
        if (b != null) {
            var g = e[0][d].split(" ");
            for (i = 0; i < g.length; i++) {
                var h = g[i].split("-");
                var j = h.pop();
                var k = h.join("-");
                if (k == b) {
                    f = g[i]
                }
            }
        }
        if (typeof c == "undefined" || c == null) {
            return f.substr(b.length + 1)
        } else {
            if (f != "") {
                e[0][d] = e[0][d].replace(f, b + "-" + c)
            } else {
                e[0][d] = e[0][d] + " " + b + "-" + c
            }
        }
        return this
    };
    a(document).ready(function () {
        if (!jQuery.easing.easeInQuad) {
            jQuery.extend(jQuery.easing, {
                def: "easeOutQuad",
                swing: function (a, b, c, d, e) {
                    return jQuery.easing[jQuery.easing.def](a, b, c, d, e)
                },
                easeInQuad: function (a, b, c, d, e) {
                    return d * (b /= e) * b + c
                },
                easeOutQuad: function (a, b, c, d, e) {
                    return -d * (b /= e) * (b - 2) + c
                },
                easeInOutQuad: function (a, b, c, d, e) {
                    if ((b /= e / 2) < 1) return d / 2 * b * b + c;
                    return -d / 2 * (--b * (b - 2) - 1) + c
                },
                easeInCubic: function (a, b, c, d, e) {
                    return d * (b /= e) * b * b + c
                },
                easeOutCubic: function (a, b, c, d, e) {
                    return d * ((b = b / e - 1) * b * b + 1) + c
                },
                easeInOutCubic: function (a, b, c, d, e) {
                    if ((b /= e / 2) < 1) return d / 2 * b * b * b + c;
                    return d / 2 * ((b -= 2) * b * b + 2) + c
                },
                easeInQuart: function (a, b, c, d, e) {
                    return d * (b /= e) * b * b * b + c
                },
                easeOutQuart: function (a, b, c, d, e) {
                    return -d * ((b = b / e - 1) * b * b * b - 1) + c
                },
                easeInOutQuart: function (a, b, c, d, e) {
                    if ((b /= e / 2) < 1) return d / 2 * b * b * b * b + c;
                    return -d / 2 * ((b -= 2) * b * b * b - 2) + c
                },
                easeInQuint: function (a, b, c, d, e) {
                    return d * (b /= e) * b * b * b * b + c
                },
                easeOutQuint: function (a, b, c, d, e) {
                    return d * ((b = b / e - 1) * b * b * b * b + 1) + c
                },
                easeInOutQuint: function (a, b, c, d, e) {
                    if ((b /= e / 2) < 1) return d / 2 * b * b * b * b * b + c;
                    return d / 2 * ((b -= 2) * b * b * b * b + 2) + c
                },
                easeInSine: function (a, b, c, d, e) {
                    return -d * Math.cos(b / e * (Math.PI / 2)) + d + c
                },
                easeOutSine: function (a, b, c, d, e) {
                    return d * Math.sin(b / e * (Math.PI / 2)) + c
                },
                easeInOutSine: function (a, b, c, d, e) {
                    return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c
                },
                easeInExpo: function (a, b, c, d, e) {
                    return b == 0 ? c : d * Math.pow(2, 10 * (b / e - 1)) + c
                },
                easeOutExpo: function (a, b, c, d, e) {
                    return b == e ? c + d : d * (-Math.pow(2, -10 * b / e) + 1) + c
                },
                easeInOutExpo: function (a, b, c, d, e) {
                    if (b == 0) return c;
                    if (b == e) return c + d;
                    if ((b /= e / 2) < 1) return d / 2 * Math.pow(2, 10 * (b - 1)) + c;
                    return d / 2 * (-Math.pow(2, -10 * --b) + 2) + c
                },
                easeInCirc: function (a, b, c, d, e) {
                    return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c
                },
                easeOutCirc: function (a, b, c, d, e) {
                    return d * Math.sqrt(1 - (b = b / e - 1) * b) + c
                },
                easeInOutCirc: function (a, b, c, d, e) {
                    if ((b /= e / 2) < 1) return -d / 2 * (Math.sqrt(1 - b * b) - 1) + c;
                    return d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c
                },
                easeInElastic: function (a, b, c, d, e) {
                    a = 1.70158;
                    var f = 0,
                        g = d;
                    if (b == 0) return c;
                    if ((b /= e) == 1) return c + d;
                    f || (f = e * .3);
                    if (g < Math.abs(d)) {
                        g = d;
                        a = f / 4
                    } else a = f / (2 * Math.PI) * Math.asin(d / g);
                    return -(g * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - a) * 2 * Math.PI / f)) + c
                },
                easeOutElastic: function (a, b, c, d, e) {
                    a = 1.70158;
                    var f = 0,
                        g = d;
                    if (b == 0) return c;
                    if ((b /= e) == 1) return c + d;
                    f || (f = e * .3);
                    if (g < Math.abs(d)) {
                        g = d;
                        a = f / 4
                    } else a = f / (2 * Math.PI) * Math.asin(d / g);
                    return g * Math.pow(2, -10 * b) * Math.sin((b * e - a) * 2 * Math.PI / f) + d + c
                },
                easeInOutElastic: function (a, b, c, d, e) {
                    a = 1.70158;
                    var f = 0,
                        g = d;
                    if (b == 0) return c;
                    if ((b /= e / 2) == 2) return c + d;
                    f || (f = e * .3 * 1.5);
                    if (g < Math.abs(d)) {
                        g = d;
                        a = f / 4
                    } else a = f / (2 * Math.PI) * Math.asin(d / g);
                    if (b < 1) return -.5 * g * Math.pow(2, 10 * (b -= 1)) * Math.sin((b * e - a) * 2 * Math.PI / f) + c;
                    return g * Math.pow(2, -10 * (b -= 1)) * Math.sin((b * e - a) * 2 * Math.PI / f) * .5 + d + c
                },
                easeInBack: function (a, b, c, d, e, f) {
                    if (f == undefined) f = 1.70158;
                    return d * (b /= e) * b * ((f + 1) * b - f) + c
                },
                easeOutBack: function (a, b, c, d, e, f) {
                    if (f == undefined) f = 1.70158;
                    return d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c
                },
                easeInOutBack: function (a, b, c, d, e, f) {
                    if (f == undefined) f = 1.70158;
                    if ((b /= e / 2) < 1) return d / 2 * b * b * (((f *= 1.525) + 1) * b - f) + c;
                    return d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c
                },
                easeInBounce: function (a, b, c, d, e) {
                    return d - jQuery.easing.easeOutBounce(a, e - b, 0, d, e) + c
                },
                easeOutBounce: function (a, b, c, d, e) {
                    return (b /= e) < 1 / 2.75 ? d * 7.5625 * b * b + c : b < 2 / 2.75 ? d * (7.5625 * (b -= 1.5 / 2.75) * b + .75) + c : b < 2.5 / 2.75 ? d * (7.5625 * (b -= 2.25 / 2.75) * b + .9375) + c : d * (7.5625 * (b -= 2.625 / 2.75) * b + .984375) + c
                },
                easeInOutBounce: function (a, b, c, d, e) {
                    if (b < e / 2) return jQuery.easing.easeInBounce(a, b * 2, 0, d, e) * .5 + c;
                    return jQuery.easing.easeOutBounce(a, b * 2 - e, 0, d, e) * .5 + d * .5 + c
                }
            })
        }
        if (!jQuery.mousewheel) {
            (function (a) {function d(b) {
                    var c = b || window.event,
                        d = [].slice.call(arguments, 1),
                        e = 0,
                        f = true,
                        g = 0,
                        h = 0;
                    b = a.event.fix(c);
                    b.type = "mousewheel";
                    if (c.wheelDelta) {
                        e = c.wheelDelta / 120
                    }
                    if (c.detail) {
                        e = -c.detail / 3
                    }
                    h = e;
                    if (c.axis !== undefined && c.axis === c.HORIZONTAL_AXIS) {
                        h = 0;
                        g = -1 * e
                    }
                    if (c.wheelDeltaY !== undefined) {
                        h = c.wheelDeltaY / 120
                    }
                    if (c.wheelDeltaX !== undefined) {
                        g = -1 * c.wheelDeltaX / 120
                    }
                    d.unshift(b, e, g, h);
                    return (a.event.dispatch || a.event.handle).apply(this, d)
                }
                var b = ["DOMMouseScroll", "mousewheel"];
                if (a.event.fixHooks) {
                    for (var c = b.length; c;) {
                        a.event.fixHooks[b[--c]] = a.event.mouseHooks
                    }
                }
                a.event.special.mousewheel = {
                    setup: function () {
                        if (this.addEventListener) {
                            for (var a = b.length; a;) {
                                this.addEventListener(b[--a], d, false)
                            }
                        } else {
                            this.onmousewheel = d
                        }
                    },
                    teardown: function () {
                        if (this.removeEventListener) {
                            for (var a = b.length; a;) {
                                this.removeEventListener(b[--a], d, false)
                            }
                        } else {
                            this.onmousewheel = null
                        }
                    }
                };
                a.fn.extend({
                    mousewheel: function (a) {
                        return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
                    },
                    unmousewheel: function (a) {
                        return this.unbind("mousewheel", a)
                    }
                })
            })(jQuery)
        }
        if (!jQuery.swipe) {
            (function (a) {
                a.fn.swipe = function (b) {
                    var c = {
                        threshold: {
                            x: 30,
                            y: 10
                        },
                        swipeLeft: function () {
                            alert("swiped left")
                        },
                        swipeRight: function () {
                            alert("swiped right")
                        }
                    };
                    var b = a.extend(c, b);
                    if (!this) {
                        return false
                    }
                    return this.each(function () {function i(a) {}function f(a) {
                            d.x = a.targetTouches[0].pageX;
                            d.y = a.targetTouches[0].pageY;
                            e.x = d.x;
                            e.y = d.y
                        }function h(a) {
                            var b = d.y - e.y;
                            if (b < c.threshold.y && b > c.threshold.y * -1) {
                                changeX = d.x - e.x;
                                if (changeX > c.threshold.x) {
                                    c.swipeLeft()
                                }
                                if (changeX < c.threshold.x * -1) {
                                    c.swipeRight()
                                }
                            }
                        }function g(a) {
                            a.preventDefault();
                            e.x = a.targetTouches[0].pageX;
                            e.y = a.targetTouches[0].pageY
                        }function f(a) {
                            d.x = a.targetTouches[0].pageX;
                            d.y = a.targetTouches[0].pageY
                        }
                        var b = a(this);
                        var d = {
                            x: 0,
                            y: 0
                        };
                        var e = {
                            x: 0,
                            y: 0
                        };
                        this.addEventListener("touchstart", f, false);
                        this.addEventListener("touchmove", g, false);
                        this.addEventListener("touchend", h, false);
                        this.addEventListener("touchcancel", i, false)
                    })
                }
            })(jQuery)
        }
        var b = {};
        b.inView = function (b) {
            var c = b.offset().top,
                d = b.outerHeight(),
                e = a(window).scrollTop(),
                f = a(window).height();
            if (c + d < e + f && c + d > e || c < e + f && c > e) {
                return true
            } else {
                return false
            }
        };
        b.autoplayPause = function (a, b) {
            b.autoplay_active = 0;
            clearTimeout(b.autoplay_stamp)
        };
        b.autoplayResume = function (a, c) {
            clearTimeout(c.autoplay_stamp);
            if (c.autoplay_enable === true && c.force_autoplay_pause !== 1) {
                c.autoplay_active = 1;
                c.autoplay_stamp = setTimeout(function () {
                    b.shiftInit(a, c, "relative", c.autoplay_shift_dir)
                }, c.autoplay_interval * 1e3)
            }
        };
        b.autoplayInit = function (c, d) {
            if (d.autoplay_enable === true) {
                a(window).bind("load", function () {
                    if (b.inView(c)) {
                        b.autoplayResume(c, d)
                    }
                }).bind("blur", function () {
                    b.autoplayPause(c, d)
                }).bind("focus", function () {
                    b.autoplayResume(c, d)
                }).bind("scroll", function () {
                    if (!b.inView(c)) {
                        if (d.autoplay_active === 1 && d.autoplay_enable === true) {
                            b.autoplayPause(c, d)
                        }
                    } else {
                        if (d.autoplay_active !== 1 && d.autoplay_enable === true) {
                            b.autoplayResume(c, d)
                        }
                    }
                })
            } else {
                clearTimeout(d.autoplay_stamp);
                d.autoplay_active = 0
            }
        };
        b.windowHeightSet = function (a, b) {
            b.elem_height = b.col_group_elems.eq(b.view_pos).outerHeight() - b.col_spacing_size * b.col_spacing_enable;
            b.slider_window.stop(true, true).animate({
                height: b.elem_height
            }, b.scroll_speed)
        };
        b.shiftApply = function (c, d) {
            var e = ["x", "y", "z"],
                f = ["slide", "slideIn", "slideOut", "switch", "swap"],
                g, h, i = 0,
                j = 0,
                k = 0,
                l = 0,
                m = 1,
                n = 1;
            if (d.transition === "random") {
                h = true;
                d.transition = f[Math.ceil(Math.random() * f.length - 1)]
            }
            if (d.scroll_axis === "random") {
                g = true;
                d.scroll_axis = e[Math.ceil(Math.random() * e.length - 1)]
            }
            if (d.scroll_axis === "x") {
                i = 1;
                k = 1
            } else if (d.scroll_axis === "y") {
                j = 1;
                l = 1
            }
            if (d.transition === "slideIn") {
                k = 0;
                l = 0
            } else if (d.transition === "slideOut") {
                i = 0;
                j = 0
            }
            if (d.transition === "switch" || d.transition === "swap" || d.transition === "shuffle") {
                n = -1
            }
            if (h === true) {
                d.transition = "random"
            }
            if (g === true) {
                d.scroll_axis = "random"
            }
            d.col_group_elems.eq(d.view_pos).css({
                left: d.shift_dir * d.elem_width * i * m,
                top: d.shift_dir * d.elem_height * j * m,
                visibility: "visible",
                "z-index": 10,
                opacity: 0
            }).animate({
                left: 0,
                top: 0,
                opacity: 1
            }, d.scroll_speed, d.easing).end().eq(d.prev_view_pos).css({
                left: 0,
                top: 0
            }).animate({
                left: -d.shift_dir * d.elem_width * k * n,
                top: -d.shift_dir * d.elem_height * l * n,
                opacity: 0
            }, d.scroll_speed, d.easing, function () {
                a(this).css({
                    visibility: "hidden",
                    "z-index": -1
                })
            });
            b.windowHeightSet(c, d);
            var o = setTimeout(function () {
                d.shift_busy = 0
            }, d.scroll_speed)
        };
        b.shiftInit = function (a, c, d, e) {
            if (c.shift_busy !== 1) {
                c.shift_dir = function () {
                    if (d === "absolute") {
                        if (e > c.view_pos) {
                            return 1
                        } else if (e < c.view_pos) {
                            return -1
                        } else {
                            return 0
                        }
                    } else if (d === "relative") {
                        return e
                    }
                }();
                c.prev_view_pos = c.view_pos;
                c.view_pos = function () {
                    if (d === "relative") {
                        if (c.loop === true) {
                            if (c.view_pos + c.shift_dir < 0) {
                                return c.col_group_count - 1
                            } else if (c.view_pos + c.shift_dir > c.col_group_count - 1) {
                                return 0
                            } else {
                                return c.view_pos + c.shift_dir
                            }
                        } else if (c.loop === false) {
                            if (c.view_pos + c.shift_dir < 0) {
                                return -2
                            } else if (c.view_pos + c.shift_dir > c.col_group_count - 1) {
                                return -2
                            } else {
                                return c.view_pos + c.shift_dir
                            }
                        }
                    } else if (d === "absolute") {
                        return e
                    }
                }();
                if (c.view_pos !== -2) {
                    if (c.ctrl_pag === true) {
                        c.ctrl_pag_elem.children().removeClass("current").eq(c.view_pos).addClass("current")
                    }
                    c.shift_busy = 1;
                    b.shiftApply(a, c);
                    b.autoplayResume(a, c)
                } else {
                    c.view_pos = c.prev_view_pos
                }
            }
        };
        b.rowsSet = function (a, b) {
            var c, d = 0,
                e;
            if (b.grid_height === "auto") {
                b.col_elems.removeClass("first-flab-col");
                for (c = 0; c < b.col_elem_count; c += 1) {
                    if (c % b.true_cols === 0) {
                        b.col_elems.eq(c).addClass("first-flab-col")
                    }
                }
            } else if (b.grid_height === "constrain") {
                b.col_elems.css({
                    height: b.col_elem_width,
                    overflow: "hidden"
                })
            } else if (typeof b.grid_height === "number") {
                b.col_elems.css({
                    height: b.grid_height,
                    overflow: "hidden"
                })
            }
        };
        b.sliderFnInit = function (c, d) {
            if (d.col_group_count > 1) {
                b.autoplayInit(c, d);
                c.swipe({
                    swipeLeft: function () {
                        b.shiftInit(c, d, "relative", 1)
                    },
                    swipeRight: function () {
                        b.shiftInit(c, d, "relative", -1)
                    }
                }).bind("mousewheel", function (a, e, f, g) {
                    var h = -1;
                    if (g !== 0 && g < 0 || f !== 0 && f > 0) {
                        h = 1
                    }
                    b.shiftInit(c, d, "relative", h);
                    a.preventDefault()
                }).bind("mouseenter", function () {
                    var e = a(window).scrollLeft(),
                        f = a(window).scrollTop();
                    b.autoplayPause(c, d);
                    d.ctrl_wrap.stop(true, true).fadeIn(500)
                }).bind("mouseleave", function () {
                    b.autoplayResume(c, d);
                    d.ctrl_wrap.stop(true, true).delay(500).fadeOut(500)
                });
                if (d.ctrl_wrap) {
                    d.ctrl_wrap.css({
                        "margin-left": function () {
                            return -a(this).outerWidth() / 2
                        },
                        "margin-top": function () {
                            return -a(this).outerHeight() / 2 - d.col_spacing_size / 2 * d.col_spacing_enable
                        }
                    }).find(".flab-ctrl").attr("unselectable", "on").css({
                        "-ms-user-select": "none",
                        "-moz-user-select": "none",
                        "-webkit-user-select": "none",
                        "user-select": "none"
                    }).bind("click", function () {
                        this.onselectstart = function () {
                            return false
                        };
                        b.shiftInit(c, d, a(this).data("shifttype"), a(this).data("shiftdest"));
                        return false
                    })
                }
                if (d.ctrl_external.length > 0) {
                    var e;
                    for (e = 0; e < d.ctrl_external.length; e += 1) {
                        var c = d.ctrl_external[e][0];
                        c.attr("data-shifttype", "absolute").attr("data-shiftdest", d.ctrl_external[e][1]).bind("click", function (e) {
                            if (a(this).data("shiftdest") <= d.col_group_count) {
                                b.shiftInit(c, d, a(this).data("shifttype"), a(this).data("shiftdest"));
                                e.preventDefault()
                            }
                        })
                    }
                }
            }
        };
        b.colGroupsSet = function (b, c) {
            var d;
            if (c.col_group_elems) {
                c.col_elems.unwrap()
            }
            for (d = 0; d < c.col_elem_count; d += c.col_group_size) {
                a('<div class="flab-col-group"></div>').appendTo(c.col_elems_wrap).append(function () {
                    if (d + c.col_group_size < c.col_elem_count) {
                        return c.col_elems_wrap.children().slice(0, c.col_group_size)
                    } else {
                        return c.col_elems_wrap.children().slice(0, c.col_elem_count - d)
                    }
                })
            }
            c.col_group_elems = b.find(".flab-col-group");
            c.col_group_elems.eq(c.view_pos).css({
                "z-index": 1,
                visibility: "visible"
            });
            c.elem_height = c.col_group_elems.eq(c.view_pos).outerHeight() - c.col_spacing_size * c.col_spacing_enable
        };
        b.ctrlWidthSet = function (b, c) {
            if (c.ctrl_pag === true) {
                c.ctrl_pag_elem.children().css({
                    display: "block"
                }).slice(c.col_group_count).css({
                    display: "none"
                }).end().end().css({
                    width: function () {
                        return c.col_group_count * (a(this).children().outerWidth() + parseInt(a(this).children().css("margin-left"), 10) + parseInt(a(this).children().css("margin-right"), 10))
                    }
                });
                c.ctrl_pag_elem.children().eq(c.view_pos).addClass("current")
            }
            if (c.ctrl_arrows === true && c.ctrl_pag === true) {
                c.ctrl_wrap.css({
                    width: function () {
                        if (c.ctrl_pag && c.ctrl_arrows && c.ctrl_pag_elem.outerWidth() > c.ctrl_arrows_elem.outerWidth()) {
                            return c.ctrl_pag_elem.outerWidth()
                        } else {
                            return c.ctrl_arrows_elem.outerWidth()
                        }
                    },
                    "margin-left": function () {
                        return -a(this).outerWidth() / 2
                    }
                })
            } else {};
        };
        b.sliderStructureInit = function (c, d) {
            c.addClass("flab-slider").children().wrapAll('<div class="flab-slider-window"></div>');
            d.slider_window = c.find(".flab-slider-window");
            b.colGroupsSet(c, d);
            d.slider_window.height(d.original_elem_height);
            b.windowHeightSet(c, d);
            if (d.ctrl_arrows === true || d.ctrl_pag === true) {
                a('<div class="flab-ctrl-wrap"></div>').appendTo(c).hide();
                d.ctrl_wrap = c.find(".flab-ctrl-wrap")
            }
            if (d.ctrl_arrows === true) {
                d.ctrl_wrap.append('<div class="flab-ctrl-car"><div class="flab-prev flab-ctrl" data-shifttype="relative" data-shiftdest="-1"></div><div class="flab-next flab-ctrl" data-shifttype="relative" data-shiftdest="1"></div></div>');
                d.ctrl_arrows_elem = d.ctrl_wrap.find(".flab-ctrl-car");
                d.ctrl_arrows_elem_width = d.ctrl_arrows_elem.outerWidth()
            }
            if (d.ctrl_pag === true) {
                d.ctrl_wrap.append(function () {
                    var a, b = "";
                    b += '<div class="flab-ctrl-pag">';
                    for (a = 0; a < d.col_elem_count; a += 1) {
                        b += '<div class="flab-ctrl" data-shifttype="absolute" data-shiftdest="' + a + '"></div>'
                    }
                    b += "</div>";
                    return b
                });
                d.ctrl_pag_elem = d.ctrl_wrap.find(".flab-ctrl-pag");
                d.ctrl_pag_elem_width = d.ctrl_pag_elem.outerWidth();
                d.ctrl_wrap.css({
                    width: function () {
                        if (d.ctrl_pag && d.ctrl_arrows && d.ctrl_pag_elem_width > d.ctrl_arrows_elem_width) {
                            return d.ctrl_pag_elem_width
                        } else {
                            return d.ctrl_arrows_elem_width
                        }
                    }
                })
            }
            b.ctrlWidthSet(c, d);
            b.sliderFnInit(c, d);
            a(window).bind("resize", function () {
                b.colGroupsSet(c, d);
                b.windowHeightSet(c, d);
                b.ctrlWidthSet(c, d)
            })
        };
        b.gridDataSet = function (b, c) {
            c.elem_width = b.outerWidth();
            c.original_elem_height = b.outerHeight();
            c.col_elems_wrap = b.find(".flab-cols");
            c.col_elems = b.find(".flab-col");
            c.col_elem_count = c.col_elems.length;
            c.col_elem_width = c.col_elems.outerWidth();
            c.true_cols = Math.round(c.elem_width / c.col_elem_width);
            if (a(window).width() < 580) {
                if (!c.original_rows) {
                    c.original_rows = c.rows
                }
                c.rows = 1
            }
            if (a(window).width() > 580 && typeof c.original_rows === "number" && c.rows !== c.original_rows) {
                c.rows = c.original_rows
            }
            c.col_group_size = c.rows * c.true_cols;
            c.col_group_count = Math.ceil(c.col_elem_count / c.col_group_size);
            if (c.view_pos >= c.col_group_count) {
                c.view_pos = 0
            }
        };
        b.styleContentGenerate = function (b, c) {
            var d = "";
            if (c.col_spacing_size !== 30 && c.col_spacing_enable !== 0) {
                d += ".flab-grid" + c.elem_selector + " .flab-cols { margin: " + -(c.col_spacing_size / 2) + "px; } .flab-grid" + c.elem_selector + " .flab-cols .flab-col { padding: " + c.col_spacing_size / 2 + "px;}";
                if (a.browser.msie && parseInt(a.browser.version, 10) === 7) {
                    d += ".ie7-flab-grid-fix.cols-wrap { margin:" + -(c.col_spacing_size / 2) + "px !important; } .ie7-flab-grid-fix.cols-wrap .flab-col > *:first-child { padding: " + c.col_spacing_size / 2 + "px !important; }"
                }
            }
            if (c.width !== "auto") {
                d += c.elem_selector + " { width: " + c.width + "; }"
            }
            return d
        };
        b.galleryTitleInit = function (b, c) {
            var d = b.find("img");
            d.each(function () {
                var b = a(this).attr("title"),
                    c = a(this).attr("alt"),
                    d = "";
                if (b !== undefined) {
                    d = b
                } else if (c !== undefined) {
                    d = c
                }
                if (d !== "") {
                    a('<span class="flab-img-title">' + d + "</span>").appendTo(a(this).parent());
                    var e = a(this).siblings(".flab-img-title"),
                        f = e.outerHeight();
                    e.css({
                        opacity: 0,
                        bottom: -f
                    });
                    a(this).parent().on("mouseenter", function () {
                        e.stop(true, true).animate({
                            opacity: 1,
                            bottom: 0
                        }, 500)
                    }).on("mouseleave", function () {
                        e.delay(250).animate({
                            opacity: 0,
                            bottom: -f
                        }, 500)
                    })
                }
            })
        };
        b.gridStructureInit = function (c, d) {
            var e = function () {
                    if (a.browser.msie && a.browser.version < 9) {
                        return "body"
                    } else {
                        return "head"
                    }
                }(),
                f = function () {
                    var a = ["flab-grid", "flab-grid-height-" + d.grid_height, "flab-image-stretch-mode-" + d.image_stretch_mode, "align" + d.align];
                    return a.join(" ")
                },
                g = function () {
                    var a = ["flab-cols-" + d.cols, "flab-rows-" + d.rows, "flab-spacing-" + (d.col_spacing_enable === true ? 1 : 0)];
                    return a.join(" ")
                },
                h = c.find("img"),
                i = h.length;
            if (a.browser.msie && parseInt(a.browser.version, 10) === 7) {
                c.addClass("ie7-flab-grid-fix")
            }
            if (c.find(".flab-cols").length === 0) {
                c.addClass(f()).children().wrapAll('<div class="flab-cols ' + g() + '"></div>').wrap('<div class="flab-col"></div>')
            }
            if (!b.css) {
                b.css = ""
            }
            b.css += b.styleContentGenerate(c, d);
            a(e).find(".flab-slider-custom-styles").remove().end().append('<style class="flab-slider-custom-styles">' + b.css + "</style>");
            if (h.length > 0) {
                if (!j) {
                    var j = 0,
                        k = 0
                }
                h.each(function (e) {
                    a(this).bind("load", function () {
                        j += 1;
                        if (j === i && k === 0 || j !== i && e === i - 1 && k === 0) {
                            k = 1;
                            b.gridDataSet(c, d);
                            b.rowsSet(c, d);
                            if (d.gallery_img_title === true) {
                                b.galleryTitleInit(c, d)
                            }
                            if (d.slider === true) {
                                b.sliderStructureInit(c, d)
                            }
                        }
                    });
                    if (typeof this.complete != "undefined" && this.complete || typeof this.naturalWidth != "undefined" && this.naturalWidth > 0) {
                        a(this).trigger("load").off("load")
                    }
                })
            } else {
                b.gridDataSet(c, d);
                b.rowsSet(c, d);
                if (d.slider === true) {
                    b.sliderStructureInit(c, d)
                }
            }
            a(window).bind("resize", function () {
                b.gridDataSet(c, d);
                b.rowsSet(c, d)
            })
        };
        a.fn.gridSlider = function (c) {
            var d = {
                slider: true,
                cols: 1,
                rows: 1,
                width: "auto",
                align: "auto",
                col_spacing_enable: true,
                col_spacing_size: 30,
                ctrl_arrows: true,
                ctrl_pag: true,
                ctrl_external: [],
                scroll_axis: "x",
                transition: "slide",
                easing: "swing",
                scroll_speed: 500,
                autoplay_enable: false,
                autoplay_interval: 5,
                autoplay_shift_dir: 1,
                view_pos: 0,
                grid_height: "auto",
                image_stretch_mode: "auto",
                gallery_img_title: false,
                loop: true
            },
                e = a(this).selector.split(", "),
                c = a.extend(d, c);
            return this.each(function (d) {
                if (!a(this).data("flab_grid_slider")) {
                    a(this).data("flab_grid_slider", true);
                    c.elem_selector = e[d];
                    b.gridStructureInit(a(this), jQuery.extend(true, {}, c))
                }
            })
        };
        var c = {};
        a("a[rel*=lightbox]").each(function () {
            var b = a(this).attr("rel");
            if (typeof c[b] == "undefined") {
                c[b] = true
            }
        });
        for (var d in c) {
            d = d.replace("[", "\\[").replace("]", "\\]");
            if (!a("a[rel=" + d + "]").eq(0).hasClass("cboxElement")) {
                a("a[rel=" + d + "]").colorbox({
                    rel: d,
                    maxWidth: "80%",
                    maxHeight: "80%",
                    fixed: true
                })
            }
        }
        var e = function (a, b) {
                if (a.length > 0) {
                    return a.attr("class").match(b)
                }
            },
            f = function (b) {
                var c = b.children(".flab-title"),
                    d = b.children(".flab-content");
                d.hide();
                c.each(function () {
                    if (a(this).hasClass("current")) {
                        a(this).next().stop(true).show(250)
                    }
                });
                if (b.hasClass("constrain-0")) {
                    c.click(function () {
                        a(this).toggleClass("current").next().toggle(250)
                    })
                } else {
                    c.click(function () {
                        a(this).addClass("current").siblings().removeClass("current").end().next().show(250).siblings(".flab-content").hide(250)
                    })
                }
            },
            i = function (b) {
                b.each(function () {
                    h(a(this))
                })
            },
            j = function (b) {
                b.each(function () {
                    a(this).width(a(this).children("img").width())
                })
            },
            k = function (a) {
                var b = a.find(".flab-gallery-item");
                gallery_elem_width = b.width();
                if (gallery_elem_width > 400) {
                    gallery_elem_width = 400
                }
                b.find('[class*="media-"]').height(gallery_elem_width)
            },
            l = function (b) {
                b.each(function () {
                    var b = a(this);
                    k(a(this));
                    a(window).bind("resize", function () {
                        k(b)
                    })
                })
            },
            m = function (a) {
                var b, c = 0,
                    d, e = a.children(".flab-col"),
                    f = e.length,
                    g = Math.round(a.width() / e.width());
                for (b = 0; b < f; b += 1) {
                    if (b % g === 0) {
                        c = 0
                    }
                    d = e.eq(b).height();
                    if (d >= c) {
                        c = d
                    } else {
                        e.eq(b).css({
                            "margin-bottom": c - d
                        })
                    }
                }
            },
            n = function (b) {
                var c = b.find("img"),
                    d = c.length,
                    e = b.children(".flab-col");
                if (c.length > 0) {
                    if (!f) {
                        var f = 0,
                            g = 0
                    }
                    c.each(function (c) {
                        a(this).bind("load", function () {
                            f += 1;
                            if (f === d && g === 0 || f !== d && c === d - 1 && g === 0) {
                                g = 1;
                                m(b)
                            }
                        });
                        if (typeof this.complete != "undefined" && this.complete || typeof this.naturalWidth != "undefined" && this.naturalWidth > 0) {
                            a(this).trigger("load").off("load")
                        }
                    })
                } else {
                    m(b)
                }
            },
            o = function (b) {
                b.find(".flab-col").each(function () {
                    a(this).children().eq(-1).addClass("last-child")
                });
                b.each(function () {
                    n(a(this))
                });
                a(window).bind("resize", function () {
                    b.each(function () {
                        n(a(this))
                    })
                })
            };

    })
})(jQuery)