<?php
global $theme;
$response = wp_remote_get('http://graph.facebook.com/'.$user);
$result = json_decode($response['body'], true);
?>

<h2><?php echo $result['likes']; ?> like <?php echo $result['name']; ?></h2>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-href="http://www.facebook.com/<?php echo $user; ?>" data-send="false" data-layout="button_count" data-width="300" data-show-faces="false"></div>