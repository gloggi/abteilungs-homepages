<?php
   /*
   Plugin Name: Gloggi Abteilungshomepages
   Plugin URI: http://gloggi.ch
   Description: Ein Plugin das das Backend der Gloggi-Abteilungshomepages einrichtet. Ben&ouml;tigt die beiden Plugins "Post Types Definitely" und "Options Definitely".
   Version: 1.0
   Author: Cosinus
   License: GPL2
   */

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
                  'title' => __( 'Alter von *', 'gloggi' ),
                  'type' => 'number',
                  'step' => 1,
                  'min' => 4,
                  'max' => 18,
                  'default' => 6,
                  'required' => true,
                ),
                'alter-bis' => array(
                  'title' => __( 'Alter bis *', 'gloggi' ),
                  'type' => 'number',
                  'step' => 1,
                  'min' => 4,
                  'max' => 18,
                  'default' => 11,
                  'required' => true,
                ),
                'stufenfarbe' => array(
                  'title' => __( 'Stufenfarbe *', 'gloggi' ),
                  'type' => 'color',
                  'required' => true,
                ),
                'stufentext' => array(
                  'title' => __( 'Stufentext', 'gloggi' ),
                  'type' => 'wysiwyg',
                ),
                'stufenlogo' => array(
                  'title' => __( 'Stufenlogo *', 'gloggi' ),
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
      'position' => 5,
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
                  'title' => __( 'Stufe *', 'gloggi' ),
                  'type' => 'select',
                  'options' => array( 'posts' => 'stufe' ), 
                  'required' => true,
                ),
                'geschlecht' => array(
                  'title' => __( 'Geschlecht *', 'gloggi' ),
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
                        'title' => __( 'Bild' ),
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
      'position' => 5,
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
                  'title' => __( 'Teilnehmende Gruppen', 'gloggi' ),
                  'type' => 'multiselect',
                  'options' => array( 'posts' => 'gruppe' ),
                  'required' => true,
                ),
                'startzeit' => array(
                  'title' => __( 'Startzeit', 'gloggi' ),
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
              ),
            ),
          ),
        ),
      ),
    ),
  ), 'gloggi' );
}


/* Leiter (Abteilungsstab) */
function gloggi_custom_post_type_leiter( $wpptd ) {
  $labels = array(
    'name' => 'Leiter',
    'singular_name' => 'Leiter',
    'menu_name' => 'Leiter',
    'name_admin_bar' => 'Leiter',
    'archives' => 'Leiter-Archiv',
    'attributes' => 'Leiter-Eigenschaften',
    'parent_item_colon' => '&Uuml;bergeordnetes Objekt:',
    'all_items' => 'Alle Leiter',
    'add_new_item' => 'Neuen Leiter hinzuf&uuml;gen',
    'add_new' => '+ Neu',
    'new_item' => 'Neuer Leiter',
    'edit_item' => 'Leiter bearbeiten',
    'update_item' => 'Leiter aktualisieren',
    'view_item' => 'Leiter ansehen',
    'view_items' => 'Leiter ansehen',
    'search_items' => 'Leiter suchen',
    'not_found' => 'Nicht gefunden',
    'not_found_in_trash' => 'Nicht im Papierkorb gefunden',
    'featured_image' => 'Profilbild',
    'set_featured_image' => 'Profilbild setzen',
    'remove_featured_image' => 'Profilbild entfernen',
    'use_featured_image' => 'Als Profilbild verwenden',
    'insert_into_item' => 'Zu Leiter hinzuf&uuml;gen',
    'uploaded_to_this_item' => 'Zu Leiter hochgeladen',
    'items_list' => 'Leiter-Liste',
    'items_list_navigation' => 'Leiter-Liste Navigation',
    'filter_items_list' => 'Leiter-Liste filtern',
  );
  $funktion_labels = array(
    'name' => 'Leiterfunktionen',
    'singular_name' => 'Funktion',
    'menu_name' => 'Leiterfunktionen',
    'all_items' => 'Alle Funktionen',
    'parent_item' => '&Uuml;bergeordnetes Objekt',
    'parent_item_colon' => '&Uuml;bergeordnetes Objekt:',
    'new_item_name' => 'Neuer Funktionsname',
    'add_new_item' => 'Neue Funktion hinzuf&uuml;gen',
    'edit_item' => 'Funktion bearbeiten',
    'update_item' => 'Funktion aktualisieren',
    'view_item' => 'Funktion ansehen',
    'separate_items_with_commas' => 'Funktionen mit Kommas abtrennen',
    'add_or_remove_items' => 'Funktionen hinzuf&uuml;gen oder entfernen',
    'choose_from_most_used' => 'Meist benutzte',
    'popular_items' => 'Beliebte Funktionen',
    'search_items' => 'Funktionen durchsuchen',
    'not_found' => 'Nicht gefunden',
    'no_terms' => 'Keine Funktionen',
    'items_list' => 'Funktionen-Liste',
    'items_list_navigation' => 'Funktionen-Liste Navigation',
  );
  $wpptd->add_components( array(
    'gloggi_leiter' => array(
      'label' => __( 'Leiter', 'gloggi' ),
      'icon' => 'dashicons-admin-users',
      'position' => 5,
      'post_types' => array(
        'leiter' => array(
          'labels' => $labels,
          'supports' => array( 'thumbnail', ),
          'taxonomies' => array( 'funktion' ),
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
            'meta-pfadiname' => array( 'sortable' => true ),
            'meta-vorname' => array( 'sortable' => true ),
            'meta-name' => array( 'sortable' => true ),
            'meta-email' => array( 'sortable' => true ),
            
          ),
          'metaboxes' => array(
            'leiterinfos' => array(
              'title' => __( 'Leiter-Informationen', 'gloggi' ),
              'fields' => array(
                'vorname' => array(
                  'title' => __( 'Vorname', 'gloggi' ),
                  'type' => 'text',
                  'required' => true,
                ),
                'name' => array(
                  'title' => __( 'Name', 'gloggi' ),
                  'type' => 'text',
                  'required' => true,
                ),
                'pfadiname' => array(
                  'title' => __( 'Pfadiname', 'gloggi' ),
                  'type' => 'text',
                ),
                'email' => array(
                  'title' => __( 'E-Mail', 'gloggi' ),
                  'type' => 'email',
                  'required' => true,
                ),
              ),
            ),
          ),
          'taxonomies' => array(
            'funktion' => array(
              'labels' => $funktion_labels,
              'hierarchical' => false,
              'public' => false,
              'show_ui' => true,
              'show_admin_column' => true,
              'show_in_nav_menus' => false,
              'show_tagcloud' => false,
              'rewrite' => false,
              'show_in_rest' => false,
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
add_action( 'wpptd', 'gloggi_custom_post_type_leiter' );
add_action( 'wpptd', 'gloggi_custom_page_type' );


/* Automatische Titel für Leiter-Posts */
add_filter( 'save_post_leiter', 'gloggi_set_leiter_title', 10, 3 );
function gloggi_set_leiter_title ( $post_id, $post, $update ){
  // Temporär den Filter entfernen damit keine Endlosschlaufe entsteht
  remove_filter( 'save_post_leiter', __FUNCTION__ );

  // Titel aus Pfadiname oder Vorname zusammensetzen
  $vorname = get_field( 'vorname', $post_id );
  $pfadiname = get_field( 'pfadiname', $post_id );
  $title = $pfadiname;
  if ( $pfadiname == '' ) {
      $title = $vorname;
    }

  // Titel aktualisieren
  wp_update_post( array( 'ID' =>$post_id, 'post_title' =>$title ) );

  // Den Filter wieder installieren
  add_filter( 'save_post_leiter', __FUNCTION__, 10, 3 );
}

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
  //remove_post_type_support('page', 'thumbnail');
}
add_action( 'init', 'gloggi_remove_unused_page_fields' );


/* Verstecke einige Einträge im Admin-Menü */
function gloggi_remove_admin_menu_pages() {
  remove_menu_page( 'index.php' );          //Dashboard
  remove_menu_page( 'jetpack' );          //Jetpack* 
  remove_menu_page( 'edit.php' );           //Posts
  remove_menu_page( 'upload.php' );         //Media
  remove_menu_page( 'edit-comments.php' );      //Comments
  if( wp_get_theme()->__get('name') == 'Gloggi Abteilungshomepages' ) {
    remove_menu_page( 'themes.php' );         //Appearance / Themes
  }
  remove_menu_page( 'plugins.php' );        //Plugins
  remove_menu_page( 'users.php' );          //Users
  remove_menu_page( 'tools.php' );          //Tools
  remove_menu_page( 'options-general.php' );    //Settings
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



// Remove toolbar options
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



// Order pages by menu_order column
function custom_post_order($query){
  if( 'stufe' == $query->get('post_type') ){
    $query->set('orderby', 'menu_order');
    $query->set('order', 'ASC');
  }
  if( 'page' == $query->get('post_type') ){
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
                      'title' => __( 'Webseitentitel', 'gloggi' ),
                      'type' => 'text',
                    ),
                    'abteilung' => array(
                      'title' => __( 'Abteilungsname', 'gloggi' ),
                      'type' => 'text',
                    ),
                    'abteilungslogo' => array(
                      'title' => __( 'Abteilungslogo', 'gloggi' ),
                      'type' => 'media',
                      'store' => 'url',
                    ),
                    'primaerfarbe' => array(
                      'title' => __( 'Prim&auml;rfarbe', 'gloggi' ),
                      'type' => 'color',
                      'default' => '#db0822',
                    ),
                    'sekundaerfarbe' => array(
                      'title' => __( 'Sekund&auml;farbe', 'gloggi' ),
                      'type' => 'color',
                      'default' => '#4a4a4a',
                    ),
                    'mitmachen-email' => array(
                      'title' => __( 'Mitmachen-Formular e-Mails gehen an...', 'gloggi' ),
                      'type' => 'email',
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
                            'title' => __( 'Linkname', 'gloggi' ),
                            'type' => 'text',
                          ),
                          'url' => array(
                            'title' => __( 'URL', 'gloggi' ),
                            'type' => 'url',
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
                    'special-events' => array(
                      'title' => __( 'Special Events', 'gloggi' ),
                      'type' => 'repeatable',
                      'repeatable' => array(
                        'fields' => array(
                          'event' => array(
                            'title' => __( 'Anlass', 'gloggi' ),
                            'type' => 'select',
                            'options' => array( 'posts' => 'anlass' ),
                          ),
                        ),
                      ),
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
