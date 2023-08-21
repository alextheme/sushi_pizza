(function($) { 
$(document).ready(function() {
	

let path = location.protocol + '//' + document.location.hostname + '/wp-content/themes/osec/partials//archive-product.php';
//Home page Ajax
$('#category, #sortby, #status').on('change', function(event) {
	console.log($("#courses-form-sort").serialize());
  event.preventDefault();
	let selected =  $(this).val();
	$.ajax({
    url: path,
    type: 'get',
		 data:$("#courses-form-sort").serialize(),
    success: function( result ) {
		 $('#courses-list').html(result);
		console.log(result);	
    }
  }) 	
});
	
let path3 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/osec/partials/front/films.php';
//Home page Ajax
$('#show-films').on('click', function() {
  $(this).hide();
  event.preventDefault();
  window.scrollBy(0, 200);
	$.ajax({
    url: path3,
    type: 'get',
		 data:'',
    success: function( result ) {
		 $('#films-yt').html(result);	
    }
  }) 	
});	

$('.woo-page .search-submit').on('click',function(){
	console.log('/?s=' + $('.search-form').serialize() + '&' + $("#courses-form-sort").serialize());
  event.preventDefault();
	let selected =  $(this).val();
	$.ajax({
    url: path,
    type: 'get',
		 data: $('.search-form').serialize() + '&' + $("#courses-form-sort").serialize(),
    success: function( result ) {
		 $('#courses-list').html(result);
		console.log(result);	
    }
  }) 	
});		
let path2 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/osec/partials//archive-events.php';
$('.events .search-submit').on('click',function(){
  event.preventDefault();
	let selected =  $(this).val();
	$.ajax({
    url: path2,
    type: 'get',
		 data: $('.search-form').serialize(),
    success: function( result ) {
		 $('#events-list').html(result);
		console.log(result);	
    }
  }) 	
});	

let path4 = location.protocol + '//' + document.location.hostname + '/wp-content/themes/osec/partials//archive-posts.php';
$('#more-posts-arch').on('click',function(){
  event.preventDefault();
	$.ajax({
    url: path4,
    type: 'get',
		 data: '',
    success: function( result ) {
		 $('#news-archive').html(result);
		console.log(result);	
    }
  }) 	
});	



});
})(jQuery);

	