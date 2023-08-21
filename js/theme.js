(function($) { 
$(document).ready(function() {

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
	
$('.harmo-one').on('click', function(e) {
  if($(this).hasClass('active')){
    $(this).removeClass('active');
    $(this).children('.harmo-content').slideUp(300);
  }else{
    $(this).addClass('active');
    $(this).children('.harmo-content').slideDown(300);
  }
});
$('.harmo-one .harmo-one-title').on('click', function(e) {
	e.preventDefault();
});
$('.tabbler .tab-links .harmo-one-title').on('click', function(e) {
	e.preventDefault();
  var id = $(this).attr('href');
  $('.tabbler .tab-links a').removeClass('active');
  $('.tabbler .tab-content').removeClass('opened');
  $(this).addClass('active');
  $(id).addClass('opened');
});

$('.button').on('click', function() {
  $(this).addClass('clicked');
});

function alignHeights(){
  var i = 0;
  $('.alignerContainer').each(function() {
    var max=0;
    var newclass = 'alignCont-'+i;
    $(this).addClass(newclass);
    $("<style type='text/css'> ."+newclass+" .alignMe{ height: auto;} </style>").appendTo("head");
     $("."+newclass+" .alignMe").each(function() {
      if($(this).outerHeight() > max){max = $(this).outerHeight();}
     });
     $("<style type='text/css'> ."+newclass+" .alignMe{ height: "+max+"px;} </style>").appendTo("head");
     i++; 
  });
} 

var rtime;
var timeout = false;
var delta = 500;
$(window).resize(function() {
  rtime = new Date();
  if (timeout === false) {
    timeout = true;
    setTimeout(resizeend, delta);
  }
});

function resizeend() {
  if(new Date() - rtime < delta) {
    setTimeout(resizeend, delta);
  }else{
    timeout = false;
    if($(window).width() > 767){
      alignHeights();
    }
  }               
}
$(window).on('load', function() {
  $('.tabbler .tab-links li:first-child a').trigger('click');
  if($(window).width() > 767){
    alignHeights();
  }
  AOS.init();
});



});
})(jQuery);

