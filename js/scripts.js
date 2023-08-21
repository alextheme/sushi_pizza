(function($) { 
$(document).ready(function() {
	
/*Translate checkout*/	
//Change language actions
let url = window.location.href; 	
	let deliver9  = "Dostawa kurierem";
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
	let sst= "Przesyłka do";
	let opt1= "Wybierz przedział czasowy";
	let alertErr = "Numer telefonu nie jest poprawny";
	let wecant = "Zamówineia online są zablokowane! (czasowo)";
	if('.home'){
		Cookies.set('lang', "pl", { expires: 365, path: '/'  });
	}
	if('.checkout'){
		Cookies.set('lang', "pl", { expires: 365, path: '/'  });
	}

//Redirection TEMP
if (url.indexOf("/ru") >= 0){
	 deliver9  = "Доставка курьером";
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
	sst= "Отгрузка в";
	opt1= "Выберите период времени";
	alertErr = "Номер телефона не правильный";
	wecant = "Онлайн-заказы заблокированы! (временно)";
	if('.home'){
		Cookies.set('lang', "ru", { expires: 365, path: '/'  });
	}
	if('.checkout'){
		Cookies.set('lang', "ru", { expires: 365, path: '/'  });
	}
	
}else if (url.indexOf("/ua") >= 0){
	 deliver9  = "Кур'єрська доставка";
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
	sst= "Відправка до";
	opt1= "Виберіть період часу";
	alertErr = "Номер телефону неправильний";
	wecant = "Онлайн замовлення заблоковані! (тимчасово)";
	if('.home'){
		Cookies.set('lang', "ua", { expires: 365, path: '/'  });
	}
	if('.checkout'){
		Cookies.set('lang', "ua", { expires: 365, path: '/'  });
	}
	
}	

let blocked_shops = $('#blocked_shops').text();	
if(blocked_shops == "calysklep"){
	Cookies.set('address', "", { expires: 365, path: '/'  });
	$('#menu-menu-glowne li ul li:nth-of-type(1)').addClass('blocked');
	$('#menu-menu-glowne li ul li:nth-of-type(2)').addClass('blocked');  
	if (url.indexOf("/zamowienie") >= 0){
		window.location.href = 'https://novesushi.pl/';
	}
}

$('.address-field span.woocommerce-input-wrapper').append('<span onclick="return funEventCheck()" id="address-accept">'+inser+'!</span>');
$('#boxy').parent().addClass('active-tab');	
/*Order fields custom*/
		
if("#delivery_way_field .woocommerce-input-wrapper"){
  $('<div class="switch-buttons"><span id="d1" class="switch-button delivery-way left-buttton" value="Dostawa 40-90 min">'+deliver9+'</span><span id="d2" class="switch-button delivery-way right-buttton" value="Odbiór osobisty">'+teke+'</span></div>').appendTo('#delivery_way_field');
  $('.delivery-way').bind('click', function(){
    if($(this).hasClass('right-buttton')){
      $(this).parent().addClass('checked');
      $('#delivery_way').val($(this).attr("value"));
	  $('#apartment_field').hide();
	  $('#square_field').hide();
	  $('#floor_field').hide();
	  $('#apartment').val('-');
	  $('#square').val('-');
	  $('#floor').val('-');
	  $('#map_field').hide('slow');
	setTimeout(function(){
		$('li[szbd="true"]').hide();
	}, 1000);
	  
	  $('.delivery-way2').parent().hide();
	  $('.delivery-dat').parent().hide();
	  $('#billing_address_1').val('Wrocław');
	  $("#shipping_method_0_local_pickup27").attr('checked', true);
	  $('#billing_address_1_field').hide();
    }else{
      $(this).parent().removeClass('checked');
      $('#delivery_way').val($(this).attr("value"));
	  $('#apartment_field').show();
	  $('#square_field').show();
	  $('#floor_field').show();
	  $('#map_field').show();
	  $('#billing_address_1_field').show();
	   $('.delivery-way2').parent().show();
	  $('.delivery-dat').parent().show();
    }  
  })
}

if("#after_deliver_field .woocommerce-input-wrapper"){
  $('<div class="switch-buttons"><span id="d3" class="switch-button delivery-way2 left-buttton" value="Zadzwonić w drzwi">'+ctd+'</span><span id="d4" class="switch-button delivery-way2 right-buttton" value="Zadzwonić do mnie">'+ctm+'</span></div>').appendTo('#after_deliver_field');
  $('.delivery-way2').bind('click', function(){
    if($(this).hasClass('right-buttton')){
      $(this).parent().addClass('checked');
      $('#after_deliver').val($(this).attr("value"));
    }else{
      $(this).parent().removeClass('checked');
      $('#after_deliver').val($(this).attr("value"));
    }  
  })
}

if("#delivery_date_field .woocommerce-input-wrapper"){
  $('#flatpickr_field').hide();
  $('<div class="switch-buttons"><span id="d5" class="switch-button delivery-dat left-buttton" value="Jak najszybciej">'+asoons+'</span><span id="d6" class="switch-button delivery-dat right-buttton" value="Przedział czasowy">'+hours+'</span></div>').prependTo('#delivery_date_field');
  $('.switch-button').bind('click', function(){
    if($(this).hasClass('right-buttton')){
	 if($(this).hasClass('delivery-dat')){
	  $("#delivery_date option:first").prop("selected", true).text(opt1);
      $(this).parent().addClass('checked');
      //$('#delivery_date').val($(this).attr("value"));
		if($(this).attr('value') == "Przedział czasowy"){
        	$('#delivery_date_field .woocommerce-input-wrapper').show();
		}
        /* $('#flatpickr').on("input", function(){
            $('#delivery_date').val(ddw + $(this).val());
         });*/
		}

    }else{
		if($(this).hasClass('delivery-dat')){
      $(this).parent().removeClass('checked');
	  if($(this).attr('value') == "Jak najszybciej"){
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
$("#burger").bind( "click", function() {
	$(this).toggleClass("open");
	$(".header").toggleClass("menu-active");
});	
	
/*Close btn*/
$(".close-btn").bind( "click", function() {
	$('.reservation-form').toggleClass("open-form");
});	
$(".sign-in-curse").bind( "click", function(event) {
	event.preventDefault();
	$('.reservation-form').toggleClass("open-form");
	let title = $('.content-header h1').text();
	$('input[name="your-course"]').val(title);
});	
$(".close-btn-temp").bind( "click", function(event) {
	$('.chose-rest-on-mobile').hide();
});
$(".slider-home").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 5000,
  dots: true,
  arrows: false,
  lazyLoad: 'ondemand',
  speed: 500
});	

	
let path = location.protocol + '//' + document.location.hostname + '/wp-content/themes/americansushi/partials/front//cart.php';
//Home page Ajax
function reloadCart() {
	$.ajax({
    url: path,
    type: 'get',
	data:{"lang":  Cookies.get('pll_language')},
    success: function( result ) {
	 	$('#product-sidebar-cart').html(result);
		// Ajax change qantity product in the cart
		$('.qty-cart').on('change', function(){
			let product_id = $(this).attr("data-product_id"),
			cart_item_key = $(this).attr("data-cart_item_key"),
			amount = $(this).val();
			ajax_product_modify("product_update", product_id, cart_item_key, amount);	

		 });	
		
		$('.close-cart-mobile').bind('click', function(){
			$('.product-sidebar .cart-content').toggleClass('active-cart');
		});
		$('a.remove-in-cart').on('click', function (e)
			{
				e.preventDefault();
				let product_id = $(this).attr("data-product_id"),
					cart_item_key = $(this).attr("data-cart_item_key");
				ajax_product_modify("product_remove", product_id, cart_item_key, 0);	
			});
		if($(window).width() < 992){
			//setTimeout(function(){$('.product-sidebar .cart-content').addClass('active-cart');}, 300);
			}	
		
		/* Sel product */
		let path5 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/americansushi/partials/front//before-checkout.php';	
		$('#checkout1').on('click', function(e) {
			e.preventDefault();
			if(blocked_shops != "calysklep"){
			$('.before-checkout-products').addClass('active-prom');
			$('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
			let product_id = $(this).attr('id');
			$.ajax({
			url: path5,
			type: 'get',
			data: {"lang": Cookies.get('pll_language')},
			success: function( result ) {
				$('#before-checkout').html(result);
				alignHeights2();
				// Ajax change qantity product in the cart
				$('.before-checkout-products .add_to_cart_button').on('click', function(e) {
					e.preventDefault();
					let amount =  parseInt($(this).attr('pamount'))+1;
					$(this).attr('pamount', amount);
					$(this).addClass('this-is-added');
					$(this).html('<span>'+more+'</span><span class="amount-pr">['+amount+'x]</span>');
				});	
			}
		  }) 	
			}else{
				alert(wecant);
			}
		});			
		$('.plus-btn').on('click', function(){
				let amount = parseInt($('.h-amount').text());
				$('.h-amount').html(parseInt(amount)+1);
			});	
			$('.minus-btn').on('click', function(){
				let amount = parseInt($('.h-amount').text());
				$('.h-amount').html(parseInt(amount)-1);
			});		
		
		}
  }) 	
};
let max=0;	
function alignHeights(){
    max=0;
	if($(window).width() > 767){
     $(".alignMe .ptext").each(function() {
      if($(this).outerHeight() > max){max = $(this).outerHeight();}
     });
      $(".alignMe .ptext").css("height",max);
	}
}
function alignHeights2(){
    max=0;
	if($(window).width() > 767){
     $(".before-check .alignMe .ptext").each(function() {
      if($(this).outerHeight() > max){max = $(this).outerHeight();}
     });
      $(".before-check .alignMe .ptext").css("height",max);
	}
}
alignHeights();			
$('.category-filtr a').on('click', function(e) {
	e.preventDefault();
	$('.category-filtr').removeClass('active-tab');
	$(this).parent().addClass('active-tab');
	let category = $(this).attr('href');
    $('html,body').animate({
        scrollTop: $(category).offset().top + 40},
        'slow');
});
if($('.category-filtr a')){
	$('.category-filtr').first().addClass('active-tab');
}
function moverigt(){
	let navPosition = $('.shop-header-tabs ul').scrollLeft();
	$(".shop-header-tabs ul").animate({
        scrollLeft: navPosition + $('.active-tab').offset().left -50},
        'slow');
}	
$(window).scroll(function (event) {
	if($('.active-tab')){
    let scroll = $(window).scrollTop();
	let category = $('.active-tab a').attr('href');
	let next = 0;
	let prev = 0;
	if($(category).next().is('div')){
		next = $(category).next().offset().top;
	}
	if($(category).prev().is('div')){
		prev = $(category).offset().top - 20;
	}
	let current = $('.active-tab');
	if(next != 0 && scroll > next){
		$('.active-tab').next().addClass('active-tab');
		current.removeClass('active-tab');
		moverigt();
	}else if(prev != 0 && scroll < prev){
		$('.active-tab').prev().addClass('active-tab');
		current.removeClass('active-tab');
		moverigt();
	}
	}
});
	
let path2 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/americansushi/partials/front//products-list.php';	
$('.category-filtr span').on('click', function() {
	let category = $(this).attr('id');
	let categoryname = $(this).text();
	//$('#products-list').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
	$('.product').addClass('disabled-product');
	$('.cart-item').addClass('disabled-product');
	$('.category-filtr span').parent().removeClass('active-tab');
	$(this).parent().addClass('active-tab');
	$.ajax({
    url: path2,
    type: 'get',
	data: {"category": category, "lang": Cookies.get('pll_language'), "categoryname": categoryname},
    success: function( result ) {
		$('#category-name1').html(category);
	 	$('#products-list').html(result);
		if(blocked_shops != "calysklep" && Cookies.get('address') && (Cookies.get('address') != "")){
			$('.product').removeClass('disabled-product');
			$('.cart-item').removeClass('disabled-product');
		}else{
			$('.product').addClass('disabled-product');
			$('.cart-item').addClass('disabled-product');
		}
		alignHeights();
		// Ajax change qantity product in the cart
		$('.add_to_cart_button').on('click', function() {
		  if(blocked_shops != "calysklep"){
		  let parent_prod = $(this).parent().parent();
		  let cloneImg = parent_prod.find('.attachment-post-thumbnail').clone(true);
		  cloneImg.addClass("floating",'temp');
		  parent_prod.append(cloneImg);
		  setTimeout(function() {  
			parent_prod.remove(cloneImg);
			reloadCart();
		  }, 1100);
		  }else{
			  alert(wecant);
		  }
		});	
    }
  }) 	
});		

	
setTimeout(function(){ $(".preloader").fadeOut(100); }, 600);

/*Add to cart animation*/

//TODO o10 olbor
if( blocked_shops !== "calysklep" ) {
	$('.product.disabled-product,.cart-item.disabled-product,#product-sidebar-cart')
		.css({ pointerEvents: 'visible', opacity: '1' });
}
$('.add_to_cart_button').on('click', function() {

  if( blocked_shops !== "calysklep" ) {

	// alert('Prosimy składać zamówienia telefonicznie. tel: 733 954 773');

	  // let parent_prod = $(this).parent().parent().parent();
	  // let cloneImg = parent_prod.find('.attachment-post-thumbnail').clone(true);
	  // cloneImg.addClass("floating",'temp');
	  // parent_prod.append(cloneImg);
	  setTimeout(function() {
		// parent_prod.remove(cloneImg);
		reloadCart();
	  }, 1200);
  } else {
	  alert(wecant);
  }
});

	
// Ajax call/modify product in the cart
function ajax_product_modify(actionp, product_idp, cart_item_keyp, amountp){

	let product_container = $(this).parents('.cart_item');
	
	console.log(amountp);
	
    // Add loader
    product_container.block({
        message: null,
        overlayCSS: {
            cursor: 'none'
        }
    });
	
	$('.cart-item').addClass('disabled-product');

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: wc_add_to_cart_params.ajax_url,
        data: {
			action: "product_remove",
            todo: actionp,
            product_id: product_idp,
            cart_item_key: cart_item_keyp,
			amount: amountp
        },
        success: function(response) {
			$('.cart-item').removeClass('disabled-product');
           reloadCart();
        }
    });
};	

	
$('.selected-rest').on('click', function(){
	$('.chose-restaurant').removeClass('animated-rest');
});
// Ajax delete product in the cart	
$('a.remove-in-cart').on('click', function (e)
{
	e.preventDefault();
	let product_id = $(this).attr("data-product_id"),
        cart_item_key = $(this).attr("data-cart_item_key");
	ajax_product_modify("product_remove", product_id, cart_item_key, 0);	
});	
// Ajax change qantity product in the cart
$('.qty-cart').on('change', function(){
	 let product_id = $(this).attr("data-product_id"),
        cart_item_key = $(this).attr("data-cart_item_key"),
		 amount = $(this).val();
		 ajax_product_modify("product_update", product_id, cart_item_key, amount);	
	 
 });	
$('.selected-rest').bind('click', function(){
	$('.rest-dropdown').toggleClass('active-drop');
});

function refreshSession(value, refresh){
	jQuery.ajax({
        type: "POST",
        url: "/wp-admin/admin-ajax.php",
        data: {
            action: 'custom_base_address',
            // add your parameters here
           address: value + ", Wrocław"
        },
        success: function (output) {
		   if(refresh == 1){
           	window.location.reload();
		   }
        }
        });
}	
	
$('.sub-menu li a, .retaurant-label, .restaurant-link').on('click', function(e){
	if($(this).hasClass("selected-rest")){
		return;
	}
	e.preventDefault();
	let value = "";
	if($(this).hasClass('retaurant-label')){
		value = $(this).attr('id');
		
	}else{
		value = $(this).text();
		if($('.chose-rest-on-mobile')){
			$('.chose-rest-on-mobile').hide();
		}
	}
	
	Cookies.set('address', value, { expires: 365, path: '/'  });
	
	refreshSession(value, 1);
  
})
	
$('#billing_address_1_field label').html('Adres&nbsp;<abbr class="required" title="wymagane">*</abbr>');

	
/* Cart */
	
$('.close-cart-mobile, .basket-mobile').bind('click', function(){
	$('.product-sidebar .cart-content').toggleClass('active-cart');
});
	
/* Sel product */
/*let path4 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/americansushi/partials/front//selected-product.php';	
$('.product a.plink').on('click', function(e) {
	e.preventDefault();
	$('.promotion-products').addClass('active-prom');
	let product_id = $(this).attr('id');
	$.ajax({
    url: path4,
    type: 'get',
	data: {"product_id": product_id},
    success: function( result ) {
	 	$('#promotion').html(result);
		console.log(result);
		// Ajax change qantity product in the cart
		$('.add_to_cart_button').on('click', function() {
		  let parent_prod = $(this).parent().parent();
		  let cloneImg = parent_prod.find('.attachment-post-thumbnail').clone(true);
		  cloneImg.addClass("floating",'temp');
		  parent_prod.append(cloneImg);
		  setTimeout(function() {  
			parent_prod.remove(cloneImg);
			reloadCart();
		  }, 1000);

		});	

		$('.close-product-sel').bind('click', function() {		
			$('.promotion-products').removeClass('active-prom');
		});
		$('.promotion-products .add_to_cart_button').bind('click', function() {
			$(this).html("Produkt dodany do koszyka");
			setTimeout(function(){$('.promotion-products').removeClass('active-prom');}, 1000);
		});
    }
  }) 	
});		*/
	
/* Sel product */
let path5 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/americansushi/partials/front//before-checkout.php';	
$('#checkout1').on('click', function(e) {
	e.preventDefault();
	if(blocked_shops != "calysklep"){
	$('.before-checkout-products').addClass('active-prom');
	$('#before-checkout').html('<div id="preloader" class="preloader preloader2"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
	let product_id = $(this).attr('id');
	$.ajax({
    url: path5,
    type: 'get',
	data: {"lang": Cookies.get('pll_language')},
    success: function( result ) {
	 	$('#before-checkout').html(result);
		alignHeights2();
		// Ajax change qantity product in the cart
		$('.before-checkout-products .add_to_cart_button').on('click', function(e) {
			e.preventDefault();
			let amount =  parseInt($(this).attr('pamount'))+1;
			$(this).attr('pamount', amount);
		    $(this).addClass('this-is-added');
		    $(this).html('<span>'+more+'</span><span class="amount-pr">['+amount+'x]</span>');
		});	
    	}
  		}) 	
	}else{
		alert(wecant);
	}
});	
if (url.indexOf("/ua") >= 0){	
$("#address-filled").on('click',function(){	
	$('.cart-subtotal th').html('сума');
	$('.woocommerce-shipping-totals th').html('Відвантаження');
	$('.order-total th').html('Разом');
	$('.order-additional th').html('Час підготовки та доставки');
	$('.order-additional td').html('Ви отримаєте SMS із часом доставки');
});
}
$('#place_order').addClass('disabled-product');	
$("#address-filled").on('click',function(){	
	let d = 0;
	jQuery('body').trigger('update_checkout');
	setTimeout(function() {
	$('#shipping_method li label').each(function(){
	if($(this).text().indexOf('Local pickup') > -1){
		$(this).html(teke);
	}
	});
	$('li[szbd="true"]').each(function(){
		if(d==0){
			$(this).addClass('activeDel');
			if($(this).text().indexOf('Przesyłka do') > -1){
				let temtxt = $(this).text().split('Przesyłka do');
				$(this).find("label").html(sst + temtxt[1]);
			}
		}
		d++;
	});
	$('#place_order').removeClass('disabled-product');	
	}, 6500);
	//setTimeout(function() {$("#billing_address_1").trigger('blur'); }, 3000);
 });

reloadCart();
$("#billing_phone").on('blur',function(){		
	   let filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
  if(filter.test( $(this).val() ))
        {
      		return true;
        }
      else
        {
        	alert(alertErr);
        	return false;
        }
					   
 });	
	
});	
})(jQuery);






