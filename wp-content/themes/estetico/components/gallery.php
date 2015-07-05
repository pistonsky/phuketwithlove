<extract>
<?php
$images = isset($images) ? $images : array();

if( empty( $images ) ) {
	return;
}

$postid = get_the_ID();
$property = new Property($postid);
?>
<div class="box estetico-property-gallery">
	
	<div>
		<div class="target"></div>
		<div class="clear"></div>	
	</div>
	
	<div class="panel">	
		<div class="left"><a href="javascript:void(0);" class="left-arrow"></a></div>		
		<ul class="images">
			<?php 
                $count = 0;
                foreach( $images as $image ) : 
                    $normal_image = wp_get_attachment_image( $image, 'properties-gallery-normal' );
                    $src_large_image = wp_get_attachment_image_src( $image, 'full' );
                    $large_image = $src_large_image[0];
                    $matches = array();
                    preg_match("/src=[\"'](.*?)[\"']/", $normal_image, $matches );
                    $normal_image = isset( $matches[1] ) ? $matches[1] : '#'; 
                    $count++;
                ?>
                <li data-index="<?php echo $count; ?>"><a href="<?php echo $large_image; ?>" data-lightbox="property-image"><?php echo wp_get_attachment_image( $image, 'properties-gallery-thumbnail' ) ?></a></li>
			<?php endforeach ?>
		</ul>
        <?php $prop_video_url = $property->getVideoUrl();
            if(!empty($prop_video_url)) : ?>
                <div style="display:none;" class="video-url" data-index="<?php echo ($count + 1); ?>">
                    <a href="<?php echo $prop_video_url;?>">
                        <img src="<?php echo $property->getVideoThumbnailUrl();?>">
                    </a>
                </div>      
        <?php endif ?>
		<div class="right"><a href="javascript:void(0);" class="right-arrow"></a></div>
		<div class="clear"></div>
	</div>
	
</div>
</extract>