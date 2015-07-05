<?php
header("Content-Type:text/css");

require_once "./../../../../../wp-load.php";

$color_skin = estetico_get_setting('colour_skin');

if(!isset($_GET['overwrite'])) {

	$color_scheme_file = $color_skin . DIRECTORY_SEPARATOR . 'scheme.php';

	if(file_exists($color_scheme_file)) {
		require( $color_scheme_file );
	} else {
		return;
	}

} else {

	$scheme = array();

	for($i = 1; $i <= 24; $i++) {
		$tmp = 'color_' . $i;
		$scheme[$tmp] = estetico_get_setting($tmp);
	}
}

$header_image = estetico_get_setting('header_image');
$header_image_repeat = estetico_get_setting('header_image_repeat');

if(empty($header_image)) {
	$header_image = $color_skin . '/img/header.jpg';
}

$background_image = estetico_get_setting('background_image');
$background_image_repeat = estetico_get_setting('background_image_repeat');

if(empty($background_image)) {
	$background_image = $color_skin . '/img/bg.jpg';
}

?>
h1, h2, h3, h4, h5, h6,
#searchform label {
	color: <?php echo $scheme['color_1'] ?>;
}

html, body {
	background: <?php if(!empty($background_image)): ?>url(<?php echo $background_image ?>) <?php endif ?> <?php echo $background_image_repeat ?> left top;
}

a.regular,
a,
.infowindow a {
	color: <?php echo $scheme['color_2'] ?>;
}

p a {
	border-bottom: 1px dotted <?php echo $scheme['color_2'] ?>;
}

a:hover {
	text-decoration: none;
	border-bottom: none;
}

.button.th-yellow,
#header nav .current-menu-parent > a,
#header nav .current-menu-parent:hover > a,
#header nav .nav-menu > .current-menu-item > a,
#header nav .nav-menu > .current-menu-item:hover > a {
	color: <?php echo $scheme['color_6'] ?>;
	text-shadow: 1px 1px 0 rgba(255, 255, 255, .5);
	background: <?php echo $scheme['color_3'] ?>;
	background: -webkit-linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	background: -moz-linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	background: -ms-linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	background: linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $scheme['color_4'] ?>', endColorstr='<?php echo $scheme['color_5'] ?>',GradientType=0 );
	box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
	-moz-box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
	-webkit-box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

#header nav .has-children .button.th-yellow,
#header nav .has-children.current-menu-item > a {
	color: <?php echo $scheme['color_6'] ?>;
	text-shadow: 1px 1px 0 rgba(255, 255, 255, .5);
	padding: 9px 24px 9px 15px !important;
	background: <?php echo $scheme['color_4'] ?>;
	background: -webkit-linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	background: -moz-linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	background: -ms-linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	background: linear-gradient(top, <?php echo $scheme['color_4'] ?>, <?php echo $scheme['color_5'] ?>);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $scheme['color_4'] ?>', endColorstr='<?php echo $scheme['color_5'] ?>',GradientType=0 );
	box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
	-moz-box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
	-webkit-box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.searchform #searchsubmit,
.filter-properties-btn,
.button.th-brown,
.button.wpb_th-brown {
	background: <?php echo $scheme['color_7'] ?>;
	background: -webkit-linear-gradient(top, <?php echo $scheme['color_8'] ?>, <?php echo $scheme['color_9'] ?>);
	background: -moz-linear-gradient(top, <?php echo $scheme['color_8'] ?>, <?php echo $scheme['color_9'] ?>);
	background: -ms-linear-gradient(top, <?php echo $scheme['color_8'] ?>, <?php echo $scheme['color_9'] ?>);
	background: linear-gradient(top, <?php echo $scheme['color_8'] ?>, <?php echo $scheme['color_9'] ?>);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $scheme['color_8'] ?>', endColorstr='<?php echo $scheme['color_9'] ?>',GradientType=0 );
}

.button.th-grey {
	background: <?php echo $scheme['color_10'] ?>;
	background: -webkit-linear-gradient(top, <?php echo $scheme['color_11'] ?>, <?php echo $scheme['color_10'] ?>);
	background: -moz-linear-gradient(top, <?php echo $scheme['color_11'] ?>, <?php echo $scheme['color_10'] ?>);
	background: -ms-linear-gradient(top, <?php echo $scheme['color_11'] ?>, <?php echo $scheme['color_10'] ?>);
	background: linear-gradient(top, <?php echo $scheme['color_11'] ?>, <?php echo $scheme['color_10'] ?>);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $scheme['color_11'] ?>', endColorstr='<?php echo $scheme['color_10'] ?>',GradientType=0 );
}

.button.th-red {
	background: <?php echo $scheme['color_12'] ?>;
	background: -webkit-linear-gradient(top, <?php echo $scheme['color_12'] ?>, <?php echo $scheme['color_13'] ?>);
	background: -moz-linear-gradient(top, <?php echo $scheme['color_12'] ?>, <?php echo $scheme['color_13'] ?>);
	background: -ms-linear-gradient(top, <?php echo $scheme['color_12'] ?>, <?php echo $scheme['color_13'] ?>);
	background: linear-gradient(top, <?php echo $scheme['color_12'] ?>, <?php echo $scheme['color_13'] ?>);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $scheme['color_12'] ?>', endColorstr='<?php echo $scheme['color_13'] ?>',GradientType=0 );
}

#header .background {
	background: <?php echo $scheme['color_14'] ?> <?php if(!empty($header_image)) : ?>url(<?php echo $header_image ?>) <?php endif ?> <?php echo $header_image_repeat ?> left top;
}

#header .background:after {
	background: url(<?php echo $color_skin ?>/img/header-line.png) repeat-x left top;
}

#header nav .active {
	color: <?php echo $scheme['color_6'] ?>;
	font-style: italic;
	text-shadow: 1px 1px 0 rgba(255, 255, 255, .5);
}

#header nav li:hover > a {
	background: <?php echo $scheme['color_1'] ?>;
}

#header nav .has-children:hover > a {
	background-color: <?php echo $scheme['color_6'] ?>;
}

#header nav li:hover ul li a {
	-webkit-border-radius: 0;
	border-radius: 0;
	background-color: <?php echo $scheme['color_6'] ?>;
}

#header nav li:hover li:hover > a {
	background-color: <?php echo $scheme['color_23'] ?>;
}

#header .controls .arrow a {
	width: 50px;
	height: 50px;
	background-color: rgba(255, 255, 255, .75);
	background-image: url(../../../core/img/controls-arrows-2.png);
	background-repeat: no-repeat;
	-webkit-border-radius: 100%;
	border-radius: 100%;
	display: block;
}

.quick-search {
	background: <?php echo $scheme['color_15'] ?>;
	background: -webkit-linear-gradient(top, <?php echo $scheme['color_16'] ?>, <?php echo $scheme['color_17'] ?>);
	background: -moz-linear-gradient(top, <?php echo $scheme['color_16'] ?>, <?php echo $scheme['color_17'] ?>);
	background: -ms-linear-gradient(top, <?php echo $scheme['color_16'] ?>, <?php echo $scheme['color_17'] ?>);
	background: linear-gradient(top, <?php echo $scheme['color_16'] ?>, <?php echo $scheme['color_17'] ?>);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $scheme['color_16'] ?>', endColorstr='<?php echo $scheme['color_17'] ?>',GradientType=0 );
	border: 1px <?php echo $scheme['color_21'] ?> solid;
	border-bottom-color: <?php echo $scheme['color_22'] ?>;
}

.quick-search .advanced-search {
	color: <?php echo $scheme['color_1'] ?>;
}

.contact-info a {
	display: block;
	float: left;
	clear: left;
	text-decoration: none;
}

.sidebar ul li a,
.list-style-a a,
.menu-sidebar-menu-container .menu li a {
	color: <?php echo $scheme['color_1'] ?>;
	text-decoration: none;
}

.sidebar ul li:hover:before,
.sidebar ul .active:before,
.list-style-a li:hover:before,
.list-style-a .active:before,
.menu-sidebar-menu-container .menu li:hover:before {
	background: <?php echo $scheme['color_18'] ?>;
	background-image: none;
}

.offer address p {
	padding: 5px 10px;
	max-height: 60px;
	margin: 0;
	color: <?php echo $scheme['color_1'] ?>;
	font-size: 14px;
	line-height: 16px;
}

.list .offer address,
.post h1.entry-title a {
	color: <?php echo $scheme['color_1'] ?>;
}

.box.general .list .offer address,
.box.general .list .offer address a {
	color: <?php echo $scheme['color_1'] ?>;
	line-height: 130%;
	border-bottom: none;
}

.offer a.sidebar-recent-posts {
	color: <?php echo $scheme['color_1'] ?>;
	line-height: 130%;
	display: inline-block;
	padding-bottom: 5px;
	border: none;
}

.offer .prop-info .highlight {
	background: <?php echo $scheme['color_18'] ?>;
	color: <?php echo $scheme['color_1'] ?>;
	border-bottom: none;
	line-height: 18px;
}

.offer .prop-info .highlight span {
	color: <?php echo $scheme['color_1'] ?>;
}

.offers .packed .offer {
	color: <?php echo $scheme['color_1'] ?>;
}

.offers .packed .price {
	background: <?php echo $scheme['color_18'] ?>;
	color: <?php echo $scheme['color_1'] ?>;
}

.property-details .price {
	background: <?php echo $scheme['color_18'] ?>;
}

.testimonials {
	background: #dbd2cb;
}

.testimonials .controls .arrow a {
	background-color: #e9e4e0;
	background-image: url(../core/img/controls-arrows.png);
	background-repeat: no-repeat;
}

.testimonials:after {
	background: url(../core/img/testimonials-curtain.png) repeat-x left top;
}

.warning {
 	background-color: #f8cf36;
}

.error {
 	background-color: <?php echo $scheme['color_12'] ?>;
}

.info {
 	background-color: #53ddf2;
}

.success {
 	background-color: #8ed074;
}

.component-visions {
	color: <?php echo $scheme['color_1'] ?>;
}

#footer {
	background: <?php echo $scheme['color_19'] ?>;
}

#footer .col {
	border-right: 1px <?php echo $scheme['color_20'] ?> solid;
}

#footer .col:first-child {
	border-left: 1px <?php echo $scheme['color_20'] ?> solid;
}

#footer a:hover {
	color: <?php echo $scheme['color_18'] ?>;
}

#footer form .text.dark {
	background: #3c2c23;
	color: #766b63;
}

.entry-image .the-date {
	background: <?php echo $scheme['color_18'] ?>;
}

.entry-thumbnail .the-date {
	background: <?php echo $scheme['color_18'] ?>;
}

.entry-meta a {
	display: inline-block;
}

#main .ui-accordion .ui-accordion-header {
	background: <?php echo $scheme['color_1'] ?>;
}

#main .ui-accordion .ui-state-active {
	background: <?php echo $scheme['color_18'] ?>;
	color: <?php echo $scheme['color_1'] ?>;
}

#main .ui-tabs .ui-corner-top {
	background: <?php echo $scheme['color_1'] ?>;
}

#main .ui-tabs .ui-tabs-active a {
	color: <?php echo $scheme['color_1'] ?>;
}

#main .ui-accordion .ui-accordion-icons a {
	color: #ffffff;
}

.ui-state-active a, 
.ui-state-active a:link, 
.ui-state-active a:visited,
.ui-accordion-header-active a {
	color: <?php echo $scheme['color_1'] ?> !important;
}

.filterbar {
	background: #ded9d5;
}

.filterbar .listing-view-change .active {
	color: <?php echo $scheme['color_2'] ?>;
}

.paging a {
	border: 1px #c3c1bf solid;
}

/*
 * Icons
 */
.icon {
	display: inline-block;
	background: <?php echo $scheme['color_24'] ?>;
}

.icon.custom-icon {
	background: transparent;
	border-radius: 0;
}

/*
 * Properties filter
 */
.search-filters .ui-widget-header {
	background: <?php echo $scheme['color_18'] ?>;
}

@media only screen and (max-width: 1024px) {
	
	.quick-search .filters select {
		color: #4c3b2f;
	}
	
}

/*
 * Custom styling on jQuery UI components
 */
 
#main .ui-tabs .ui-state-hover a,
#main .ui-tabs .ui-tabs-active {
	background: <?php echo $scheme['color_18'] ?>;
}