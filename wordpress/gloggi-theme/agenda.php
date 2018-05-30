<?php
/*
Template Name: Agenda
*/
global $post;
$agenda_content = wpptd_get_post_meta_value( $post->ID, 'agenda-content' );
$agenda_noevents = wpptd_get_post_meta_value( $post->ID, 'agenda-noevents' );
$agenda_trennbanner = wpptd_get_post_meta_value( $post->ID, 'agenda-separator-banner1');
if( $agenda_trennbanner ) {
	$agenda_trennbanner = '<div class="content__big_image_container">' . wp_get_attachment_image( $agenda_trennbanner, array(), false, array( 'class' => 'content__big_image parallax__layer' ) ) . '</div>';
} else $agenda_trennbanner = '';
$agenda_jahresplan_title = wpptd_get_post_meta_value( $post->ID, 'agenda-annual-plan-title');
$agenda_jahresplan_content = wpptd_get_post_meta_value( $post->ID, 'agenda-annual-plan-content');
$agenda_trennbanner2 = wpptd_get_post_meta_value( $post->ID, 'agenda-separator-banner2');
if( $agenda_trennbanner2 ) {
	$agenda_trennbanner2 = '<div class="content__big_image_container">' . wp_get_attachment_image( $agenda_trennbanner2, array(), false, array( 'class' => 'content__big_image parallax__layer' ) ) . '</div>';
} else $agenda_trennbanner2 = '';
$agenda_specialevents_title = wpptd_get_post_meta_value( $post->ID, 'agenda-special-events-title');

$standard_anlassverantwortlicher = wpod_get_option( 'gloggi_einstellungen', 'anlassverantwortungs-email' );

$any_specialevents_exist = false;
$specialevents = new WP_Query( array( 'post_type' => 'specialevent', ) );
if( $specialevents->have_posts() ) {
  $any_specialevents_exist = true;
}


// Funktion um Mailadressen zu verschleiern
function encode_all_to_htmlentities($str) {
  $str = mb_convert_encoding($str , 'UTF-32', 'UTF-8');
  $t = unpack("N*", $str);
  $t = array_map(function($n) { return "&#$n;"; }, $t);
  return implode("", $t);
}

function get_annual_plan_for_einheit( $einheit, $einheiten_by_id ) {
  if( $einheit['jahresplan'] ) return array( $einheit['jahresplan'], $einheit['name'] );
  if( $einheit['parent'] === null ) return array( false, null );
  return get_annual_plan_for_einheit( $einheiten_by_id[$einheit['parent']], $einheiten_by_id );
}

// Sammle einige Infos zu Gruppen, Stufen und Abteilung in einem Array
$einheiten = array();
$gruppen = new WP_Query( array( 'post_type' => 'gruppe', 'orderby' => array( 'menu_order' => 'ASC' ), 'posts_per_page' => -1 ) );
while( $gruppen->have_posts() ) : $gruppen->the_post();
  $name = get_the_title();
  $parent = wp_get_post_parent_id( $post->ID );
  if( !$parent || $parent == $post->ID ) $parent = wpptd_get_post_meta_value( $post->ID, 'stufe' );
  $einheiten[] = array(
    'id' => $post->ID,
    'name' => $name,
    'linkname' => sanitize_title( $name ),
    'type' => 'gruppe',
    'parent' => $parent,
    'logo' => wp_get_attachment_url( wpptd_get_post_meta_value( $post->ID, 'logo' ) ),
    'farbe' => wpptd_get_post_meta_value( $post->ID, 'gruppenfarbe' ),
    'jahresplan' => wp_get_attachment_url( wpptd_get_post_meta_value( $post->ID, 'jahresplan' ) ),
  );
endwhile; wp_reset_postdata();
$stufen = new WP_Query( array( 'post_type' => 'stufe', 'orderby' => array( 'alter-von' => 'ASC', 'alter-bis' => 'ASC' ), 'posts_per_page' => -1 ) );
while( $stufen->have_posts() ) : $stufen->the_post();
  $name = get_the_title();
  $einheiten[] = array(
    'id' => $post->ID,
    'name' => $name,
    'linkname' => sanitize_title( $name ),
    'type' => 'stufe',
    'parent' => 0,
    'logo' => wp_get_attachment_url( wpptd_get_post_meta_value( $post->ID, 'stufenlogo' ) ),
    'farbe' => wpptd_get_post_meta_value( $post->ID, 'stufenfarbe' ),
    'jahresplan' => wp_get_attachment_url( wpptd_get_post_meta_value( $post->ID, 'jahresplan' ) ),
  );
endwhile; wp_reset_postdata();
$abteilungsname = wpod_get_option( 'gloggi_einstellungen', 'abteilung' );
$einheiten[] = array( 'id' => 0, 'name' => $abteilungsname, 'linkname' => sanitize_title( $abteilungsname ), 'type' => 'abteilung', 'parent' => null, 'logo' => wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' ), 'jahresplan' => wp_get_attachment_url( wpod_get_option( 'gloggi_einstellungen', 'jahresplan' ) ) );
set_query_var( 'agenda_gruppen', array_map( function($e) { return sanitize_title( $e['name'] ); }, $einheiten ) );

// Ordne das Einheiten-Array auf verschiedene nützliche Arten an
// $einheiten_by_id enthält im key die ID der Einheit (0 steht für die Abteilung) und im value die Angaben zur Einheit (wie in $einheiten).
$einheiten_by_id = array_reduce( $einheiten, function($r, $e) { $r[$e['id']] = $e; return $r; } );
// $einheiten_by_parent enthält im key die ID der Einheit und im value eine Liste von IDs aller direkt untergeordneten Einheiten.
// Nur diejenigen Einheiten die auch tatsächlich untergeordnete Einheiten haben sind in den keys enthalten.
$einheiten_by_parent = array_reduce( $einheiten, function($r, $e) { if( $e['parent'] !== null ) { $r[$e['parent']][] = $e['id']; } return $r; } );
// $subchildren ist dasselbe wie $einheiten_by_parent, aber die value-Listen sind ergänzt durch indirekt untergeordneten Einheiten (also auch Enkel-Gruppen, Urenkel-Gruppen etc.).
// Nur diejenigen Einheiten die auch tatsächlich untergeordnete Einheiten haben sind in den keys enthalten.
$subchildren = gloggi_aggregate_subchildren($einheiten_by_parent, 0);

?>
<?php get_template_part('header'); ?>


<div class="content__block">
  <div class="content__text">
    <p class="wysiwyg"><?php echo $agenda_content; ?></p>
  </div>
  <div class="agenda">
    <div class="agenda__sections">
<?php foreach( $einheiten as $einheit ) : ?>
      <a class="agenda__section button--inactive select" id="<?php echo sanitize_title( $einheit['name'] ); ?>" href="?<?php echo $einheit['type']; ?>=<?php echo sanitize_title( $einheit['name'] ); ?>" data-showclass="<?php echo $einheit['type']; ?>-<?php echo sanitize_title( $einheit['name'] ); ?>"><?php echo $einheit['name']; ?></a>
<?php endforeach; ?>
    </div>
<?php $anlaesse = new WP_Query( array( 'post_type' => 'anlass', 'meta_query' => array( array( 'key' => 'endzeit', 'value' => date( 'YmdHis' ), 'compare' => '>=', ), ), 'posts_per_page' => -1, 'meta_key' => 'startzeit', 'orderby' => 'meta_value_num', 'order' => 'ASC' ) );
$specialevent_anlaesse = array();
if( $anlaesse->have_posts() ) : ?>
  </div>
  <div class="agenda__entries-first" id="naechster-anlass">
  </div>
  <div id="selectgroup"><p>W&auml;hle eine Gruppe aus, um ihre Eintr&auml;ge anzuzeigen.</p></div>
  <div id="noentries" class="hide"><p><?php echo $agenda_noevents; ?></p></div>
  <div class="agenda__entries">
<?php while ( $anlaesse->have_posts() ) : $anlaesse->the_post();
  // Lese alle Angaben zum aktuellen Anlass auf einmal
  $anlassinfos = wpptd_get_post_meta_values( $post->ID );
  // Generiere eine Liste von Gruppen, die gemäss Eintrag im Backend am Anlass teilnehmen (für die Anzeige)
  $anlassgruppen = implode( ', ', array_map( function($g) use ($einheiten_by_id) { return $einheiten_by_id[$g]['name']; }, $anlassinfos['teilnehmende-gruppen'] ) );
  // Generiere eine Liste von sämtlichen Gruppen und ihren (direkten und indirekten) Untergruppen, die gemäss Eintrag im Backend und gemäss Hierarchie am Anlass sein sollten (für die Filterung mit den Buttons)
  $alle_anlassgruppen = array_unique( array_reduce($anlassinfos['teilnehmende-gruppen'], function($r, $e) use($subchildren){ return (array_key_exists($e, $subchildren) ? array_merge($r, $subchildren[$e]) : $r ); }, $anlassinfos['teilnehmende-gruppen'] ) );
  $change = true;
  while( $change ) {
    $change = false;
    foreach( $subchildren as $parent => $sc ) {
      if( !in_array($parent, $alle_anlassgruppen) && count( array_intersect( $alle_anlassgruppen, $sc ) ) == count( $sc ) ) {
        $alle_anlassgruppen[] = $parent;
        $change = true;
      }
    }
  }
  // Generiere aus $alle_anlassgruppen eine Liste von CSS classes für diesen Anlass, die für die Filterung mit den Buttons verwendet wird.
  $anlassgruppen_classes = implode( ' ', array_map( function($g) use ($einheiten_by_id) { return $einheiten_by_id[$g]['type'] . '-' . $einheiten_by_id[$g]['linkname']; }, $alle_anlassgruppen) );
  // Falls genau eine Gruppe im Backend für diesen Anlass eingetragen ist, übernehme deren Gruppenlogo und Gruppenfarbe für den Anlass
  $anlasslogo = null;
  $anlassfarbe = null;
  if( count( $anlassinfos['teilnehmende-gruppen'] ) == 1 ) {
    $anlasslogo = $einheiten_by_id[$anlassinfos['teilnehmende-gruppen'][0]]['logo'];
    $anlassfarbe = $einheiten_by_id[$anlassinfos['teilnehmende-gruppen'][0]]['farbe'];
  }
  // Anlassverantwortlicher ist der AL, oder wenn im Backend eingetragen eine andere Mailadresse.
  $anlassverantwortlicher = $standard_anlassverantwortlicher;
  $anlassverantwortlicher_email = get_the_author_meta( 'user_email' );
  $anlassverantwortlicher_name = get_the_author_meta( 'user_email' );
  if( $anlassverantwortlicher_email ) $anlassverantwortlicher = $anlassverantwortlicher_email;
  $anlassverantwortlicher_name = get_the_author_meta( 'nickname' );
  if( !$anlassverantwortlicher_name ) $anlassverantwortlicher_name = $anlassverantwortlicher;
  // Bereite diverse Zeit- und Ortfelder für die Anzeige vor
  $startzeitpunkt = date_create_from_format( 'YmdHis', $anlassinfos['startzeit'] );
  $endzeitpunkt = date_create_from_format( 'YmdHis', $anlassinfos['endzeit'] );
  $startort = wpptd_get_post_meta_values( $anlassinfos['startort'] );
  $startort['name'] = get_the_title( $anlassinfos['startort'] );
  $startzeitundort = date_format( $startzeitpunkt, 'd.m.Y H:i' ) . ' ' . $startort['name'];
  $endort = $startort;
  $endzeitundort = date_format( $endzeitpunkt, 'd.m.Y H:i' );
  if( $anlassinfos['endort'] && $anlassinfos['endort'] != $anlassinfos['startort'] ) {
    $endort = wpptd_get_post_meta_values( $anlassinfos['endort'] );
    $endort['name'] = get_the_title( $anlassinfos['endort'] );
    $endzeitundort .= ' ' . $endort['name'];
  }
  // Ein Anlass kann zu einem Special Event zugeordnet sein
  $specialevent_titel = "";
  $specialevent_pluralname = "";
  if( $anlassinfos['is-specialevent'] ) {
    $specialevent_titel = get_the_title( $anlassinfos['specialevent'] );
    $specialevent_pluralname = wpptd_get_post_meta_value( $anlassinfos['specialevent'], 'pluralname' );
    $specialevent_anlass = $anlassinfos;
    $specialevent_anlass['anlassgruppen_classes'] = $anlassgruppen_classes;
    $specialevent_anlass['ID'] = $post->ID;
    $specialevent_anlass['anlassfarbe'] = $anlassfarbe;
    $specialevent_anlass['anlasslogo'] = $anlasslogo;
    $specialevent_anlass['startzeitpunkt'] = $startzeitpunkt;
    $specialevent_anlass['title'] = get_the_title();
    $specialevent_anlass['anlassgruppen'] = $anlassgruppen;
    $specialevent_anlaesse[$specialevent_titel][] = $specialevent_anlass;
  }
  // Bug im Plugin Post Types Definitely: Repeatable-Felder geben mit wpptd_get_post_meta_values die richtige Anzahl Elemente,
  // aber alle nur mit dem Standardinhalt zurück. Benütze stattdessen für dieses Feld wpptd_get_post_meta_value().
  $downloads = wpptd_get_post_meta_value( $post->ID, 'downloads' );
?>
    <div class="agenda__entry <?php echo $anlassgruppen_classes; ?>" data-starttime="<?php echo $anlassinfos['startzeit']; ?>">
      <a href="#agenda-entry-<?php echo $post->ID; ?>">
        <div class="circle-small color-primary" style="<?php if( $anlassfarbe ) : echo 'background-color: ' . $anlassfarbe . ' !important;'; endif; ?>">
          <?php if( $anlasslogo ) : ?><img src="<?php echo $anlasslogo; ?>" alt=""><?php else: ?><p><?php echo date_format( $startzeitpunkt, 'j.n.y' ); ?></p><?php endif; ?>
        </div>
      </a>
      <div class="agenda__entry-content">
        <a href="#agenda-entry-<?php echo $post->ID; ?>">
          <h3><?php echo get_the_title(); ?></h3>
          <p class="agenda__date"><?php echo implode(', ', array_filter(array( $anlassgruppen, date_format( $startzeitpunkt, 'j.n.y' ),  ) ) ); ?></p>
          <p><?php echo wp_trim_words( $anlassinfos['beschreibung'] , 40 ); ?></p>
        </a>
        <a href="#agenda-entry-<?php echo $post->ID; ?>">Mehr &gt;&gt;</a>
      </div>
    </div>
    <div class="lightbox" id="agenda-entry-<?php echo $post->ID; ?>">
      <a href="#_">
        <div class="lightbox__background">
        </div>
      </a>
      <div class="lightbox__content-wrapper">
        <div class="lightbox__content agenda__detail">
          <div class="lightbox__banner agenda__header">
            <div class="agenda__header-text">
              <h3><?php echo get_the_title(); ?></h3>
              <p>Start: <?php echo $startzeitundort; ?></p>
<?php if( $startzeitundort != $endzeitundort ) : ?>
              <p>Ende: <?php echo $endzeitundort; ?></p>
<?php endif; ?>
            </div>
            <img src="<?php echo wp_get_attachment_url(wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' )); ?>" height="50" alt="">
          </div>
          <div class="lightbox__body">
            <div class="agenda__map" data-address1="<?php echo $startort['coords']; ?>" data-address2="<?php echo $endort['coords']; ?>">
            </div>
            <div class="agenda__body">
              <div class="lightbox__section"><p class="wysiwyg"><?php echo $anlassinfos['beschreibung']; ?></p></div>
              <div class="lightbox__section"><p class="wysiwyg">Hast du noch Fragen? Dann melde dich bei <a href="<?php echo encode_all_to_htmlentities( 'mailto:' . $anlassverantwortlicher );?>"><?php echo encode_all_to_htmlentities( $anlassverantwortlicher_name );?></a><?php if( $any_specialevents_exist && $anlassinfos['is-specialevent'] ) : ?> oder lies allgemeine Informationen über <a href="#special-event-<?php echo sanitize_title( $specialevent_titel ); ?>"><?php echo $specialevent_pluralname; ?></a><?php endif; ?>.</p></div>
              <div class="lightbox__section">
                <div class="content__two-columns content__columns--1-1">
<?php if( $anlassinfos['mitnehmen'] ) : ?>
                  <div><h4>Mitnehmen</h4><p class="wysiwyg"><?php echo $anlassinfos['mitnehmen']; ?></p></div>
<?php endif; ?>
<?php if( $downloads && count( $downloads ) ) : ?>
                  <div>
                    <h4>Downloads</h4>
                    <ul>
<?php foreach( $downloads as $download ) : ?>
                      <li><a href="<?php echo wp_get_attachment_url( $download['download'] ); ?>"><?php echo $download['name']; ?></a></li>
<?php endforeach; ?>
                    </ul>
                  </div>
<?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php
  endwhile; wp_reset_postdata(); ?>
  </div>
<?php else: ?>
    <p><?php echo $agenda_noevents; ?></p>
  </div>
<?php endif; ?>
</div>

<?php echo $agenda_trennbanner; ?>

<?php
$show_jahresplan_section = false;
if( $agenda_jahresplan_content ) {
	$show_jahresplan_section = true;
}
foreach( $einheiten as $einheit ) {
	if( $einheit['jahresplan'] ) {
		$show_jahresplan_section = true;
		break;
	}
}
if( $show_jahresplan_section ) : ?>
<div id="annualplans" class="content__block">
  <h2 class="heading-2"><?php echo ( $agenda_jahresplan_title ? $agenda_jahresplan_title : __( 'Jahresplan' ) ); ?></h2>
  <div class="content__text"><p class="wysiwyg"><?php echo $agenda_jahresplan_content; ?></p></div>
  <ul class="agenda__year-agenda">
<?php foreach($einheiten_by_id as $einheit) :
  list($jahresplan, $gruppenname) = get_annual_plan_for_einheit( $einheit, $einheiten_by_id );
  if( $jahresplan ) : ?>
    <li class="annualplan <?php echo $einheit['type'] . '-' . $einheit['linkname']; ?>">
      <a href="<?php echo $jahresplan; ?>">
        <img class="agenda__anualplan svg" src="<?php echo get_bloginfo('template_directory'); ?>/files/img/doc.svg" alt="">
        <p><?php echo $einheit['name']; ?></p>
      </a>
    </li>
<?php endif;
endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<?php echo $agenda_trennbanner2; ?>

<?php
$specialevents = new WP_Query( array( 'post_type' => 'specialevent', 'orderby' => array( 'menu_order' => 'ASC' ), 'posts_per_page' => -1 ) );
if( $specialevents->have_posts() ) : ?>
<div class="content__block">
  <h2 class="heading-2"><?php echo $agenda_specialevents_title; ?></h2>
  <ul class="agenda__special-events">
<?php while( $specialevents->have_posts() ) : $specialevents->the_post();
$specialevent_titel = get_the_title(); ?>
    <li>
      <a href="#special-event-<?php echo sanitize_title( get_the_title() ); ?>">
        <div class="circle-medium color-primary">
          <p><?php echo get_the_title(); ?></p>
        </div>
      </a>
    </li>
    <div class="lightbox" id="special-event-<?php echo sanitize_title( get_the_title() ); ?>">
      <a href="#_">
        <div class="lightbox__background">
        </div>
      </a>
      <div class="lightbox__content-wrapper">
        <div class="lightbox__content agenda__detail">
          <div class="lightbox__banner agenda__header">
            <div class="agenda__header-text">
              <h3><?php echo $specialevent_titel; ?></h3>
            </div>
            <img src="<?php echo wp_get_attachment_url(wpod_get_option( 'gloggi_einstellungen', 'abteilungslogo' )); ?>" height="50" alt="">
          </div>
          <div class="lightbox__body agenda__body">
            <div class="lightbox__section"><p class="wysiwyg"><?php echo wpptd_get_post_meta_value( $post->ID, 'description' ); ?></p></div>
            <div class="lightbox__section"><p class="wysiwyg">Hast du noch Fragen? Dann melde dich bei <a href="<?php echo encode_all_to_htmlentities( 'mailto:' . $standard_anlassverantwortlicher );?>"><?php echo encode_all_to_htmlentities( $standard_anlassverantwortlicher );?></a>.</p></div>
            <?php gloggi_display_indexed_event_set($specialevent_anlaesse, $specialevent_titel, "N&auml;chste " . wpptd_get_post_meta_value( $post->ID, 'pluralname' )); ?>
          </div>
        </div>
      </div>
    </div>
<?php endwhile; wp_reset_postdata(); ?>
  </ul>
</div>
<?php endif; ?>
<?php get_template_part( 'footer' ); ?>
