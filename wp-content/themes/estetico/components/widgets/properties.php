<?php 

if( ! isset( $properties ) ) {
	return;
}

?>

<div class="box general">
	<h3 class="title"><?php echo $instance['listing_title'] ?></h3>
	<div class="offers">
		<ul class="list packed">
			<?php foreach( $properties as $property ) :
			$image_src = $property->getImage('properties-featured-thumbnail');
			$image = "";
			if($image_src) {
				$image = '<img src="' . $image_src . '" alt="">';
			}
			?>
			<li class="offer">
				<div class="image">
					<a href="<?php echo esc_attr( $property->getLink() ) ?>"><?php echo $image ?></a>
				</div>
				<address><a href="<?php echo esc_attr( $property->getLink() ) ?>"><?php echo $property->getAddress() ?></a></address>
				<?php if($property->getForSaleRent() == 'sale') : ?>
				<span class="price"><?php echo $property->getPrice(true) ?> </span>
				<?php endif ?>
			</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>