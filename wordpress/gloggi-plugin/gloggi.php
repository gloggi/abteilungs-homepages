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
    'attributes' => __( 'Stufen-Eigenschaften', 'gloggi' ),
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
          'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
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
                  'default' => 11
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
          'supports' => array( 'title', 'thumbnail', 'author', ),
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
                'startzeit' => array(
                  'title' => __( 'Startzeit*', 'gloggi' ),
                  'type' => 'datetime',
                  'required' => true,
                ),
                'startort' => array(
                  'title' => __( 'Startort*', 'gloggi' ),
                  'type' => 'select',
                  'options' => array( 'posts' => 'location' ),
                  'required' => true,
                ),
                'endzeit' => array(
                  'title' => __( 'Endzeit*', 'gloggi' ),
                  'type' => 'datetime',
                  'required' => true,
                ),
                'endort' => array(
                  'title' => __( 'Endort', 'gloggi' ),
                  'type' => 'select',
                  'options' => array( 'posts' => 'location' ),
                ),
                'mitnehmen' => array(
                  'title' => __( 'Mitnehmen', 'gloggi' ),
                  'type' => 'wysiwyg',
                  'default' => '<ul><li>Pfadihemd & Krawatte</li><li>Wetterfeste Kleidung</li><li>Wanderschuhe</li></ul>',
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
                'specialevent' => array(
                  'title' => __( 'Special Event', 'gloggi' ),
                  'type' => 'multiselect',
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
                'name' => array(
                  'title' => __( 'Name', 'gloggi' ),
                  'type' => 'text',
                  'required' => false,
                ),
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
          'description' => __( 'Mit Special Events kann man neuen Eltern erkl&auml;ren, was z.B. ein So-La ist. Diese So-La-Beschreibung wird dann automatisch auf allen Anl&auml;ssen verlinkt, welche als So-La markiert werden.', 'gloggi' ),
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
                'pluralname' => array(
                  'title' => __( 'Name im Plural*', 'gloggi' ),
                  'description' => __( 'Wird gebraucht f&uuml;r "lies allgemeine Informationen &uuml;ber [So-Las]".', 'gloggi' ),
                  'type' => 'text',
                  'required' => true,
                ),
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

/* Location */
function gloggi_custom_post_type_location( $wpptd ) {
  $labels = array(
    'name' => __( 'Locations', 'gloggi' ),
    'singular_name' => __( 'Location', 'gloggi' ),
    'menu_name' => __( 'Locations', 'gloggi' ),
    'name_admin_bar' => __( 'Location', 'gloggi' ),
    'archives' => __( 'Location-Archiv', 'gloggi' ),
    'parent_item_colon' => __( '&Uuml;bergeordnetes Objekt:', 'gloggi' ),
    'all_items' => __( 'Alle Locations', 'gloggi' ),
    'add_new_item' => __( 'Neue Location hinzuf&uuml;gen', 'gloggi' ),
    'add_new' => __( '+ Neu', 'gloggi' ),
    'new_item' => __( 'Neue Location', 'gloggi' ),
    'edit_item' => __( 'Location bearbeiten', 'gloggi' ),
    'update_item' => __( 'Location aktualisieren', 'gloggi' ),
    'view_item' => __( 'Location ansehen', 'gloggi' ),
    'search_items' => __( 'Location suchen', 'gloggi' ),
    'not_found' => __( 'Nicht gefunden', 'gloggi' ),
    'not_found_in_trash' => __( 'Nicht im Papierkorb gefunden', 'gloggi' ),
    'featured_image' => __( 'Location-Bild', 'gloggi' ),
    'set_featured_image' => __( 'Location-Bild setzen', 'gloggi' ),
    'remove_featured_image' => __( 'Location-Bild entfernen', 'gloggi' ),
    'use_featured_image' => __( 'Als Location-Bild verwenden', 'gloggi' ),
    'insert_into_item' => __( 'In Location einf&uuml;gen', 'gloggi' ),
    'uploaded_to_this_item' => __( 'Zu Location hochgeladen', 'gloggi' ),
    'items_list' => __( 'Location-Liste', 'gloggi' ),
    'items_list_navigation' => __( 'Location-Liste Navigation', 'gloggi' ),
    'filter_items_list' => __( 'Location-Liste filtern', 'gloggi' ),
  );
  $capabilities = array(
  // Meta-capabilities (which are granted automatically to roles based on context and the primitive capabilities of the role)
  'edit_post' => 'edit_location',
  'read_post' => 'read_location',
  'delete_post' => 'delete_location',
  // Primitive capabilities (which can be granted directly to a role)
  'create_posts' => 'create_locations',
  'publish_posts' => 'publish_locations',
  'read' => 'read_locations',
  'read_private_posts' => 'read_private_locations',
  'edit_posts' => 'edit_locations',
  'edit_private_posts' => 'edit_private_locations',
  'edit_published_posts' => 'edit_published_locations',
  'edit_others_posts' => 'edit_others_locations',
  'delete_posts' => 'delete_locations',
  'delete_private_posts' => 'delete_private_locations',
  'delete_published_posts' => 'delete_published_locations',
  'delete_others_posts' => 'delete_others_locations',
  );
  $wpptd->add_components( array(
    'gloggi_locations' => array(
      'label' => __( 'Locations', 'gloggi' ),
      'icon' => 'dashicons-admin-site',
      'position' => 9,
      'post_types' => array(
        'location' => array(
          'labels' => $labels,
          'supports' => array( 'title', ),
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
          'capabilities' => $capabilities,
          'map_meta_cap' => true,
          'metaboxes' => array(
            'location' => array(
              'title' => __( 'Location-Informationen', 'gloggi' ),
              'fields' => array(
                'coords' => array(
                  'title' => __( 'Koordinaten', 'gloggi' ),
                  'type' => 'text',
                  'placeholder' => '2 683 237 / 1 248 144',
                  'required' => true,
                  'description' => __( 'Hilfsmittel: <a href="https://tools.retorte.ch/map/" target="_blank">Koordinator</a>', 'gloggi' )
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
                'index-largebanner' => array(
                  'title' => __( 'Banner-Bild', 'gloggi' ),
                  'type' => 'checkbox',
                  'label' => __( 'Gross', 'gloggi' ),
                  'default' => true,
                ),
                'index-content1' => array(
                  'title' => __( 'Inhalt 1', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'index-content1-fleur-de-lis' => array(
                  'title' => __( 'Pfadililie und Kleeblatt anzeigen', 'gloggi' ),
                  'type' => 'checkbox',
                  'label' => __( 'Ja', 'gloggi' ),
                  'default' => true,
                ),
                'index-contact-form-title' => array(
                  'title' => __( 'Formular-Titel', 'gloggi' ),
                  'type' => 'text',
                  'default' => 'Mitmachen',
                ),
                'index-contact-form-receiver' => array(
                  'title' => __( 'Formular-Mails gehen an...', 'gloggi' ),
                  'description' => __( 'Wenn leer geht es an die Mailadresse aus den Einstellungen.', 'gloggi' ),
                  'type' => 'email',
                ),
                'index-contact-form-fields' => array(
                  'title' => __( 'Formularfelder', 'gloggi' ),
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
                          'date' => __( 'Datum', 'gloggi' ),
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
                'index-social-links' => array(
                  'title' => __( 'Social Media Links', 'gloggi' ),
                  'type' => 'repeatable',
                  'repeatable' => array(
                    'fields' => array(
                      'url' => array(
                        'title' => __( 'URL', 'gloggi' ),
                        'type' => 'url',
                      ),
                      'type' => array(
                        'title' => __( 'Typ', 'gloggi' ),
                        'type' => 'select',
                        'options' => array(
                          'instagram' => __( 'Instagram', 'gloggi' ),
                          'facebook' => __( 'Facebook', 'gloggi' ),
                          'twitter' => __( 'Twitter', 'gloggi' ),
                        ),
                      ),
                    ),
                  ),
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
                'waswirtun-separator-banner' => array(
                  'title' => __( 'Trennbanner zwischen Stufen und Inhalt 2', 'gloggi' ),
                  'type' => 'media',
                ),
                'waswirtun-content2' => array(
                  'title' => __( 'Inhalt 2', 'gloggi' ),
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
                'werwirsind-showgroups' => array(
                  'title' => __( 'Gruppen darstellen', 'gloggi' ),
                  'type' => 'checkbox',
                  'label' => __( 'Ja', 'gloggi' ),
                  'default' => true,
                ),
                'werwirsind-group-title' => array(
                  'title' => __( 'Titel Gruppenabschnitt', 'gloggi' ),
                  'type' => 'text',
                ),
                'werwirsind-group-form-page' => array(
                  'title' => __( 'Mitmachen-Buttons verlinken auf...*', 'gloggi'  ),
                  'type' => 'select',
                  'options' => array( 'posts' => 'page' ),
                  'required' => true,
                  'description' => __( 'Die Seite die das Mitmachen-Formular anzeigt, standardmässig "Mitmachen"', 'gloggi' ),
                ),
                'werwirsind-group-agenda-page' => array(
                  'title' => __( 'Agenda-Einträge verlinken auf...*', 'gloggi'  ),
                  'type' => 'select',
                  'options' => array( 'posts' => 'page' ),
                  'required' => true,
                  'description' => __( 'Die Seite die das Mitmachen-Formular anzeigt, standardmässig "Mitmachen"', 'gloggi' ),
                ),
                'werwirsind-separator-banner2' => array(
                  'title' => __( 'Trennbanner zwischen Gruppen und Kontakten', 'gloggi' ),
                  'type' => 'media',
                ),
                'werwirsind-contacts-title' => array(
                  'title' => __( 'Titel Kontaktabschnitt', 'gloggi' ),
                  'type' => 'text',
                  'default' => 'Kontakt',
                ),
                'werwirsind-separator-banner3' => array(
                  'title' => __( 'Trennbanner zwischen Kontakten und Inhalt 2', 'gloggi' ),
                  'type' => 'media',
                ),
                'werwirsind-content2' => array(
                  'title' => __( 'Inhalt 2', 'gloggi' ),
                  'type' => 'wysiwyg',
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
add_action( 'wpptd', 'gloggi_custom_post_type_location' );
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
  return $result;
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
    'read_private_locations', 'edit_private_locations', 'edit_others_locations', 'delete_private_locations', 'delete_published_locations', 'delete_others_locations',
    'create_pages', 'publish_pages', 'read_pages', 'read_private_pages', 'edit_pages', 'edit_private_pages', 'edit_published_pages', 'edit_others_pages', 'delete_pages', 'delete_private_pages', 'delete_published_pages', 'delete_others_pages',
    'update_plugins', 'update_themes', 'update_core',
    'manage_gloggi_options',
  );
  $leiter_caps = array( 'read', 'level_1',
    'upload_files', 'delete_posts', // Kann Medien hochladen und eigene Medien löschen
    // Keine Rechte auf Stufen
    'create_gruppen', 'read_gruppen', 'edit_gruppen', 'edit_published_gruppen',
    'create_anlaesse', 'publish_anlaesse', 'read_anlaesse', 'edit_anlaesse', 'edit_published_anlaesse', 'delete_anlaesse', 'delete_published_anlaesse',
    // Keine Rechte auf Kontakten
    // Keine Rechte auf Special Events
    'create_locations', 'publish_locations', 'read_locations', 'edit_locations', 'edit_published_locations', 'delete_locations',
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


/* Erlaube alle users als "Autor" (Besitzer) fuer eine Gruppe oder einen Anlass oder eine Location, nicht nur "authors" mit dem veralteten access level 1 oder hoeher. */
function gloggi_allow_all_authors( $query_args ) {
  if ( function_exists( get_current_screen ) ) {
    $screen = get_current_screen();
      if( ( $screen->post_type == 'gruppe' || $screen->post_type == 'anlass' || $screen->post_type == 'location' ) && $screen->parent_base == 'edit' ) {
        $query_args['who'] = '';
      }
  }
  return $query_args;
}
add_filter( 'wp_dropdown_users_args', 'gloggi_allow_all_authors' );

/* Author bei Gruppe und Anlass umbenennen */
function gloggi_rename_author_meta_boxes( $post ) {
  remove_meta_box( 'authordiv', get_post_type($post), 'core' );
  add_meta_box( 'authordiv', __( 'Verantwortlich', 'gloggi' ), 'post_author_meta_box', get_post_type($post), 'advanced', 'high' );
}
add_action( 'add_meta_boxes_gruppe',  'gloggi_rename_author_meta_boxes', 999 );
add_action( 'add_meta_boxes_anlass',  'gloggi_rename_author_meta_boxes', 999 );


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

/* Sortiere verschiedene Admin-Tabellen */
function gloggi_custom_post_order() {
  if( !isset( $_GET['orderby'] ) && !isset( $_GET['order'] ) ) {
    if ('anlass' == $_GET['post_type']){
      $_GET['orderby'] = 'meta-startzeit';
      $_GET['order'] = 'desc';
    }
    if( 'stufe' == $_GET['post_type'] or 'kontakt' == $_GET['post_type'] or 'page' == $_GET['post_type'] ) {
      $_GET['orderby'] = 'menu_order';
      $_GET['order'] = 'asc';
    }
  }
}
if( is_admin() ) {
  add_action('load-edit.php', 'gloggi_custom_post_order', 9, 0);
}

/* Duplizieren von Anlaessen in der Anlass-Liste */
function gloggi_duplicate_anlass_as_draft(){
  global $wpdb;
  if ( !( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'gloggi_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_redirect( admin_url() );
  }
  if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) ) {
    return;
  }

  $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
  $post = get_post( $post_id );
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;
  if (isset( $post ) && $post != null) {
    $args = array(
      'post_author'    => $new_post_author,
      'post_name'      => $post->post_name,
      'post_parent'    => $post->post_parent,
      'post_status'    => 'draft',
      'post_title'     => $post->post_title,
      'post_type'      => $post->post_type,
      'menu_order'     => $post->menu_order
    );
    $new_post_id = wp_insert_post( $args );
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
    if (count($post_meta_infos)!=0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        if( $meta_key == '_wp_old_slug' ) continue;
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
      }
      $sql_query.= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }
    /*// Taxonomies
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }
    */
    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
    exit;
  }
}
add_action( 'admin_action_gloggi_duplicate_post_as_draft', 'gloggi_duplicate_anlass_as_draft' );

/* Duplizieren-Link auf Anlaessen */
function gloggi_duplicate_anlass_link( $actions, $post ) {
  if ($post->post_type=='anlass' && current_user_can('edit_anlaesse')) {
    $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=gloggi_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Eine Kopie dieses Eintrags erstellen" rel="permalink">Duplizieren</a>';
  }
  return $actions;
}
add_filter( 'post_row_actions', 'gloggi_duplicate_anlass_link', 10, 2 );

/* Speichere die alte Dauer eines bearbeiteten Anlasses zur spaeteren Verifikation */
function gloggi_save_anlass_duration( $post_id, $post, $update = false ) {
  global $saved_anlass_duration;
  $saved_anlass_duration = strtotime( wpptd_get_post_meta_value( $post_id, 'endzeit' ) ) - strtotime( wpptd_get_post_meta_value( $post_id, 'startzeit' ) );
  $saved_anlass_duration = ( $saved_anlass_duration < 0 ? 0 : $saved_anlass_duration );
}
add_filter( 'save_post_anlass', 'gloggi_save_anlass_duration', 9, 3 );

/* Validiere das Enddatum auf Anlaessen, nachdem es von Post Types Definitely gespeichert wurde */
function gloggi_validate_anlass_enddate( $meta_values_validated ) {
  global $saved_anlass_duration;
  $startzeit = strtotime( $meta_values_validated['startzeit'] );
  $endzeit = strtotime( $meta_values_validated['endzeit'] );
  if( $startzeit > $endzeit ) {
    $new_endzeit = new DateTime( '@' . ( $startzeit + $saved_anlass_duration ) );
    $meta_values_validated['endzeit'] = $new_endzeit->format( 'YmdHis' );
    set_transient( 'gloggi_post_meta_error_anlass', __( "Die Startzeit darf nicht sp&auml;ter als die Endzeit sein. Die Endzeit wurde automatisch angepasst.", 'gloggi' ), 120 );
  }
  return $meta_values_validated;
}
add_filter( 'wpptd_validated_post_meta_values_anlass', 'gloggi_validate_anlass_enddate', 11, 3 );

/* Zeige die Hinweismeldung, wenn das Enddatum eines Anlasses automatisch angepasst wurde */
function gloggi_display_anlass_enddate_correction_notice( $post ) {
  $errors = get_transient( 'gloggi_post_meta_error_anlass' );
  if ( $errors ) {
    echo '<div id="gloggi-post-meta-errors" class="notice notice-error is-dismissible"><p>';
    echo $errors;
    echo '</p></div>';
    delete_transient( 'gloggi_post_meta_error_anlass' );
  }
}
add_action( 'edit_form_top', 'gloggi_display_anlass_enddate_correction_notice', 10, 1 );

/* Zeige die Beschreibung von custom post types */
function gloggi_display_cpt_description( $post ) {
  $obj = get_post_type_object( get_post_type( $post ) );
  if( $obj ) echo esc_html( $obj->description );
}
add_action( 'edit_form_top', 'gloggi_display_cpt_description', 10, 1 );

/* Zeige die Beschreibung der menu_order Felder */
function gloggi_display_cpt_menu_order_description( $post ) {
  $post_type = get_post_type( $post );
  if( $post_type == 'stufe' ) {
    echo __( '<br>F&uuml;r die Anzeige auf "Was-wir-tun"-Seiten. &Uuml;berall sonst werden Stufen nach Alter sortiert.', 'gloggi' );
  } else if( $post_type == 'gruppe' ) {
    echo __( '<br>F&uuml;r die Anzeige auf "Wer-wir-sind"-Seiten und die Buttons auf der Agenda', 'gloggi' );
  } else if( $post_type == 'kontakt' ) {
    echo __( '<br>F&uuml;r die Anzeige auf "Wer-wir-sind"-Seiten', 'gloggi' );
  } else if( $post_type == 'specialevent' ) {
    echo __( '<br>F&uuml;r die Auflistung in der Agenda', 'gloggi' );
  } else if( $post_type == 'page' ) {
    echo __( '<br>Position im Hauptmen&uuml;', 'gloggi' );
  }
}
add_action( 'page_attributes_misc_attributes', 'gloggi_display_cpt_menu_order_description', 10, 1 );


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
                    'favicon' => array(
                      'title' => __( 'Webseiten-Icon', 'gloggi' ),
                      'type' => 'media',
                      'store' => 'url',
                      'mime_types' => 'image/png',
                      'description' => __( 'Das kleine Icon auf dem Tab im Browser. Sollte ein 32x32 Pixel grosses PNG-Bild sein.', 'gloggi' ),
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
                    'anlassverantwortungs-email' => array(
                      'title' => __( 'Kontakt f&uuml;r Anl&auml;sse ohne Kontaktangabe*', 'gloggi' ),
                      'type' => 'email',
                      'required' => true,
                    ),
                    'mitmachen-email' => array(
                      'title' => __( 'Kontakt f&uuml;r Formulare ohne Kontaktangabe*', 'gloggi' ),
                      'type' => 'email',
                      'required' => true,
                    ),
                    'footer-groups-list-title' => array(
                      'title' => __( 'Titel der Gruppen-Liste im Footer', 'gloggi' ),
                      'type' => 'text',
                      'default' => 'Gruppen'
                    ),
                    'footer-groups-page' => array(
                      'title' => __( 'Gruppen-Links verlinken auf...*', 'gloggi'  ),
                      'description' => __( 'Die Seite die die Gruppen anzeigt, standardmässig "Wer wir sind"', 'gloggi' ),
                      'type' => 'select',
                      'options' => array( 'posts' => 'page' ),
                      'required' => true,
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


/* Setze Webseiten-Titel */
function gloggi_set_blogname() {
  if ( function_exists( 'wpod_get_option' ) ) {
    $title = wpod_get_option( 'gloggi_einstellungen', 'blogname' );
    update_option( 'blogname', $title );
  }
}
add_action( 'init', 'gloggi_set_blogname' );

/* Workaround bis der Bug Fix in https://core.trac.wordpress.org/ticket/42794 gemerged wird.
 * Dieses JavaScript-Schnipsel holt den proposed fix nach, indem es am Ende der
 * UploaderStatus.initialize() Funktion die UploaderStatus.ready() Funktion aufruft. */
function gloggi_fix_media_uploader() {
    ?>
<script>
    var oldUploaderStatus = wp.media.view.UploaderStatus;
    wp.media.view.UploaderStatus = oldUploaderStatus.extend({
       initialize: function() {
           oldUploaderStatus.prototype.initialize.call(this);
           this.ready();
       }
    });
</script><?php
}
function gloggi_add_media_modification_js() {
    add_action( 'admin_print_footer_scripts', 'gloggi_fix_media_uploader' );
}
add_action( 'wp_enqueue_media', 'gloggi_add_media_modification_js' );

?>
