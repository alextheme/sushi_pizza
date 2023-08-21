<footer class="lg100 footer bckg-white p-top p-bottom-30">
	<div class="container">
		<div class="row align-center justify-spaceb copyright">
			<div class="left-copy">
				<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', ) ); ?>
			</div>
			<div class="right-copy">
				<span><?php echo pll__( 'Strona stworzona i prowadzona przez' ); ?> <a class="txt-red" href="https://entsolve.pl" target="_blank">entsolve.pl</a></span>
			</div>
		</div>
	</div>
</footer>	

<?php wp_footer(); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="<?php echo get_template_directory_uri(); ?>/css/lightgallery.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>

    
<script>function WHCreateCookie(name,value,days){var date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));var expires="; expires="+date.toGMTString();document.cookie=name+"="+value+expires+"; path=/"}function WHReadCookie(name){var nameEQ=name+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' ')c=c.substring(1,c.length);if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length)}return null}window.onload=WHCheckCookies;function WHCheckCookies(){if(WHReadCookie('cookies_accepted')!='T'){var message_container=document.createElement('div');message_container.id='cookies-message-container';var html_code='<div id="cookies-message" style="margin: 0 auto; left:0; right:0; padding: 10px 60px 10px 10px;  color: #fff; position:fixed; font-size: 12px; line-height: 22px; text-align: left; bottom: 0px;  background-color:#000; max-width: 1210px; z-index: 999999;"><p style="margin: 0;">Ta strona używa plików cookies. Pozostając na tej stronie, wyrażasz zgodę na korzystanie z plików cookies. Więcej informacji można znaleźć w <a href="/cookies_regulamin.pdf" style="color:#f1f1f1;" target="_blank">Polityce w sprawie Cookies.</a><a href="javascript:WHCloseCookiesWindow();" id="accept-cookies-checkbox" name="accept-cookies" style=" float:right; cursor: pointer; color:#fff; text-decoration: none; "><span style=" background-color: #000; display: block; position:absolute; right:0; top:0; height: 42px; line-height:42px; width: 42px; text-align: center; color: #fff; font-size: 25px;">&#10005;</span></a></p></div>';message_container.innerHTML=html_code;document.body.appendChild(message_container)}}function WHCloseCookiesWindow(){WHCreateCookie('cookies_accepted','T',365);document.getElementById('cookies-message-container').removeChild(document.getElementById('cookies-message'))}</script>


    </body>
</html>
