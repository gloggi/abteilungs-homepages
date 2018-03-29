<?php

// Automatic updates for our theme
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'http://wp-updates.gloggi.ch/gloggi-theme.json',
	__FILE__,
	'gloggi-theme'
);
add_filter( 'auto_update_theme', '__return_true' );

// Check for our Gloggi Plugin
function gloggi_dependencies() {
  if( ! function_exists('gloggi_dependencies_satisfied') )
    echo '<div class="error"><p>' . __( 'Fehler: Das Gloggi-Theme ben&ouml;tigt das Gloggi-Plugin um zu funktionieren!', 'gloggi' ) . '</p></div>';
}
add_action( 'admin_notices', 'gloggi_dependencies' );

add_theme_support( 'post-thumbnails' );


// Header scripts und stylesheets
function gloggi_scripts() {
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/files/css/bootstrap.min.css' );
  wp_enqueue_style( 'main', get_template_directory_uri() . '/files/css/main.css' );
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/files/js/bootstrap.min.js', array( 'jquery' ) );
  if( is_page_template( 'agenda.php' ) ) {
    wp_enqueue_script( 'agenda', get_template_directory_uri() . '/files/js/agenda.js', array( 'jquery' ) );
    // Gib die Gruppenliste und den Google Maps API key an das Agenda-Skript
    wp_localize_script( 'agenda', 'Groups', get_query_var( 'agenda_gruppen' ) );
    wp_localize_script( 'agenda', 'GmapsAPIKey', gloggi_set_google_maps_api_key() );
  }
}
add_action( 'wp_enqueue_scripts', 'gloggi_scripts' );


// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );
function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );


// NON-Wordpress-related from here on

function gloggi_aggregate_subchildren($subchildren, $root, $visited=array()) {
  $visited[] = $root;
  if( is_array( $subchildren ) && array_key_exists( $root, $subchildren ) ) {
    $children = $subchildren[$root];
    foreach( $children as $child ) {
      if( in_array( $child, $visited ) ) continue;
      $subchildren = gloggi_aggregate_subchildren($subchildren, $child, $visited);
      if( array_key_exists( $child, $subchildren ) ) $subchildren[$root] = array_merge( $subchildren[$root], $subchildren[$child] );
    }
  }
  return $subchildren;
}

function gloggi_display_indexed_event_set($event_set, $index, $title, $agenda_link_prefix='') {
  if( $event_set[$index] && count($event_set[$index]) > 0 ) : ?>
  <div class="lightbox__section"><h3><?php echo $title; ?></h3></div>
  <?php foreach( $event_set[$index] as $event ) : ?>
  <div class="eventslist-list-entry lightbox__section agenda__entry <?php echo $event['anlassgruppen_classes']; ?>" data-starttime="<?php echo $event['startzeit']; ?>">
    <a href="<?php echo $agenda_link_prefix; ?>#agenda-entry-<?php echo $event['ID']; ?>">
      <div class="circle-small color-primary" style="<?php if( $event['anlassfarbe'] ) : echo 'background-color: ' . $event['anlassfarbe'] . ' !important;'; endif; ?>">
        <?php if( $event['anlasslogo'] ) : ?><img src="<?php echo $event['anlasslogo']; ?>" alt=""><?php else: ?><p><?php echo date_format( $event['startzeitpunkt'], 'j.n.y' ); ?></p><?php endif; ?>
      </div>
    </a>
    <div class="agenda__entry-content">
      <a href="<?php echo $agenda_link_prefix; ?>#agenda-entry-<?php echo $event['ID']; ?>">
        <h3><?php echo $event['title']; ?></h3>
        <p class="agenda__date"><?php echo implode(', ', array_filter(array( $event['anlassgruppen'], date_format( $event['startzeitpunkt'], 'j.n.y' ),  ) ) ); ?></p>
        <p><?php echo wp_trim_words( $event['beschreibung'] , 40 ); ?></p>
      </a>
      <a href="<?php echo $agenda_link_prefix; ?>#agenda-entry-<?php echo $event['ID']; ?>">Mehr &gt;&gt;</a>
    </div>
  </div>
  <?php endforeach;
  endif;
}

?>
