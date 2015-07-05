<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories 		= array();
		$of_categories_obj 	= get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp 	= array_unshift($of_categories, "Select a category:");

		//Access the WordPress Pages via an Array
		$of_pages 			= array();
		$of_pages_obj 		= get_pages('sort_column=post_parent,menu_order');
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp 		= array_unshift($of_pages, "Select a page:");

		//Testing
		$of_options_select 	= array("one","two","three","four","five");
		$of_options_radio 	= array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");

		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		(
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_one"		=> "Block One",
				"block_two"		=> "Block Two",
				"block_three"	=> "Block Three",
			),
			"enabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_four"	=> "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();

		if ( is_dir($alt_stylesheet_path) )
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) )
		    {
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false )
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/images/bg/'; // change this to where you store your bg images
		$bg_images = array();

		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) {
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		            	natsort($bg_images); //Sorts the array into a natural order
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }
		    }
		}


		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/

		//More Options
		$uploads_arr 		= wp_upload_dir();
		$all_uploads_path 	= $uploads_arr['path'];
		$all_uploads 		= get_option('of_uploads');
		$other_entries 		= array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat 		= array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos 			= array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center");

		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post");


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

$of_options[] = array( 	"name"		=> __( "Agents", THEME_NAME ),
						"type"		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __( "Enable direct agents contact", THEME_NAME ),
						"desc" 		=> __( "Enable direct contact form with your agents on a global level. You can enable/disable this option per agent bases if you edit the specific agent profile.", THEME_NAME ),
						"id" 		=> "enable_direct_agents_contact",
						"std" 		=> "1",
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"name" 		=> __( "Message subject", THEME_NAME ),
						"desc" 		=> __( "The subject of the email received by the agent.", THEME_NAME ),
						"id" 		=> "agent_message_subject",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Message format", THEME_NAME ),
						"desc" 		=> __( "Available short tags: %property_name%, %property_link%, %person_name%, %person_phone%, %person_email%, %agent_name%, %person_message%", THEME_NAME ),
						"id" 		=> "agent_message_format",
						"std" 		=> __( "Hello %agent_name%,

Somebody named %person_name% wants to contact you about property %property_name% (%property_link%).

Theirs contact details are:
Name: %person_name%
Phone: %person_phone%
Email: %person_email%

Message: %person_message%", THEME_NAME ),
						"type" 		=> "textarea"
				);

// Properties settings
$of_options[] = array(	"name"		=> __( "Properties", THEME_NAME ),
						"type"		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __( "Enable schema.org", THEME_NAME ),
						"desc" 		=> __( "Add <a href=\"http://www.schema.org/\" target=\"_blank\">schema.org</a> support to improve properties SEO for major seach engines like Google, Bing and Yahoo.", THEME_NAME ),
						"id" 		=> "schemaorg_enabled",
						"std" 		=> false,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"name" 		=> __( "WPML properties", THEME_NAME ),
						"desc" 		=> __( "How to handle missing properties translation if WPML plugin is installed", THEME_NAME ),
						"id" 		=> "handle_missing_wpml_property_translations",
						"std" 		=> "always_show_original",
						"type" 		=> "select",
						"options"	=> array('always_show_original' => __('Always show original', THEME_NAME), 'show_local_copy' => __('Show localized copy if exists', THEME_NAME))
				);

$of_options[] = array( 	"name" 		=> __( "Main properties page", THEME_NAME ),
						"desc" 		=> __( "Defines the page where properties will be listed. Select only one.", THEME_NAME ),
						"id" 		=> "main_properties_page",
						"std" 		=> "",
						"type" 		=> "nested_pages"
				);

$of_options[] = array( 	"name" 		=> __( "Main agents page", THEME_NAME ),
						"desc" 		=> __( "The agencies page will go to this page to filter the agents. It should have a valid Agents template.", THEME_NAME ),
						"id" 		=> "main_agents_page",
						"std" 		=> "",
						"type" 		=> "nested_pages"
				);

$of_options[] = array( 	"name" 		=> __( "Property address format", THEME_NAME ),
						"desc" 		=> __( "Sets the default address format. Available parameters:<br>
							%street_address%, %street_number%, %city%, %state%, %county%, %floor%, %country%, %postal_code%, %apartment_number%<br><br>You can use conditional state 
							if you want to eliminate gaps in the output format.<br><br>Usage:<br><strong>[if %street_address%], %street_adress%[/if]</strong><br><br>or<br><br><strong>[if %street_address%], %street_number%[/if]</strong>", THEME_NAME ),
						"id" 		=> "address_format",
						"std" 		=> "%street_number% %street_address%, %county%, %city%, %postal_code%",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __( "Default properties listing type", THEME_NAME ),
						"desc" 		=> __( "List or Grid layout", THEME_NAME ),
						"id" 		=> "properties_default_listing_type",
						"std" 		=> "grid",
						"type" 		=> "select",
						"options"	=> array( 'grid' => __( 'Grid' ), 'list' => __( 'List' ) )
				);

$of_options[] = array( 	"name" 		=> __( "Properties not found text", THEME_NAME ),
						"desc" 		=> __( "Text customer will see this if no properties are found according to their criteria.", THEME_NAME ),
						"id" 		=> "no_properties_found_text",
						"std" 		=> __( 'There are no properties found.', THEME_NAME ),
						"type" 		=> "text"
				);


$of_options[] = array( 	"name" 		=> __( "Google Maps API key", THEME_NAME ),
						"desc" 		=> __( "Enter your Google Maps API key. Google allows FREE usage up to 25 000 loads per day. If your site exceeds it, you need to apply for Business license.
						<br><br>If you don't have one, visit <a target=\"_blank\" href=\"https://developers.google.com/maps/documentation/javascript/tutorial#api_key\">Google Maps API page</a> for more information.", THEME_NAME ),
						"id" 		=> "google_maps_api_key",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Google Maps API server key", THEME_NAME ),
						"desc" 		=> __( "Enter your Google Maps API server key. This key will be used to give your customer better Google Maps services like geo location auto suggestion. 
							Google allows up to 2 500 calls per day. If your site is exceeding this quota you need to apply for Business license.
							<br><br>If you don't have such, visit <a target=\"_blank\" href=\"https://developers.google.com/maps/documentation/javascript/tutorial#api_key\">Google Maps API page</a> for more information.", THEME_NAME ),
						"id" 		=> "google_maps_api_server_key",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Distances", THEME_NAME ),
						"desc" 		=> __( "A list of comma separated numbers that represent distances of properties in that radius, available for search options.", THEME_NAME ),
						"id" 		=> "distances",
						"std" 		=> "1, 2, 3, 4, 5, 10, 20, 30, 50, 100",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Default distance", THEME_NAME ),
						"desc" 		=> __( "If the customer has chosen a location to search for, but without distance within it, this is the default one.", THEME_NAME ),
						"id" 		=> "default_distance",
						"std" 		=> "20",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Properties per page", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "properties_per_page",
						"std" 		=> 9,
						"type" 		=> "text"
				);

$of_options[] = array(	"name"		=> __( "Measuring units", THEME_NAME ),
						"desc"		=> __( "US or Metric", THEME_NAME ),
						"id"		=> "measuring_units",
						"type"		=> "select",
						"std"		=> "metric",
						"options"	=> array( 'metric' => __( 'Metric' ), 'us' => __( 'US' ) )
				);

$of_options[] = array(	"name"		=> __( "Use property address field", THEME_NAME ),
						"desc"		=> __( "Since theme update to version 1.1, the address field is no longer active. If you have properties before version 1.1, you can leave this option to \"yes\". In that case, you will not be able to take full advantage of the new address fields. ", THEME_NAME ),
						"id"		=> "use_property_address_field",
						"type"		=> "select",
						"std"		=> "yes",
						"options"	=> array( 'no' => __( 'No' ), 'yes' => __( 'Yes' ) )
				);

$of_options[] = array(	"name"		=> __( "Type of pets available", THEME_NAME ),
						"desc"		=> __( "List of the pets allowed in the property. Write each type on new line.", THEME_NAME ),
						"id"		=> "pets_allowed_list",
						"type"		=> "textarea",
						"std"		=> ""
				);

$of_options[] = array(	"name"		=> __( "Mark featured items", THEME_NAME ),
						"desc"		=> __( "Show a flag on each featured item when items are displayed in a list or grid view mode.", THEME_NAME ),
						"id"		=> "mark_featured_items",
						"type"		=> "radio",
						"std"		=> "yes",
						"options"	=> array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) )
				);

$of_options[] = array(	"name"		=> __( 'Show "Recently viewed" block', THEME_NAME ),
						"desc"		=> __( "Show a block of properties that are recently viewed by the customer.", THEME_NAME ),
						"id"		=> "show_recently_viewed",
						"type"		=> "radio",
						"std"		=> "no",
						"options"	=> array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) )
				);

$of_options[] = array(	"name"		=> __( 'Show "Other people also viewed" block', THEME_NAME ),
						"desc"		=> __( "Show a block of properties that are also viewed by other customers.", THEME_NAME ),
						"id"		=> "show_other_people_also_viewed",
						"type"		=> "radio",
						"std"		=> "no",
						"options"	=> array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) )
				);

$of_options[] = array(	"name"		=> __( 'Property details gallery delay', THEME_NAME ),
						"desc"		=> __( "The seconds to wait before the next image appears. Anything but 0 will start an auto slideshow.", THEME_NAME ),
						"id"		=> "property_details_gallery_delay",
						"type"		=> "text",
						"std"		=> "0"
				);

$of_options[] = array( 	"name" 		=> __( "Properties images", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "properties_images",
						"std" 		=> __( "<h3 style=\"margin: 0 0 10px;\">Properties images.</h3>
						This section is to help you upload images faster - straight from your camera, without burdening vistors with the download of huge photos. You can setup all required photo formats. 
						<br><br>Please, have in mind that if you change any of the thumb settings, you have to regenerate all thumbnails. 
						You can install <a href=\"http://wordpress.org/plugins/force-regenerate-thumbnails/\" target=\"_blank\">Force regenerate thumbnails</a> plugin.", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "Gallery thumbnail width", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_gallery_thumbnail_width",
						"std" 		=> 80,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Gallery thumbnail height", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_gallery_thumbnail_height",
						"std" 		=> 60,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Featured thumbnail width", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_featured_thumbnail_width",
						"std" 		=> 100,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Featured thumbnail height", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_featured_thumbnail_height",
						"std" 		=> 73,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Carousel thumbnail width", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_carousel_thumbnail_width",
						"std" 		=> 218,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Carousel thumbnail height", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_carousel_thumbnail_height",
						"std" 		=> 148,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Gallery normal width", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_gallery_normal_width",
						"std" 		=> 650,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Gallery normal height", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_gallery_normal_height",
						"std" 		=> 340,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Property listing width", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_listing_width",
						"std" 		=> 230,
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Property listing height", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "image_properties_listing_height",
						"std" 		=> 167,
						"type" 		=> "text"
				);


// Price settings
$of_options[] = array(	"name"		=> __( "Price", THEME_NAME ),
						"type"		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __( "Currency", THEME_NAME ),
						"desc" 		=> __( "Default currency for properties.<br>Popular currencies: &pound; &euro; $", THEME_NAME ),
						"id" 		=> "currency",
						"std" 		=> "$",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Currency ISO 4217 code", THEME_NAME ),
						"desc" 		=> __( "Not a required field but if you enable schema.org it is good to provide the code here to make your search results more correct.", THEME_NAME ),
						"id" 		=> "currency_iso_code",
						"std" 		=> "USD",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Thousands separator", THEME_NAME ),
						"desc" 		=> __( "Type \"<strong>s</strong>\" (without commas) if you'd like an empty space.", THEME_NAME ),
						"id" 		=> "thousands_separator",
						"std" 		=> "s",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Decimal point", THEME_NAME ),
						"desc" 		=> __( "Type \"<strong>s</strong>\" (without commas) if you'd like an empty space.", THEME_NAME ),
						"id" 		=> "decimal_point",
						"std" 		=> ".",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Currency position", THEME_NAME ),
						"desc" 		=> __( "Where the currency sign should stay. In front of (example: &pound; 12345) or after the end of ( 12345 &pound; ) number", THEME_NAME ),
						"id" 		=> "currency_position",
						"std" 		=> "front",
						"type" 		=> "radio",
						"options"	=> array( 'front' => __( 'Front of number' ), 'end' => __( 'End of number' ) )
				);

$of_options[] = array( 	"name" 		=> __( "Currency and number separator", THEME_NAME ),
						"desc" 		=> __( "The separator between the currency sign and the number.<br>Type \"<strong>s</strong>\" (without commas) if you'd like an empty space.", THEME_NAME ),
						"id" 		=> "currency_number_separator",
						"std" 		=> "s",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Show zeros after decimal for natural numbers", THEME_NAME ),
						"desc" 		=> __( "If price is just a natural number show or hide .00 after it.<br>Example: 555555 or 555555.00", THEME_NAME ),
						"id" 		=> "show_zeros_natural_numbers",
						"std" 		=> "no",
						"type" 		=> "radio",
						"options"	=> array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) )
				);

// Social media settings
$of_options[] = array( 	"name" 		=> __( "Social media", THEME_NAME ),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> "Facebook",
						"desc" 		=> __( "Please enter your Facebook identificator", THEME_NAME ),
						"id" 		=> "social_media_facebook",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "Twitter",
						"desc" 		=> __( "Please enter your Twitter identificator", THEME_NAME ),
						"id" 		=> "social_media_twitter",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "YouTube",
						"desc" 		=> __( "Please enter your Youtube identificator", THEME_NAME ),
						"id" 		=> "social_media_youtube",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "Google Plus",
						"desc" 		=> __( "Please enter your Google Plus identificator", THEME_NAME ),
						"id" 		=> "social_media_google_plus",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "Pinterest",
						"desc" 		=> __( "Please enter your Pinterest identificator", THEME_NAME ),
						"id" 		=> "social_media_pinterest",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> "LinkedIn",
						"desc" 		=> __( "Please enter your <strong>full</strong> LinkedIn url address", THEME_NAME ),
						"id" 		=> "social_media_linkedin",
						"std" 		=> "",
						"type" 		=> "text",
				);

// Contacts settings
$of_options[] = array( 	"name" 		=> __( "Contacts", THEME_NAME ),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __( "Send form to email", THEME_NAME ),
						"desc" 		=> __( "Enter the email address to which you want to recieve the contact form's data. If it is left empty, the administrator's email will be used.", THEME_NAME ),
						"id" 		=> "contacts_recipient",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Office address", THEME_NAME ),
						"desc" 		=> __( "Your office address.", THEME_NAME ),
						"id" 		=> "contacts_address",
						"std" 		=> "",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __( "Phone 1", THEME_NAME ),
						"desc" 		=> __( "The phone number to contact.", THEME_NAME ),
						"id" 		=> "contacts_phone_1",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Phone 2", THEME_NAME ),
						"desc" 		=> __( "The phone number to contact.", THEME_NAME ),
						"id" 		=> "contacts_phone_2",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Email 1", THEME_NAME ),
						"desc" 		=> __( "The email to contact. It will be public (visible).", THEME_NAME ),
						"id" 		=> "contacts_email_1",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Email 2", THEME_NAME ),
						"desc" 		=> __( "The email to contact. It will be public (visible).", THEME_NAME ),
						"id" 		=> "contacts_email_2",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Contact form subject", THEME_NAME ),
						"desc" 		=> __( "The subject of the email", THEME_NAME ),
						"id" 		=> "contacts_subject",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Contact form e-mail format", THEME_NAME ),
						"desc" 		=> __( "You can create your own message by using these placeholders: %first_name%, %last_name%, %phone%, %email%, %ip%, %message%", THEME_NAME ),
						"id" 		=> "contacts_message_format",
						"std" 		=> "A message from: %first_name%: %message%",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __( "Contact form success message", THEME_NAME ),
						"desc" 		=> __( "The customer will see this after sending the form successfully.", THEME_NAME ),
						"id" 		=> "contacts_success",
						"std" 		=> "",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __( "Enable reCAPTCHA", THEME_NAME ),
						"desc" 		=> __( "Enable reCAPTCHA service to protect all your contact forms from spammers.", THEME_NAME ),
						"id" 		=> "recaptcha_enabled",
						"std" 		=> "no",
						"type" 		=> "radio",
						"options"	=> array('yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME))
				);

$of_options[] = array( 	"name" 		=> __( "reCAPTCHA public key", THEME_NAME ),
						"desc" 		=> __( "Enter your public key generated from <a href='http://recaptcha.net/' target='_blank'>http://recaptcha.net/‎</a>‎", THEME_NAME ),
						"id" 		=> "recaptcha_public_key",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "reCAPTCHA private key", THEME_NAME ),
						"desc" 		=> __( "Enter your private key generated from <a href='http://recaptcha.net/' target='_blank'>http://recaptcha.net/‎</a>‎", THEME_NAME ),
						"id" 		=> "recaptcha_private_key",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "reCAPTCHA theme name", THEME_NAME ),
						"desc" 		=> __( "reCAPTACHA allows you to use a specific theme. You can see the available options at <a href=\"https://developers.google.com/recaptcha/docs/customization\" target=\"_blank\">https://developers.google.com/recaptcha/docs/customization</a>‎", THEME_NAME ),
						"id" 		=> "recaptcha_theme",
						"std" 		=> "red",
						"type" 		=> "select",
						"options"	=> array("red" => __('Red', THEME_NAME), 'white' => __('White', THEME_NAME), 'blackglass' => __('Blackglass'), 'clean' => __('Clean', THEME_NAME))
				);

// General settings
$of_options[] = array( 	"name" 		=> __( "General", THEME_NAME ),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __( "Quick search heading", THEME_NAME ),
						"desc" 		=> __( "The heading text for the quick search component", THEME_NAME ),
						"id" 		=> "quick_search_heading",
						"std" 		=> __( "Find your dream home today", THEME_NAME ),
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Enable footer middle section", THEME_NAME ),
						"desc" 		=> __( "By default the footer has three sections. This checkbox controls the middle section.", THEME_NAME ),
						"id" 		=> "enable_footer_middle_section",
						"std" 		=> false,
						"type" 		=> "checkbox"
				);

$of_options[] = array( 	"name" 		=> __( "Middle section title", THEME_NAME ),
						"desc" 		=> __( "The title that will appear on top of the description.", THEME_NAME ),
						"id" 		=> "footer_middle_section_title",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Middle section text", THEME_NAME ),
						"desc" 		=> __( "The footer's middle column free text", THEME_NAME ),
						"id" 		=> "footer_middle_section_text",
						"std" 		=> "",
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __( "Middle section read more link", THEME_NAME ),
						"desc" 		=> __( "A link address the customer can go to.", THEME_NAME ),
						"id" 		=> "footer_middle_section_read_more_link",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Middle section read more text", THEME_NAME ),
						"desc" 		=> __( "A custom link name. \"Read more\" is fine.", THEME_NAME ),
						"id" 		=> "footer_middle_section_read_more_text",
						"std" 		=> "",
						"type" 		=> "text"
				);

$of_options[] = array(         
						"name"      => __( "Social links title", THEME_NAME ),
               		 	"desc"      => __( "The title that will appear on top of the description.", THEME_NAME ),
                		"id"        => "newsletter_title",
                		"std"       => __( "You can find us also", THEME_NAME ),
                		"type"      => "text"
        		);

$of_options[] = array(        
						"name"      => __( "Social links description", THEME_NAME ),
               	 		"desc"      => __( "Describes what customers will get if they go to the social links.", THEME_NAME ),
                		"id"        => "newsletter_description",
                		"std"       => __( "Check our awesome domestic cats in Facebook or Twitter", THEME_NAME ),
                		"type"      => "textarea"
    			);

$of_options[] = array( 	"name" 		=> __( "404 page text", THEME_NAME ),
						"desc" 		=> __( "The text the customers will see when Not found page appears.", THEME_NAME ),
						"id" 		=> "page_not_found_text",
						"std" 		=> __( "The think you are looking for cannot be found.", THEME_NAME ),
						"type" 		=> "textarea"
				);

$of_options[] = array( 	"name" 		=> __( "404 page jump to links", THEME_NAME ),
						"desc" 		=> __( "List the pages your customers can choose to go to from the 404 page", THEME_NAME ),
						"id" 		=> "page_not_found_jump_to_links",
						"std" 		=> "",
						"type" 		=> "nested_pages"
				);

// Styling options
$of_options[] = array( 	"name" 		=> __( "Styling", THEME_NAME ),
						"type" 		=> "heading"
				);

$template_uri = get_template_directory_uri();

$desc = __('Pick one of the predefined colour skins.', THEME_NAME) . '<div style="width:500px;overflow:hidden">';
$desc .= '<div style="float: left;margin-right:5px">' . __('Yellow', THEME_NAME) . '<br><img src="' . $template_uri . '/assets/core/img/wp-admin/colour_skin_thumb_yellow.png" alt="' . __( 'Yellow', THEME_NAME ) . '" title="' . __( 'Yellow', THEME_NAME ) . '"></div>';
$desc .= '<div style="float: left;margin-right:5px">' . __('Green', THEME_NAME) . '<br><img src="' . $template_uri . '/assets/core/img/wp-admin/colour_skin_thumb_green.png" alt="' . __( 'Green', THEME_NAME ) . '" title="' . __( 'Green', THEME_NAME ) . '"></div>';
$desc .= '<div style="float: left;margin-right:5px">' . __('Orange', THEME_NAME) . '<br><img src="' . $template_uri . '/assets/core/img/wp-admin/colour_skin_thumb_orange.png" alt="' . __( 'Orange', THEME_NAME ) . '" title="' . __( 'Orange', THEME_NAME ) . '"></div>';
$desc .= '<div style="float: left;margin-right:5px">' . __('Ocean', THEME_NAME) . '<br><img src="' . $template_uri . '/assets/core/img/wp-admin/colour_skin_thumb_ocean.png" alt="' . __( 'Ocean', THEME_NAME ) . '" title="' . __( 'Ocean', THEME_NAME ) . '"></div>';
$desc .= '<div style="float: left;margin-right:5px">' . __('Forest', THEME_NAME) . '<br><img src="' . $template_uri . '/assets/core/img/wp-admin/colour_skin_thumb_forest.png" alt="' . __( 'Forest', THEME_NAME ) . '" title="' . __( 'Forest', THEME_NAME ) . '"></div>';
$desc .= '</div>';

$of_options[] = array( 	"name" 		=> __( "Colour skin", THEME_NAME ),
						"desc" 		=> $desc,
						"id" 		=> "colour_skin",
						"std" 		=> "yellow",
						"type" 		=> "select",
						"options"	=> array('yellow' => __('Yellow', THEME_NAME), 'green' => __('Green', THEME_NAME), 'orange' => __('Orange', THEME_NAME), 'ocean' => __('Ocean', THEME_NAME), 'forest' => __('Forest', THEME_NAME))
				);

$of_options[] = array( 	"name" 		=> __( "Header configuration", THEME_NAME ),
						"desc" 		=> __( 'Choose header configuration from 4 different configurations. Keep in mind that Revolution slider goes hand in hand with Quick search. See "Flat header image" option below for float image upload.', THEME_NAME ),
						"id" 		=> "header_configuration",
						"std" 		=> "home_slider_inner_flat",
						"type" 		=> "radio",
						"options"	=> array('home_slider_inner_flat' => __('Homepage: Revolution slider, Inner pages: flat image', THEME_NAME), 
											'home_flat_inner_flat' => __('Homepage: flat header, Inner page: flat image', THEME_NAME), 
											'home_slider_inner_slider' => __('Homepage: Revolution slider, Inner pages: Revolution slider', THEME_NAME),
											'home_flat_inner_slider' => __('Homepage: flat image, Inner pages: Revolution slider')
										)
				);

$of_options[] = array( 	"name" 		=> __( "Revolution slider ID", THEME_NAME ),
						"desc" 		=> __( 'Set the ID of the Revolution slider you want to use.', THEME_NAME ),
						"id" 		=> "revslider_id",
						"std" 		=> "homeslider",
						"type" 		=> "text"
				);

$of_options[] = array( 	"name" 		=> __( "Sidebar position", THEME_NAME ),
						"desc" 		=> __( 'Switch the sidebar and the mainbar positions.', THEME_NAME ),
						"id" 		=> "sidebar_position",
						"std" 		=> "left",
						"type" 		=> "radio",
						"options"	=> array( "left" => __('Left', THEME_NAME), 'right' => __('Right'))
				);

$of_options[] = array( 	"name" 		=> __( "Sidebar position on mobile", THEME_NAME ),
						"desc" 		=> __( 'The position of the sidebar when the site is visited on a mobile device - before or after the main content.', THEME_NAME ),
						"id" 		=> "sidebar_position_mobile",
						"std" 		=> "after",
						"type" 		=> "radio",
						"options"	=> array( "before" => __('Before', THEME_NAME), 'after' => __('After'))
				);

/**
 * Returns an array of system fonts
 * Feel free to edit this, update the font fallbacks, etc.
 */
function options_typography_get_os_fonts() {
	// OS Font Defaults
	$os_faces = array(
		'Arial, sans-serif' => 'Arial',
		'"Avant Garde", sans-serif' => 'Avant Garde',
		'Cambria, Georgia, serif' => 'Cambria',
		'Copse, sans-serif' => 'Copse',
		'Garamond, "Hoefler Text", Times New Roman, Times, serif' => 'Garamond',
		'Georgia, serif' => 'Georgia',
		'"Helvetica Neue", Helvetica, sans-serif' => 'Helvetica Neue',
		'Tahoma, Geneva, sans-serif' => 'Tahoma'
	);
	return $os_faces;
}

/**
 * Returns a select list of Google fonts
 * Feel free to edit this, update the fallbacks, etc.
 */
function options_typography_get_google_fonts() {
	// Google Font Defaults
	$google_faces = array(
		'Arvo, serif' => 'Arvo',
		'Copse, sans-serif' => 'Copse',
		'Droid Sans, sans-serif' => 'Droid Sans',
		'Droid Serif, serif' => 'Droid Serif',
		'Lobster, cursive' => 'Lobster',
		'Nobile, sans-serif' => 'Nobile',
		'Open Sans, sans-serif' => 'Open Sans',
		'Oswald, sans-serif' => 'Oswald',
		'Pacifico, cursive' => 'Pacifico',
		'Rokkitt, serif' => 'Rokkit',
		'PT Sans, sans-serif' => 'PT Sans',
		'Quattrocento, serif' => 'Quattrocento',
		'Raleway, cursive' => 'Raleway',
		'Ubuntu, sans-serif' => 'Ubuntu',
		'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
	);
	return $google_faces;
}

$typography_mixed_fonts = array_merge( options_typography_get_os_fonts() , options_typography_get_google_fonts() );
asort($typography_mixed_fonts);

$of_options[] = array( 'name' => __( 'System Fonts and Google Fonts Mixed', THEME_NAME ),
	'desc' => __( 'Google fonts mixed with system fonts.', THEME_NAME ),
	'id' => 'google_mixed',
	'std' => array( 'size' => '12px', 'face' => 'Open Sans, sans-serif', 'color' => '#171717'),
	'type' => 'typography',
	'options' => array(
		'faces' => $typography_mixed_fonts,
		'styles' => false )
	);

$of_options[] = array( 	"name" 		=> __( "Flat header image", THEME_NAME ),
						"desc" 		=> __( "940x220 image for inner pages. Image will be auto cropped.", THEME_NAME ),
						"id" 		=> "inner_pages_header",
						"std" 		=> "",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __( "Logo", THEME_NAME ),
						"desc" 		=> __( "Upload your company's logo", THEME_NAME ),
						"id" 		=> "logo",
						"std" 		=> "",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __( "Background", THEME_NAME ),
						"desc" 		=> __( "Upload a pattern or image for your main background.", THEME_NAME ),
						"id" 		=> "background_image",
						"std" 		=> "",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __( "Background repeat", THEME_NAME ),
						"desc" 		=> __( "Choose the \"repeat\" option if you want your pattern to repeat", THEME_NAME ),
						"id" 		=> "background_image_repeat",
						"std" 		=> "repeat",
						"type" 		=> "radio",
						"options"	=> array('repeat' => __('Repeat', THEME_NAME), 'no-repeat' => __('No repeat', THEME_NAME))
				);

$of_options[] = array( 	"name" 		=> __( "Header background", THEME_NAME ),
						"desc" 		=> __( "Upload a pattern or image for your header background.", THEME_NAME ),
						"id" 		=> "header_image",
						"std" 		=> "",
						"type" 		=> "media"
				);

$of_options[] = array( 	"name" 		=> __( "Header background repeat", THEME_NAME ),
						"desc" 		=> __( "Choose the \"repeat\" option if you want your pattern to repeat", THEME_NAME ),
						"id" 		=> "header_image_repeat",
						"std" 		=> "repeat",
						"type" 		=> "radio",
						"options"	=> array('repeat' => __('Repeat', THEME_NAME), 'no-repeat' => __('No repeat', THEME_NAME))
				);

$of_options[] = array( 	"name" 		=> __( "Show breadcrumbs", THEME_NAME ),
						"desc" 		=> __( "Show/hide breadcrumbs on all pages.", THEME_NAME ),
						"id" 		=> "show_breadcrumbs",
						"std" 		=> "yes",
						"type" 		=> "radio",
						"options"	=> array('yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME))
				);

// Color scheme
$of_options[] = array( 	"name" 		=> __( "Color scheme", THEME_NAME ),
						"type" 		=> "heading"
				);

$colour_skin 	= estetico_get_setting('colour_skin');

require_once get_template_directory() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR . $colour_skin . DIRECTORY_SEPARATOR . 'scheme.php';

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_1",
						"std" 		=> __( "<strong>Important:</strong> If you have <strong>just</strong> changed the skin option, please refresh the page. After the refresh is done, please click the reset button to restore the original colours of the skin (if you have any previous changes). If you don't do that, the theme will still use the old colours.", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "Enable color overwrite", THEME_NAME ),
						"desc" 		=> __( "The colours of the current skin will be overwritten by the ones you specify here", THEME_NAME ),
						"id" 		=> "enable_color_overwrite",
						"std" 		=> "no",
						"type" 		=> "radio",
						"options"	=> array('yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME))
				);

$of_options[] = array(	"name"		=> "",
						"type"		=> 'reset_colors',
						"desc"		=> "The colours will be reset to the skin's default ones. All changes will be automatically saved."
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_1",
						"std" 		=> __( "Headings and top navigation hover style", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "", THEME_NAME ),
						"id" 		=> "color_1",
						"std" 		=> $scheme['color_1'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_1",
						"std" 		=> __( "Links", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "", THEME_NAME ),
						"id" 		=> "color_2",
						"std" 		=> $scheme['color_2'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_1",
						"std" 		=> __( "General buttons and top navigation buttons states.", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Background colour for old browsers", THEME_NAME ),
						"id" 		=> "color_3",
						"std" 		=> $scheme['color_3'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient top colour", THEME_NAME ),
						"id" 		=> "color_4",
						"std" 		=> $scheme['color_4'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient bottom colour", THEME_NAME ),
						"id" 		=> "color_5",
						"std" 		=> $scheme['color_5'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Top navigation hover colour", THEME_NAME ),
						"id" 		=> "color_6",
						"std" 		=> $scheme['color_6'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Top navigation sub level hover colour", THEME_NAME ),
						"id" 		=> "color_23",
						"std" 		=> $scheme['color_23'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Other button styles", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Background colour for old browsers", THEME_NAME ),
						"id" 		=> "color_7",
						"std" 		=> $scheme['color_7'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient top colour", THEME_NAME ),
						"id" 		=> "color_8",
						"std" 		=> $scheme['color_8'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient bottom colour", THEME_NAME ),
						"id" 		=> "color_9",
						"std" 		=> $scheme['color_9'],
						"type" 		=> "color"
				);


$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Other button styles", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);


$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient top colour", THEME_NAME ),
						"id" 		=> "color_10",
						"std" 		=> $scheme['color_10'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient bottom colour", THEME_NAME ),
						"id" 		=> "color_11",
						"std" 		=> $scheme['color_11'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Other button styles", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);


$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient top colour", THEME_NAME ),
						"id" 		=> "color_12",
						"std" 		=> $scheme['color_12'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient bottom colour", THEME_NAME ),
						"id" 		=> "color_13",
						"std" 		=> $scheme['color_13'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Header background colour", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "", THEME_NAME ),
						"id" 		=> "color_14",
						"std" 		=> $scheme['color_14'],
						"type" 		=> "color"
				);


$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Quick search block", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Background colour for old browsers", THEME_NAME ),
						"id" 		=> "color_15",
						"std" 		=> $scheme['color_15'],
						"type" 		=> "color"
				);


$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient top colour", THEME_NAME ),
						"id" 		=> "color_16",
						"std" 		=> $scheme['color_16'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Gradient bottom colour", THEME_NAME ),
						"id" 		=> "color_17",
						"std" 		=> $scheme['color_17'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Border", THEME_NAME ),
						"id" 		=> "color_21",
						"std" 		=> $scheme['color_21'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Border bottom colour", THEME_NAME ),
						"id" 		=> "color_22",
						"std" 		=> $scheme['color_22'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Sidebar navigation hover colour", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);


$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "", THEME_NAME ),
						"id" 		=> "color_18",
						"std" 		=> $scheme['color_18'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Footer", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Background", THEME_NAME ),
						"id" 		=> "color_19",
						"std" 		=> $scheme['color_19'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Columns separator", THEME_NAME ),
						"id" 		=> "color_20",
						"std" 		=> $scheme['color_20'],
						"type" 		=> "color"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "random_id_2",
						"std" 		=> __( "Misc", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "", THEME_NAME ),
						"desc" 		=> __( "Icons main colour", THEME_NAME ),
						"id" 		=> "color_24",
						"std" 		=> $scheme['color_24'],
						"type" 		=> "color"
				);

// Search settings
$of_options[] = array( 	"name" 		=> __( "Search", THEME_NAME ),
						"type" 		=> "heading"
				);


$of_options[] = array( 	"name" 		=> __( "Quick search", THEME_NAME ),
						"desc" 		=> "",
						"id" 		=> "quick_seach_settings_info",
						"std" 		=> __( "<h3 style=\"margin: 0 0 10px;\">Quick search settings.</h3><p>You can set up to 10 search criterias.</p>", THEME_NAME ),
						"icon" 		=> true,
						"type" 		=> "info"
				);

$of_options[] = array( 	"name" 		=> __( "Show quick search", THEME_NAME ),
						"desc" 		=> __( "If you don't want a quick search box to stay over your beautiful revolution slider, you can turn it of.", THEME_NAME ),
						"id" 		=> "show_quick_search",
						"std" 		=> 'yes',
						'options'	=> array('yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME)),
						"type" 		=> "radio"
				);

$of_options[] = array( 	"name" 		=> __( 'Which components to use for Quick search', THEME_NAME ),
						"desc" 		=> __( "Drag and drop components from the left column to the right one to set the kind of fields that will appear on the quick search form. You can also sort their order.", THEME_NAME ),
						"id" 		=> "quick_search_items",
						"std" 		=> '',
						'options'	=> array(
							'type' => __('Type', THEME_NAME), 
							'beds' => __('Beds', THEME_NAME),
							'bedrooms' => __('Bedrooms', THEME_NAME), 
							'bathrooms' => __('Bathrooms', THEME_NAME), 
							'min_price' => __('Min price', THEME_NAME), 
							'max_price' => __('Max price', THEME_NAME),
							'city'		=> __('City', THEME_NAME),
							'feature'	=> __('Feature', THEME_NAME),
							'for_sale_rent' => __('For sale or rent', THEME_NAME),
							'pets_allowed'	=> __('Pets allowed', THEME_NAME)
						),
						"type" 		=> "selectable"
				);

$of_options[] = array( 	"name" 		=> __( 'Which components to use for Properties filter', THEME_NAME ),
						"desc" 		=> __( "Drag and drop components from the left column to the right one to set the kind of fields that will appear on the properties filter widget. You can also sort their order.", THEME_NAME ),
						"id" 		=> "properties_filter_items",
						"std" 		=> '',
						'options'	=> array(
							'beds' 			=> __('Beds', THEME_NAME),
							'city' 			=> __('City', THEME_NAME),
							'for_sale_rent' => __('For sale or rent', THEME_NAME),
                            'property_status' => __('Property status', THEME_NAME),
							'type' 			=> __('Type', THEME_NAME), 
							'location' 		=> __('Location', THEME_NAME),
							'distance' 		=> __('Distance', THEME_NAME),
							'keywords' 		=> __('Keywords', THEME_NAME),
							'price' 		=> __('Price', THEME_NAME),
							'bedrooms' 		=> __('Bedrooms', THEME_NAME), 
							'bathrooms' 	=> __('Bathrooms', THEME_NAME),
							'sq_feet' 		=> __('Sq. Feet', THEME_NAME),
							'pets_allowed'	=> __('Pets allowed', THEME_NAME),
							'feature'		=> __('Feature', THEME_NAME),
							'year_built'	=> __('Year built', THEME_NAME)
						),
						"type" 		=> "selectable"
				);

// Footer settings
$of_options[] = array( 	"name" 		=> __( "Footer", THEME_NAME ),
						"type" 		=> "heading"
				);

$of_options[] = array( 	"name" 		=> __( "Enable footer", THEME_NAME ),
						"desc" 		=> __( "If you don't need the footer section of the theme, you can hide it from here.", THEME_NAME ),
						"id" 		=> "enable_footer",
						"std" 		=> "yes",
						"type" 		=> "radio",
						"options"	=> array('yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME))
				);

$of_options[] = array( 	"name" 		=> __( "RSS Icon", THEME_NAME ),
						"desc" 		=> __( "Show/hide the rss icon and its functionality. This does not turns off Wordpress RSS sharing. Just removes the icon from footer.", THEME_NAME ),
						"id" 		=> "footer_hide_rss",
						"std" 		=> "no",
						"type" 		=> "radio",
						"options"	=> array('yes' => __('Yes', THEME_NAME), 'no' => __('No', THEME_NAME))
				);

$of_options[] = array(         
						"name"      => __( "Copyright text", THEME_NAME ),
               		 	"desc"      => __( "The copyright text to appear at the very bottom of the page in the footer section.", THEME_NAME ),
                		"id"        => "footer_copyright",
                		"std"       => "",
                		"type"      => "textarea"
        		);

$of_options[] = array( 	"name" 		=> __( "Free HTML", THEME_NAME ),
						"desc" 		=> __( "Input custom text, html, css or JavaScript. This code will be placed between the main columns and the copyright text.", THEME_NAME ),
						"id" 		=> "footer_free_html",
						"std" 		=> "",
						"type" 		=> "textarea"
				);

	}//End function: of_options()
}//End chack if function exists: of_options()
?>
