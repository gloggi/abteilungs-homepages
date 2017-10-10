<?php
   /*
   Plugin Name: Gloggi Abteilungshomepages
   Plugin URI: http://gloggi.ch
   Description: Ein Plugin das das Backend der Gloggi-Abteilungshomepages einrichtet. Ben&ouml;tigt die beiden Plugins "Post Types Definitely" und "Options Definitely".
   Version: 1.0
   Author: Cosinus
   License: GPL2
   */

// Automatische Updates fuer das Plugin
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'http://wp-updates.gloggi.ch/gloggi-plugin.json',
	__FILE__,
	'gloggi-abteilungshomepages-plugin'
);
add_filter( 'auto_update_plugin', '__return_true' );

// Post Types Definitely und Options Definitely muessen aktiv sein
add_action( 'admin_init', 'gloggi_dependencies_satisfied' );
function gloggi_dependencies_satisfied() {
  if ( is_admin() && current_user_can( 'activate_plugins' ) &&
     ( !is_plugin_active( 'post-types-definitely/post-types-definitely.php' ) ||
       !is_plugin_active( 'options-definitely/options-definitely.php' ) ) ) {
    add_action( 'admin_notices', 'dependency_missing_notice' );

    deactivate_plugins( plugin_basename( __FILE__ ) ); 

    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }
  }
}
function dependency_missing_notice(){
  ?><div class="error"><p>F&uuml;r die Aktivierung m&uuml;ssen "Post Types Definitely" und "Options Definitely" installiert und aktiviert sein.</p></div><?php
}

/* Stufe */
function gloggi_custom_post_type_stufe( $wpptd ) {
  $labels = array(
    'name' => 'Stufen',
    'singular_name' => 'Stufe',
    'menu_name' => 'Stufen',
    'name_admin_bar' => 'Stufe',
    'archives' => 'Stufen-Archiv',
    'parent_item_colon' => '&Uuml;bergeordnetes Objekt:',
    'all_items' => 'Alle Stufen',
    'add_new_item' => 'Neue Stufe hinzuf&uuml;gen',
    'add_new' => '+ Neu',
    'new_item' => 'Neue Stufe',
    'edit_item' => 'Stufe bearbeiten',
    'update_item' => 'Stufe aktualisieren',
    'view_item' => 'Stufe ansehen',
    'search_items' => 'Stufe suchen',
    'not_found' => 'Nicht gefunden',
    'not_found_in_trash' => 'Nicht im Papierkorb gefunden',
    'featured_image' => 'Titelbild',
    'set_featured_image' => 'Titelbild setzen',
    'remove_featured_image' => 'Titelbild entfernen',
    'use_featured_image' => 'Als Titelbild verwenden',
    'insert_into_item' => 'In Stufe einf&uuml;gen',
    'uploaded_to_this_item' => 'Zu Stufe hochgeladen',
    'items_list' => 'Stufen-Liste',
    'items_list_navigation' => 'Stufen-Liste Navigation',
    'filter_items_list' => 'Stufen-Liste filtern',
  );
  $capabilities = array(
	// Meta-capabilities (which are granted automatically to roles based on context and the primitive capabilities of the role)
	'edit_post' => 'edit_stufe',
	'read_post' => 'read_stufe',
	'delete_post' => 'delete_stufe',
	// Primitive capabilities (which can be granted directly to a role)
	'create_posts' => 'create_stufen',
	'publish_posts' => 'publish_stufen',
	'read' => 'read_stufen',
	'read_private_posts' => 'read_private_stufen',
	'edit_posts' => 'edit_stufen',
	'edit_private_posts' => 'edit_private_stufen',
	'edit_published_posts' => 'edit_published_stufen',
	'edit_others_posts' => 'edit_others_stufen',
	'delete_posts' => 'delete_stufen',
	'delete_private_posts' => 'delete_private_stufen',
	'delete_published_posts' => 'delete_published_stufen',
	'delete_others_posts' => 'delete_others_stufen',
  );
  $wpptd->add_components( array(
    'gloggi_stufen' => array(
      'label' => __( 'Stufen', 'gloggi' ),
      'icon' => 'dashicons-networking',
      'position' => 5,
      'post_types' => array(
        'stufe' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'thumbnail', ),
          /* Permalinks entfernen */
          'public' => false,
          'publicly_queriable' => true,
          'show_ui' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'has_archive' => false,
          'rewrite' => false,
          /* ... Permalinks entfernt. */
          'table_columns' => array(
            'author' => false,
            'comments' => false,
            'date' => false,
            'meta-alter-von' => array( 'sortable' => true, ),
            'meta-alter-bis' => array( 'sortable' => true, ),
            'meta-stufentext' => array( ),
          ),
          'capabilities' => $capabilities,
          'map_meta_cap' => true,
          'metaboxes' => array(
            'reihenfolge' => array(
              'title' => __( 'Sortierung' ),
              'context' => 'side',
              'fields' => array(
                'menu_order' => array(
                  'title' => __( 'Reihenfolge', 'gloggi' ),
                  'type' => 'number',
                ),
              ),
            ),
            'stufeninfos' => array(
              'title' => __( 'Stufeninformationen', 'gloggi' ),
              'fields' => array(
                'alter-von' => array(
                  'title' => __( 'Alter von*', 'gloggi' ),
                  'type' => 'number',
                  'step' => 1,
                  'min' => 4,
                  'max' => 18,
                  'default' => 6,
                  'required' => true,
                ),
                'alter-bis' => array(
                  'title' => __( 'Alter bis*', 'gloggi' ),
                  'type' => 'number',
                  'step' => 1,
                  'min' => 4,
                  'max' => 18,
                  'default' => 11,
                  'required' => true,
                ),
                'stufenfarbe' => array(
                  'title' => __( 'Stufenfarbe*', 'gloggi' ),
                  'type' => 'color',
                  'required' => true,
                ),
                'stufentext' => array(
                  'title' => __( 'Stufentext', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'stufenlogo' => array(
                  'title' => __( 'Stufenlogo*', 'gloggi' ),
                  'type' => 'media',
                  'required' => false,
                ),
                'jahresplan' => array(
                  'title' => __( 'Jahresplan', 'gloggi' ),
                  'type' => 'media',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}


/* Gruppe */
function gloggi_custom_post_type_gruppe( $wpptd ) {
  $labels = array(
    'name' => 'Gruppen',
    'singular_name' => 'Gruppe',
    'menu_name' => 'Gruppen',
    'name_admin_bar' => 'Gruppe',
    'archives' => 'Gruppen-Archiv',
    'attributes' => 'Gruppen-Eigenschaften',
    'parent_item_colon' => '&Uuml;bergeordnete Gruppe:',
    'all_items' => 'Alle Gruppen',
    'add_new_item' => 'Neue Gruppe hinzuf&uuml;gen',
    'add_new' => '+ Neu',
    'new_item' => 'Neue Gruppe',
    'edit_item' => 'Gruppe bearbeiten',
    'update_item' => 'Gruppe aktualisieren',
    'view_item' => 'Gruppe ansehen',
    'view_items' => 'Gruppen ansehen',
    'search_items' => 'Gruppe suchen',
    'not_found' => 'Nicht gefunden',
    'not_found_in_trash' => 'Nicht im Papierkorb gefunden',
    'featured_image' => 'Titelbild',
    'set_featured_image' => 'Titelbild setzen',
    'remove_featured_image' => 'Titelbild entfernen',
    'use_featured_image' => 'Als Titelbild verwenden',
    'insert_into_item' => 'In Gruppe einf&uuml;gen',
    'uploaded_to_this_item' => 'Zu Gruppe hochgeladen',
    'items_list' => 'Gruppen-Liste',
    'items_list_navigation' => 'Gruppen-Liste Navigation',
    'filter_items_list' => 'Gruppen-Liste filtern',
  );
  $wpptd->add_components( array(
    'gloggi_gruppen' => array(
      'label' => __( 'Gruppen', 'gloggi' ),
      'icon' => 'dashicons-groups',
      'position' => 6,
      'post_types' => array(
        'gruppe' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'thumbnail', 'page-attributes', ),
          'hierarchical' => true,
          /* Permalinks entfernen */
          'public' => false,
          'publicly_queriable' => true,
          'show_ui' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'has_archive' => false,
          'rewrite' => false,
          /* ... Permalinks entfernt. */
          'table_columns' => array(
            'author' => false,
            'comments' => false,
            // Aus irgend einem Grund werden die Einträge nicht hierarchisch angezeigt wenn das Datum versteckt wird
            //'date' => false,
            'meta-stufe' => array( 'sortable' => true ),
            'meta-geschlecht' => array( 'sortable' => true ),
          ),
          'metaboxes' => array(
            'gruppeninfos' => array(
              'title' => __( 'Gruppeninformationen', 'gloggi' ),
              'fields' => array(
                'stufe' => array(
                  'title' => __( 'Stufe*', 'gloggi' ),
                  'type' => 'select',
                  'options' => array( 'posts' => 'stufe' ), 
                  'required' => true,
                ),
                'geschlecht' => array(
                  'title' => __( 'Geschlecht*', 'gloggi' ),
                  'type' => 'select',
                  'options' => array(
                    'b' => __( 'Gemischt', 'gloggi' ),
                    'm' => __( 'Knaben', 'gloggi' ),
                    'w' => __( 'M&auml;dchen', 'gloggi' ),
                  ),
                  'required' => true,
                ),
                'logo' => array(
                  'title' => __( 'Gruppenlogo', 'gloggi' ),
                  'type' => 'media',
                ),
                'gruppenfarbe' => array(
                  'title' => __( 'Gruppenfarbe', 'gloggi' ),
                  'type' => 'color',
                ),
                'einzugsgebiet' => array(
                  'title' => __( 'Einzugsgebiet', 'gloggi' ),
                  'type' => 'text',
                ),
                'beschreibung' => array(
                  'title' => __( 'Beschreibung', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'kontakt-mail' => array(
                  'title' => __( 'Kontakt-Mailadresse*', 'gloggi' ),
                  'type' => 'email',
                  'required' => true,
                ),
                'kontakt-name' => array(
                  'title' => __( 'Angezeigter Kontakt-Name*', 'gloggi' ),
                  'type' => 'text',
                  'required' => true,
                ),
                'nachfolgergruppen' => array(
                  'title' => __( 'Alte Kinder wechseln zu...', 'gloggi' ),
                  'type' => 'repeatable',
                  'repeatable' => array(
                    'fields' => array(
                      'nachfolgergruppe' => array(
                        'title' => __( 'Nachfolgergruppe', 'gloggi' ),
                        'type' => 'select',
                        'options' => array( 'posts' => 'gruppe' ),
                      ),
                    ),
                  ),
                ),
                'highlight-bilder' => array(
                  'title' => __( 'Bilder', 'gloggi' ),
                  'type' => 'repeatable',
                  'repeatable' => array(
                    'limit' => 4,
                    'fields' => array(
                      'bild' => array(
                        'title' => __( 'Bild*' ),
                        'type' => 'media',
                        'required' => true,
                      ),
                      'beschreibung' => array(
                        'title' => __( 'Beschreibung' ),
                        'type' => 'text',
                      ),
                    ),
                  ),
                ),
                'jahresplan' => array(
                  'title' => __( 'Jahresplan', 'gloggi' ),
                  'type' => 'media',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}

/* Anlass */
function gloggi_custom_post_type_anlass( $wpptd ) {
  $labels = array(
    'name' => 'Anl&auml;sse',
    'singular_name' => 'Anlass',
    'menu_name' => 'Anl&auml;sse',
    'name_admin_bar' => 'Anlass',
    'archives' => 'Anlass-Archiv',
    'attributes' => 'Anlass-Eigenschaften',
    'parent_item_colon' => '&Uuml;bergeordnetes Objekt:',
    'all_items' => 'Alle Anl&auml;sse',
    'add_new_item' => 'Neuen Anlass hinzuf&uuml;gen',
    'add_new' => '+ Neu',
    'new_item' => 'Neuer Anlass',
    'edit_item' => 'Anlass bearbeiten',
    'update_item' => 'Anlass aktualisieren',
    'view_item' => 'Anlass ansehen',
    'view_items' => 'Anl&auml;sse ansehen',
    'search_items' => 'Anlass suchen',
    'not_found' => 'Nicht gefunden',
    'not_found_in_trash' => 'Nicht im Papierkorb gefunden',
    'featured_image' => 'Titelbild',
    'set_featured_image' => 'Titelbild setzen',
    'remove_featured_image' => 'Titelbild entfernen',
    'use_featured_image' => 'Als Titelbild verwenden',
    'insert_into_item' => 'In Anlass einf&uuml;gen',
    'uploaded_to_this_item' => 'Zu Anlass hochgeladen',
    'items_list' => 'Anlass-Liste',
    'items_list_navigation' => 'Anlass-Liste Navigation',
    'filter_items_list' => 'Anlass-Liste filtern',
  );
  $wpptd->add_components( array(
    'gloggi_anlaesse' => array(
      'label' => __( 'Anl&auml;sse', 'gloggi' ),
      'icon' => 'dashicons-calendar-alt',
      'position' => 8,
      'post_types' => array(
        'anlass' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'thumbnail', ),
          /* Permalinks entfernen */
          'public' => false,
          'publicly_queriable' => true,
          'show_ui' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'has_archive' => false,
          'rewrite' => false,
           /*... Permalinks entfernt. */
          'table_columns' => array(
            'author' => false,
            'comments' => false,
            'date' => false,
            'meta-teilnehmende-gruppen' => array( 'sortable' => true ),
            'meta-startzeit' => array( 'sortable' => true ),
            'meta-startort' => array( 'sortable' => true ),
            'meta-endzeit' => array( 'sortable' => true ),
            'meta-endort' => array( 'sortable' => true ),
          ),
          'metaboxes' => array(
            'anlassinfos' => array(
              'title' => __( 'Anlass-Informationen', 'gloggi' ),
              'fields' => array(
                'beschreibung' => array(
                  'title' => __( 'Beschreibung', 'gloggi' ),
                  'type' => 'textarea',
                ),
                'teilnehmende-gruppen' => array(
                  'title' => __( 'Teilnehmende Gruppen*', 'gloggi' ),
                  'type' => 'multiselect',
                  'options' => array( 'posts' => 'gruppe' ),
                  'required' => true,
                ),
                'anlassverantwortlicher' => array(
                  'title' => __( 'Anlassverantwortlich', 'gloggi' ),
                  'type' => 'email',
                  'description' => __( 'Wenn leer wird beim Anlass der AL angezeigt.', 'gloggi' ),
                ),
                'startzeit' => array(
                  'title' => __( 'Startzeit*', 'gloggi' ),
                  'type' => 'datetime',
                  'required' => true,
                ),
                'startort' => array(
                  'title' => __( 'Startort', 'gloggi' ),
                  'type' => 'map',
                  'store' => 'coords',
                  'default' => 'Museumstrasse 2, 8001 Z&uuml;rich, Schweiz',
                ),
                'endzeit' => array(
                  'title' => __( 'Endzeit', 'gloggi' ),
                  'type' => 'datetime',
                ),
                'endort' => array(
                  'title' => __( 'Endort', 'gloggi' ),
                  'type' => 'map',
                  'store' => 'coords',
                  'default' => 'Museumstrasse 2, 8001 Z&uuml;rich, Schweiz',
                ),
                'mitnehmen' => array(
                  'title' => __( 'Mitnehmen', 'gloggi' ),
                  'type' => 'wysiwyg',
                  'default' => '<ul><li>Pfadiuniform</li><li>Wetterfeste Kleidung</li></ul>',
                ),
                'downloads' => array(
                  'title' => __( 'Downloads', 'gloggi' ),
                  'type' => 'repeatable',
                  'repeatable' => array(
                    'fields' => array(
                      'name' => array(
                        'title' => __( 'Name', 'gloggi' ),
                        'type' => 'text',
                      ),
                      'download' => array(
                        'title' => __( 'Download', 'gloggi' ),
                        'type' => 'media',
                        'store' => 'id',
                      ),
                    ),
                  ),
                ),
                'is-specialevent' => array(
                  'title' => __( 'Special Event', 'gloggi' ),
                  'type' => 'checkbox',
                  'label' => 'Ist ein Event folgender Art...',
                ),
                'specialevent' => array(
                  'title' => __( 'Art', 'gloggi' ),
                  'type' => 'radio',
                  'options' => array( 'posts' => 'specialevent' ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}


/* Kontakt (Abteilungsstab) */
function gloggi_custom_post_type_kontakt( $wpptd ) {
  $labels = array(
    'name' => 'Kontakte',
    'singular_name' => 'Kontakt',
    'menu_name' => 'Kontakte',
    'name_admin_bar' => 'Kontakte',
    'archives' => 'Kontakt-Archiv',
    'attributes' => 'Kontakt-Eigenschaften',
    'parent_item_colon' => '&Uuml;bergeordnetes Objekt:',
    'all_items' => 'Alle Kontakte',
    'add_new_item' => 'Neuen Kontakt hinzuf&uuml;gen',
    'add_new' => '+ Neu',
    'new_item' => 'Neuer Kontakt',
    'edit_item' => 'Kontakt bearbeiten',
    'update_item' => 'Kontakt aktualisieren',
    'view_item' => 'Kontakt ansehen',
    'view_items' => 'Kontakte ansehen',
    'search_items' => 'Kontakte suchen',
    'not_found' => 'Nicht gefunden',
    'not_found_in_trash' => 'Nicht im Papierkorb gefunden',
    'featured_image' => 'Profilbild',
    'set_featured_image' => 'Profilbild setzen',
    'remove_featured_image' => 'Profilbild entfernen',
    'use_featured_image' => 'Als Profilbild verwenden',
    'insert_into_item' => 'Zu Kontakt hinzuf&uuml;gen',
    'uploaded_to_this_item' => 'Zu Kontakt hochgeladen',
    'items_list' => 'Kontakt-Liste',
    'items_list_navigation' => 'Kontakt-Liste Navigation',
    'filter_items_list' => 'Kontakt-Liste filtern',
  );
  $wpptd->add_components( array(
    'gloggi_kontakt' => array(
      'label' => __( 'Kontakte', 'gloggi' ),
      'icon' => 'dashicons-admin-users',
      'position' => 7,
      'post_types' => array(
        'kontakt' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'page-attributes' ),
          /* Permalinks entfernen */
          'public' => false,
          'publicly_queriable' => true,
          'show_ui' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'has_archive' => false,
          'rewrite' => false,
          /* ... Permalinks entfernt. */
          'table_columns' => array(
            'author' => false,
            'comments' => false,
            'date' => false,
            'meta-email' => array( 'sortable' => true ),
          ),
          'metaboxes' => array(
            'kontaktinfos' => array(
              'title' => __( 'Kontakt-Informationen', 'gloggi' ),
              'fields' => array(
                'email' => array(
                  'title' => __( 'E-Mail*', 'gloggi' ),
                  'type' => 'email',
                  'required' => true,
                ),
                'kontaktbild' => array(
                  'title' => __( 'Bild', 'gloggi' ),
                  'type' => 'media',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}


/* Special Event */
function gloggi_custom_post_type_specialevent( $wpptd ) {
  $labels = array(
    'name' => 'Special Events',
    'singular_name' => 'Special Event',
    'menu_name' => 'Special Events',
    'name_admin_bar' => 'Special Events',
    'archives' => 'Special-Event-Archiv',
    'attributes' => 'Special-Event-Eigenschaften',
    'parent_item_colon' => '&Uuml;bergeordnetes Objekt:',
    'all_items' => 'Alle Special Events',
    'add_new_item' => 'Neuen Special Event hinzuf&uuml;gen',
    'add_new' => '+ Neu',
    'new_item' => 'Neuer Special Event',
    'edit_item' => 'Special Event bearbeiten',
    'update_item' => 'Special Event aktualisieren',
    'view_item' => 'Special Event ansehen',
    'view_items' => 'Special Events ansehen',
    'search_items' => 'Special Events suchen',
    'not_found' => 'Nicht gefunden',
    'not_found_in_trash' => 'Nicht im Papierkorb gefunden',
    'featured_image' => 'Event-Bild',
    'set_featured_image' => 'Event-Bild setzen',
    'remove_featured_image' => 'Event-Bild entfernen',
    'use_featured_image' => 'Als Event-Bild verwenden',
    'insert_into_item' => 'Zu Special Event hinzuf&uuml;gen',
    'uploaded_to_this_item' => 'Zu Special Event hochgeladen',
    'items_list' => 'Special-Event-Liste',
    'items_list_navigation' => 'Special-Event-Liste Navigation',
    'filter_items_list' => 'Special-Event-Liste filtern',
  );
  $wpptd->add_components( array(
    'gloggi_specialevent' => array(
      'label' => __( 'Special Events', 'gloggi' ),
      'icon' => plugins_url('tent.png', __FILE__),
      'position' => 9,
      'post_types' => array(
        'specialevent' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'page-attributes' ),
          /* Permalinks entfernen */
          'public' => false,
          'publicly_queriable' => true,
          'show_ui' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'has_archive' => false,
          'rewrite' => false,
          /* ... Permalinks entfernt. */
          'table_columns' => array(
            'author' => false,
            'comments' => false,
            'date' => false,
            'meta-email' => array( 'sortable' => true ),
          ),
          'metaboxes' => array(
            'specialevent' => array(
              'title' => __( 'Special-Event-Informationen', 'gloggi' ),
              'fields' => array(
                'description' => array(
                  'title' => __( 'Beschreibung*', 'gloggi' ),
                  'type' => 'wysiwyg',
                  'required' => true,
                ),
                'kontaktbild' => array(
                  'title' => __( 'Bild', 'gloggi' ),
                  'type' => 'media',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}


/* Page */
function gloggi_custom_page_type( $wpptd ) {
  $labels = array(
    'featured_image' => 'Banner',
    'set_featured_image' => 'Banner setzen',
    'remove_featured_image' => 'Banner entfernen',
    'use_featured_image' => 'Als Banner verwenden',
  );
  $wpptd->add_components( array(
    'gloggi_pages' => array(
      'position' => 10,
      'post_types' => array(
        'page' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'thumbnail' ),
          /* Permalinks entfernen */
          'public' => false,
          'publicly_queriable' => true,
          'show_ui' => true,
          'exclude_from_search' => true,
          'show_in_nav_menus' => false,
          'has_archive' => false,
          'rewrite' => false,
          /* ... Permalinks entfernt. */
          'table_columns' => array(
            'author' => false,
            'comments' => false,
            'date' => false,
          ),
          'metaboxes' => array(
            'index' => array(
              'title' => __( 'Seiteninformationen', 'gloggi' ),
              'fields' => array(
                'index-content1' => array(
                  'title' => __( 'Inhalt 1', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'index-contact-form-fields' => array(
                  'title' => __( 'Mitmachen-Formularfelder', 'gloggi' ),
                  'type' => 'repeatable',
                  'repeatable' => array(
                    'fields' => array(
                      'name' => array(
                        'title' => __( 'Titel', 'gloggi' ),
                        'type' => 'text',
                      ),
                      'type' => array(
                        'title' => __( 'Typ', 'gloggi' ),
                        'type' => 'select',
                        'options' => array(
                          'text' => __( 'Text', 'gloggi' ),
                          'textarea' => __( 'Text (mehrzeilig)', 'gloggi' ),
                          'number' => __( 'Zahl', 'gloggi' ),
                          'email' => __( 'e-Mail-Adresse', 'gloggi' ),
                          'tel' => __( 'Telefonnummer', 'gloggi' ),
                          'gender' => __( 'Geschlecht', 'gloggi' ),
                        ),
                      ),
                      'required' => array(
                        'title' => __( 'Erforderlich?', 'gloggi' ),
                        'type' => 'checkbox',
                        'label' => __( 'Ja', 'gloggi' ),
                      ),
                    ),
                  ),
                ),
                'index-separator-banner' => array(
                  'title' => __( 'Trennbanner', 'gloggi' ),
                  'type' => 'media',
                ),
                'index-content2' => array(
                  'title' => __( 'Inhalt 2', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'index-separator-banner2' => array(
                  'title' => __( 'Trennbanner 2', 'gloggi' ),
                  'type' => 'media',
                ),
                'index-content3' => array(
                  'title' => __( 'Inhalt 3', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
              ),
            ),
            'agenda' => array(
              'title' => __( 'Seiteninformationen "Agenda"', 'gloggi' ),
              'fields' => array(
                'agenda-content' => array(
                  'title' => __( 'Inhalt', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'agenda-noevents' => array(
                  'title' => __( 'Keine Events', 'gloggi' ),
                  'description' => __( 'Falls keine zuk&uuml;nftigen Anl&auml;sse eingetragen sind, wird dieser Text unter dem Inhalt angezeigt.', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'agenda-separator-banner1' => array(
                  'title' => __( 'Trennbanner zwischen Events und Jahrespl&auml;nen', 'gloggi' ),
                  'type' => 'media',
                ),
                'agenda-annual-plan-title' => array(
                  'title' => __( 'Titel Jahresplanabschnitt', 'gloggi' ),
                  'type' => 'text',
                ),
                'agenda-annual-plan-content' => array(
                  'title' => __( 'Text Jahresplanabschnitt', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'agenda-separator-banner2' => array(
                  'title' => __( 'Trennbanner zwischen Jahrespl&auml;nen und Special Events', 'gloggi' ),
                  'type' => 'media',
                ),
                'agenda-special-events-title' => array(
                  'title' => __( 'Titel Special Events', 'gloggi' ),
                  'type' => 'text',
                ),
              ),
            ),
            'waswirtun' => array(
              'title' => __( 'Seiteninformationen "Was wir tun"', 'gloggi' ),
              'fields' => array(
                'waswirtun-content' => array(
                  'title' => __( 'Inhalt', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
              ),
            ),
            'werwirsind' => array(
              'title' => __( 'Seiteninformationen "Wer wir sind"', 'gloggi' ),
              'fields' => array(
                'werwirsind-content' => array(
                  'title' => __( 'Inhalt', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'werwirsind-separator-banner1' => array(
                  'title' => __( 'Trennbanner zwischen Inhalt und Gruppen', 'gloggi' ),
                  'type' => 'media',
                ),
                'werwirsind-group-title' => array(
                  'title' => __( 'Titel Gruppenabschnitt', 'gloggi' ),
                  'type' => 'text',
                ),
                'werwirsind-separator-banner2' => array(
                  'title' => __( 'Trennbanner zwischen Gruppen und Kontakt', 'gloggi' ),
                  'type' => 'media',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}


/* Unsere custom post types und taxonomies */
add_action( 'wpptd', 'gloggi_custom_post_type_stufe' );
add_action( 'wpptd', 'gloggi_custom_post_type_gruppe' );
add_action( 'wpptd', 'gloggi_custom_post_type_anlass' );
add_action( 'wpptd', 'gloggi_custom_post_type_kontakt' );
add_action( 'wpptd', 'gloggi_custom_post_type_specialevent' );
add_action( 'wpptd', 'gloggi_custom_page_type' );


/* Unsere custom roles */
function gloggi_create_roles( $role_slugs ) {
	$result = array();
	foreach( $role_slugs as $role_slug => $name ) {
		$result[$role_slug] = get_role( $role_slug );
		if( !$result[$role_slug] ) {
			add_role( $role_slug, $name, array() );
			$result[$role_slug] = get_role( $role_slug );
		}
	}
	return result;
}
function gloggi_set_capabilities( $role, $capabilities ) {
	$all_caps = array( 'create_sites', 'delete_sites', 'manage_network', 'manage_sites', 'manage_network_users', 'manage_network_plugins', 'manage_network_themes', 'manage_network_options', 'upgrade_network', 'setup_network', 'activate_plugins', 'delete_others_pages', 'delete_others_posts', 'delete_pages', 'delete_posts', 'delete_private_pages', 'delete_private_posts', 'delete_published_pages', 'delete_published_posts', 'edit_dashboard', 'edit_others_pages', 'edit_others_posts', 'edit_pages', 'edit_posts', 'edit_private_pages', 'edit_private_posts', 'edit_published_pages', 'edit_published_posts', 'edit_theme_options', 'export', 'import', 'list_users', 'manage_categories', 'manage_links', 'manage_options', 'moderate_comments', 'promote_users', 'publish_pages', 'publish_posts', 'read_private_pages', 'read_private_posts', 'read', 'remove_users', 'switch_themes', 'upload_files', 'customize', 'delete_site', 'update_core', 'update_plugins', 'update_themes', 'install_plugins', 'install_themes', 'upload_plugins', 'upload_themes', 'delete_themes', 'delete_plugins', 'edit_plugins', 'edit_themes', 'edit_files', 'edit_users', 'create_users', 'delete_users', 'unfiltered_html' );
	$role_obj = get_role( $role );
	$cap_array = array();
	foreach( $all_caps as $cap ) {
		$cap_array[$cap] = false;
	}
	foreach( $capabilities as $cap ) {
		$cap_array[$cap] = true;
	}
	foreach( $cap_array as $cap=>$allow ) {
		if( $allow ) {
			$role_obj->add_cap( $cap );
		} else {
			$role_obj->remove_cap( $cap );
		}
	}
}
function gloggi_add_plugin_capabilities() {
	remove_role( 'subscriber' );
	remove_role( 'contributor' );
	remove_role( 'author' );
	remove_role( 'editor' );

    $al_caps = array( 'create_users', 'list_users', 'edit_users', 'promote_users', 'delete_users',
		'create_stufen', 'publish_stufen', 'read_stufen', 'read_private_stufen', 'edit_stufen', 'edit_private_stufen', 'edit_published_stufen', 'edit_others_stufen', 'delete_stufen', 'delete_private_stufen', 'delete_published_stufen', 'delete_others_stufen',
		'update_plugins', 'update_themes', 'update_core',
	);
    $leiter_caps = array( 'read', 'upload_files' );

    $roles = gloggi_create_roles( array( 'administrator' => __( 'Administrator' ), 'al' => __( 'Abteilungsleiter' ), 'leiter' => __( 'Leiter' ) ) );

    gloggi_set_capabilities( 'al', array_merge( $al_caps, $leiter_caps ) );
    gloggi_set_capabilities( 'leiter', array_merge( $leiter_caps ) );
}
add_action( 'admin_init', 'gloggi_add_plugin_capabilities' );


function endswith($string, $test) {
  $strlen = strlen($string);
  $testlen = strlen($test);
  if ($testlen > $strlen) return false;
  return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}
function gloggi_hide_irrelevant_metaboxes() {
  $screen = get_current_screen();
  if ( is_admin() && ($screen->id == 'page') ) {
    // Find the current page template
    global $post;
    $current = get_post_meta( $post->ID, '_wp_page_template', true );
    // Find all page templates except for the current one
    $templates = array_filter( array_values(get_page_templates()), function($t) use ($current) { return $current != $t . '.php' && $current != $t && '' != $t; } );
    if( !empty($templates) ) {
      // Get a list of CSS selectors belonging to these page templates
      $css_selectors = array_map( function($t){ return sprintf( "#%s.postbox", pathinfo($t, PATHINFO_FILENAME) ); }, $templates );
      // Hide the metaboxes belonging to the page templates
      echo sprintf( "<style>%s { display: none; }</style>", implode( ', ', $css_selectors ));
    }
  }
}
add_action( 'admin_head', 'gloggi_hide_irrelevant_metaboxes' );


/* Standard-Editor für Seiteninhalt und Beitragsbild entfernen */
function gloggi_remove_unused_page_fields() {
  remove_post_type_support('page', 'editor');
}
add_action( 'init', 'gloggi_remove_unused_page_fields' );


/* Verstecke einige Einträge im Admin-Menü */
function gloggi_remove_admin_menu_pages() {
  remove_menu_page( 'index.php' );          //Dashboard
  remove_menu_page( 'upload.php' );         //Media
  remove_menu_page( 'profile.php' );          //Profil
}
add_action( 'admin_menu', 'gloggi_remove_admin_menu_pages' );

/* Verstecke einige Dashboard-Widgets */
function gloggi_remove_dashboard_widgets(){
  remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}
add_action('wp_dashboard_setup', 'gloggi_remove_dashboard_widgets');



/* Verstecke einige Einträge in der oberen Toolbar */
function gloggi_remove_toolbar_buttons() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
  $wp_admin_bar->remove_menu('customize');
  $wp_admin_bar->remove_menu('appearance');
  $wp_admin_bar->remove_menu('comments');
  //$wp_admin_bar->remove_menu('new-content');
  $wp_admin_bar->remove_menu('post-new.php');
  $wp_admin_bar->remove_menu('user-new.php');
  $wp_admin_bar->remove_menu('edit');
  $wp_admin_bar->remove_menu('user-info');
  $wp_admin_bar->remove_menu('edit-profile');
  $wp_admin_bar->remove_menu('search');
}
add_action( 'wp_before_admin_bar_render', 'gloggi_remove_toolbar_buttons' );
function gloggi_remove_help_tabs($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}
add_filter( 'contextual_help', 'gloggi_remove_help_tabs', 999, 3 );
add_filter( 'screen_options_show_screen', '__return_false' );



/* Sortiere pages nach der menu_order-Spalte */
function custom_post_order($query){
  if( ('stufe' == $query->get('post_type')) or ('kontakt' == $query->get('post_type')) or ('page' == $query->get('post_type'))){
    $query->set('orderby', 'menu_order');
    $query->set('order', 'ASC');
  }
}
if( is_admin() ) {
  add_action('pre_get_posts', 'custom_post_order');
}



/* Globales Einstellungs-Menü */
function gloggi_register_options( $wpod ) {
  $wpod->add_components( array(
    'gloggi_menu' => array(
      'label' => __( 'Einstellungen', 'gloggi' ),
      'icon' => 'dashicons-admin-generic',
      'position' => 4,
      'screens' => array(
        'gloggi_einstellungen' => array(
          'title' => __( 'Seiteneinstellungen', 'gloggi' ),
          'label' => __( 'Seiteneinstellungen', 'gloggi' ),
          'description' => __( 'Globale Einstellungen f&uuml;r die ganze Seite.', 'gloggi' ),
          'tabs' => array(
            // Der Tab-Name ist gleichzeitig der Optionsname der mit get_option abgerufen werden kann
            'gloggi_einstellungen' => array(
              'title' => __( 'Allgemein', 'gloggi' ),
              'sections' => array(
                'gloggi_einstellungen' => array(
                  'title' => '',
                  'fields' => array(
                    'blogname' => array(
                      'title' => __( 'Webseitentitel*', 'gloggi' ),
                      'type' => 'text',
                      'required' => true,
                    ),
                    'abteilung' => array(
                      'title' => __( 'Abteilungsname*', 'gloggi' ),
                      'type' => 'text',
                      'required' => true,
                    ),
                    'abteilungslogo' => array(
                      'title' => __( 'Abteilungslogo', 'gloggi' ),
                      'type' => 'media',
                      'store' => 'url',
                    ),
                    'primaerfarbe' => array(
                      'title' => __( 'Prim&auml;rfarbe*', 'gloggi' ),
                      'type' => 'color',
                      'default' => '#db0822',
                      'required' => true,
                    ),
                    'sekundaerfarbe' => array(
                      'title' => __( 'Sekund&auml;farbe*', 'gloggi' ),
                      'type' => 'color',
                      'default' => '#4a4a4a',
                      'required' => true
                    ),
                    'mitmachen-email' => array(
                      'title' => __( 'Mitmachen-Formular e-Mails gehen an...*', 'gloggi' ),
                      'type' => 'email',
                      'required' => true,
                    ),
                    'anlassverantwortungs-email' => array(
                      'title' => __( 'Kontakt f&uuml;r Anl&auml;sse ohne Kontaktangabe*', 'gloggi' ),
                      'type' => 'email',
                      'required' => true,
                    ),
                    'instagram' => array(
                      'title' => __( 'Instagram-Link', 'gloggi' ),
                      'type' => 'url',
                    ),
                    'facebook' => array(
                      'title' => __( 'Facebook-Link', 'gloggi' ),
                      'type' => 'url',
                    ),
                    'twitter' => array(
                      'title' => __( 'Twitter-Link', 'gloggi' ),
                      'type' => 'url',
                    ),
                    'footer-links' => array(
                      'title' => __( 'Links im Footer', 'gloggi' ),
                      'type' => 'repeatable',
                      'repeatable' => array(
                        'fields' => array(
                          'name' => array(
                            'title' => __( 'Linkname*', 'gloggi' ),
                            'type' => 'text',
                            'required' => true,
                          ),
                          'url' => array(
                            'title' => __( 'URL*', 'gloggi' ),
                            'type' => 'url',
                            'required' => true,
                          ),
                        ),
                      ),
                    ),
                    'footer-contact' => array(
                      'title' => __( 'Kontakt im Footer', 'gloggi' ),
                      'type' => 'wysiwyg',
                    ),
                  ),
                ),
                'gloggi_agenda' => array(
                  'title' => 'Agenda',
                  'fields' => array(
                    'jahresplan' => array(
                      'title' => __( 'Abteilungs-Jahresplan', 'gloggi' ),
                      'type' => 'media',
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}
add_action( 'wpod', 'gloggi_register_options' );


/* Setze den Google Maps API key */
function gloggi_set_google_maps_api_key() {
  return 'AIzaSyADsv_Hk2XaEMIT9gBEkJFbOUnMxTsKWOs';
}
add_filter( 'wpdlib_google_maps_api_key', 'gloggi_set_google_maps_api_key' );


/* Setze Webseiten-Titel */
function gloggi_set_blogname() {
  if ( function_exists( 'wpod_get_option' ) ) {
    $title = wpod_get_option( 'gloggi_einstellungen', 'blogname' );
    update_option( 'blogname', $title );
  }
}
add_action( 'init', 'gloggi_set_blogname' );

?>
