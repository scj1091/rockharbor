<?php
$date = strtotime($date);
?>
<time datetime="<?php echo date('Y-m-d', $date); ?>"<?php if (isset($pubdate) && $pubdate === true) { echo ' pubdate'; } ?>>
	<span class="month"><?php echo date('F', $date); ?></span>
	<span class="day"><?php echo date('j', $date); ?></span>
	<?php if (date('Y', $date) !== date('Y')): ?>
	<span class="year"><?php echo date('Y', $date); ?></span>
	<?php endif; ?>
</time>