<?php
/*
Template Name: Wer wir sind
*/
global $post;
$werwirsind_content = wpptd_get_post_meta_value( $post->ID, 'werwirsind-content' );
$werwirsind_trennbanner1 = wpptd_get_post_meta_value( $post->ID, 'werwirsind-separator-banner1');
$werwirsind_group_title = wpptd_get_post_meta_value( $post->ID, 'werwirsind-group-title');
$werwirsind_trennbanner2 = wpptd_get_post_meta_value( $post->ID, 'werwirsind-separator-banner2');
// Suche irgendeine Seite die das "index.php"-Template verwendet, und somit ein "Mitmachen"-Formular enthält.
$mitmachen_seite = '/';
$index_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'index.php', ) );
if( count($index_pages) ) {
  $mitmachen_seite = get_the_permalink( $index_pages[0]->ID );
} ?>
<?php get_template_part('header'); ?>

<?php if( $werwirsind_content ) : ?>
<div class="content__block">
    <p><?php echo $werwirsind_content; ?></p>
</div>
<?php endif; ?>

<?php if( $werwirsind_trennbanner1 ) : ?>
<div class="content__big_image_container">
    <img class="content__big_image" src="<?php echo wp_get_attachment_url( $werwirsind_trennbanner1 ); ?>">
</div>
<?php endif; ?>

<div class="content__block">
<?php if( $werwirsind_group_title ) : ?>
    <h2 class="heading-2"><?php echo $werwirsind_group_title; ?></h2>
<?php endif; ?>

<div class="groups__container">
<?php

// Lese alle Stufeninfos
$stufen_query = new WP_Query( array( 'post_type' => 'stufe', 'orderby' => array( 'alter-von' => 'ASC', 'alter-bis' => 'ASC') ) );
$stufen = array();
while( $stufen_query->have_posts() ) : $stufen_query->the_post();
  $stufeninfos = wpptd_get_post_meta_values( $post->ID );
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
$gruppen_query = new WP_Query( array( 'post_type' => 'gruppe' ) );
$gruppen = array();
while( $gruppen_query->have_posts() ) : $gruppen_query->the_post();
  $gruppeninfos = wpptd_get_post_meta_values( $post->ID );
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
    // Bug im Plugin Post Types Definitely: Ein Select-Feld für einen CPT in einem Repeatable-Feld gibt mit wpptd_get_post_meta_values die richtige Anzahl Elemente,
    // aber alle nur mit dem Standardinhalt zurück. Benütze stattdessen für dieses Feld wpptd_get_post_meta_value().
    'nachfolgergruppen' => array_map( function($nfg){ return $nfg['nachfolgergruppe']; }, wpptd_get_post_meta_value( $post->ID, 'nachfolgergruppen' ) ),
    'vorgaengergruppen' => array(),
    'elterngruppe' => wp_get_post_parent_id( $post->ID ),
  );
  $gruppen[$post->ID] = $gruppe;
endwhile; wp_reset_postdata();

// Generiere Vorgängergruppen-Listen
foreach( $gruppen as $gruppe ) :
  foreach( $gruppe['nachfolgergruppen'] as $nachfolger ) :
    $gruppen[$nachfolger]['vorgaengergruppen'][] = $gruppe['ID'];
  endforeach;
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
    <a href="#<?php echo $gruppe['linkname']; ?>">
      <div class="groups__entry">
        <div class="circle-medium" style="background-color: <?php echo $gruppe['farbe']; ?>;">
<?php if( $gruppe['logo'] ) : ?><img src="<?php echo wp_get_attachment_url( $gruppe['logo'] ) ?>" alt=""><?php endif; ?>
        </div>
        <div class="circle-notification">
          <img src="<?php echo get_bloginfo('template_directory') . '/files/' . ($gruppe['geschlecht'] == 'm' ? 'm.svg' : ( $gruppe['geschlecht'] == 'w' ? 'f.svg' : 'b.svg' ) ); ?>" alt="">
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
            <h2 <?php if( strlen($gruppe['name']) > 16 ) : ?>style="font-size: calc(600px/<?php echo strlen($gruppe['name']); ?>*1.6);"<?php endif; ?>><?php echo $gruppe['name']; ?></h2>
<?php if( $gruppe['logo'] ) : ?>
            <div class="circle-small color-white">
              <object id="test" data="<?php echo wp_get_attachment_url( $gruppe['logo'] ); ?>" type="image/svg+xml"></object>
            </div>
<?php endif; ?>
          </div>
          <div class="lightbox__body">
            <div class="lightbox__section">
              <div class="content__columns content__columns--1-1">
                <div class="content__column">
                  <p><?php echo $gruppe['beschreibung']; ?></p>
                  <a href="<?php echo $mitmachen_seite; ?>#mitmachen" class="button button--small">Mitmachen</a>
                </div>
                <div>
                  <div class="group__info-box">
                    <p><b>Alter:</b> <?php echo $gruppe['alter-von']; ?> - <?php echo $gruppe['alter-bis']; ?> Jahre</p>
<?php if( $gruppe['einzugsgebiet'] ) : ?>
                    <p><b>Region:</b> <?php echo $gruppe['einzugsgebiet']; ?></p>
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
                    <p><b>Kontakt:</b> <a>TODO</a></p>
                  </div>
                  <div class="group__pictures">
                    <img class="group__picture" src="./Gloggi_files/field.jpg">
                    
                  </div>
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

<?php if( $werwirsind_trennbanner2 ) : ?>
<div class="content__big_image_container">
  <img class="content__big_image" src="<?php echo wp_get_attachment_url( $werwirsind_trennbanner2 ); ?>">
</div>
<?php endif; ?>

<div class="content__block">
    <h2 class="heading-2">Kontakt</h2>
    <div class="contact">
        <div class="contact__left">
            <h3>Pfadi Lägern</h3>
            <p>info@pfadi-laegern.ch</p><p>

            </p><h3>Wölfli</h3>
            <p>wöflfi@pfadi-laegern.ch</p>

            <h3>Hochwacht</h3>
            <p>hochwacht@pfadi-laegern.ch</p>

            <h3>Atar</h3>
            <p>atar@pfadi-laegern.ch</p>
        </div>
        <div class="contact__right">
            <img src="./Gloggi_files/mountain.jpg">
            <img src="./Gloggi_files/mountain.jpg">
        </div>
    </div>
</div>

<?php get_template_part( 'footer' ); ?>
