<?php

// Check for our Gloggi Plugin
function gloggi_dependencies() {
  if( ! function_exists('gloggi_dependencies_satisfied') )
    echo '<div class="error"><p>' . __( 'Fehler: Das Gloggi-Theme ben&ouml;tigt das Gloggi-Plugin um zu funktionieren!', 'gloggi' ) . '</p></div>';
}
add_action( 'admin_notices', 'gloggi_dependencies' );

add_theme_support( 'post-thumbnails' );


// Header scripts und stylesheets
function gloggi_scripts() {
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/files/bootstrap.min.css' );
  wp_enqueue_style( 'main', get_template_directory_uri() . '/files/main.css' );
  wp_enqueue_style( 'print', get_template_directory_uri() . '/files/print.css' );
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/files/bootstrap.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'main', get_template_directory_uri() . '/files/main.js', array( 'jquery' ) );
  if( is_page_template( 'agenda.php' ) ) {
    wp_enqueue_script( 'agenda', get_template_directory_uri() . '/files/agenda.js', array( 'jquery' ) );
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


?>
