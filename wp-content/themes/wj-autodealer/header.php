<!DOCTYPE html>
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php
  if(defined("AT_DIR")){ 
      $header_data = AT_Registry::get_instance()->get( 'header_data');
      $page_title = $header_data['page_title'];
  } else {
    $page_title = '';	
    if ( ! isset( $content_width ) ) {
      $content_width = 900;
    }
  }
  ?>
  <title><?php echo $page_title; ?></title>
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
  <meta property="og:title" content="<?php echo $page_title; ?>" />
  <meta property="og:url" content="<?php if (is_home()) {print site_url();} else {print get_permalink();} ?>" />
  <?php
    if(AT_Core::get_instance()->get_option('favicon')){
      echo '<link href="'.AT_Core::get_instance()->get_option('favicon').'" rel="icon" type="image/x-icon" />';
    }
  ?>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php AT_Notices::get_frontend_notice(); ?>