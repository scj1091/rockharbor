<?php
xdebug_break();
$faqOut = '';
$faqHeader = false;
if ( have_rows( 'faq' ) ) :
    $faqOut .= '<div class="faq-slides">';
    while ( have_rows( 'faq' ) ) : the_row();
        $slideOut = '<div class="faq-slide"><div class="slide-inner">' . PHP_EOL;
        $linkContents = '';
        $faq_image = wp_get_attachment_image( get_sub_field( 'image' ), 'full', 'flase', array( 'class' => 'faq-img' ) );
        $faq_url = get_sub_field( 'url' );
        if ( !empty( $faq_title = get_sub_field( 'title' ) ) ) {
            $faqHeader = true;
            $linkContents .= "<h4>$faq_title &nbsp;</h4>" . PHP_EOL;
        }
        $linkContents .= $faq_image;
        $slideOut = '<div class="faq-slide"><div class="slide-inner">' . PHP_EOL
            . $theme->Html->tag( 'a', $linkContents, array(
            'href' => $faq_url
        )) . '</div></div>';
        $faqOut .= $slideOut . PHP_EOL;
    endwhile;
    $faqOut .= "</div>";
    if ( $faqHeader ):
        $faqTitle = <<<EOT
    <div class="home-title clearfix">
        <div class="icon icon-faq"></div>
        <h2 class="faq-title">Frequently Asked Questions</h2>
    </div>
EOT;
        $faqOut = $faqTitle . PHP_EOL . $faqOut;
    else:
        $faqOut = '<style type="text/css">.faq-slides .slick-prev, .faq-slides .slick-next { top: 40%; }</style>' . PHP_EOL . $faqOut;
    endif;
    echo $faqOut;
    unset( $faqOut );
endif;
?>
