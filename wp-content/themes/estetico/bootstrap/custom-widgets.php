<?php

/**
 * Recent_Comments widget class
 *
 * @since 2.8.0
 */
if( ! class_exists('WP_Widget_Recent_Comments_Extended') ) {
class WP_Widget_Recent_Comments_Extended extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_comments_extended', 'description' => __( 'The most recent comments', THEME_NAME ) );
		parent::__construct('recent-comments-extended', __('Recent Comments Extended', THEME_NAME), $widget_ops);
		$this->alt_option_name = 'widget_recent_comments_extended';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array($this, 'recent_comments_style') );

		add_action( 'comment_post', array($this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array($this, 'flush_widget_cache') );
	}

	function recent_comments_style() {
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_comments_extended', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get('widget_recent_comments_extended', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

 		extract($args, EXTR_SKIP);
 		$output = '';

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments', THEME_NAME );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
 			$number = 5;

		$comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) ) );
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;

		$output .= '<div class="offers"><ul class="list packed">';
		if ( $comments ) {
			// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
			$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
			_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

			foreach ( (array) $comments as $comment) {
				$output .=  '<li class="offer">';

				$output .= '<div class="image"><a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_post_thumbnail($comment->comment_post_ID, array(100, 100)) . '</a></div>';

				$output .= /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%1$s on %2$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
			}
 		}
		$output .= '</ul></div>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('widget_recent_comments_extended', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments_extended']) )
			delete_option('widget_recent_comments_extended');

		return $instance;
	}

	function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
}

/**
 * Recent_Posts widget class
 *
 * A brutal copy of the original Recent_Posts widget by Wordpress but extended a little bit for more usability.
 *
 * @since 2.8.0
 */
if( ! class_exists('WP_Widget_Recent_Posts_Extended') ) {
class WP_Widget_Recent_Posts_Extended extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your site", THEME_NAME) );
		parent::__construct('recent-posts-extended', __('Recent Posts Extended', THEME_NAME), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts_extend', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', THEME_NAME );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number )
 			$number = 10;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_thumbnails = isset( $instance['show_thumbnails'] ) ? $instance['show_thumbnails'] : true;

		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if ($r->have_posts()) :
		?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<div class="offers">
		<ul class="list packed">
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li class="offer<?php if( !$show_thumbnails ) : ?> no-image<?php endif ?>">
				<?php if($show_thumbnails): ?>
				<div class="image">
					<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php echo get_the_post_thumbnail(get_the_ID(), array(100, 100)) ?></a>
				</div>
			<?php endif ?>
			
			<?php
				$the_title = get_the_title();
				if ( mb_strlen( $the_title, 'utf8' ) > 80 ) {
				   $last_space = strrpos( substr( $the_title, 0, 80 ), ' ' );
				   $the_title = substr( $the_title, 0, $last_space ) . '...';
				}
			?>
				<a href="<?php the_permalink() ?>" class="sidebar-recent-posts"><?php echo $the_title; ?></a>
				<span class="date"><?php echo get_the_date(); ?></span>
			</li>
		<?php endwhile; ?>
		</ul>
	</div>
		<?php echo $after_widget; ?>
	<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts_extend', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['show_thumbnails'] = isset( $new_instance['show_thumbnails'] ) ? (bool) $new_instance['show_thumbnails'] : false;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts_extend', 'widget');
	}

	function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_thumbnails = isset( $instance['show_thumbnails'] ) ? (bool) $instance['show_thumbnails'] : true;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_thumbnails ); ?> id="<?php echo $this->get_field_id( 'show_thumbnails' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnails' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumbnails' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label></p>
<?php
	}
}

}

if( ! class_exists( 'PropertiesFilterWidget' ) ) {
	class PropertiesFilterWidget extends WP_Widget {
		
		function PropertiesFilterWidget() {

			parent::__construct( false, __( 'Properties filter', THEME_NAME ) );
		}

		function widget( $args, $instance ) {

			$price_min 	= PropertiesManager::getPriceMin();
			$price_max 	= PropertiesManager::getPriceMax();
			$types		= PropertiesManager::getAllTypes();
			$types_selected = array();
			$types_filtered = array();
			$features 	= PropertiesManager::getAllFeatures();
			$features_selected = array();
			$features_filtered = array();

			if(isset($_GET['feature'])) {
				$features_selected = explode(',', $_GET['feature']);
			}

			$features_filtered = PropertiesManager::getFeaturesFiltered($_GET);

			if(isset($_GET['type'])) {
				$types_selected = explode(',', $_GET['type']);
			}

			$types_filtered = PropertiesManager::getTypesFiltered($_GET);

			if( isset( $_GET['price'] ) ) {
				$price = explode(',', $_GET['price']);
				$price_min_value = $price[0];
				$price_max_value = $price[1];
			} else {

				if( isset( $_GET['min_price'] ) ) {

					$price_min_value = $_GET['min_price'];
				} else {
					$price_min_value = $price_min;
				}
				
				if( isset( $_GET['max_price'] ) ) {

					$price_max_value = $_GET['max_price'];
				} else {
					$price_max_value = $price_max;
				}
			}

			$bedrooms_min = $bedrooms_min_value = 0;
			$bedrooms_max = $bedrooms_max_value = 20;

			if(isset($_GET['bedrooms'])) {

				if( strpos( $_GET['bedrooms'], ',') !== false ) {
					$bedrooms = explode(',', $_GET['bedrooms']);
					$bedrooms_min_value = $bedrooms[0];
					$bedrooms_max_value = $bedrooms[1];
				} else {
					$bedrooms_min_value = $_GET['bedrooms'];
				}
			}

			$bathrooms_min = $bathrooms_min_value = 0;
			$bathrooms_max = $bathrooms_max_value = 20;

			if(isset($_GET['bathrooms'])) {

				if( strpos( $_GET['bathrooms'], ',') !== false ) {
					$bathrooms = explode(',', $_GET['bathrooms']);
					$bathrooms_min_value = $bathrooms[0];
					$bathrooms_max_value = $bathrooms[1];
				} else {
					$bathrooms_min_value = $_GET['bathrooms'];
				}
			}

			$sq_feet_min = $sq_feet_min_value = 0;
			$sq_feet_max = $sq_feet_max_value = PropertiesManager::getSqFeetMax();

			if(isset($_GET['sq_feet'])) {

				if( strpos( $_GET['sq_feet'], ',') !== false ) {
					$sq_feet = explode(',', $_GET['sq_feet']);
					$sq_feet_min_value = $sq_feet[0];
					$sq_feet_max_value = $sq_feet[1];
				} else {
					$sq_feet_min_value = $_GET['sq_feet'];
				}
			}

			$year_built_min = $year_built_min_value = PropertiesManager::getYearBuiltMin();
			$year_built_max = $year_built_max_value = date('Y');

			if(isset($_GET['year_built'])) {
				$years = explode(',',$_GET['year_built']);
				$year_built_min_value = $years[0];
				$year_built_max_value = $years[1];
			}

			$main_properties_page = estetico_get_properties_page_id();

			$reset_url = estetico_get_properties_page_url_wpml($main_properties_page);

			$args = array(
				'price_min' 			=> $price_min,
				'price_max' 			=> $price_max,
				'price_min_value' 		=> $price_min_value,
				'price_max_value' 		=> $price_max_value,
				'bedrooms_min' 			=> $bedrooms_min,
				'bedrooms_max' 			=> $bedrooms_max,
				'bedrooms_min_value' 	=> $bedrooms_min_value,
				'bedrooms_max_value' 	=> $bedrooms_max_value,
				'bathrooms_min' 		=> $bathrooms_min,
				'bathrooms_max' 		=> $bathrooms_max,
				'bathrooms_min_value' 	=> $bathrooms_min_value,
				'bathrooms_max_value' 	=> $bathrooms_max_value,
				'year_built_min'		=> $year_built_min,
				'year_built_max'		=> $year_built_max,
				'year_built_min_value'	=> $year_built_min_value,
				'year_built_max_value'	=> $year_built_max_value,
				'sq_feet_min' 			=> $sq_feet_min,
				'sq_feet_max' 			=> $sq_feet_max,
				'sq_feet_min_value' 	=> $sq_feet_min_value,
				'sq_feet_max_value' 	=> $sq_feet_max_value,
				'types'					=> $types,
				'types_filtered'		=> $types_filtered,
				'types_selected'		=> $types_selected,
				'features' 				=> $features,
				'features_selected' 	=> $features_selected,
				'features_filtered'		=> $features_filtered,
				'distance' 				=> isset($_GET['distance']) ? $_GET['distance'] : 0,
				'city' 					=> isset($_GET['city']) ? $_GET['city'] : '',
				'beds' 					=> isset($_GET['beds']) ? $_GET['beds'] : 0,
				'location_latitude' 	=> isset($_GET['lat']) ? $_GET['lat'] : '',
				'location_longitude' 	=> isset($_GET['lng']) ? $_GET['lng'] : '',
				'location' 				=> isset($_GET['location']) ? $_GET['location'] : '',
				'distances_config'		=> explode(',', estetico_get_setting('distances')),
				'keywords'				=> isset($_GET['keywords']) ? $_GET['keywords'] : '',
				'for_sale_rent'			=> isset($_GET['for_sale_rent']) ? $_GET['for_sale_rent'] : 'both',
                'property_status'		=> isset($_GET['property_status']) ? $_GET['property_status'] : '',
				'pets_allowed'			=> isset($_GET['pets_allowed']) ? $_GET['pets_allowed'] : '',
				'reset_url'				=> $reset_url
			);

			estetico_load_component('widgets/properties_filter', $args);
		}

		function update( $new_instance, $old_instance ) {

			return $new_instance;
		}

		function form( $instance ) {

			$output = __('This is just a placeholder. Please go to "Appearance -> Theme options -> Search settings" to configure fiilters.', THEME_NAME );

			echo $output;
		}
	}
}

if( ! class_exists( 'AgentWidget' ) ) {
	class AgentWidget extends WP_Widget {
		
		function AgentWidget() {

			parent::__construct( false, __( 'Agent details', THEME_NAME ) );
		}

		function widget( $args, $instance ) {

			global $post;

			if(!isset($post)) {
				return;
			}

			$meta = get_post_meta($post->ID);

			require_once COMPONENTS_PATH . "/widgets/agent.php";
		}

		function update( $new_instance, $old_instance ) {

			return $new_instance;
		}

		function form( $instance ) {

			$description = isset( $instance['description'] ) ? $instance['description'] : '';

			$output = "";

			$textarea = new TextareaFormControl(array(
				'label' => array('for' => $this->get_field_id( 'description' ), 'text' => __('Description', THEME_NAME)),
				'name' => $this->get_field_name( 'description' ),
				'id'	=> $this->get_field_id('description'),
				'value' => $description
			));

			$output .= $textarea;

			echo $output;
		}
	}
}

if( ! class_exists( 'QuickSearchWidget' ) ) {
	class QuickSearchWidget extends WP_Widget {
		
		function QuickSearchWidget() {

			parent::__construct( false, __( 'Quick search', THEME_NAME ) );
		}

		function widget( $args, $instance ) {

			$description = $instance['description'];

			require_once COMPONENTS_PATH . "/widgets/quick_search.php";
		}

		function update( $new_instance, $old_instance ) {

			return $new_instance;
		}

		function form( $instance ) {

			$description = isset( $instance['description'] ) ? $instance['description'] : '';

			$output = "";

			$textarea = new TextareaFormControl(array(
				'label' => array('for' => $this->get_field_id( 'description' ), 'text' => __('Description', THEME_NAME)),
				'name' => $this->get_field_name( 'description' ),
				'id'	=> $this->get_field_id('description'),
				'value' => $description
			));

			$output .= $textarea;

			echo $output;
		}
	}
}

if( ! class_exists( 'FeaturePropertiesWidget' ) ) {
	class FeaturePropertiesWidget extends WP_Widget {
		
		function FeaturePropertiesWidget() {

			parent::__construct( false, __( 'Properties listing', THEME_NAME ) );
		}

		function widget( $args, $instance ) {

			$properties = PropertiesManager::getProperties($instance['listing_type'], $instance['order_way'], $instance['items_count']);

			require_once COMPONENTS_PATH . '/widgets/properties.php';
		}

		function update( $new_instance, $old_instance ) {

			return $new_instance;
		}

		function form( $instance ) {
			
			$listing_title = isset( $instance['listing_title'] ) ? $instance['listing_title'] : '';
			$listing_type = isset( $instance['listing_type'] ) ? $instance['listing_type'] : 'newest';
			$order_way = isset( $instance['order_way'] ) ? $instance['order_way'] : 'default';
			$items_count = isset( $instance['items_count']) ? $instance['items_count'] : 10;

			$output = ""; 
			
			$input = new InputFormControl(array(
				'label' => array('for' => $this->get_field_id( 'listing_title' ), 'text' => __( 'Listing title', THEME_NAME )),
				'id' => $this->get_field_id( 'listing_title' ),
				'name' => $this->get_field_name( 'listing_title' ),
				'value' => $listing_title,
				'placeholder' => __('Box listing title', THEME_NAME)
			));

			$output .= $input;

			$select = new SelectFormControl(array(
				'label' => array('for' => $this->get_field_id( 'listing_type' ), 'text' => __( 'Listing type', THEME_NAME )),
				'options' => array(
					'featured' 	=> __('Featured', THEME_NAME),
					'related'	=> __('Related', THEME_NAME),
					'newest'	=> __('Newest', THEME_NAME),
					'most_viewed' => __('Most viewed', THEME_NAME)
				),
				'value' => $listing_type,
				'id' 	=> $this->get_field_id( 'listing_type' ),
				'name' 	=> $this->get_field_name( 'listing_type' )
			));

			$output .= $select;

			$select = new SelectFormControl(array(
				'label' => array('for' => $this->get_field_id( 'order_way' ), 'text' => __( 'Order', THEME_NAME )),
				'options' => array(
					'default' 	=> __('Default', THEME_NAME),
					'random'	=> __('Random', THEME_NAME)
				),
				'value' => $order_way,
				'id' 	=> $this->get_field_id( 'order_way' ),
				'name' 	=> $this->get_field_name( 'order_way' )
			));

			$output .= $select;

			$input = new InputFormControl(array(
				'label' => array('for' => $this->get_field_id( 'items_count' ), 'text' => __( 'Items count', THEME_NAME )),
				'id' => $this->get_field_id( 'items_count' ),
				'name' => $this->get_field_name( 'items_count' ),
				'value' => $items_count
			));
			
			$output .= $input;

			echo $output;
		}
	}
}

if( ! function_exists( 'estetico_register_custom_widgets_batch' ) ) {
	function estetico_register_custom_widgets_batch() {
		register_widget( 'FeaturePropertiesWidget' );
		register_widget( 'QuickSearchWidget' );
		register_widget( 'AgentWidget' );
		register_widget( 'PropertiesFilterWidget' );
		register_widget( 'WP_Widget_Recent_Posts_Extended' );
		register_widget( 'WP_Widget_Recent_Comments_Extended' );
	}
}

add_action( 'widgets_init', 'estetico_register_custom_widgets_batch' );

?>