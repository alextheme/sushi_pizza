(function ($) {
    $(document).ready(function () {

        $('.wp-block-gallery').lightGallery({
            selector: 'a',
            download: false
        });

        $('.wp-block-image figure').lightGallery({
            selector: 'a',
            download: false
        });

        $('.gallery').lightGallery({
            selector: 'a',
            download: false
        });
        $('.harmo-one:nth-of-type(3)').addClass('active').children('.harmo-content').slideDown(300);

        $('.harmo-one').on('click', function (e) {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).children('.harmo-content').slideUp(300);
            } else {
                $(this).addClass('active');
                $(this).children('.harmo-content').slideDown(300);
            }
        });
        $('.harmo-one .harmo-one-title').on('click', function (e) {
            e.preventDefault();
        });
        $('.tabbler .tab-links .harmo-one-title').on('click', function (e) {
            e.preventDefault();
            var id = $(this).attr('href');
            $('.tabbler .tab-links a').removeClass('active');
            $('.tabbler .tab-content').removeClass('opened');
            $(this).addClass('active');
            $(id).addClass('opened');
        });

        $('.button').on('click', function () {
            $(this).addClass('clicked');
        });

        // AOS.init();
    });
})(jQuery);

