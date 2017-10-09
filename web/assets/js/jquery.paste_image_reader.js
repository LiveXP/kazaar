// Created by STRd6
// MIT License
// jquery.paste_image_reader.js
// https://codepen.io/netsi1964/pen/IoJbg

var camera = ((new Audio()).canPlayType("audio/ogg; codecs=vorbis") === '') ?
    'https://s3-us-west-2.amazonaws.com/s.cdpn.io/9729/79190__nathan-lomeli__iphone-camera-click.wav' :
    'https://s3-us-west-2.amazonaws.com/s.cdpn.io/9729/79190__nathan-lomeli__iphone-camera-click.ogg';
var snd = new Audio(camera); // buffers automatically when created
function snapshoot() {
    snd.play();
}


(function ($) {
    var defaults;
    $.event.fix = (function (originalFix) {
        return function (event) {
            event = originalFix.apply(this, arguments);
            if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
                event.clipboardData = event.originalEvent.clipboardData;
            }
            return event;
        };
    })($.event.fix);

    defaults = { callback: $.noop, matchType: /image.*/};

    return $.fn.pasteImageReader = function (options) {
        if (typeof options === "function") {
            options = {
                callback: options
            };
        }
        options = $.extend({}, defaults, options);
        return this.each(function () {
            var $this, element;
            element = this;
            $this = $(this);
            return $this.bind('paste', function (event) {
                var clipboardData, found;
                found = false;
                clipboardData = event.clipboardData;
                return Array.prototype.forEach.call(clipboardData.types, function (type, i) {
                    var file, reader;
                    if (found) {
                        return;
                    }
                    if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
                        file = clipboardData.items[i].getAsFile();
                        reader = new FileReader();
                        reader.onload = function (evt) {
                            return options.callback.call(element, {
                                dataURL: evt.target.result,
                                event: evt,
                                file: file,
                                name: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                        snapshoot();
                        return found = true;
                    }
                });
            });
        });
    };
})(jQuery);


$("html").pasteImageReader(function (results) {
    var dataURL, filename;
    filename = results.filename;
    dataURL = results.dataURL;

    $data.text(dataURL);
    $size.val(results.file.size);
    $type.val(results.file.type);
    $test.attr('href', dataURL);

    var img = document.createElement('img');
    img.src = dataURL;
    img.onload = function() {
        var w = img.width;
        var h = img.height;
        var target = $(".target.active").data('target');
        $('#'+target).val(dataURL);
        return $(".target.active").css({backgroundImage: "url(" + dataURL + ")"}).data({'width': w, 'height': h});
    };
});

var $data, $size, $type, $test, $width, $height;
$(function () {
    $data = $('.data');
    $size = $('.size');
    $type = $('.type');
    $test = $('#test');
    $width = $('#width');
    $height = $('#height');
    $('.target').on('click', function() {
        var $this = $(this);
        var bi = $this.css('background-image');
        if (bi !== 'none') {
            $data.text(bi.substr(4,bi.length-6));
        }


        $('.target.active').removeClass('active');
        $this.addClass('active');

        // $this.toggleClass('contain');
        $this.toggleClass('full-size');
        if ($this.hasClass('full-size')) {
            $this.css({'width':$this.data('width'), 'height':$this.data('height'), 'z-index':'10'})
        } else {
            $this.css({'width':'', 'height':'', 'z-index':''})
        }

    });
});