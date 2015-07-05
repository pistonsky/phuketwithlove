<?php

$options = array(
	'post_type' => 'faq',
	'status'	=> 'publish'
);
$faqs = get_posts( $options );

?>

<ul class="faq-item" id="top">
	
	<?php foreach($faqs as $faq): ?>
	<!-- FAQ Item Begin -->
	<li>
		<dl>
			<dd>
				<span class="question-symbol">
					Q:
				</span>
				<div class="question"><?php echo $faq->post_title ?></div>
			</dd>
			<dd>
				<span class="answer-symbol">
					A: 
				</span>
				<div class="answer-content"><?php echo $faq->post_content ?></div>
			</dd>
			<dd>
				<a href="#top" class="regular" title="Back to top">Back to top</a>
			</dd>
		</dl>
	</li>
	<!-- FAQ Item End -->
	<?php endforeach ?>

</ul>