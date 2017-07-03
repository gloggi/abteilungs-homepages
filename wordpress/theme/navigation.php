<nav class="navbar navbar-default" id="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Navigation umschalten</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img class="navbar__logo" src="<?php echo wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' ); ?>" height="50" alt="">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right"><?php
class Walker_Navigation extends Walker_Page {
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $output .= sprintf( '<li class="dropdown"><a href="%s">%s</a>', get_the_permalink( $item ), get_the_title( $item ) );
    }
    function end_el( &$output, $object, $depth = 0, $args = array() ) {
        $output .= '</li>';
    }
    function start_lvl( &$output, $depth = 0, $args = array() )  {
        $output .= '<ul>';
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul>';
    }
}

wp_list_pages(array( 'post_type' => 'page', 'title_li' => null, 'walker' => new Walker_Navigation() ));

?></ul></div>
    </div>
</nav>
