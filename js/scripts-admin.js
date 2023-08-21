(function($) { 
$(document).ready(function() {
	

if($('.shop-admin-btns')){
	$('.shop-admin-btns a').on('click', function(e){
		e.preventDefault();
		let address2 = $(this).attr('id');
		if($('#'+$(this).attr('id')).hasClass('clicked') && $(this).attr('id') !=""){
			address2 = "";
		}
		if($(this).attr('id') =="calysklep"){
			$('.shop-admin-btns a').removeAttr('style').removeClass('clicked');
			$('#'+$(this).attr('id')).addClass('clicked');
			$('#'+$(this).attr('id')).css({'color':'#ff0000'});
		}else{
			$('.shop-admin-btns a').removeAttr('style').removeClass('clicked');
		}
		jQuery.ajax({
	        type: "POST",
	        url: "/wp-admin/admin-ajax.php",
	        data: {
	            action: 'block_shop',
	            // add your parameters here
	           address: address2
	        },
	        success: function (output) {
			   alert("Operacja przebiegła pomyślnie!");
	        }
        });

	});
}

});
})(jQuery);