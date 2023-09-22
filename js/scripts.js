jQuery(document).ready(function ($) {

    /*Translate checkout*/
    //Change language actions
    let url = window.location.href;
    let deliver9 = "Dostawa kurierem";
    let teke = "Odbiór osobisty";
    let ctd = "Zadzwonić w drzwi";
    let ctm = "Zadzwonić do mnie";
    let asoons = "Jak najszybciej";
    let hours = "Przedział czasowy";
    let more = "Więcej";
    let inser = "Potwierdź";
    let rest = "Wybierz restaurację!";
    let added = "Produkt dodany do koszyka";
    let ddw = "Data dostawy: ";
    let sst = "Przesyłka do";
    let opt1 = "Wybierz przedział czasowy";
    let alertErr = "Numer telefonu nie jest poprawny";
    let wecant = "Zamówineia online są zablokowane! (czasowo)";
    if ('.home') {
        Cookies.set('lang', "pl", {expires: 365, path: '/'});
    }
    if ('.checkout') {
        Cookies.set('lang', "pl", {expires: 365, path: '/'});
    }

    //Redirection TEMP
    if (url.indexOf("/ru") >= 0) {
        deliver9 = "Доставка курьером";
        teke = "Самовывоз";
        ctd = "Позвонить в двери";
        ctm = "Позвони мне";
        asoons = "Как можно быстрее";
        hours = "Интервал времени";
        more = "Более";
        inser = "Подтвердить";
        rest = "Выбери ресторан!";
        added = "Выбери ресторан!";
        ddw = "Дата доставки: ";
        sst = "Отгрузка в";
        opt1 = "Выберите период времени";
        alertErr = "Номер телефона не правильный";
        wecant = "Онлайн-заказы заблокированы! (временно)";
        if ('.home') {
            Cookies.set('lang', "ru", {expires: 365, path: '/'});
        }
        if ('.checkout') {
            Cookies.set('lang', "ru", {expires: 365, path: '/'});
        }

    } else if (url.indexOf("/ua") >= 0) {
        deliver9 = "Кур'єрська доставка";
        teke = "Самовивіз особисто";
        ctd = "Дзвінок у двері";
        ctm = "Зателефонуй мені";
        asoons = "Якнайшвидше";
        hours = "Інтервал часу";
        more = "більше";
        inser = "Підтвердити";
        rest = "Виберіть ресторан!";
        added = "Виберіть ресторан!";
        ddw = "дата доставки: ";
        sst = "Відправка до";
        opt1 = "Виберіть період часу";
        alertErr = "Номер телефону неправильний";
        wecant = "Онлайн замовлення заблоковані! (тимчасово)";
        if ('.home') {
            Cookies.set('lang', "ua", {expires: 365, path: '/'});
        }
        if ('.checkout') {
            Cookies.set('lang', "ua", {expires: 365, path: '/'});
        }
    }

    // Джерело: https://petrov.net.ua/mobile-device-definition-in-javascript/
    const isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (
                isMobile.Android() ||
                isMobile.BlackBerry() ||
                isMobile.iOS() ||
                isMobile.Opera() ||
                isMobile.Windows()
            );
        }
    }

    let blocked_shops = $('#blocked_shops').text();
    if (blocked_shops === "calysklep") {
        Cookies.set('address', "", {expires: 365, path: '/'});
        $('#menu-menu-glowne li ul li:nth-of-type(1)').addClass('blocked');
        $('#menu-menu-glowne li ul li:nth-of-type(2)').addClass('blocked');
        if (url.indexOf("/zamowienie") >= 0) {
            window.location.href = 'https://novesushi.pl/';
        }
    }

    $('.address-field span.woocommerce-input-wrapper').append('<span onclick="return funEventCheck()" id="address-accept">' + inser + '!</span>');
    $('#boxy').parent().addClass('active-tab');
    /*Order fields custom*/

    if ("#delivery_way_field .woocommerce-input-wrapper") {
        $(`<div class="switch-buttons">
                <span id="d1" class="switch-button delivery-way left-buttton" value="Dostawa 40-90 min">${ deliver9 }</span>
                <span id="d2" class="switch-button delivery-way right-buttton" value="Odbiór osobisty">${ teke }</span>
               </div>`).appendTo('#delivery_way_field');

        $('.delivery-way').bind('click', function (e) {
            if ($(this).hasClass('right-buttton')) {
                $(this).parent().addClass('checked');
                $('#delivery_way').val($(this).attr("value"));
                $('#apartment_field').hide();
                $('#square_field').hide();
                $('#floor_field').hide();
                $('#apartment').val('-');
                $('#square').val('-');
                $('#floor').val('-');
                $('#map_field').hide('slow');
                setTimeout(function () {
                    $('li[szbd="true"]').hide();
                }, 1000);

                $('.delivery-way2').parent().hide();
                $('.delivery-dat').parent().hide();
                $('#billing_address_1').val('Wrocław');
                $("#shipping_method_0_local_pickup27").attr('checked', true);
                $('#billing_address_1_field').hide();

                addCouponForDiscount( $(this).data('coupon-name') )
            } else {
                $(this).parent().removeClass('checked');
                $('#delivery_way').val($(this).attr("value"));
                $('#apartment_field').show();
                $('#square_field').show();
                $('#floor_field').show();
                $('#map_field').show();
                $('#billing_address_1_field').show();
                $('.delivery-way2').parent().show();
                $('.delivery-dat').parent().show();

                removeCouponForDiscount()
            }
        })
    }

    /**
     * **************** coupons olbor ****************
     */
    const coupon = document.getElementById('customer_details')?.dataset.selfPickupDiscountCoupon;

    function addCouponForDiscount( couponName ) {
        $('input[type="text"][name="coupon_code"]#coupon_code').val(couponName)
        $('button[type="submit"][name="apply_coupon"].button').click()
    }
    function removeCouponForDiscount() {
        $('input[type="text"][name="coupon_code"]#coupon_code').val()
        $('.cart-discount .woocommerce-remove-coupon').click()
    }
    const $wrapperCustomerDetails = $('.wrapper_customer_details')
    if ( $wrapperCustomerDetails.hasClass('coupon_active')) {
        const coupons = $wrapperCustomerDetails.data('coupons')
        const rightButton = $('.switch-button.delivery-way.right-buttton')
        const couponName = String(coupon).toLowerCase()

        if ( coupons && typeof coupons === 'object' ) {
            Object.keys(coupons).forEach(k => {
                if ( coupons[k].toLowerCase() === couponName ) {
                    rightButton.click()
                }
            })
        }
    }
    /* *************** coupons **************** */



    if ("#after_deliver_field .woocommerce-input-wrapper") {
        $('<div class="switch-buttons"><span id="d3" class="switch-button delivery-way2 left-buttton" value="Zadzwonić w drzwi">' + ctd + '</span><span id="d4" class="switch-button delivery-way2 right-buttton" value="Zadzwonić do mnie">' + ctm + '</span></div>').appendTo('#after_deliver_field');
        $('.delivery-way2').bind('click', function () {
            if ($(this).hasClass('right-buttton')) {
                $(this).parent().addClass('checked');
                $('#after_deliver').val($(this).attr("value"));
            } else {
                $(this).parent().removeClass('checked');
                $('#after_deliver').val($(this).attr("value"));
            }
        })
    }

    if ("#delivery_date_field .woocommerce-input-wrapper") {
        $('#flatpickr_field').hide();
        $('<div class="switch-buttons"><span id="d5" class="switch-button delivery-dat left-buttton" value="Jak najszybciej">' + asoons + '</span><span id="d6" class="switch-button delivery-dat right-buttton" value="Przedział czasowy">' + hours + '</span></div>').prependTo('#delivery_date_field');
        $('.switch-button').bind('click', function () {
            if ($(this).hasClass('right-buttton')) {
                if ($(this).hasClass('delivery-dat')) {
                    $("#delivery_date option:first").prop("selected", true).text(opt1);
                    $(this).parent().addClass('checked');
                    //$('#delivery_date').val($(this).attr("value"));
                    if ($(this).attr('value') == "Przedział czasowy") {
                        $('#delivery_date_field .woocommerce-input-wrapper').show();
                    }
                    /* $('#flatpickr').on("input", function(){
                        $('#delivery_date').val(ddw + $(this).val());
                     });*/
                }

            } else {
                if ($(this).hasClass('delivery-dat')) {
                    $(this).parent().removeClass('checked');
                    if ($(this).attr('value') == "Jak najszybciej") {
                        $('#delivery_date_field .woocommerce-input-wrapper').hide();
                        //$("#delivery_date option:first").prop("selected", true);

                        /*$('#flatpickr').on("input", function(){
                         $('#delivery_date').val(ddw + $(this).val());
                      });*/
                    }

                }
            }
        })
    }

    AOS.init();

    /*Burger*/
    $("#burger").bind("click", function () {
        if ( $("body").hasClass("menu-active") ) {
            $(this).removeClass("open");
            $("body").removeClass("menu-active")
                .css({
                    paddingRight: '0px',
                    overflowY: 'auto' })
        } else {
            $(this).addClass("open");
            $("body").addClass("menu-active")
                .css({
                    paddingRight: `${window.innerWidth - document.body.offsetWidth}px`,
                    overflow: 'hidden'
                })
        }

    });

    /*Mobile Lang Top*/
    $(".menu_mobile__lang_top li.current-lang").on("click", function (e) {
        e.preventDefault();
        $(".menu_mobile__lang_top").toggleClass("lang_top_open");
    });

    /*Close btn*/
    $(".close-btn").bind("click", function () {
        $('.reservation-form').toggleClass("open-form");
    });
    $(".sign-in-curse").bind("click", function (event) {
        event.preventDefault();
        $('.reservation-form').toggleClass("open-form");
        let title = $('.content-header h1').text();
        $('input[name="your-course"]').val(title);
    });
    $(".close-btn-temp").bind("click", function (event) {
        $('.chose-rest-on-mobile').hide();
    });
    $(".slider-home").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: !true,
        autoplaySpeed: 5000,
        dots: true,
        arrows: false,
        lazyLoad: 'ondemand',
        speed: 500
    });

    $('.category-filtr a').on('click', function (e) {
        e.preventDefault();

        $('.category-filtr').removeClass('active-tab');
        $(this).parent().addClass('active-tab');
        let category = $(this).attr('href');
        $('html,body').animate({ scrollTop: $(category).offset().top + 40 },'slow');

        moverigt()
    });

    if ($('.category-filtr a')) {
        $('.category-filtr').first().addClass('active-tab');
    }

    function moverigt() {
        document.querySelector('.shop-header-tabs ul')?.scrollTo({
            left: document.querySelector('.active-tab')?.offsetLeft,
            behavior: 'smooth'
        })
    }

    let timeScroll = null;
    $(window).scroll(function (event) {
        if (timeScroll) {
            clearTimeout(timeScroll)
        }

        timeScroll = setTimeout(() => {
            if ($('.active-tab')) {
                let scroll = $(window).scrollTop();
                let category = $('.active-tab a').attr('href');
                let next = 0;
                let prev = 0;

                if ($(category).next().is('div')) {
                    next = $(category).next().offset().top;
                }

                if ($(category).prev().is('div')) {
                    prev = $(category).offset().top - 20;
                }

                let current = $('.active-tab');

                if (next != 0 && scroll > next) {
                    $('.active-tab').next().addClass('active-tab');
                    current.removeClass('active-tab');
                    moverigt();
                } else if (prev != 0 && scroll < prev) {
                    $('.active-tab').prev().addClass('active-tab');
                    current.removeClass('active-tab');
                    moverigt();
                }
            }
        }, 100)

    });

    let path2 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/americansushi/partials/front//products-list.php';
    $('.category-filtr span').on('click', function () {
        console.log('filter span click')
        let category = $(this).attr('id');
        let categoryname = $(this).text();

        // Preloader
        $('#products-list').append('<div id="preloader" class="preloader preloader2" style="top:0;left:0;transform:none;background-color: rgba(0,0,0,0.7);"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');

        $('.product').addClass('disabled-product');
        $('.cart-item').addClass('disabled-product');
        $('.category-filtr span').parent().removeClass('active-tab');
        $(this).parent().addClass('active-tab');
        $.ajax({
            url: path2,
            type: 'get',
            data: {
                "category": category,
                "lang": Cookies.get('pll_language'),
                "categoryname": categoryname
            },
            success: function (result) {
                $('#category-name1').html(category);
                $('#products-list').html(result);
                if (blocked_shops != "calysklep" && Cookies.get('address') && (Cookies.get('address') != "")) {
                    $('.product').removeClass('disabled-product');
                    $('.cart-item').removeClass('disabled-product');
                } else {
                    $('.product').addClass('disabled-product');
                    $('.cart-item').addClass('disabled-product');
                }
            }
        })
    });


    setTimeout(function () {
        $(".preloader_header").fadeOut(100);
    }, 600);

    /*Add to cart animation*/

    if (blocked_shops !== "calysklep") {
        $('.product.disabled-product,.cart-item.disabled-product,#product-sidebar-cart')
            .css({pointerEvents: 'visible', opacity: '1'});
    }

    $('.selected-rest').on('click', function () {
        $('.chose-restaurant').removeClass('animated-rest');
    });

    $('.selected-rest').bind('click', function () {
        $('.rest-dropdown').toggleClass('active-drop');
    });

    function refreshSession(value, refresh) {
        console.log( 'refreshSession | script.js' )
        jQuery.ajax({
            type: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: {
                action: 'custom_base_address',
                // add your parameters here
                address: value + ", Wrocław"
            },
            success: function (output) {
                if (refresh == 1) {
                    window.location.reload();
                }
            }
        });
    }

    $('.sub-menu li a, .retaurant-label, .restaurant-link').on('click', function (e) {
        if ($(this).hasClass("selected-rest")) {
            return;
        }
        e.preventDefault();
        let value = "";
        if ($(this).hasClass('retaurant-label')) {
            value = $(this).attr('id');

        } else {
            value = $(this).text();
            if ($('.chose-rest-on-mobile')) {
                $('.chose-rest-on-mobile').hide();
            }
        }

        Cookies.set('address', value, {expires: 365, path: '/'});

        refreshSession(value, 1);

    })

    $('#billing_address_1_field label').html('Adres&nbsp;<abbr class="required" title="wymagane">*</abbr>');


    /* Cart */

    $('.close-cart-mobile, .basket-mobile').bind('click', function () {
        if ( $('.product-sidebar .cart-content').hasClass('active-cart') ) {
            $('.product-sidebar .cart-content').removeClass('active-cart');
        } else {
            updateShoppingCartAjax();
            $('.product-sidebar .cart-content').addClass('active-cart');
        }
    });

    // Close cart
    $(document).on('click', function (e) {
        var $elem = e.target;
        var cartContent = $elem.closest('.cart-content');
        var basketMobile = $elem.closest('.basket-mobile');

        if ( ! ( cartContent || basketMobile )) {
            $('#product-sidebar-cart .cart-content').removeClass('active-cart')
        }
    })

    /* Open popup -- Variable Product */
    /* Open popup -- Variable Product */
    /* Open popup -- Variable Product */
    // $('.product_type_variable.add_to_cart_button').on('click', function (event) {
    //     console.log('variable pop-up');
    //     event.preventDefault();
    //
    //
    //     if (blocked_shops === "calysklep") { alert(wecant);  return; }
    //
    //     let productId = $(this).attr('data-product_id');
    //
    //     // Get Popup for select variant product
    //     $.ajax({
    //         // url: wc_add_to_cart_params.ajax_url,
    //         url: ajax_data.ajaxUrl,
    //         type: 'POST',
    //         data: {
    //             action: 'o10_show_popup_select_variable_product',
    //             nonce: ajax_data.nonce,
    //             lang: Cookies.get('pll_language'),
    //             productId: productId,
    //         },
    //         success: function (result) {
    //             $('#before-checkout').html(result);
    //         },
    //         error: function (msg) {
    //             console.log(msg)
    //         },
    //         beforeSend: function () {
    //             $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
    //             $('body')
    //                 .css({ paddingRight: `${window.innerWidth - document.body.offsetWidth}px` })
    //                 .addClass('body--preloader_show');
    //         },
    //         complete: function (response) {
    //             if ( response.responseJSON?.error ) {
    //                 $('body')
    //                     .css({ paddingRight: '0px' })
    //                     .removeClass('body--preloader_show');
    //             }
    //         }
    //     })
    // });


    if (isMobile.any()) {

        /* Open popup -- Variable + Additional Product */
        $('.product_type_variable.add_to_cart_button').on('click', function (event) {
            event.preventDefault();
            console.log('Var+AddProdPopUp | script.js');

            if (blocked_shops === "calysklep") { alert(wecant);  return; }

            let productId = $(this).attr('data-product_id');

            const arr_url = window.location.href.split('/')
            let lang_ru = arr_url.find((element) => element === 'ru' );
            let lang_ua = arr_url.find((element) => element === 'ua' );

            // Get Popup for select variant product
            $.ajax({
                // url: wc_add_to_cart_params.ajax_url,
                url: ajax_data.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'o10_show_popup_select_variant_with_additional_products',
                    nonce: ajax_data.nonce,
                    lang: lang_ru || lang_ua || '',
                    productId: productId,
                },
                success: function (result) {
                    $('#before-checkout').html(result);
                },
                error: function (response) {
                    console.log(response)
                    $('#before-checkout').html(response.responseText);
                },
                beforeSend: function () {
                    $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                    $('body')
                        .css({ paddingRight: `${window.innerWidth - document.body.offsetWidth}px` })
                        .addClass('body--preloader_show');
                },
                complete: function (response) {
                    if ( response.responseJSON?.error ) {
                        $('body')
                            .css({ paddingRight: '0px' })
                            .removeClass('body--preloader_show');
                    }
                }
            })
        });
    }


    /* Add to from cart -- Simple Product */
    /* Add to from cart -- Simple Product */
    /* Add to from cart -- Simple Product */
    $('.product_type_simple.add_to_cart_button').on('click', function (event) {
        event.preventDefault();
        console.log('simple.')

        if (blocked_shops === "calysklep") { alert(wecant);  return; }

        let product_id = $(this).attr('data-product_id');

        // Get Popup for select variant product
        $.ajax({
            type: 'POST',
            dataType: 'json',
            // url: wc_add_to_cart_params.ajax_url,
            url: ajax_data.ajaxUrl,
            data: {
                action: 'o10_woocommerce_ajax_add_to_cart',
                nonce: ajax_data.nonce,
                lang: Cookies.get('pll_language'),
                product_id: product_id,
                variation_id: 0,
                quantity: 1,
            },
            success: function (result) {
                $('#before-checkout').html(result);
                updateShoppingCartAjax();
            },
            error: function (msg) {
                console.log(msg);
            },
            beforeSend: function () {
                $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                $('body')
                    .css({ paddingRight: `${window.innerWidth - document.body.offsetWidth}px` })
                    .addClass('body--preloader_show');
            },
            complete: function () {
                $('body')
                    .css({ paddingRight: '0px' })
                    .removeClass('body--preloader_show');
            }
        })
    });

    /* Open popup -- Grouped Product */
    $('.button.product_type_grouped').on('click', function (event) {
        event.preventDefault();

        console.log('Grouped Product pop-up');

        if (blocked_shops === "calysklep") { alert(wecant);  return; }

        let productId = this.dataset.product_id

        // Get Popup for select Grouped Product
        $.ajax({
            // url: wc_add_to_cart_params.ajax_url,
            url: ajax_data.ajaxUrl,
            type: 'POST',
            data: {
                action: 'o10_show_popup_select_grouped_product',
                nonce: ajax_data.nonce,
                lang: Cookies.get('pll_language'),
                productId: productId,
            },
            success: function (result) {
                $('#before-checkout').html(result);
            },
            error: function (response) {
                console.log(response)
                $('#before-checkout').html(response.responseText);
            },
            beforeSend: function () {
                $('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
                $('body')
                    .css({ paddingRight: `${window.innerWidth - document.body.offsetWidth}px` })
                    .addClass('body--preloader_show');
            },
            complete: function (response) {
                if ( response.responseJSON?.error ) {
                    $('body')
                        .css({ paddingRight: '0px' })
                        .removeClass('body--preloader_show');
                }
            }
        })
    })

    // UPDATE Cart
    function updateShoppingCartAjax() {

        $.ajax({
            // url: wc_add_to_cart_params.ajax_url,
            url: ajax_data.ajaxUrl,
            type: 'get',
            data: {
                action: 'o10_update_cart',
                nonce: ajax_data.nonce,
                lang: Cookies.get('pll_language'),
            },
            success: function (response) {
                $('#product-sidebar-cart').html(response);
                $('#product-sidebar-cart .cart-content').addClass('active-cart');
            },
            error: function (response) {
                console.error(response.statusText);
                console.error(response.responseText);
            },
            beforeSend: function () {
                $('.cart_content_preloader').addClass('show');
            },
            complete: function () {
                $('.cart_content_preloader').removeClass('show');
            }
        })
    }

    if (url.indexOf("/ua") >= 0) {
        $("#address-filled").on('click', function () {
            $('.cart-subtotal th').html('сума');
            $('.woocommerce-shipping-totals th').html('Відвантаження');
            $('.order-total th').html('Разом');
            $('.order-additional th').html('Час підготовки та доставки');
            $('.order-additional td').html('Ви отримаєте SMS із часом доставки');
        });
    }

    $('#place_order').addClass('disabled-product');

    $("#address-filled").on('click', function () {
        let d = 0;
        jQuery('body').trigger('update_checkout');
        setTimeout(function () {
            $('#shipping_method li label').each(function () {
                if ($(this).text().indexOf('Local pickup') > -1) {
                    $(this).html(teke);
                }
            });
            $('li[szbd="true"]').each(function () {
                if (d == 0) {
                    $(this).addClass('activeDel');
                    if ($(this).text().indexOf('Przesyłka do') > -1) {
                        let temtxt = $(this).text().split('Przesyłka do');
                        $(this).find("label").html(sst + temtxt[1]);
                    }
                }
                d++;
            });
            $('#place_order').removeClass('disabled-product');
        }, 6500);
    });

    $("#billing_phone").on('blur', function () {
        let filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        if (filter.test($(this).val())) {
            return true;
        } else {
            alert(alertErr);
            return false;
        }

    });


    // Set padding for Category Filter Elements
    // window.getComputedStyle(document.documentElement).getPropertyValue('--color-font-general');
    function setTopPositionCart() {
        const $headerTabs = document.querySelector('.shop-header-tabs')
        document.documentElement.style
            .setProperty('--products-margin-top', `${$headerTabs?.offsetHeight + 45}px`);
    }

    $( window ).on( "resize", function() {
        setTopPositionCart();
    })
    setTopPositionCart();


    /**
     * Language Change
     */
    function noChangeLang(args) {
        console.log(args)
    }
    const buttonsLanguageChange = document.querySelectorAll('.lang-item:not(.current-lang)')
    buttonsLanguageChange?.forEach(buttonLangChange => {
        buttonLangChange.addEventListener('click', function (eventClick)  {
            const cartAmount = document.querySelector('.cart-amount').innerText

            if ( cartAmount !== '0' ) {
                eventClick.preventDefault()

                const defaultHref = eventClick.currentTarget.children[0].href
                const text = Cookies.get('pll_language') === 'ru'
                    ? ['При именении языка сайта корзина будет очищена. Продолжить?', 'Да', 'Нет']
                    : Cookies.get('pll_language') === 'ua'
                        ? ['При зміні мови сайту корзина буде очищена. Продовжити?', 'Так', 'Ні']
                        : ['Po zmianie języka strony koszyk zostanie wyczyszczony. Czy mam kontynuować?', 'Tak', 'Nie']

                const buttonYes = $(`<span class="change_lang_msg_info__btn">${text[1]}</span>`)
                buttonYes.on('click', () => {
                    $.ajax({
                        url: ajax_data.ajaxUrl,
                        data: {
                            action: 'o10_remove_items_from_cart',
                            nonce: ajax_data.nonce,
                            defaultHref: defaultHref,
                        },
                        beforeSend: function (e) {
                            console.log('before', e)
                        },
                        success: function (result) {
                            console.log(result)
                            window.location.href = result.defaultHref
                        },
                        error: function (msg) {
                            console.log(msg)
                        },
                        complete: function () {
                            document.body.classList.remove('body--change_lang_popup')
                        }
                    })
                });

                const buttonNo = $(`<span class="change_lang_msg_info__btn">${text[2]}</span>`)
                buttonNo.on('click', () => {
                    document.body.classList.remove('body--change_lang_popup')
                });

                const buttons = $(`<div class="change_lang_msg_info__buttons"></div>`).append( buttonYes ).append( buttonNo )
                const msg = $(`<span class="change_lang_msg_info__text">${text[0]}</span>`)
                const wrapper = $(`<div class="change_lang_msg_info__wrapper"></div>`).append( msg ).append( buttons )
                $(document.body)
                    .addClass('body--change_lang_popup')
                    .append( $(`<div class="change_lang_msg_info"></div>`).append( wrapper ) )
            }
        })
    })
});







