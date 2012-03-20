<?php

$img = $theme->Html->image('feature-1.jpg', array('parent' => false));
if ($link1) {
	$out = "<a id=\"feature-1\" href=\"$link1\">$img</a>";
} else {
	$out = $img;
}

echo $theme->Html->tag('div', $out, array(
	'style' => 'padding: 10px 0'
));

$img = $theme->Html->image('feature-2.jpg', array('parent' => false));
if ($link2) {
	$out = "<a id=\"feature-2\" href=\"$link2\">$img</a>";
} else {
	$out = $img;
}

echo $theme->Html->tag('div', $out);

?>
<script>
	(function($) {
		// some good old fashioned javascript image swapping :/ #YAWPH
		$('#feature-1, #feature-2').mouseover(function() {
			var id = $(this).attr('id');
			var src = $(this).children('img').attr('src');
			$(this).children('img').attr('src', src.replace(id, id+'-hover'));
		});
		$('#feature-1, #feature-2').mouseout(function() {
			var id = $(this).attr('id');
			var src = $(this).children('img').attr('src');
			$(this).children('img').attr('src', src.replace(id+'-hover', id));
		});
	})(jQuery);
</script>