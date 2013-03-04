<?php

wp_link_pages(array(
	'before' => '<div class="page-link">',
	'after' => '</div>',
	'link_before' => '<span class="page-numbers">',
	'link_after' => '</span>',
	'nextpagelink' => __('Next', 'rockharbor'),
	'previouspagelink' => __('Previous', 'rockharbor'),
));