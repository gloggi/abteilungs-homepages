<?php
global $post;
$primaerfarbe = wpod_get_option( 'gloggi_einstellungen', 'primaerfarbe' );
$sekundaerfarbe = wpod_get_option( 'gloggi_einstellungen', 'sekundaerfarbe' );
$banner = get_the_post_thumbnail_url( $post );
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo('name'); if (!is_front_page()) : echo ' | '; wp_title(''); endif; ?></title>
  <style>
    .color-primary, .navbar, #navbar, .button, .button--inactive, .lightbox__banner, .dropdown-menu, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .navbar-right, .group__detail-banner {
      background-color: <?php echo $primaerfarbe ?>;
    }
    a, .agenda__year-agenda li a p {
      color: <?php echo $primaerfarbe; ?>;
    }
    .button--inactive, .agenda__entry-content > a {
      color: <?php echo $primaerfarbe; ?> !important;
    }
    .form-control, .group__info-box, .button:focus, .button--inactive:focus {
      border-color: <?php echo $primaerfarbe; ?>;
    }
    .navbar-toggle:active, .navbar-toggle:hover, .navbar-toggle:focus {
      background-color: <?php echo $primaerfarbe; ?> !important;
    }
    h2, h3, h4 {
      color: <?php echo $primaerfarbe; ?> !important;
    }
    .svg path {
      fill: <?php echo $primaerfarbe; ?> !important;
    }
    
    .color-secondary, ::selection, a:hover, a ::selection, .text--question, .agenda__body b, .navbar-default .navbar-nav > li > a:hover {
      color: <?php echo $sekundaerfarbe; ?>;
    }
    .button:active, .button--inactive:active, .icon-bar, .circle-notification {
      background-color: <?php echo $sekundaerfarbe; ?>;
    }
    .form-control:focus {
      border-color: <?php echo $sekundaerfarbe; ?>;
    }
    .navbar-toggle {
      border-color: <?php echo $sekundaerfarbe; ?> !important;
    }
    <?php if( $banner ) : ?>
    .header__image, .header__image-large {
      background: url('<?php echo $banner; ?>');
    }
    <?php else : ?>
    .header__image, .header__image-large {
      background-color: <?php echo $sekundaerfarbe; ?>;
    }
    <?php endif; ?>
  </style>
  <?php wp_head();?>
</head>
<body>
