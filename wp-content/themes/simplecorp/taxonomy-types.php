<?php 
$portfolio_page = of_get_option('sc_portfoliodesc');
$template_name = get_post_meta( $portfolio_page, '_wp_page_template', true );
?>

<?php include ($template_name) ?>