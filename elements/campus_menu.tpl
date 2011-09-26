<?php
/**
 * This is hard-coded because the order is defined here, not in the db. Also time.
 */
$reverse = isset($reverse) ? $reverse : false;
?>
<ul class="campus-list">
	<li><a href="http://missionviejo.rockharbor.org" class="mission-viejo">Mission Viejo</a></li>
	<li><a href="http://fullerton.rockharbor.org" class="fullerton">Fullerton</a></li>
	<li><a href="http://huntingtonbeach.rockharbor.org" class="huntington-beach">Huntington Beach</a></li>
	<li><a href="http://rockharbor.org" class="back<?php if ($reverse) { echo ' reverse'; } ?>">Back to ROCKHARBOR.org</a></li>
</ul>