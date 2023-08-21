<?php

$search = '';
$category = '';
$letter = '';
$sortby = '';
$status = '';

$args = array( 'type' => 'product', 'taxonomy' => 'product_cat', 'hide_empty' => true ); 
$categories = get_categories( $args ); 


if($_GET){
	if ($_GET['s'] && !empty($_GET['s'])) {
	    $search = $_GET['s'];
	}
	if ($_GET['cat'] && !empty($_GET['cat'])) {
	    $category = $_GET['cat'];
	}
	if ($_GET['let'] && !empty($_GET['let'])) {
	    $letter = $_GET['let'];
	}
	if ($_GET['sortby'] && !empty($_GET['sortby'])) {
	    $sortby = $_GET['sortby'];
	}
	if ($_GET['status'] && !empty($_GET['status'])) {
	    $status = $_GET['status'];
	}
}

?>

<div class="container">
	<div class="row">
		<h1 class="lg100 text-center"><?php if(is_archive('barcamp')){ echo "Wszystkie wydarzenia"; }else if(is_shop()){echo "Wszystkie szkolenia";}?></h1>
		<div class="lg50 xs100 form-search">
			<form role="search" <?php echo $aria_label;?> method="get" class="search-form">
				<input type="search" id="search-input" class="search-field shadowed" placeholder="Wpisz czego szukasz?" value="<?php echo $search; ?>" name="s" />
				<input type="submit" class="search-submit" value="Szukaj" />
			</form>
		</div>
		<?php if(is_shop()) : ?>
		<form method="get" class="lg100 filter-courses p-top p-bottom-30" action="/courses" id="courses-form-sort">
			<div class="lg100 filter-by-letter">
				<span id="all">All</span>
				<span id="a">A</span>
				<span id="b">B</span>
				<span id="c">C</span>
				<span id="d">D</span>
				<span id="e">E</span>
				<span id="f">F</span>
				<span id="g">G</span>
				<span id="h">H</span>
				<span id="i">I</span>
				<span id="j">J</span>
				<span id="k">K</span>
				<span id="l">l</span>
				<span id="m">M</span>
				<span id="n">N</span>
				<span id="o">O</span>
				<span id="p">P</span>
				<span id="q">Q</span>
				<span id="r">R</span>
				<span id="s">S</span>
				<span id="t">T</span>
				<span id="u">U</span>
				<span id="v">V</span>
				<span id="w">W</span>
				<span id="x">X</span>
				<span id="y">Y</span>
				<span id="z">Z</span>
			</div>
			<div class="select-filters p-top-30">
				<select name="cat" id="category">
					<option value="">Wszystkie kategorie</option>
					<?php 
						if ( count($categories) > 0 ) :
							foreach ( $categories as $categoryT ) : ?>

									<option <?php if($category == $categoryT->slug){echo 'selected'; }?> value="<?php echo $categoryT->slug; ?>"><?php echo $categoryT->name; ?></option>
							<?php
							endforeach;
						endif;
					?>
				</select>	
				<select name="sortby" id="sortby">
					<option value="">Numer i tutuł</option>
						<option <?php if($sortby == 'asc'){echo 'selected'; }?> value="asc">Rosnąco A-Z</option>
						<option <?php if($sortby == 'desc'){echo 'selected'; }?> value="desc">Malejąco Z-A</option>
					?>
				</select>	
				<select name="status" id="status1">
					<option value="">Status</option>
						<option <?php if($status == 'online'){echo 'selected'; }?> value="online">Online</option>
						<option <?php if($status == 'offline'){echo 'selected'; }?> value="offline">Offline</option>
					?>
				</select>			
			</div>
		</form>
		<?php elseif(is_archive('barcamp')):?>
		<div class="lg100 filters-b p-top xs-offset-top">
			<!--<span class="filter-button" id="event">
				Event	
			</span>-->
			<span class="filter-button" id="online">
				Online	
			</span>
			<span class="filter-button active-filter-b" id="all">
				Wszystkie	
			</span>
		</div>
		<?php endif; ?>
	</div>
</div>

