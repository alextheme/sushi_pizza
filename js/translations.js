(function($) { 
$(document).ready(function() {
	
/*Translate checkout*/	
//Change language actions
let url = window.location.href; 	
//$('#billing_phone').val('+48');	
//Redirection TEMP
if (url.indexOf("/ru") >= 0){
	$('label[for="billing_first_name"]').html('Имя&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#billing_first_name').attr("placeholder", "Имя и фамилия");
	$('label[for="flatpickr"]').html('Дата и время');	
	$('label[for="billing_phone"]').html('Телефон&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#billing_phone').attr("placeholder", "Телефон");
		
	$('billing_address_1').attr("placeholder", "Назва вулиці, номер будинку / номер квартири");
	
	$('label[for="apartment"]').html('Квартира&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#apartment').attr("placeholder", "Введите");
	
	$('label[for="square"]').html('Подъезд&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#square').attr("placeholder", "Введите");
	
	$('label[for="floor"]').html('Этаж&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#floor').attr("placeholder", "Введите");
	$('#flatpickr').attr("placeholder", "Введите");
	$('label[for="order_comments"]').html('Примечания к заказу&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#order_comments').attr("placeholder", "Комментарии к заказу");
	setTimeout(function() {
	$('label[for="billing_address_1"]').html('Адрес&nbsp;<abbr class="required" title="required">*</abbr>');
	$('#order_review_heading').html('Ваш заказ');
	$('.cart-subtotal th').html('Сумма');
	$('.woocommerce-shipping-totals th').html('Доставка');
	$('.order-total th').html('Вместе');
	$('.order-additional th').html('Время подготовки и доставки');
	$('.order-additional td').html('Вы получите SMS через некоторое время');
	$('.woocommerce-info').html('Есть купон? Нажмите здесь, <a href="#" class="showcoupon">чтобы добавить свой код</a>');
	$('.checkout_coupon p:first-child').html('Если у вас есть купон на скидку, введите его ниже.');
	$('#coupon_code').attr("placeholder", "Код купона");
	$('button[name="apply_coupon"]').html('Используйте купон');
	$('.info-ack span').html('Ваши личные данные будут использоваться для обработки заказа, облегчения использования веб-сайта и других целей, описанных в нашей <a href="/ru/политика-конфиденциальности/" class="woocommerce-privacy-policy-link" target="_blank">политике конфиденциальности.</a>');	
	$('.paymeny-methods h3').html('Способ оплаты');	
	$('.cash').html('Наличные');	
	$('.woocommerce-terms-and-conditions-checkbox-text').html('Я прочитал и принимаю правила <a href="https://americansushiexpress.pl/ru/регламент/" class="woocommerce-terms-and-conditions-link" target="_blank">регламент</a>');	
	$('#place_order').html('Покупаю и оплачиваю');
	$('label[for="shipping_method_0_local_pickup27"]').html('Самовывоз');		
	}, 1500);

}else if (url.indexOf("/ua") >= 0){
	
	$('label[for="billing_first_name"]').html('Ім`я&nbsp;<abbr class="required" title="required">*</abbr>');
	$('#billing_first_name').attr("placeholder", "Ім'я та прізвище");
	$('#billing_address_1').attr("placeholder", "Назва вулиці, номер будинку / номер квартири");
	$('label[for="flatpickr"]').html('дата і час');
	$('label[for="billing_phone"]').html('Телефон&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#billing_phone').attr("placeholder", "Телефон");
		
	
	$('label[for="apartment"]').html('Квартира&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#apartment').attr("placeholder", "Введіть");
	
	$('label[for="square"]').html('Підїзд&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#square').attr("placeholder", "Введіть");
	
	$('label[for="floor"]').html('Поверх&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#floor').attr("placeholder", "Введіть");
	$('#flatpickr').attr("placeholder", "Введіть");
	$('label[for="order_comments"]').html('Коментарі до замовлення&nbsp;<abbr class="required" title="required">*</abbr>');	
	$('#order_comments').attr("placeholder", "Коментарі до замовлення");
	setTimeout(function() {
	$('label[for="billing_address_1"]').html('Адреса&nbsp;<abbr class="required" title="required">*</abbr>');
	$('#order_review_heading').html('Ваше замовлення');
	$('.cart-subtotal th').html('Сума');
	$('.woocommerce-shipping-totals th').html('Відвантаження');
	$('.order-total th').html('Разом');
	$('.order-additional th').html('Час підготовки та доставки');
	$('.order-additional td').html('Ви отримаєте SMS із часом доставки');
	$('.woocommerce-info').html('Є купон? Натисніть тут,<a href="#" class="showcoupon">щоб додати свій код</a>');
	$('.checkout_coupon p:first-child').html('якщо у вас є купон на знижку, введіть його нижче.');
	$('#coupon_code').attr("placeholder", "Код купона");
	$('button[name="apply_coupon"]').html('Використовуйте купон');
	$('.info-ack span').html('Ваші особисті дані будуть використані для обробки замовлення, полегшення використання веб-сайту та інших цілей, описаних у нашій <a href="/uk/політика-конфіденційності/" class="woocommerce-privacy-policy-link" target="_blank">політиці конфіденційності.</a>');	
	$('.paymeny-methods h3').html('Спосіб оплати');	
	$('.cash').html('Готівка');	
	$('.woocommerce-terms-and-conditions-checkbox-text').html('Я прочитав і приймаю <a href="https://americansushiexpress.pl/ua/регламент-2/" class="woocommerce-terms-and-conditions-link" target="_blank">регламент</a>');	
	$('#place_order').html('Купую і оплачую');
	$('label[for="shipping_method_0_local_pickup27"]').html('Самовывоз');	
		
	}, 3500);

}
setTimeout(function() {	
$('#shipping_method li').each(function( index ) {
	if (url.indexOf("/ru") >= 0){
		let valbefore = ($(this).find('label').text()).split("do");
		$(this).find('label').html("Доставка до"+valbefore[1]);
	}else if (url.indexOf("/uk") >= 0){
		let valbefore = ($(this).find('label').text()).split("do");
		$(this).find('label').html("Доставка до"+valbefore[1]);
	}
	
});
},1000);
	
	
if(Cookies.get('lang') == "ru"){
   setTimeout(function() {
	   $('.woocommerce-order-received .woocommerce-thankyou-order-received').html("Спасибо. Мы получили ваш заказ.");
	   $('.woocommerce-order-overview__order').html('ПОРЯДКОВЫЙ НОМЕР: <strong>'+ $('.woocommerce-order-overview__order').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__date').html('ДАТА: <strong>'+ $('.woocommerce-order-overview__date').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__email').html('ЭЛЕКТРОННЫЙ АДРЕС: <strong>'+ $('.woocommerce-order-overview__email').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__total').html('ВМЕСТЕ: <strong>'+ $('.woocommerce-order-overview__total').find('strong').text());
	   $('.woocommerce-order-overview__payment-method').html('МЕТОД ОПЛАТЫ: <strong>'+ $('.woocommerce-order-overview__payment-method').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__payment-method strong').html('Оплата при доставке');
	   //$('.woocommerce-order-details').hide();
	   $('.woocommerce-order > p').html('Спасибо. Мы получили ваш заказ.');
	   //$('.order_details tr:nth-of-type(3) td').html('Оплата при доставке');
	   $('.shipped_via').html(' Доставка');
	   $('.woocommerce-order-details__title').html('Детали заказа');
	   if($('.order_details tr:nth-of-type(2) td').children().length > 1){
			$('.shipped_via').html(' Доставка');
		}else{
			$('.order_details tr:nth-of-type(2) td').html('Самовывоз');
		}
	   
	   $('.order_details tr td').each(function(){
		  if($(this).text().indexOf('Zadzwonić do mnie:') > -1){
			   $(this).html('Зателефонуй мені');
		   }
		   if($(this).text().indexOf('Zadzwonić w dzrzwi') > -1){
			   $(this).html('Звонить в дверь');
		   }
		   if($(this).text().indexOf('Za pobraniem') > -1){
			   $(this).html('Оплата при доставке');
		   }
		   if($(this).text().indexOf('Odbiór osobisty') > -1){
			   $(this).html('Самовывоз');
		   }
		   if($(this).text().indexOf('Jak najszybciej') > -1){
			   $(this).html('как можно быстрее');
		   }   
		});		
	   
	   
	  $('.order_details tr th').each(function(){
		  if($(this).text().indexOf('Kwota:') > -1){
			   $(this).html('Сума');
		   }
		   if($(this).text().indexOf('Wysyłka:') > -1){
			   $(this).html('Доставка');
		   }
			if($(this).text().indexOf('Metoda płatności:') > -1){
			   $(this).html('Метод оплаты:');
		   }
			if($(this).text().indexOf('Razem:') > -1){
			   $(this).html('Вместе');
		   }
			if($(this).text().indexOf('Addres:') > -1){
			   $(this).html('Адрес');
		   }
		   if($(this).text().indexOf('Adnotacja:') > -1){
			   $(this).html('Примечания');
		   }
		   if($(this).text().indexOf('Mieszkanie:') > -1){
			   $(this).html('Квартира');
		   }
		   if($(this).text().indexOf('Klatka:') > -1){
			   $(this).html('Подъезд');
		   }
		   if($(this).text().indexOf('Piętro:') > -1){
			   $(this).html('Этаж');
		   }	
		   if($(this).text().indexOf('Po dojechaniu:') > -1){
			   $(this).html('После прибытия:');
		   }	
		   if($(this).text().indexOf('Przedział czasowy') > -1){
			   $(this).html('Интервал времени');
		   }	
		   if($(this).text().indexOf('Telefon') > -1){
			   $(this).html('Телефон');
		   }
		  
	   });	
	   
	   
	    $('#menu-menu-glowne li a').each(function(){
		  if($(this).text().indexOf('Menu') > -1){
			   $(this).html('Меню');
		   }
		   if($(this).text().indexOf('Oferty pracy') > -1){
			   $(this).html('Предложения о работе');
		   }
		   if($(this).text().indexOf('Dostawa') > -1){
			   $(this).html('Доставка');
		   }
		   if($(this).text().indexOf('Kontakt') > -1){
			   $(this).html('Контакт');
		   }
		});	
	   
	  },200);
}else if(Cookies.get('lang') == "ua"){
		setTimeout(function() {
	  $('.woocommerce-order-received .woocommerce-thankyou-order-received').html("Дякую. Ми отримали ваше замовлення.");
	   $('.woocommerce-order-overview__order').html('НОМЕР ЗАМОВЛЕННЯ: <strong>'+ $('.woocommerce-order-overview__order').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__date').html('ДАТА: <strong>'+ $('.woocommerce-order-overview__date').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__email').html('ЕЛЕКТРОННА ПОШТА: <strong>'+ $('.woocommerce-order-overview__email').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__total').html('РАЗОМ: <strong>'+ $('.woocommerce-order-overview__total').find('strong').text());
	   $('.woocommerce-order-overview__payment-method').html('СПОСІБ ОПЛАТИ: <strong>'+ $('.woocommerce-order-overview__payment-method').find('strong').text()+'</strong>');
	   $('.woocommerce-order-overview__payment-method strong').html('Оплата при доставці');
		//$('.order_details tr:nth-of-type(3) td').html('Оплата при доставці');
		if($('.order_details tr:nth-of-type(2) td').children().length > 1){
			$('.shipped_via').html(' Доставка');
		}else{
			$('.order_details tr:nth-of-type(2) td').html('Самовивіз особисто');
		}
		
	   //$('.woocommerce-order-details').hide();
	   $('.woocommerce-order > p').html('Дякую. Ми отримали ваше замовлення.');
		 $('.woocommerce-order-details__title').html('Деталі замовлення');
			
		$('.order_details tr td').each(function(){
		  if($(this).text().indexOf('Zadzwonić do mnie') > -1){
			   $(this).html('Позвони мне');
		   }
			if($(this).text().indexOf('Zadzwonić w dzrzwi') > -1){
			   $(this).html('Дзвінок у двері');
		   }
			if($(this).text().indexOf('Za pobraniem') > -1){
			   $(this).html('Оплата при доставці');
		   }
			if($(this).text().indexOf('Odbiór osobisty') > -1){
			   $(this).html('Самовивіз особисто');
		   }
			if($(this).text().indexOf('Jak najszybciej') > -1){
			   $(this).html('якнайшвидше');
		   }   

		});		
			
		$('.order_details tr th').each(function(){
		  if($(this).text().indexOf('Kwota:') > -1){
			   $(this).html('Сумма');
		   }
		   if($(this).text().indexOf('Wysyłka:') > -1){
			   $(this).html('Доставка');
		   }
			if($(this).text().indexOf('Metoda płatności:') > -1){
			   $(this).html('Спосіб оплати');
		   }
			if($(this).text().indexOf('Razem:') > -1){
			   $(this).html('Разом');
		   }
			if($(this).text().indexOf('Addres:') > -1){
			   $(this).html('Адреса');
		   }
		   if($(this).text().indexOf('Adnotacja:') > -1){
			   $(this).html('Зауваження');
		   }
		   if($(this).text().indexOf('Mieszkanie:') > -1){
			   $(this).html('Квартира');
		   }
		   if($(this).text().indexOf('Klatka:') > -1){
			   $(this).html('Підїзд');
		   }
		   if($(this).text().indexOf('Piętro:') > -1){
			   $(this).html('Поверх');
		   }	
		   if($(this).text().indexOf('Po dojechaniu:') > -1){
			   $(this).html('Після прибуття:');
		   }	
		   if($(this).text().indexOf('Przedział czasowy') > -1){
			   $(this).html('Інтервал часу');
		   }	
			if($(this).text().indexOf('Telefon') > -1){
			   $(this).html('Телефон');
		   }
		  
	   });	
			
		 $('#menu-menu-glowne li a').each(function(){
		  if($(this).text().indexOf('Menu') > -1){
			   $(this).html('Меню');
		   }
		   if($(this).text().indexOf('Oferty pracy') > -1){
			   $(this).html('Пропозиції роботи');
		   }
		   if($(this).text().indexOf('Dostawa') > -1){
			   $(this).html('Доставка');
		   }
		   if($(this).text().indexOf('Kontakt') > -1){
			   $(this).html('Зв’язатися');
		   }
		});			
		
			
			
		},200);
	
		
}
	
	
	
});	
	
})(jQuery);






