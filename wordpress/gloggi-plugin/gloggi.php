<?php
   /*
   Plugin Name: Gloggi Abteilungshomepages
   Plugin URI: http://gloggi.ch
   Description: Ein Plugin das das Backend der Gloggi-Abteilungshomepages einrichtet. Ben&ouml;tigt die beiden Plugins "Post Types Definitely" und "Options Definitely".
   Version: %%GULP_INJECT_VERSION%%
   Author: Cosinus
   License: GPL2
   */

// Automatische Updates fuer das Plugin
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'http://wp-updates.gloggi.ch/gloggi-plugin.json',
  __FILE__,
  'gloggi-plugin'
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
    'name' => __( 'Stufen', 'gloggi' ),
    'singular_name' => __( 'Stufe', 'gloggi' ),
    'menu_name' => __( 'Stufen', 'gloggi' ),
    'name_admin_bar' => __( 'Stufe', 'gloggi' ),
    'archives' => __( 'Stufen-Archiv', 'gloggi' ),
    'parent_item_colon' => __( '&Uuml;bergeordnetes Objekt:', 'gloggi' ),
    'all_items' => __( 'Alle Stufen', 'gloggi' ),
    'add_new_item' => __( 'Neue Stufe hinzuf&uuml;gen', 'gloggi' ),
    'add_new' => __( '+ Neu', 'gloggi' ),
    'new_item' => __( 'Neue Stufe', 'gloggi' ),
    'edit_item' => __( 'Stufe bearbeiten', 'gloggi' ),
    'update_item' => __( 'Stufe aktualisieren', 'gloggi' ),
    'view_item' => __( 'Stufe ansehen', 'gloggi' ),
    'search_items' => __( 'Stufe suchen', 'gloggi' ),
    'not_found' => __( 'Nicht gefunden', 'gloggi' ),
    'not_found_in_trash' => __( 'Nicht im Papierkorb gefunden', 'gloggi' ),
    'featured_image' => __( 'Titelbild', 'gloggi' ),
    'set_featured_image' => __( 'Titelbild setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Titelbild entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Titelbild verwenden', 'gloggi' ),
    'insert_into_item' => __( 'In Stufe einf&uuml;gen', 'gloggi' ),
    'uploaded_to_this_item' => __( 'Zu Stufe hochgeladen', 'gloggi' ),
    'items_list' => __( 'Stufen-Liste', 'gloggi' ),
    'items_list_navigation' => __( 'Stufen-Liste Navigation', 'gloggi' ),
    'filter_items_list' => __( 'Stufen-Liste filtern', 'gloggi' ),
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
    'name' => __( 'Gruppen', 'gloggi' ),
    'singular_name' => __( 'Gruppe', 'gloggi' ),
    'menu_name' => __( 'Gruppen', 'gloggi' ),
    'name_admin_bar' => __( 'Gruppe', 'gloggi' ),
    'archives' => __( 'Gruppen-Archiv', 'gloggi' ),
    'attributes' => __( 'Gruppen-Eigenschaften', 'gloggi' ),
    'parent_item_colon' => __( '&Uuml;bergeordnete Gruppe:', 'gloggi' ),
    'all_items' => __( 'Alle Gruppen', 'gloggi' ),
    'add_new_item' => __( 'Neue Gruppe hinzuf&uuml;gen', 'gloggi' ),
    'add_new' => __( '+ Neu', 'gloggi' ),
    'new_item' => __( 'Neue Gruppe', 'gloggi' ),
    'edit_item' => __( 'Gruppe bearbeiten', 'gloggi' ),
    'update_item' => __( 'Gruppe aktualisieren', 'gloggi' ),
    'view_item' => __( 'Gruppe ansehen', 'gloggi' ),
    'view_items' => __( 'Gruppen ansehen', 'gloggi' ),
    'search_items' => __( 'Gruppe suchen', 'gloggi' ),
    'not_found' => __( 'Nicht gefunden', 'gloggi' ),
    'not_found_in_trash' => __( 'Nicht im Papierkorb gefunden', 'gloggi' ),
    'featured_image' => __( 'Titelbild', 'gloggi' ),
    'set_featured_image' => __( 'Titelbild setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Titelbild entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Titelbild verwenden', 'gloggi' ),
    'insert_into_item' => __( 'In Gruppe einf&uuml;gen', 'gloggi' ),
    'uploaded_to_this_item' => __( 'Zu Gruppe hochgeladen', 'gloggi' ),
    'items_list' => __( 'Gruppen-Liste', 'gloggi' ),
    'items_list_navigation' => __( 'Gruppen-Liste Navigation', 'gloggi' ),
    'filter_items_list' => __( 'Gruppen-Liste filtern', 'gloggi' ),
  );
  $capabilities = array(
  // Meta-capabilities (which are granted automatically to roles based on context and the primitive capabilities of the role)
  'edit_post' => 'edit_gruppe',
  'read_post' => 'read_gruppe',
  'delete_post' => 'delete_gruppe',
  // Primitive capabilities (which can be granted directly to a role)
  'create_posts' => 'create_gruppen',
  'publish_posts' => 'publish_gruppen',
  'read' => 'read_gruppen',
  'read_private_posts' => 'read_private_gruppen',
  'edit_posts' => 'edit_gruppen',
  'edit_private_posts' => 'edit_private_gruppen',
  'edit_published_posts' => 'edit_published_gruppen',
  'edit_others_posts' => 'edit_others_gruppen',
  'delete_posts' => 'delete_gruppen',
  'delete_private_posts' => 'delete_private_gruppen',
  'delete_published_posts' => 'delete_published_gruppen',
  'delete_others_posts' => 'delete_others_gruppen',
  );
  $wpptd->add_components( array(
    'gloggi_gruppen' => array(
      'label' => __( 'Gruppen', 'gloggi' ),
      'icon' => 'dashicons-groups',
      'position' => 6,
      'post_types' => array(
        'gruppe' => array(
          'labels' => $labels,
          'supports' => array( 'title', 'thumbnail', 'page-attributes', 'author' ),
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
            // Aus irgend einem Grund werden die Eintraege nicht hierarchisch angezeigt wenn das Datum versteckt wird
            //'date' => false,
            'meta-stufe' => array( 'sortable' => true ),
            'meta-geschlecht' => array( 'sortable' => true ),
          ),
          'capabilities' => $capabilities,
          'map_meta_cap' => true,
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
    'name' => __( 'Anl&auml;sse', 'gloggi' ),
    'singular_name' => __( 'Anlass', 'gloggi' ),
    'menu_name' => __( 'Anl&auml;sse', 'gloggi' ),
    'name_admin_bar' => __( 'Anlass', 'gloggi' ),
    'archives' => __( 'Anlass-Archiv', 'gloggi' ),
    'attributes' => __( 'Anlass-Eigenschaften', 'gloggi' ),
    'parent_item_colon' => __( '&Uuml;bergeordnetes Objekt:', 'gloggi' ),
    'all_items' => __( 'Alle Anl&auml;sse', 'gloggi' ),
    'add_new_item' => __( 'Neuen Anlass hinzuf&uuml;gen', 'gloggi' ),
    'add_new' => __( '+ Neu', 'gloggi' ),
    'new_item' => __( 'Neuer Anlass', 'gloggi' ),
    'edit_item' => __( 'Anlass bearbeiten', 'gloggi' ),
    'update_item' => __( 'Anlass aktualisieren', 'gloggi' ),
    'view_item' => __( 'Anlass ansehen', 'gloggi' ),
    'view_items' => __( 'Anl&auml;sse ansehen', 'gloggi' ),
    'search_items' => __( 'Anlass suchen', 'gloggi' ),
    'not_found' => __( 'Nicht gefunden', 'gloggi' ),
    'not_found_in_trash' => __( 'Nicht im Papierkorb gefunden', 'gloggi' ),
    'featured_image' => __( 'Titelbild', 'gloggi' ),
    'set_featured_image' => __( 'Titelbild setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Titelbild entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Titelbild verwenden', 'gloggi' ),
    'insert_into_item' => __( 'In Anlass einf&uuml;gen', 'gloggi' ),
    'uploaded_to_this_item' => __( 'Zu Anlass hochgeladen', 'gloggi' ),
    'items_list' => __( 'Anlass-Liste', 'gloggi' ),
    'items_list_navigation' => __( 'Anlass-Liste Navigation', 'gloggi' ),
    'filter_items_list' => __( 'Anlass-Liste filtern', 'gloggi' ),
  );
  $capabilities = array(
  // Meta-capabilities (which are granted automatically to roles based on context and the primitive capabilities of the role)
  'edit_post' => 'edit_anlass',
  'read_post' => 'read_anlass',
  'delete_post' => 'delete_anlass',
  // Primitive capabilities (which can be granted directly to a role)
  'create_posts' => 'create_anlaesse',
  'publish_posts' => 'publish_anlaesse',
  'read' => 'read_anlaesse',
  'read_private_posts' => 'read_private_anlaesse',
  'edit_posts' => 'edit_anlaesse',
  'edit_private_posts' => 'edit_private_anlaesse',
  'edit_published_posts' => 'edit_published_anlaesse',
  'edit_others_posts' => 'edit_others_anlaesse',
  'delete_posts' => 'delete_anlaesse',
  'delete_private_posts' => 'delete_private_anlaesse',
  'delete_published_posts' => 'delete_published_anlaesse',
  'delete_others_posts' => 'delete_others_anlaesse',
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
          'capabilities' => $capabilities,
          'map_meta_cap' => true,
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
                  'title' => __( 'e-Mail Verantwortliche(r)', 'gloggi' ),
                  'type' => 'email',
                  'description' => __( 'Ansprechsadresse für offene Fragen. Wenn leer wird beim Anlass eine Mailadresse vom AL angezeigt.', 'gloggi' ),
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
                  'title' => __( 'Endzeit*', 'gloggi' ),
                  'type' => 'datetime',
                  'required' => true,
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
    'name' => __( 'Kontakte', 'gloggi' ),
    'singular_name' => __( 'Kontakt', 'gloggi' ),
    'menu_name' => __( 'Kontakte', 'gloggi' ),
    'name_admin_bar' => __( 'Kontakte', 'gloggi' ),
    'archives' => __( 'Kontakt-Archiv', 'gloggi' ),
    'attributes' => __( 'Kontakt-Eigenschaften', 'gloggi' ),
    'parent_item_colon' => __( '&Uuml;bergeordnetes Objekt:', 'gloggi' ),
    'all_items' => __( 'Alle Kontakte', 'gloggi' ),
    'add_new_item' => __( 'Neuen Kontakt hinzuf&uuml;gen', 'gloggi' ),
    'add_new' => __( '+ Neu', 'gloggi' ),
    'new_item' => __( 'Neuer Kontakt', 'gloggi' ),
    'edit_item' => __( 'Kontakt bearbeiten', 'gloggi' ),
    'update_item' => __( 'Kontakt aktualisieren', 'gloggi' ),
    'view_item' => __( 'Kontakt ansehen', 'gloggi' ),
    'view_items' => __( 'Kontakte ansehen', 'gloggi' ),
    'search_items' => __( 'Kontakte suchen', 'gloggi' ),
    'not_found' => __( 'Nicht gefunden', 'gloggi' ),
    'not_found_in_trash' => __( 'Nicht im Papierkorb gefunden', 'gloggi' ),
    'featured_image' => __( 'Profilbild', 'gloggi' ),
    'set_featured_image' => __( 'Profilbild setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Profilbild entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Profilbild verwenden', 'gloggi' ),
    'insert_into_item' => __( 'Zu Kontakt hinzuf&uuml;gen', 'gloggi' ),
    'uploaded_to_this_item' => __( 'Zu Kontakt hochgeladen', 'gloggi' ),
    'items_list' => __( 'Kontakt-Liste', 'gloggi' ),
    'items_list_navigation' => __( 'Kontakt-Liste Navigation', 'gloggi' ),
    'filter_items_list' => __( 'Kontakt-Liste filtern', 'gloggi' ),
  );
  $capabilities = array(
  // Meta-capabilities (which are granted automatically to roles based on context and the primitive capabilities of the role)
  'edit_post' => 'edit_kontakt',
  'read_post' => 'read_kontakt',
  'delete_post' => 'delete_kontakt',
  // Primitive capabilities (which can be granted directly to a role)
  'create_posts' => 'create_kontakte',
  'publish_posts' => 'publish_kontakte',
  'read' => 'read_kontakte',
  'read_private_posts' => 'read_private_kontakte',
  'edit_posts' => 'edit_kontakte',
  'edit_private_posts' => 'edit_private_kontakte',
  'edit_published_posts' => 'edit_published_kontakte',
  'edit_others_posts' => 'edit_others_kontakte',
  'delete_posts' => 'delete_kontakte',
  'delete_private_posts' => 'delete_private_kontakte',
  'delete_published_posts' => 'delete_published_kontakte',
  'delete_others_posts' => 'delete_others_kontakte',
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
          'capabilities' => $capabilities,
          'map_meta_cap' => true,
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
    'name' => __( 'Special Events', 'gloggi' ),
    'singular_name' => __( 'Special Event', 'gloggi' ),
    'menu_name' => __( 'Special Events', 'gloggi' ),
    'name_admin_bar' => __( 'Special Events', 'gloggi' ),
    'archives' => __( 'Special-Event-Archiv', 'gloggi' ),
    'attributes' => __( 'Special-Event-Eigenschaften', 'gloggi' ),
    'parent_item_colon' => __( '&Uuml;bergeordnetes Objekt:', 'gloggi' ),
    'all_items' => __( 'Alle Special Events', 'gloggi' ),
    'add_new_item' => __( 'Neuen Special Event hinzuf&uuml;gen', 'gloggi' ),
    'add_new' => __( '+ Neu', 'gloggi' ),
    'new_item' => __( 'Neuer Special Event', 'gloggi' ),
    'edit_item' => __( 'Special Event bearbeiten', 'gloggi' ),
    'update_item' => __( 'Special Event aktualisieren', 'gloggi' ),
    'view_item' => __( 'Special Event ansehen', 'gloggi' ),
    'view_items' => __( 'Special Events ansehen', 'gloggi' ),
    'search_items' => __( 'Special Events suchen', 'gloggi' ),
    'not_found' => __( 'Nicht gefunden', 'gloggi' ),
    'not_found_in_trash' => __( 'Nicht im Papierkorb gefunden', 'gloggi' ),
    'featured_image' => __( 'Event-Bild', 'gloggi' ),
    'set_featured_image' => __( 'Event-Bild setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Event-Bild entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Event-Bild verwenden', 'gloggi' ),
    'insert_into_item' => __( 'Zu Special Event hinzuf&uuml;gen', 'gloggi' ),
    'uploaded_to_this_item' => __( 'Zu Special Event hochgeladen', 'gloggi' ),
    'items_list' => __( 'Special-Event-Liste', 'gloggi' ),
    'items_list_navigation' => __( 'Special-Event-Liste Navigation', 'gloggi' ),
    'filter_items_list' => __( 'Special-Event-Liste filtern', 'gloggi' ),
  );
  $capabilities = array(
  // Meta-capabilities (which are granted automatically to roles based on context and the primitive capabilities of the role)
  'edit_post' => 'edit_specialevent',
  'read_post' => 'read_specialevent',
  'delete_post' => 'delete_specialevent',
  // Primitive capabilities (which can be granted directly to a role)
  'create_posts' => 'create_specialevents',
  'publish_posts' => 'publish_specialevents',
  'read' => 'read_specialevents',
  'read_private_posts' => 'read_private_specialevents',
  'edit_posts' => 'edit_specialevents',
  'edit_private_posts' => 'edit_private_specialevents',
  'edit_published_posts' => 'edit_published_specialevents',
  'edit_others_posts' => 'edit_others_specialevents',
  'delete_posts' => 'delete_specialevents',
  'delete_private_posts' => 'delete_private_specialevents',
  'delete_published_posts' => 'delete_published_specialevents',
  'delete_others_posts' => 'delete_others_specialevents',
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
          'capabilities' => $capabilities,
          'map_meta_cap' => true,
          'metaboxes' => array(
            'specialevent' => array(
              'title' => __( 'Special-Event-Informationen', 'gloggi' ),
              'fields' => array(
                'description' => array(
                  'title' => __( 'Beschreibung*', 'gloggi' ),
                  'type' => 'wysiwyg',
                  'required' => true,
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
    'featured_image' => __( 'Banner', 'gloggi' ),
    'set_featured_image' => __( 'Banner setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Banner entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Banner verwenden', 'gloggi' ),
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
function gloggi_add_capabilities( $role, $capabilities ) {
  $role_obj = get_role( $role );
  foreach( $capabilities as $cap ) {
    $role_obj->add_cap( $cap );
  }
}
function gloggi_add_plugin_capabilities() {
  remove_role( 'subscriber' );
  remove_role( 'contributor' );
  remove_role( 'author' );
  remove_role( 'editor' );

  $al_caps = array( 'create_users', 'list_users', 'edit_users', 'promote_users', 'delete_users',
    'delete_others_posts', // Kann alle Medien löschen
    'create_stufen', 'publish_stufen', 'read_stufen', 'read_private_stufen', 'edit_stufen', 'edit_private_stufen', 'edit_published_stufen', 'edit_others_stufen', 'delete_stufen', 'delete_private_stufen', 'delete_published_stufen', 'delete_others_stufen',
    'publish_gruppen', 'read_private_gruppen', 'edit_private_gruppen', 'edit_others_gruppen', 'delete_gruppen', 'delete_private_gruppen', 'delete_published_gruppen', 'delete_others_gruppen',
    'read_private_anlaesse', 'edit_private_anlaesse', 'edit_others_anlaesse', 'delete_private_anlaesse', 'delete_others_anlaesse',
    'create_kontakte', 'publish_kontakte', 'read_kontakte', 'read_private_kontakte', 'edit_kontakte', 'edit_private_kontakte', 'edit_published_kontakte', 'edit_others_kontakte', 'delete_kontakte', 'delete_private_kontakte', 'delete_published_kontakte', 'delete_others_kontakte',
    'create_specialevents', 'publish_specialevents', 'read_specialevents', 'read_private_specialevents', 'edit_specialevents', 'edit_private_specialevents', 'edit_published_specialevents', 'edit_others_specialevents', 'delete_specialevents', 'delete_private_specialevents', 'delete_published_specialevents', 'delete_others_specialevents',
    'create_pages', 'publish_pages', 'read_pages', 'read_private_pages', 'edit_pages', 'edit_private_pages', 'edit_published_pages', 'edit_others_pages', 'delete_pages', 'delete_private_pages', 'delete_published_pages', 'delete_others_pages',
    'update_plugins', 'update_themes', 'update_core', 'activate_plugins', 'install_plugins',
    'manage_gloggi_options',
  );
  $leiter_caps = array( 'read', 'level_1',
    'upload_files', 'delete_posts', // Kann Medien hochladen und eigene Medien löschen
    // Keine Rechte auf Stufen
    'create_gruppen', 'read_gruppen', 'edit_gruppen', 'edit_published_gruppen',
    'create_anlaesse', 'publish_anlaesse', 'read_anlaesse', 'edit_anlaesse', 'edit_published_anlaesse', 'delete_anlaesse', 'delete_published_anlaesse',
    // Keine Rechte auf Kontakten
    // Keine Rechte auf Special Events
    // Keine Rechte auf Pages
  );

  $roles = gloggi_create_roles( array( 'administrator' => __( 'Administrator', 'gloggi' ), 'al' => __( 'Abteilungsleiter', 'gloggi' ), 'leiter' => __( 'Leiter', 'gloggi' ) ) );

  gloggi_add_capabilities( 'administrator', array_merge( $al_caps, $leiter_caps ) );
  gloggi_set_capabilities( 'al', array_merge( $al_caps, $leiter_caps ) );
  gloggi_set_capabilities( 'leiter', array_merge( $leiter_caps ) );
}
add_action( 'admin_init', 'gloggi_add_plugin_capabilities' );
/* Aendere die noetige Capability um die Gloggi-Einstellungen zu speichern */
function gloggi_change_gloggi_einstellungen_capability( $capability ) {
  return 'manage_gloggi_options';
}
add_filter( 'option_page_capability_gloggi_einstellungen', 'gloggi_change_gloggi_einstellungen_capability' );


/* Erlaube alle users als "Autor" (Besitzer) fuer eine Gruppe, nicht nur "authors" mit dem veralteten access level 1 oder hoeher. */
function gloggi_allow_all_authors( $query_args ) {
  if ( function_exists( get_current_screen ) ) {
    $screen = get_current_screen();
      if( $screen->post_type == 'gruppe' && $screen->parent_base == 'edit' ) {
        $query_args['who'] = '';
      }
  }
  return $query_args;
}
add_filter( 'wp_dropdown_users_args', 'gloggi_allow_all_authors' );


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


/* Standard-Editor fuer Seiteninhalt und Beitragsbild entfernen */
function gloggi_remove_unused_page_fields() {
  remove_post_type_support('page', 'editor');
}
add_action( 'init', 'gloggi_remove_unused_page_fields' );


/* Verstecke einige Eintraege im Admin-Menue */
function gloggi_remove_admin_menu_pages() {
  remove_menu_page( 'index.php' );          //Dashboard
  //remove_menu_page( 'upload.php' );       //Media
  remove_menu_page( 'profile.php' );        //Profil
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



/* Verstecke einige Eintraege in der oberen Toolbar */
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

/* Verstecke die Anzahl-Posts-Spalte in der Users Ansicht */
function gloggi_remove_users_column($column_headers) {
  unset($column_headers['posts']);
  return $column_headers;
}
add_action('manage_users_columns','gloggi_remove_users_column');

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



/* Globales Einstellungs-Menue */
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
          'capability' => 'manage_gloggi_options',
          'tabs' => array(
            // Der Tab-Name ist gleichzeitig der Optionsname der mit get_option abgerufen werden kann
            'gloggi_einstellungen' => array(
              'title' => __( 'Allgemein', 'gloggi' ),
              'capability' => 'manage_gloggi_options',
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
                      'store' => 'id',
                    ),
                    'primaerfarbe' => array(
                      'title' => __( 'Prim&auml;rfarbe*', 'gloggi' ),
                      'type' => 'color',
                      'default' => '#db0822',
                      'required' => true,
                    ),
                    'sekundaerfarbe' => array(
                      'title' => __( 'Sekund&auml;rfarbe*', 'gloggi' ),
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
