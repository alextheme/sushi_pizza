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

    // $('.address-field span.woocommerce-input-wrapper').append('<span onclick="return funEventCheck()" id="address-accept">' + inser + '!</span>');
    $('#boxy').parent().addClass('active-tab');
    /*Order fields custom*/

    if ("#delivery_way_field .woocommerce-input-wrapper") {

        // id Shipping Method for default delivery settings
        let shippingMethodInputs = null;

        // Дожидаемся пока подгрузяться списки с методами доставки
        // чтоб подключить к ним лейблы через атрибут 'for'
        // и принудительно установить доставку курьером, нажав левый лейбл
        const intervalID = setInterval(() => {
            shippingMethodInputs = $('#shipping_method li input[type="radio"]');

            if (shippingMethodInputs.length) {
                clearInterval(intervalID);

                const defaultMethodSipping = shippingMethodInputs.length > 1 ? shippingMethodInputs[1] : undefined;

                // Создаем лейблы переключения между методами доставки (курьер/самовывоз)
                $(`<div class="switch-buttons switch_delivery_methods">
					<label
						for="${ defaultMethodSipping ? defaultMethodSipping.id : '' }"
						style="display:inline-block;line-height:40px"
						id="d1"
						class="switch-button delivery-way left-button"
						value="Dostawa 15-90 min"
					>
						<svg style="margin-bottom:-4px;margin-right:5px;" class="bi mr-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
							<g id="surface1">
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 4.332031 12.667969 L 3 12.667969 C 2.816406 12.667969 2.667969 12.515625 2.667969 12.332031 C 2.667969 12.148438 2.816406 12 3 12 L 4.332031 12 C 4.515625 12 4.667969 12.148438 4.667969 12.332031 C 4.667969 12.515625 4.515625 12.667969 4.332031 12.667969 Z M 4.332031 12.667969 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 15.167969 12.667969 L 14.332031 12.667969 C 14.148438 12.667969 14 12.515625 14 12.332031 C 14 12.148438 14.148438 12 14.332031 12 L 14.890625 12 L 15.339844 9.605469 C 15.332031 8.378906 14.285156 7.332031 13 7.332031 L 10.8125 7.332031 L 9.75 12 L 11.667969 12 C 11.851562 12 12 12.148438 12 12.332031 C 12 12.515625 11.851562 12.667969 11.667969 12.667969 L 9.332031 12.667969 C 9.230469 12.667969 9.136719 12.621094 9.074219 12.542969 C 9.007812 12.460938 8.984375 12.359375 9.007812 12.261719 L 10.21875 6.925781 C 10.253906 6.773438 10.390625 6.667969 10.546875 6.667969 L 13 6.667969 C 14.652344 6.667969 16 8.011719 16 9.667969 L 15.492188 12.394531 C 15.464844 12.550781 15.328125 12.667969 15.167969 12.667969 Z M 15.167969 12.667969 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 13 14 C 12.082031 14 11.332031 13.253906 11.332031 12.332031 C 11.332031 11.414062 12.082031 10.667969 13 10.667969 C 13.917969 10.667969 14.667969 11.414062 14.667969 12.332031 C 14.667969 13.253906 13.917969 14 13 14 Z M 13 11.332031 C 12.449219 11.332031 12 11.78125 12 12.332031 C 12 12.882812 12.449219 13.332031 13 13.332031 C 13.550781 13.332031 14 12.882812 14 12.332031 C 14 11.78125 13.550781 11.332031 13 11.332031 Z M 13 11.332031 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 5.667969 14 C 4.746094 14 4 13.253906 4 12.332031 C 4 11.414062 4.746094 10.667969 5.667969 10.667969 C 6.585938 10.667969 7.332031 11.414062 7.332031 12.332031 C 7.332031 13.253906 6.585938 14 5.667969 14 Z M 5.667969 11.332031 C 5.117188 11.332031 4.667969 11.78125 4.667969 12.332031 C 4.667969 12.882812 5.117188 13.332031 5.667969 13.332031 C 6.21875 13.332031 6.667969 12.882812 6.667969 12.332031 C 6.667969 11.78125 6.21875 11.332031 5.667969 11.332031 Z M 5.667969 11.332031 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 4.332031 6.667969 L 1.667969 6.667969 C 1.484375 6.667969 1.332031 6.515625 1.332031 6.332031 C 1.332031 6.148438 1.484375 6 1.667969 6 L 4.332031 6 C 4.515625 6 4.667969 6.148438 4.667969 6.332031 C 4.667969 6.515625 4.515625 6.667969 4.332031 6.667969 Z M 4.332031 6.667969 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 4.332031 8.667969 L 1 8.667969 C 0.816406 8.667969 0.667969 8.515625 0.667969 8.332031 C 0.667969 8.148438 0.816406 8 1 8 L 4.332031 8 C 4.515625 8 4.667969 8.148438 4.667969 8.332031 C 4.667969 8.515625 4.515625 8.667969 4.332031 8.667969 Z M 4.332031 8.667969 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 4.332031 10.667969 L 0.332031 10.667969 C 0.148438 10.667969 0 10.515625 0 10.332031 C 0 10.148438 0.148438 10 0.332031 10 L 4.332031 10 C 4.515625 10 4.667969 10.148438 4.667969 10.332031 C 4.667969 10.515625 4.515625 10.667969 4.332031 10.667969 Z M 4.332031 10.667969 "></path>
								<path style="stroke:none;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 9.332031 12.667969 L 7 12.667969 C 6.816406 12.667969 6.667969 12.515625 6.667969 12.332031 C 6.667969 12.148438 6.816406 12 7 12 L 9.066406 12 L 10.582031 5.332031 L 3 5.332031 C 2.816406 5.332031 2.667969 5.183594 2.667969 5 C 2.667969 4.816406 2.816406 4.667969 3 4.667969 L 11 4.667969 C 11.101562 4.667969 11.199219 4.710938 11.261719 4.792969 C 11.324219 4.871094 11.347656 4.976562 11.324219 5.074219 L 9.660156 12.40625 C 9.625 12.558594 9.488281 12.667969 9.332031 12.667969 Z M 9.332031 12.667969 "></path>
							</g>
						</svg>
						${ deliver9 }
					</label>
					<label
						for="${ shippingMethodInputs.length ? shippingMethodInputs[0].id : '' }"
						style="display:inline-block;line-height:40px"
						id="d2"
						class="switch-button delivery-way right-button"
						value="Odbiór osobisty"
					>
						<svg style="margin-bottom:-3px;margin-right:5px;width:15px;height:auto;" class="bi mr-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
							<g id="surface1">
								<path style="stroke:#fff;stroke-width:.3;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 15.945312 14.199219 L 14.863281 3.890625 C 14.851562 3.761719 14.734375 3.667969 14.605469 3.679688 C 14.476562 3.695312 14.382812 3.8125 14.398438 3.941406 L 15.476562 14.246094 C 15.511719 14.574219 15.40625 14.902344 15.183594 15.144531 C 14.964844 15.390625 14.648438 15.53125 14.320312 15.53125 L 1.679688 15.53125 C 1.351562 15.53125 1.035156 15.390625 0.816406 15.144531 C 0.59375 14.902344 0.488281 14.574219 0.523438 14.246094 L 1.175781 8.023438 C 1.1875 7.894531 1.09375 7.78125 0.964844 7.765625 C 0.835938 7.753906 0.722656 7.847656 0.707031 7.976562 L 0.0546875 14.199219 C 0.0078125 14.65625 0.15625 15.117188 0.464844 15.460938 C 0.773438 15.804688 1.21875 16 1.679688 16 L 14.320312 16 C 14.78125 16 15.226562 15.804688 15.535156 15.460938 C 15.84375 15.117188 15.992188 14.65625 15.945312 14.199219 Z M 15.945312 14.199219 "></path>
								<path style="stroke:#fff;stroke-width:.3;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 14.695312 2.265625 L 14.5 0.394531 C 14.472656 0.167969 14.285156 0 14.0625 0 L 12.007812 0 C 11.765625 0 11.570312 0.195312 11.570312 0.4375 L 11.570312 3.636719 L 4.429688 3.636719 L 4.429688 0.4375 C 4.429688 0.195312 4.234375 0 3.992188 0 L 1.9375 0 C 1.714844 0 1.527344 0.167969 1.503906 0.394531 L 0.878906 6.335938 C 0.867188 6.464844 0.960938 6.578125 1.089844 6.59375 C 1.097656 6.59375 1.105469 6.59375 1.113281 6.59375 C 1.230469 6.59375 1.335938 6.503906 1.347656 6.382812 L 1.96875 0.46875 L 3.960938 0.46875 L 3.960938 3.667969 C 3.960938 3.910156 4.15625 4.105469 4.398438 4.105469 L 11.601562 4.105469 C 11.84375 4.105469 12.039062 3.910156 12.039062 3.667969 L 12.039062 0.46875 L 14.03125 0.46875 L 14.226562 2.316406 C 14.238281 2.445312 14.355469 2.539062 14.484375 2.527344 C 14.613281 2.511719 14.707031 2.398438 14.695312 2.265625 Z M 14.695312 2.265625 "></path>
								<path style="stroke:#fff;stroke-width:.3;fill-rule:nonzero;fill:rgb(255, 255, 255);fill-opacity:1;" d="M 10.9375 10.75 L 9.476562 8.535156 L 9.855469 8.632812 C 9.980469 8.664062 10.109375 8.589844 10.140625 8.464844 C 10.171875 8.339844 10.097656 8.210938 9.972656 8.179688 L 9.089844 7.949219 L 8.328125 6.792969 C 8.253906 6.683594 8.132812 6.617188 8 6.617188 C 7.867188 6.617188 7.746094 6.683594 7.671875 6.792969 L 5.0625 10.75 C 4.984375 10.871094 4.976562 11.027344 5.042969 11.152344 C 5.113281 11.28125 5.246094 11.359375 5.390625 11.359375 L 10.609375 11.359375 C 10.753906 11.359375 10.886719 11.28125 10.957031 11.152344 C 11.023438 11.027344 11.015625 10.871094 10.9375 10.75 Z M 5.535156 10.890625 L 8 7.152344 L 8.691406 8.199219 L 8.492188 9.0625 C 8.464844 9.191406 8.542969 9.316406 8.667969 9.34375 C 8.6875 9.351562 8.703125 9.351562 8.722656 9.351562 C 8.828125 9.351562 8.925781 9.277344 8.949219 9.167969 L 9.046875 8.742188 L 10.464844 10.890625 Z M 5.535156 10.890625 "></path>
							</g>
						</svg>
						${ teke }
					</label>
				   </div>`).appendTo('#delivery_way_field');

                $('.delivery-way').bind('click', function (e) {
                    if ($(this).hasClass('right-button')) {
                        $(this).parent().addClass('checked');
                        $('#delivery_way').val($(this).attr("value"));
                        $('#apartment_field').hide();
                        $('#square_field').hide();
                        $('#floor_field').hide();
                        $('#map_field').hide('slow');
                        setTimeout(function () {
                            $('li[szbd="true"]').hide();
                        }, 1000);

                        $('.delivery-way2').parent().hide();
                        $('.delivery-dat').parent().hide();
                        $('#apartment').val('-');
                        $('#square').val('-');
                        $('#floor').val('-');
                        $('#billing_address_1').val('Wrocław');
                        $('#billing_address_1_field').hide();
                        addressCorrect = true;

                    } else {
                        $(this).parent().removeClass('checked');
                        $('#delivery_way').val($(this).attr("value"));
                        $('#apartment_field').show();
                        $('#square_field').show();
                        $('#floor_field').show();
                        $('#map_field').show();
                        $('#apartment').val('');
                        $('#square').val('');
                        $('#floor').val('');
                        $('#billing_address_1').val('');
                        $('#billing_address_1_field').show();
                        $('.delivery-way2').parent().show();
                        $('.delivery-dat').parent().show();

                        setTimeout(function () {
                            window.scrollTo(0, 0);
                        }, 10);
                        addressCorrect = false;
                    }
                });

                // Нажимаем на кнопку и удаляем ненужные сообщения
                $('.switch-button.delivery-way.left-button').click();
                $('.woocommerce-notices-wrapper').html('');
                window.scrollTo(0, 0);
            }

            // todo temp brmsk
            // $('#billing_first_name').val('test---test');
            // $('#billing_phone').val('0987743778');
            // $('#apartment').val('3');
            // $('#square').val('5');
            // $('#floor').val('7');
            // $('#order_comments').val('Будь ласка, зверніть увагу, що якщо порівняння db нечутливе до регістру ...');

        }, 100);
    }

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
        const icon1 = '<svg style="vertical-align:-5px;margin-right:5px;" class="bi" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">\n' +
            '    <g id="surface1">\n' +
            '        <path style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" d="M 15.726562 3.480469 L 15.6875 3.441406 C 15.320312 3.074219 14.722656 3.074219 14.351562 3.445312 L 12.910156 4.898438 C 12.835938 4.972656 12.714844 4.976562 12.632812 4.90625 L 10.617188 3.042969 C 10.417969 2.859375 10.164062 2.761719 9.898438 2.761719 L 6.894531 2.761719 C 6.824219 2.761719 6.753906 2.789062 6.703125 2.835938 L 4.160156 5.253906 C 3.785156 5.625 3.761719 6.226562 4.101562 6.59375 C 4.285156 6.789062 4.53125 6.898438 4.796875 6.898438 L 4.800781 6.898438 C 5.0625 6.894531 5.316406 6.785156 5.492188 6.59375 L 7.289062 4.691406 L 7.933594 4.691406 L 3.738281 9.378906 L 1.148438 9.378906 C 0.535156 9.378906 0.03125 9.835938 0 10.421875 C -0.015625 10.730469 0.09375 11.019531 0.304688 11.242188 C 0.511719 11.460938 0.800781 11.585938 1.101562 11.585938 L 4.964844 11.585938 C 5.039062 11.585938 5.113281 11.554688 5.164062 11.496094 L 7.144531 9.386719 L 8.808594 11.125 L 8.304688 14.335938 C 8.179688 14.867188 8.433594 15.410156 8.910156 15.621094 C 9.058594 15.6875 9.214844 15.722656 9.371094 15.722656 C 9.539062 15.722656 9.703125 15.683594 9.859375 15.609375 C 10.152344 15.460938 10.367188 15.191406 10.445312 14.859375 L 11.300781 10.257812 C 11.320312 10.167969 11.289062 10.074219 11.226562 10.011719 L 8.9375 7.722656 L 10.753906 5.90625 L 12.023438 7.175781 C 12.378906 7.53125 12.996094 7.53125 13.347656 7.175781 L 15.726562 4.796875 C 15.902344 4.621094 16 4.386719 16 4.140625 C 16 3.890625 15.902344 3.65625 15.726562 3.480469 Z M 15.726562 3.480469 "></path>\n' +
            '        <path style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" d="M 12.6875 3.585938 C 13.597656 3.585938 14.339844 2.84375 14.339844 1.933594 C 14.339844 1.019531 13.597656 0.277344 12.6875 0.277344 C 11.773438 0.277344 11.03125 1.019531 11.03125 1.933594 C 11.03125 2.84375 11.773438 3.585938 12.6875 3.585938 Z M 12.6875 3.585938 "></path>\n' +
            '    </g>\n' +
            '</svg>'
        const icon2 = '<svg style="vertical-align:-3px;margin-right:10px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-clock-fill" viewBox="0 0 16 16">\n' +
            '    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"></path>\n' +
            '</svg>'
        $('#flatpickr_field').hide();
        $('<div class="switch-buttons"><span id="d5" class="switch-button delivery-dat left-buttton" value="Jak najszybciej">' + icon1 + asoons + '</span><span id="d6" class="switch-button delivery-dat right-buttton" value="Przedział czasowy">' + icon2 + hours + '</span></div>').prependTo('#delivery_date_field');
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

    /* WORK TIME */
    const dataWorkTime = $('.popup_info__wrapper').data('work_time');

    function isTimeInRange(timeStart, timeEnd, currentTime) {
        const arrTimeStart = timeStart.split(':').map(t => +t);
        const arrTimeEnd = timeEnd.split(':').map((t, i) => (i === 0 && /^0*$/.test(t)) ? 24 : +t);

        // Устанавливаем верхний и нижний пределы временного диапазона
        const startTime = arrTimeStart[0] * 60 + arrTimeStart[1];
        const endTime = arrTimeEnd[0] * 60 + arrTimeEnd[1];

        // Переводим текущее время в минуты
        let currentTimeInMinutes = currentTime.getHours() * 60 + currentTime.getMinutes();

        // Проверяем, находится ли текущее время в заданном диапазоне
        return (currentTimeInMinutes >= startTime && currentTimeInMinutes <= endTime);
    }

    function isWorkTime(data) {
        // const currentTime = new Date('2023-10-26 00:59:00');
        const currentTime = new Date();
        const dayOfWeek = currentTime.getDay();

        const args = data
            .split('|')
            .map(s => s.split(','))
            .map((a,i) => i%2 === 0 ? a.map(s => +s) : a.map(s => s.split('-')));

        let times = undefined;
        for (let i = 0; i < args.length; i+=2) {
            if (i%2 === 0 && args[i].includes(dayOfWeek)) {
                times = args[i+1];
            }
        }

        if (!(Array.isArray(times) && times.length !== 0)) {
            console.log(dayOfWeek, 'no work day');
            return false;
        }

        const result = [];
        times.forEach(time => result.push(isTimeInRange(time[0], time[1], currentTime)));

        return result.some(el => el === true)
    }

    window.workTime = isWorkTime(dataWorkTime);

    // const dayStartW = 1, dayEndW = 5;
    // const timeStartW = '14:00', timeEndW = '22:00';
    // const timeStart = '14:00', timeEnd = '00:00';
    // function is_day_week_in_range(firstDay = 1, lastDay = 5, d) {
    //     // 0-Sunday, 1-Monday, 2-Wednesday, 3-Thursday, 4-Friday, 5-Sunday, 6-Saturday
    //     let dayOfWeek = 0;
    //     if (typeof d === 'number') {
    //         dayOfWeek = d;
    //     } else {
    //         const currentTime = new Date();
    //         dayOfWeek = currentTime.getDay();
    //     }
    //
    //     return (dayOfWeek >= firstDay && dayOfWeek <= lastDay);
    // }
    // function is_time_in_range(timeStart = '14:00', timeEnd = '22:00', H, m) {
    //     const convertTime = (t, i) => {
    //         if (i === 0 && (t === '00' || t === '0')) return 24;
    //         else return +t;
    //     }
    //     const arrTimeStart = timeStart.split(':').map(convertTime);
    //     const arrTimeEnd = timeEnd.split(':').map(convertTime);
    //
    //     // Устанавливаем верхний и нижний пределы временного диапазона
    //     const startTime = arrTimeStart[0] * 60 + arrTimeStart[1];
    //     const endTime = arrTimeEnd[0] * 60 + arrTimeEnd[1];
    //
    //     // Переводим текущее время в минуты
    //     let currentTimeInMinutes = 0;
    //     if (typeof H === 'number' && typeof m === 'number') {
    //         currentTimeInMinutes = H * 60 + m;
    //     } else {
    //         const currentTime = new Date();
    //         currentTimeInMinutes = currentTime.getHours() * 60 + currentTime.getMinutes();
    //     }
    //
    //     // Проверяем, находится ли текущее время в заданном диапазоне
    //     return (currentTimeInMinutes >= startTime && currentTimeInMinutes <= endTime);
    // }
    //
    // const dayWork = is_day_week_in_range(dayStartW, dayEndW);
    // if (dayWork) {
    //     window.workTime = is_time_in_range(timeStartW, timeEndW);
    // } else {
    //     window.workTime = is_time_in_range(timeStart, timeEnd);
    // }

    // Blocked buttons
    if (!window.workTime) {
        $('.add_to_cart_button').addClass('add_to_cart_button--blocked');
        $('#checkout1').addClass('add_to_cart_button--blocked');
    } else {
        $('.add_to_cart_button').removeClass('add_to_cart_button--blocked');
        $('#checkout1').removeClass('add_to_cart_button--blocked');
    }

    function workTimeShowPopup(logTxt) {
        console.log(logTxt);

        if (popup_select_menu_info_show || !workTime) {
            $('body').addClass('body--popup_select_menu_info');

            $('.popup_select_menu_info .popup_info__button').on('click', function (event) {
                $('body').removeClass('body--popup_select_menu_info');
            })
        }

        popup_select_menu_info_show = false;
    }

    console.log(window.workTime)

    /* WORK TIME SHOW POPUP */
    let popup_select_menu_info_show = true;
    $('.add_to_cart_button').on('click', function (event) {
        workTimeShowPopup('* add_to_cart_button *');
    });
    $('.right-product-text').on('click', event => {
        workTimeShowPopup('* right-product-text *');
    });
    $('.product-footer').on('click', event => {
        workTimeShowPopup('* product-footer *');
    });
    $('.cproduct-footer').on('click', event => {
        workTimeShowPopup('* cproduct-footer *');
    });

    /* Open popup -- Variable Product */
    /* Open popup -- Variable Product */
    /* Open popup -- Variable Product */
    // $('.product_type_variable.add_to_cart_button').on('click', function (event) {
    //     console.log('variable pop-up');
    //
    //     event.preventDefault();

    //    if (!window.workTime) return;
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
            console.log('Var+AddProdPopUp | script.js');

            event.preventDefault();

            if (!window.workTime) return;

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
        console.log('simple.');

        if (!window.workTime) return;

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







