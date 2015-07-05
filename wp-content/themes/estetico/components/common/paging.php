<?php if( $found_posts > $properties_per_page && $list_style != 'map') : ?>
<div class="paging">
	<ul>
		<?php for( $i = 1; $i <= ceil( $found_posts / $properties_per_page ); $i+= 1) : ?>
		<li><a href="<?php echo estetico_preserve_url( array('start_page' => $i) ) ?>" class="<?php echo ( $start_page == $i ? ' active' : '' ) ?>"><?php echo $i ?></a></li>
		<?php endfor ?>
	</ul>
</div>
<?php endif ?>