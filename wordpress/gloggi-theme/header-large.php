<?php global $post; ?>
<?php get_template_part('html-head'); ?>

<div class="header-large">
	<svg xmlns="http://www.w3.org/2000/svg" style="height:0; width:0;"><defs><filter id="shadow"><feDropShadow stdDeviation="4"/></filter></defs></svg>
    <?php echo get_the_post_thumbnail( $post->ID, array(), array('class' => 'header__image-large' ) ); ?>
    <div class="header__banner-large">
        <img class="header__logo-large" src="<?php echo wp_get_attachment_url(wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' )); ?>" alt="" style="filter:url(#shadow)"/>
        <h1 class="header__title"><?php echo wpod_get_option( 'gloggi_einstellungen', 'abteilung'); ?></h1>
    </div>
</div>

<?php get_template_part('navigation'); ?>

<div class="window">
    <div class="content">
