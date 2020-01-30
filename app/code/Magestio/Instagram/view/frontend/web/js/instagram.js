define([
        'jquery',
        'mage/url',
        'mage/translate',
        'jquery/ui',
        'owlcarousel'
    ], function($, urlBuilder, $t){
        "use strict";

        $.widget('mage.instagramCaroussel', {

            options: {
                carousselOptions: {
                    responsive: {
                        0: {
                            items:1
                        },
                        768: {
                            items:3
                        },
                        992: {
                            items:4
                        },
                        1200: {
                            items:4
                        }
                    },
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    dots: true,
                    navRewind: true,
                    animateIn: 'fadeIn',
                    animateOut: 'fadeOut',
                    loop: true,
                    nav: true,
                    margin: 10,
                    navText: ["<span class='arrow left'><span></span><span></span></span>","<span class='arrow right'><span></span><span></span></span>"],
                },
                instagramBlockSelector: '.instagram-footer',
                owlCarousselSelector: '#instagram-photos'
            },

            _create: function () {
                var self = this;
                $.ajax({
                    url: urlBuilder.build("/instagram/index/index"),
                    type: 'get',

                    /** @inheritdoc */
                    beforeSend: function () {
                    },

                    error: function() {
                        self.removeBlock();
                    },

                    /** @inheritdoc */
                    success: function (res) {

                        if (res.length > 0) {
                            for (var i = 0; i < res.length; i++) {

                                var div = $('<div></div>');
                                div.addClass("item");
                                var a = $('<a></a>');
                                a.attr("href", res[i].url);
                                a.attr("rel", "noopener");
                                a.attr("target", "_blank");
                                var img = $('<img/>');
                                img.attr("src", res[i].image);

                                a.append(img);
                                div.append(a);

                                $(self.options.owlCarousselSelector).append(div);
                            }
                            var owlInstagram = $(self.options.owlCarousselSelector).owlCarousel(self.options.carousselOptions);

                        } else {
                            self.removeBlock();
                        }
                    }
                });

            },

            removeBlock: function () {
                $(this.options.instagramBlockSelector).remove();
            }

        });

        return $.mage.instagramCaroussel;

    }

);