<?php
$mailchimp = $theme->options('mailchimp_id');
?>
<div class="newsletter">
    <h3>E-Bulletin</h3>
    <form action="//rockharbor.us4.list-manage.com/subscribe/post?u=185dbb9016568292b89c8731c&amp;id=06151f2612" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
        <input placeholder="Email Address" type="email" value="" name="MERGE0" class="required email" id="mce-EMAIL">
        <?php if (isset($mailchimp) && !empty($mailchimp)): ?><input type="hidden" value="<?php echo $mailchimp; ?>" name="group[405][<?php echo $mailchimp; ?>]"><?php endif; ?>
        <input type="submit" value="Subscribe" id="mc-embedded-subscribe" class="button">
    </form>
</div>
