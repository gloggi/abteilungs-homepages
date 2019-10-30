<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings configuration
    |--------------------------------------------------------------------------
    */

    // ---------------
    // Settings fields
    // ---------------

  'fields' => [
    'page-name' => [
      'type' => 'text',
      'default' => 'page-name-default',
      'hint' => 'page-name-hint',
      'override' => 'backpack.base.project_name',
      'validation' => 'required',
    ],
    'abteilung' => [
      'type' => 'text',
      'hint' => 'abteilung-hint',
      'validation' => 'required',
    ],
    'abteilungslogo' => [
      'type' => 'image',
      'disk' => 'public',
      'validation' => 'required',
    ],
    'favicon' => [
      'type' => 'image',
      'hint' => 'favicon-hint',
    ],
    'primaerfarbe' => [
      'type' => 'color_picker',
      'default' => '#db0822',
      'validation' => 'required',
    ],
    'sekundaerfarbe' => [
      'type' => 'color_picker',
      'default' => '#4a4a4a',
      'validation' => 'required',
    ],
    'anlassverantwortungs-email' => [
      'type' => 'email',
      'validation' => 'email|required',
    ],
    'mitmachen-email' => [
      'type' => 'email',
      'validation' => 'required',
    ],
    'footer-groups-list-title' => [
      'type' => 'text',
      'default' => 'Gruppen',
    ],
    /*'footer-groups-page' => [
      'type' => 'select2',
      'hint' => 'footer-groups-page-hint',
      'entity' => 'page',
      'model' => \App\Models\Page::class,
      'attribute' => 'footer-groups-page',
      'validation' => 'required',
    ],*//*
    'footer-links' => [
      'type' => 'repeatable',
      'title' => __('Links im Footer', 'gloggi'),
      'type' => 'repeatable',
      'repeatable' => [
        'fields' => [
          'name' => [
            'title' => __('Linkname*', 'gloggi'),
            'type' => 'text',
            'required' => true,
          ],
          'url' => [
            'title' => __('URL*', 'gloggi'),
            'type' => 'url',
            'required' => true,
          ],
        ],
      ],
    ],*/
    'footer-contact' => [
      'type' => 'summernote',
    ],
    'abteilungs-jahresplan' => [
      'type' => 'upload',
    ],
  ],

];
