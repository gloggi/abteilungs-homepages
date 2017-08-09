    </div>
</div>

<div class="footer">
    <div class="footer__content">
        <div class="footer__column">
            <h3 class="heading--footer"> Gruppen </h3>
            <ul><?php
// Suche irgendeine Seite die das "werwirsind.php"-Template verwendet, und somit Gruppendetail-Ansichten enthält.
$werwirsind_seite = '/wer-wir-sind';
$werwirsind_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'werwirsind.php', ) );
if( count($werwirsind_pages) ) {
  $werwirsind_seite = get_the_permalink( $werwirsind_pages[0]->ID );
}
class Walker_Footer_Groups extends Walker {
    var $db_fields = array( 'parent' => 'post_parent', 'id' => 'ID' );
    var $werwirsind_seite;
    function __construct($werwirsind_seite) {
        $this->werwirsind_seite = $werwirsind_seite;
    }
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $title = get_the_title( $item );
        $output .= '<li><a href="' . $this->werwirsind_seite . '#' . sanitize_title( $title ) . '">' . $title . '</a>';
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

wp_list_pages(array( 'post_type' => 'gruppe', 'title_li' => null, 'walker' => new Walker_Footer_Groups( $werwirsind_seite ) ));

?></ul>
        </div>
        <div class="footer__column">
            <h3 class="heading--footer"> Links </h3>
            <ul>
<?php foreach( wpod_get_option( 'gloggi_einstellungen', 'footer-links' ) as $link ) :?>
                <li> <a href="<?php echo $link['url'];?>"><?php echo $link['name'];?></a></li>
<?php endforeach; ?>
            </ul>
        </div>
        <div class="footer__column">
            <h3 class="heading--footer"> Kontakt </h3>
            <p><?php echo wpod_get_option( 'gloggi_einstellungen', 'footer-contact' ); ?></p>
            <br>
            <p><a>Mehr &gt;&gt;</a></p>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
