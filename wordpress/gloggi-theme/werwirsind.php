<?php
/*
Template Name: Wer wir sind
*/
global $post;
$werwirsind_content = wpptd_get_post_meta_value( $post->ID, 'werwirsind-content' );
$werwirsind_trennbanner1 = wpptd_get_post_meta_value( $post->ID, 'werwirsind-separator-banner1');
if( $werwirsind_trennbanner1 ) {
    $werwirsind_trennbanner1 = '<div class="content__big_image_container">' . wp_get_attachment_image( $werwirsind_trennbanner1, array(), false, array( 'class' => 'content__big_image parallax__layer' ) ) . '</div>';
} else $werwirsind_trennbanner1 = '';
$werwirsind_group_title = wpptd_get_post_meta_value( $post->ID, 'werwirsind-group-title');
$werwirsind_trennbanner2 = wpptd_get_post_meta_value( $post->ID, 'werwirsind-separator-banner2');
if( $werwirsind_trennbanner2 ) {
    $werwirsind_trennbanner2 = '<div class="content__big_image_container">' . wp_get_attachment_image( $werwirsind_trennbanner2, array(), false, array( 'class' => 'content__big_image parallax__layer' ) ) . '</div>';
} else $werwirsind_trennbanner2 = '';
// Suche irgendeine Seite die das "index.php"-Template verwendet, und somit ein "Mitmachen"-Formular enthält.
$mitmachen_seite = '/';
$index_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'index.php', ) );
if( count($index_pages) ) {
  $mitmachen_seite = get_the_permalink( $index_pages[0]->ID );
}
// Suche irgendeine Seite die das "agenda.php"-Template verwendet, und somit Anlässe anzeigt.
$agenda_seite = '/';
$agenda_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'agenda.php', ) );
if( count($agenda_pages) ) {
  $agenda_seite = get_the_permalink( $agenda_pages[0]->ID );
}
$abteilungslogo = wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' );


// Funktion um Mailadressen zu verschleiern
function encode_all_to_htmlentities($str) {
  $str = mb_convert_encoding($str , 'UTF-32', 'UTF-8');
  $t = unpack("N*", $str);
  $t = array_map(function($n) { return "&#$n;"; }, $t);
  return implode("", $t);
}
?>
<?php get_template_part('header'); ?>


<?php if( $werwirsind_content ) : ?>
<div class="content__block">
    <p class="wysiwyg"><?php echo $werwirsind_content; ?></p>
</div>
<?php endif; ?>

<?php echo $werwirsind_trennbanner1; ?>

<div class="content__block">
<?php if( $werwirsind_group_title ) : ?>
    <h2 class="heading-2"><?php echo $werwirsind_group_title; ?></h2>
<?php endif; ?>

<div class="groups__container">
<?php

// Lese alle Stufeninfos
$stufen_query = new WP_Query( array( 'post_type' => 'stufe', 'orderby' => array( 'alter-von' => 'ASC', 'alter-bis' => 'ASC'), 'posts_per_page' => -1 ) );
$stufen = array();
while( $stufen_query->have_posts() ) : $stufen_query->the_post();
  $stufeninfos = wpptd_get_post_meta_values( $post->ID );
  if( !$stufeninfos['stufenlogo'] ) {
      $stufeninfos['stufenlogo'] = $abteilungslogo;
  }
  $stufe = array(
    'ID' => $post->ID,
    'name' => get_the_title(),
    'logo' => $stufeninfos['stufenlogo'],
    'farbe' => $stufeninfos['stufenfarbe'],
    'alter-von' => $stufeninfos['alter-von'],
    'alter-bis' => $stufeninfos['alter-bis'],
  );
  $stufen[$post->ID] = $stufe;
endwhile; wp_reset_postdata();

// Lese alle Gruppeninfos
$gruppen_query = new WP_Query( array( 'post_type' => 'gruppe', 'posts_per_page' => -1 ) );
$gruppen = array();
while( $gruppen_query->have_posts() ) : $gruppen_query->the_post();
  $gruppeninfos = wpptd_get_post_meta_values( $post->ID );
  if( !$gruppeninfos['logo'] ) {
      $gruppeninfos['logo'] = $abteilungslogo;
  }
  $gruppe = array(
    'ID' => $post->ID,
    'name' => get_the_title(),
    'linkname' => sanitize_title( get_the_title() ),
    'stufe' => $gruppeninfos['stufe'],
    'farbe' => $gruppeninfos['gruppenfarbe'] ? $gruppeninfos['gruppenfarbe'] : '#539704',
    'logo' => $gruppeninfos['logo'],
    'geschlecht' => $gruppeninfos['geschlecht'],
    'alter-von' => $stufen[$gruppeninfos['stufe']]['alter-von'],
    'alter-bis' => $stufen[$gruppeninfos['stufe']]['alter-bis'],
    'beschreibung' => $gruppeninfos['beschreibung'],
    'einzugsgebiet' => $gruppeninfos['einzugsgebiet'],
    'kontakt-mail' => $gruppeninfos['kontakt-mail'],
    'kontakt-name' => $gruppeninfos['kontakt-name'],
    // Bug im Plugin Post Types Definitely: Felder in einem Repeatable-Feld geben mit wpptd_get_post_meta_values die richtige Anzahl Elemente,
    // aber alle nur mit dem Standardinhalt zurück. Benütze stattdessen für dieses Feld wpptd_get_post_meta_value().
    'nachfolgergruppen' => array_map( function($nfg){ return $nfg['nachfolgergruppe']; }, wpptd_get_post_meta_value( $post->ID, 'nachfolgergruppen' ) ),
    'vorgaengergruppen' => array(),
    'elterngruppe' => wp_get_post_parent_id( $post->ID ),
    'untergruppen' => array(),
    'highlight-bilder' => wpptd_get_post_meta_value( $post->ID, 'highlight-bilder' ),
  );
  $gruppen[$post->ID] = $gruppe;
endwhile; wp_reset_postdata();

// Generiere Vorgängergruppen-Listen
foreach( $gruppen as $gruppe ) :
  foreach( $gruppe['nachfolgergruppen'] as $nachfolger ) :
    $gruppen[$nachfolger]['vorgaengergruppen'][] = $gruppe['ID'];
  endforeach;
endforeach;

// Generiere Untergruppen-Listen
foreach( $gruppen as $gruppe ) :
  if( $gruppe['elterngruppe'] ) :
    $gruppen[$gruppe['elterngruppe']]['untergruppen'][] = $gruppe['ID'];
  endif;
endforeach;

// Generiere HTML
foreach( $stufen as $stufe ) : ?>
<div class="groups__section" style="background-color: <?php echo $stufe['farbe']; ?>;">
  <div class="groups__section-icon">
    <img src="<?php echo wp_get_attachment_url( $stufe['logo'] ); ?>" alt="">
    <div>
      <h3><?php echo $stufe['name']; ?></h3>
      <p><?php echo $stufe['alter-von']; ?> - <?php echo $stufe['alter-bis']; ?> Jahre</p>
    </div>
  </div>

  <div class="groups__section-entries">
<?php foreach( $gruppen as $gruppe ) :
    if( $gruppe['stufe'] != $stufe['ID'] ) continue;
    if( !$gruppe['elterngruppe'] ) : ?>
    <a href="#<?php echo $gruppe['linkname']; ?>" title="<?php echo $gruppe['name']; ?>">
      <div class="groups__entry">
        <div class="circle-medium" style="background-color: <?php echo $gruppe['farbe']; ?>;">
        <img src="<?php echo wp_get_attachment_url( $gruppe['logo'] ); ?>" alt="">
        </div>
        <div class="circle-notification">
          <img src="<?php echo get_bloginfo('template_directory') . '/files/img/' . ($gruppe['geschlecht'] == 'm' ? 'm.svg' : ( $gruppe['geschlecht'] == 'w' ? 'f.svg' : 'b.svg' ) ); ?>" alt="">
        </div>
      </div>
    </a>
<?php endif; ?>
    <div class="lightbox" id="<?php echo $gruppe['linkname']; ?>">
      <a href="#_">
        <div class="lightbox__background"></div>
      </a>
      <div class="lightbox__content-wrapper">
        <div class="lightbox__content">
          <div class="lightbox__banner group__detail-banner">
            <h2 class="heading-2--inverted" <?php if( strlen($gruppe['name']) > 16 ) : ?>style="font-size: calc(600px/<?php echo strlen($gruppe['name']); ?>*1.6);"<?php endif; ?>><?php echo $gruppe['name']; ?></h2>
            <div class="circle-small" style="background-color: <?php echo $gruppe['farbe']; ?>;">
              <img src="<?php echo wp_get_attachment_url( $gruppe['logo'] ); ?>"></img>
            </div>
          </div>
          <div class="lightbox__body">
            <div class="lightbox__section">
              <div class="content__columns content__columns--1-1">
                <div class="content__column">
                  <p class="wysiwyg"><?php echo $gruppe['beschreibung']; ?></p>
                  <a href="<?php echo $mitmachen_seite; ?>#mitmachen" class="button button--small">Mitmachen</a>
                </div>
                <div>
                  <div class="group__info-box">
                    <p><b>Alter:</b> <?php echo $gruppe['alter-von']; ?> - <?php echo $gruppe['alter-bis']; ?> Jahre</p>
                    <p><b>Geschlecht:</b> <?php echo ( $gruppe['geschlecht'] == 'm' ? "Knaben" : ( $gruppe['geschlecht'] == 'w' ? "Mädchen" : "Gemischte Gruppe" ) ); ?></p>
<?php if( $gruppe['einzugsgebiet'] ) : ?>
                    <p><b>Region:</b> <?php echo $gruppe['einzugsgebiet']; ?></p>
<?php endif; ?>
<?php if( $gruppe['elterngruppe'] ) : ?>
                    <p><b>&Uuml;bergeordnete Gruppe:</b> <a href="#<?php echo $gruppen[$gruppe['elterngruppe']]['linkname']; ?>"><?php echo $gruppen[$gruppe['elterngruppe']]['name']; ?></a></p>
<?php endif; ?>
<?php if( $gruppe['untergruppen'] ) : ?>
                    <p><b>Untergruppen:</b></p><ul>
<?php foreach( $gruppe['untergruppen'] as $untergruppe ) : ?>
                      <li><a href="#<?php echo $gruppen[$untergruppe]['linkname']; ?>"><?php echo $gruppen[$untergruppe]['name']; ?></a></li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
<?php if( $gruppe['vorgaengergruppen'] ) : ?>
                    <p><b>Vorgängergruppe:</b></p><ul>
<?php foreach( $gruppe['vorgaengergruppen'] as $vorgaenger ) : ?>
                      <li><a href="#<?php echo $gruppen[$vorgaenger]['linkname']; ?>"><?php echo $gruppen[$vorgaenger]['name']; ?></a></li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
<?php if( $gruppe['nachfolgergruppen'] ) : ?>
                    <p><b>Nachfolgergruppe:</b></p><ul>
<?php foreach( $gruppe['nachfolgergruppen'] as $nachfolger ) : ?>
                      <li><a href="#<?php echo $gruppen[$nachfolger]['linkname']; ?>"><?php echo $gruppen[$nachfolger]['name']; ?></a></li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
                    <p><a class="group__next_event" href="<?php echo $agenda_seite; ?>?gruppe=<?php echo sanitize_title( $gruppe['name'] ); ?>#naechster-anlass">Nächster Anlass</a></p>
                    <p><b>Kontakt:</b> <a href="<?php echo encode_all_to_htmlentities('mailto:' . $gruppe['kontakt-mail']); ?>"><?php echo $gruppe['kontakt-name']; ?></a></p>
                  </div>
<?php if( $gruppe['highlight-bilder'] ) : ?>
                  <div class="group__pictures">
<?php foreach( $gruppe['highlight-bilder'] as $bild ) : ?>
                    <img class="group__picture" alt="<?php echo $bild['beschreibung']; ?>" src="<?php echo wp_get_attachment_url( $bild['bild'] ); ?>">
<?php endforeach; ?>
                  </div>
<?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>

</div>
</div>

<?php echo $werwirsind_trennbanner2; ?>

<?php

// Lese alle Kontaktinfos
$kontakt_query = new WP_Query( array( 'post_type' => 'kontakt', 'orderby' => array( 'menu_order' => 'ASC', 'name' => 'ASC'), 'posts_per_page' => -1 ) );
$kontakte = array();
while( $kontakt_query->have_posts() ) : $kontakt_query->the_post();
  $kontaktinfos = wpptd_get_post_meta_values( $post->ID );
  $kontakt = array(
    'ID' => $post->ID,
    'name' => get_the_title(),
    'email' => $kontaktinfos['email'],
  );
  $kontakte[] = $kontakt;
endwhile; wp_reset_postdata();

if( $kontakt_query->have_posts() ) : ?>
<div class="content__block">
    <h2 class="heading-2">Kontakt</h2>
    <div class="contact__container">
<?php while( $kontakt_query->have_posts() ) : $kontakt_query->the_post();
    $email = wpptd_get_post_meta_value( $post->ID, 'email' ); ?>
        <div class="contact">
            <div class="contact__text">
                <h3><?php echo get_the_title(); ?></h3>
                <p><a href="<?php echo encode_all_to_htmlentities( 'mailto:' . $email ); ?>"><?php echo encode_all_to_htmlentities( $email ); ?></a></p>
            </div>
<?php   $bild_id = wpptd_get_post_meta_value( $post->ID, 'kontaktbild' );
        if( $bild_id ) : ?>
            <img class="contact__image" src="<?php echo wp_get_attachment_url( $bild_id ); ?>" alt="<?php echo get_the_title(); ?>">
<?php   endif; ?>
        </div>
<?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php get_template_part( 'footer' ); ?>
