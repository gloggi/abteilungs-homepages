<?php global $post; ?>
<?php get_template_part('html-head'); ?>

<div class="header">
    <img class="header__image-large" src="<?php echo get_the_post_thumbnail_url( $post ); ?>" />
    <div class="header__banner">
        <svg xmlns="http://www.w3.org/2000/svg" style="height:0"><defs><filter id="shadow"><feDropShadow stdDeviation="4"/></filter></defs></svg>
        <img class="header__logo" src="<?php echo wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' ); ?>" alt="" style="filter:url(#shadow)"/>
        <h1 class="header__title"><?php echo wpod_get_option( 'gloggi_einstellungen', 'abteilung'); ?></h1>
    </div>
    <h1 class="header__heading"><?php echo get_the_title( $post ); ?></h1>
</div>

<?php get_template_part('navigation'); ?>

<div class="window">
    <div class="content">
