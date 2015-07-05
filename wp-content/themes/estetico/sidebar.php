<?php

global $sidebar_name;

$_sidebar_name = 'default_sidebar';

if(isset($sidebar_name) && !empty($sidebar_name)) {

	switch ($sidebar_name) {
		case 'blog':
			$_sidebar_name = 'blog_sidebar';
			break;

		case 'property_details':
			$_sidebar_name = 'property_details_sidebar';
		break;

		case 'properties':
			$_sidebar_name = 'properties_sidebar';
		break;
	}
}

if ( is_active_sidebar( $_sidebar_name ) ) : ?>
	<div class="sidebar" role="sidebar">
		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( $_sidebar_name ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</div><!-- #tertiary -->
<?php endif; ?>