<?php

namespace App;

/**
 * Trait PageTemplates
 * @package App
 * @property CrudPanel $crud
 */
trait PageTemplates {
    /*
    |--------------------------------------------------------------------------
    | Page Templates for Backpack\PageManager
    |--------------------------------------------------------------------------
    |
    | Each page template has its own method, that define what fields should show up using the Backpack\CRUD API.
    | Use snake_case for naming and PageManager will make sure it looks pretty in the create/update form
    | template dropdown.
    |
    | Any fields defined here will show up after the standard page fields:
    | - select template
    | - page name (only seen by admins)
    | - page title
    | - page slug
    */

    private function form_page() {
        $this->crud->addFields([
          [
            'name' => 'largebanner',
            'label' => trans('Banner-Bild gross'),
            'type' => 'checkbox',
            'default' => true,
          ],
          [
            'name' => 'content1',
            'label' => trans('Inhalt 1'),
            'type' => 'summernote',
          ],
          [
            'name' => 'content1-fleur-de-lis',
            'label' => trans('Pfadililie und Kleeblatt anzeigen'),
            'type' => 'checkbox',
            'default' => true,
          ],
          [
            'name' => 'contact-form-title',
            'label' => trans('Formular-Titel'),
            'type' => 'text',
            'default' => 'Mitmachen',
          ],
          [
            'name' => 'contact-form-receiver',
            'label' => trans('Formular-Mails gehen an...'),
            'hint' => trans('Wenn leer geht es an die Mailadresse aus den Einstellungen.'),
            'type' => 'email',
          ], /*[
            'name' => 'contact-form-fields',
            'label' => trans('Formularfelder'),
            'type' => 'repeatable',
            [
            'name' => 'repeatable',
              [
            'name' => 'fields',
                [
            'name' => 'name',
                  'label' => trans('Titel'),
                  'type' => 'text',
                ],
                [
            'name' => 'type',
                  'label' => trans('Typ'),
                  'type' => 'select2_from_array',
                  'options' => [
                    'text' => trans('Text'),
                    'textarea' => trans('Text (mehrzeilig)'),
                    'number' => trans('Zahl'),
                    'email' => trans('e-Mail-Adresse'),
                    'tel' => trans('Telefonnummer'),
                    'gender' => trans('Geschlecht'),
                    'date' => trans('Datum'),
                    'clothing-size' => trans('Kleidergrösse (XS - XL)'),
                  ],
                ],
                [
            'name' => 'required',
                  'label' => trans('Erforderlich?'),
                  'type' => 'checkbox',
                  'label' => trans('Ja'),
                ],
              ],
            ],
          ],*/ [
            'name' => 'separator-banner',
            'label' => trans('Trennbanner'),
            'type' => 'upload',
          ], /*[
            'name' => 'social-links',
            'label' => trans('Social Media Links'),
            'type' => 'repeatable',
            [
            'name' => 'repeatable',
              [
            'name' => 'fields',
                [
            'name' => 'url',
                  'label' => trans('URL'),
                  'type' => 'url',
                ],
                [
            'name' => 'type',
                  'label' => trans('Typ'),
                  'type' => 'select2_from_array',
                  'options' => [
                    'instagram' => trans('Instagram'),
                    'facebook' => trans('Facebook'),
                    'twitter' => trans('Twitter'),
                  ],
                ],
              ],
            ],
          ], */[
            'name' => 'content2',
            'label' => trans('Inhalt 2'),
            'type' => 'summernote',
          ],
          [
            'name' => 'separator-banner2',
            'label' => trans('Trennbanner 2'),
            'type' => 'upload',
          ],
          [
            'name' => 'content3',
            'label' => trans('Inhalt 3'),
            'type' => 'summernote',
          ],
        ]);
    }

    private function who_we_are() {
        $this->crud->addFields([
          [
            'name' => 'content',
            'label' => trans('Inhalt'),
            'type' => 'summernote',
          ],
          [
            'name' => 'separator-banner1',
            'label' => trans('Trennbanner zwischen Inhalt und Gruppen'),
            'type' => 'upload',
          ],
          [
            'name' => 'showgroups',
            'label' => trans('Gruppen darstellen'),
            'type' => 'checkbox',
            'default' => true,
          ],
          [
            'name' => 'group-title',
            'label' => trans('Titel Gruppenabschnitt'),
            'type' => 'text',
          ],
          [
            'name' => 'group_form_page_id',
            'label' => trans('Mitmachen-Buttons verlinken auf...'),
            'type' => 'select2',
            'entity' => 'groupFormPage',
            'attribute' => 'name',
            'hint' => trans('Die Seite die das Mitmachen-Formular anzeigt, standardmässig "Mitmachen"'),
          ],
          [
            'name' => 'group_agenda_page_id',
            'label' => trans('Agenda-Einträge verlinken auf...'),
            'type' => 'select2',
            'entity' => 'groupAgendaPage',
            'attribute' => 'name',
            'hint' => trans('Die Seite die die Agendaeintr&auml;ge anzeigt, standardmässig "Agenda"'),
          ],
          [
            'name' => 'separator-banner2',
            'label' => trans('Trennbanner zwischen Gruppen und Kontakten'),
            'type' => 'upload',
          ],
          [
            'name' => 'contacts-title',
            'label' => trans('Titel Kontaktabschnitt'),
            'type' => 'text',
            'default' => 'Kontakt',
          ],
          [
            'name' => 'separator-banner3',
            'label' => trans('Trennbanner zwischen Kontakten und Inhalt 2'),
            'type' => 'upload',
          ],
          [
            'name' => 'content2',
            'label' => trans('Inhalt 2'),
            'type' => 'summernote',
          ],
        ]);
    }

    private function what_we_do() {
        $this->crud->addFields([
          [
            'name' => 'content',
            'label' => trans('Inhalt'),
            'type' => 'summernote',
          ],
          [
            'name' => 'separator-banner',
            'label' => trans('Trennbanner zwischen Stufen und Inhalt 2'),
            'type' => 'upload',
          ],
          [
            'name' => 'content2',
            'label' => trans('Inhalt 2'),
            'type' => 'summernote',
          ],
        ]);
    }

    private function agenda() {
        $this->crud->addFields([
          [
            'name' => 'content',
            'label' => trans('Inhalt'),
            'type' => 'summernote',
          ],
          [
            'name' => 'noevents',
            'label' => trans('Keine Events'),
            'hint' => trans('Falls keine zuk&uuml;nftigen Anl&auml;sse eingetragen sind, wird dieser Text unter dem Inhalt angezeigt.'),
            'type' => 'summernote',
          ],
          [
            'name' => 'separator-banner1',
            'label' => trans('Trennbanner zwischen Events und Jahrespl&auml;nen'),
            'type' => 'upload',
          ],
          [
            'name' => 'annual-plan-title',
            'label' => trans('Titel Jahresplanabschnitt'),
            'type' => 'text',
          ],
          [
            'name' => 'annual-plan-content',
            'label' => trans('Text Jahresplanabschnitt'),
            'type' => 'summernote',
          ],
          [
            'name' => 'separator-banner2',
            'label' => trans('Trennbanner zwischen Jahrespl&auml;nen und Special Events'),
            'type' => 'upload',
          ],
          [
            'name' => 'special-events-title',
            'label' => trans('Titel Special Events'),
            'type' => 'text',
          ],
        ]);
    }
}
