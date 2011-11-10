<?php

$img = $theme->Html->image('feature-1.png', array('parent' => true));
if ($link1) {
	$out = "<a id=\"feature-1\" href=\"$link1\">$img</a>";
} else {
	$out = $img;
}

echo $theme->Html->tag('div', $out, array(
	'style' => 'padding: 10px 0'
));

$img = $theme->Html->image('feature-4.png', array('parent' => true));
if ($link2) {
	$out = "<a id=\"feature-4\" href=\"$link2\">$img</a>";
} else {
	$out = $img;
}

echo $theme->Html->tag('div', $out);

?>
<script>
	(function($) {
		var themeBaseHover = '<?php echo $theme->info('url'); ?>/img/';
		var themeBase = '<?php echo $theme->info('base_url'); ?>/img/';
		// some good old fashioned javascript image swapping :/
		$('#feature-1, #feature-4').mouseover(function() {
			var id = $(this).attr('id');
			var src = $(this).children('img').attr('src');
			$(this).children('img').attr('src', src.replace(id, id+'-hover').replace(themeBase, themeBaseHover));
		});
		$('#feature-1, #feature-4').mouseout(function() {
			var id = $(this).attr('id');
			var src = $(this).children('img').attr('src');
			$(this).children('img').attr('src', src.replace(id+'-hover', id).replace(themeBaseHover, themeBase));
		});
	})(jQuery);
</script>