<div class="box">
	<div class="search-filters">

		<?php 

		$default_properties_filter_items = array('type' => 1, 'bedrooms' => 1, 'bathrooms' => 1, 'min_price' => 1, 'max_price' => 1);
		$properties_filter_items = estetico_get_setting( 'properties_filter_items' );

		if( ! is_array($properties_filter_items) || $properties_filter_items == '' ) {
			$properties_filter_items = $default_properties_filter_items;
		}

		$at_least_one = false;
		$properties_filter_items_selected = 0;
		foreach($properties_filter_items as $key => $value) {
			if($value == 1) {
				$at_least_one = true;
				$properties_filter_items_selected++;
			}
		}

		foreach($properties_filter_items as $filter => $filter_enable ) {
			
			if($filter_enable == 0) {
				continue;
			}

		switch($filter) {

		case 'for_sale_rent': ?>

		<div class="filter-group grid-fluid-3">
            <h4><?php echo __('For sale or rent', THEME_NAME) ?></h4>
            <div class="col float-left">
                <input type="radio" name="for_sale_rent" value="sale"<?php if($for_sale_rent == 'sale') : ?> checked="checked"<?php endif ?>><?php echo __( 'Sale', THEME_NAME) ?>
            </div>
            <div class="col float-left">
                <input type="radio" name="for_sale_rent" value="rent"<?php if($for_sale_rent == 'rent') : ?> checked="checked"<?php endif ?>><?php echo __( 'Rent', THEME_NAME) ?>
            </div>
            <div class="col float-left">
                <input type="radio" name="for_sale_rent" value=""<?php if($for_sale_rent == 'both') : ?> checked="checked"<?php endif ?>><?php echo __( 'Both', THEME_NAME) ?>
            </div>
		</div>
        <br class="clear"/>

		<?php

		break;

        case 'property_status': ?>
        
        <div class="filter-group grid-fluid-3">
            <h4><?php echo __('Property status', THEME_NAME) ?></h4>
            <div class="styled-select">
				<select name="property_status">					
                    <option value="">All</option>
					<?php $prop_status = estetico_get_property_status(); 
                    foreach( $prop_status as $_status ) : 
                        if(!empty($_status)) :  
                            $status = str_replace(' ', '_', strtolower($_status)); ?>
                            <option value="<?php echo $status ?>"<?php if(isset($property_status) && $status == $property_status ) : ?> selected="selected"<?php endif ?>><?php echo $_status; ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
				</select>
			</div>
        </div>
        <?php

		break;
        
		case 'city' : ?>

		<div class="filter-group">

			<h4><?php echo __('City', THEME_NAME) ?></h4>
			<div class="styled-select">
				<select name="city">
						<option value="">-</option>
					<?php $cities = estetico_get_all_cities(); foreach( $cities as $_city ) : ?>
						<option value="<?php echo esc_attr( $_city ) ?>"<?php if(isset($city) && $_city == $city ) : ?> selected="selected"<?php endif ?>><?php echo esc_html($_city) ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>

		<?php

		break;

		case 'beds' : ?>

		<div class="filter-group">

			<h4><?php echo __('Beds', THEME_NAME) ?></h4>
			<div class="styled-select">
				<select name="beds">
						<option value="">-</option>
					<?php for( $i = 1; $i <= 20; $i++ ) : ?>
						<option value="<?php echo esc_attr( trim( $i ) ) ?>"<?php if(isset($beds) && $beds == $i ) : ?> selected="selected"<?php endif ?>><?php echo $i ?>+</option>
					<?php endfor ?>
				</select>
			</div>
		</div>

		<?php

		break;

		case 'year_built' : ?>

		<div class="filter-group">

			<h4><?php echo __('Year built', THEME_NAME) ?></h4>
			<div class="slider slider-price" data-min-value="<?php echo esc_attr( $year_built_min_value ) ?>" data-max-value="<?php echo esc_attr( $year_built_max_value ) ?>" data-min="<?php echo esc_attr( $year_built_min ) ?>" data-max="<?php echo esc_attr( $year_built_max ) ?>" data-name="year_built" data-step="1"></div>
			<div class="values">
				<span class="value min"><?php echo esc_attr( $year_built_min ) ?></span>
				<span class="value max"><?php echo esc_attr( $year_built_max ) ?></span>
				<div class="selected-range">
					<span class="value min-value"><?php echo esc_attr( $year_built_min_value ) ?></span>
					-
					<span class="value max-value"><?php echo esc_attr( $year_built_max_value ) ?></span>
				</div>
			</div>
		</div>

		<?php

		break;

		case 'type' : ?>

		<?php if( ! empty( $types ) ) : ?>
		<h4><?php echo __('Type', THEME_NAME) ?></h4>
		<ul class="types">
			<?php foreach( $types as $type ) : ?>

			<?php $continue = true; foreach($types_filtered as $type_filter) {
				$continue = false;
				if($type_filter->slug == $type->slug) {
					$continue = true;
					$type = $type_filter;
					break;
				}
			} ?>

			<?php if($continue == true) : ?>
			<li><input type="checkbox" class="type-holder" <?php if( in_array($type->slug, $types_selected )) : ?> checked="checked"<?php endif ?> value="<?php echo esc_attr( $type->slug ) ?>"><a href="?type=<?php echo $type->slug ?>"><?php echo $type->name ?></a> (<?php echo $type->count ?>)</li>
			<?php endif ?>

			<?php endforeach ?>
		</ul>
		<?php endif ?>

		<?php 

		break;

		case 'feature' : ?>
		
		<?php if( ! empty( $features ) ) : ?>
		<h4><?php echo __('Features', THEME_NAME) ?></h4>
		<ul class="features">
			<?php foreach( $features as $feature ) : ?>

			<?php $continue = true; foreach($features_filtered as $feature_filter) {
				$continue = false;
				if($feature_filter->slug == $feature->slug) {
					$continue = true;
					$feature = $feature_filter;
					break;
				}
			} ?>

			<?php if($continue == true) : ?>
			<li><input type="checkbox" class="feature-holder" <?php if( in_array($feature->slug, $features_selected )) : ?> checked="checked"<?php endif ?> value="<?php echo esc_attr( $feature->slug ) ?>"><a href="?type=<?php echo $feature->slug ?>"><?php echo $feature->name ?></a> (<?php echo $feature->count ?>)</li>
			<?php endif ?>

			<?php endforeach ?>
		</ul>
		<?php endif ?>

		<?php

		break;

		case 'location' : ?>

		<div class="filter-group">
			<h4><?php echo __( 'Location', THEME_NAME ) ?></h4>
			<input type="text" name="location" class="property-location-filter" value="<?php echo esc_attr( isset($location) ? $location : '' ) ?>" placeholder="<?php echo esc_attr( __('Type a start location', THEME_NAME ) ) ?>">
			<input type="hidden" name="location_latitude" class="property-location-latitude" value="<?php echo isset($location_latitude) ? $location_latitude : '' ?>">
			<input type="hidden" name="location_longitude" class="property-location-longitude" value="<?php echo isset($location_longitude) ? $location_longitude : '' ?>">
		</div>

		<?php 

		break; 

		case 'distance' : ?>

		<div class="filter-group">
			<h4><?php echo __( 'Distance', THEME_NAME) ?></h4>
			<div class="styled-select">
				<select name="distance">
						<option value="">-</option>
					<?php foreach( $distances_config as $distance_available ) : ?>
						<option value="<?php echo esc_attr( trim( $distance_available ) ) ?>"<?php if(isset($distance) && $distance == $distance_available ) : ?> selected="selected"<?php endif ?>><?php echo sprintf( __('Within %d miles', THEME_NAME ), $distance_available ) ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>

		<?php 

		break;

		case 'keywords' : ?>

		<div class="filter-group">
			<h4><?php echo __( 'Keywords', THEME_NAME ) ?></h4>
			<input type="text" name="type" placeholder="keywords" class="property-filter-keywords" value="<?php echo esc_attr( isset( $keywords ) ? $keywords : '' ) ?>">
		</div>

		<?php 

		break;

		case 'price' : ?>

		<div class="filter-group">

			<h4><?php echo __('Price range', THEME_NAME) ?></h4>
			<div class="slider slider-price" data-min-value="<?php echo esc_attr( $price_min_value ) ?>" data-max-value="<?php echo esc_attr( $price_max_value ) ?>" data-min="<?php echo esc_attr( $price_min ) ?>" data-max="<?php echo esc_attr( $price_max ) ?>" data-name="price" data-step="100"></div>
			<div class="values">
				<span class="value min"><?php echo esc_attr( $price_min ) ?></span>
				<span class="value max"><?php echo esc_attr( $price_max ) ?></span>
				<div class="selected-range">
					<span class="value min-value"><?php echo esc_attr( $price_min_value ) ?></span>
					-
					<span class="value max-value"><?php echo esc_attr( $price_max_value ) ?></span>
				</div>
			</div>
		</div>

		<?php

		break;

		case 'bedrooms' : ?>

		<div class="filter-group">
			<h4><?php echo __('Bedrooms', THEME_NAME) ?></h4>
			<div class="slider slider-bedrooms" data-min-value="<?php echo esc_attr( $bedrooms_min_value ) ?>" data-max-value="<?php echo esc_attr( $bedrooms_max_value ) ?>" data-min="<?php echo esc_attr( $bedrooms_min ) ?>" data-max="<?php echo esc_attr( $bedrooms_max ) ?>" data-name="bedrooms" data-step="1"></div>
			<div class="values">
				<span class="value min"><?php echo esc_attr( $bedrooms_min ) ?></span>
				<span class="value max"><?php echo esc_attr( $bedrooms_max ) ?></span>
				<div class="selected-range">
					<span class="value min-value"><?php echo esc_attr( $bedrooms_min_value ) ?></span>
					-
					<span class="value max-value"><?php echo esc_attr( $bedrooms_max_value ) ?></span>
				</div>
			</div>
		</div>

		<?php

		break;

		case 'bathrooms' : ?>

		<div class="filter-group">
			<h4><?php echo __('Bathrooms', THEME_NAME) ?></h4>
			<div class="slider slider-bathrooms" data-min-value="<?php echo esc_attr( $bathrooms_min_value ) ?>" data-max-value="<?php echo esc_attr( $bathrooms_max_value ) ?>" data-min="<?php echo esc_attr( $bathrooms_min ) ?>" data-max="<?php echo esc_attr( $bathrooms_max ) ?>" data-name="bathrooms" data-step="1"></div>
			<div class="values">
				<span class="value min"><?php echo esc_attr( $bathrooms_min ) ?></span>
				<span class="value max"><?php echo esc_attr( $bathrooms_max ) ?></span>
				<div class="selected-range">
					<span class="value min-value"><?php echo esc_attr( $bathrooms_min_value ) ?></span>
					-
					<span class="value max-value"><?php echo esc_attr( $bathrooms_max_value ) ?></span>
				</div>
			</div>
		</div>

		<?php 

		break;

		case 'sq_feet' : ?>

		<div class="filter-group">
			<h4><?php echo __('Sq. feet', THEME_NAME) ?></h4>
			<div class="slider slider-sqft" data-min-value="<?php echo esc_attr( $sq_feet_min_value ) ?>" data-max-value="<?php echo esc_attr( $sq_feet_max_value ) ?>" data-min="<?php echo esc_attr( $sq_feet_min ) ?>" data-max="<?php echo esc_attr( $sq_feet_max ) ?>" data-name="sq_feet" data-step="1"></div>
			<div class="values">
				<span class="value min"><?php echo esc_attr( $sq_feet_min ) ?></span>
				<span class="value max"><?php echo esc_attr( $sq_feet_max ) ?></span>
				<div class="selected-range">
					<span class="value min-value"><?php echo esc_attr( $sq_feet_min_value ) ?></span>
					-
					<span class="value max-value"><?php echo esc_attr( $sq_feet_max_value ) ?></span>
				</div>
			</div>
		</div>

		<?php

		break;

		case 'pets_allowed' : ?>

		<div class="filter-group  grid-fluid-3">
			<h4><?php echo __('Pets allowed', THEME_NAME) ?></h4>
			<div class="col float-left">
				<input type="radio" name="pets_allowed" value="yes"<?php if($pets_allowed == 'yes') : ?> checked="checked"<?php endif ?>><?php echo __( 'Yes', THEME_NAME) ?>
			</div>
			<div class="col float-left">
				<input type="radio" name="pets_allowed" value="no"<?php if($pets_allowed == 'no') : ?> checked="checked"<?php endif ?>><?php echo __( 'No', THEME_NAME) ?>
			</div>
			<br class="clear"/>
		</div>

		<?php 

		break;

		} // End switch

	} // End foreach

	?>

		<input type="button" value="<?php echo __('Filter properties', THEME_NAME ) ?>" class="filter-properties-btn">
		
		<a href="<?php echo $reset_url ?>" class="reset-filters"><?php echo __('Reset all filters', THEME_NAME) ?></a>
	</div>
</div>