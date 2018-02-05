# Changelog
Alle bemerkenswerten Änderungen an diesem Projekt werden in dieser Datei festgehalten.

Das Format basiert auf [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
und dieses Projekt hält sich an [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Changelog als Markdown auf GitHub hochgeladen
- Das Geschlecht der Gruppenmitglieder wird jetzt in der Gruppendetailansicht angezeigt.


## [1.2.1] - 2018-02-02
### Changed
- Jahrespläne werden jetzt auch via Buttons gefiltert, nur der passende (oder nächste übergeordnete) Jahresplan der gewählten Gruppe wird angezeigt.


## [1.2.0] - 2018-02-24
### Added
- Neuer Objekttyp "Location", mit dem die Start- und Endorte von Anlässen konsistenter erfasst werden können.
- Es ist jetzt möglich, Anlässe im Backend zu duplizieren, um nicht für jeden Samstagnachmittag alles von neuem eingeben zu müssen.
- Medien-Menü wird wieder im Backend angezeigt.
- Gruppen-Kreise auf Wer-wir-sind-Seite haben jetzt einen Tooltip mit dem Gruppennamen wenn man mit der Maus darauf zeigt.

### Changed
- Anlass-Vorlage enthält jetzt "Pfadihemd und Krawatte" statt "Pfadiuniform" (korrektes Wording).
- Die Agendaeinträge werden jetzt auch auf iOS korrekt sortiert.
- Die Kontakt-Mailadresse auf Anlässen wird jetzt vom Anlassverantwortlichen genommen.
- Kontakt-Bilder sind jetzt rund.
- Die Medien-Uploaders funktionieren jetzt auf allen Seiten im Login-Bereich.
- Der Verantwortliche (Autor) von Anlässen kann jetzt geändert werden.
- Agenda- und Gruppen-Kreise werden jetzt wenn möglich in der Gruppenfarbe eingefärbt.
- Beim Ändern von Anlässen wird nun überprüft, ob die Endzeit nach der Startzeit liegt. Falls nicht, wird die Endzeit automatisch angepasst.
- Bug gefixt, wegen dem nicht alle Gruppen auf der Wer-wir-sind-Seite angezeigt wurden.
- ALs und Leiter können jetzt auch Medien löschen, Leiter aber nur ihre eigenen.
- Falsche Marker-Position bei Anlassdetail korrigiert (trat bei Orten auf von denen GMaps keine Adresse kennt).
- Verbesserte Anzeige der Agenda-Seite und des Headers auf kleinen Bildschirmen, Listen, Buttons, Formulare und Jahresplan-Logos schöner dargestellt, viele weitere visuelle Verbesserungen.
- Diverse CSS-styles aufgeräumt.
- Texte mit WYSIWYG Texteditor funktionieren jetzt besser.
- Jahresplan-Abschnitt wird nur noch angezeigt wenn sinnvoll.

### Removed
- Feld für Anlassverantwortlichen-Mailadresse entfernt

## [1.1.0] - 2017-11-29
### Added
- Anzeige von Special Events implementiert.
- Formatierung der WYSIWYG-Texte.

### Changed
- Kontakte-Sektion schöner gestaltet.
- Lightboxes auf Safari iOS gefixt.
- Abteilungslogo wird überall als Standardwert angezeigt, falls kein Stufen- oder Gruppenlogo gesetzt ist.
- Feld für 'Anlassverantwortlich' e-Mailadresse klarer beschrieben.
- 'Keine Events'-Text wird korrekt auf der Agenda-Seite angezeigt.
- Links im Footer werden nur noch angezeigt wenn auch welche vorhanden sind.
- ALs können jetzt die Abteilungseinstellungen sehen und ändern.
- Endzeit für Anlässe ist jetzt obligatorisch, da es für die Anzeige zukünftiger Anlässe benötigt wird.
- Diverse Logos korrigiert.
- Auflösung der Bilder auf der Webseite wird jetzt an die Bildschirmgrösse angepasst.
- Verbesserungen an Header und Footer.
- Tippfehler im Backend korrigiert.

## [1.0.0] - 2017-10-26
### Added
- Ursprüngliches Release.

[Unreleased]: https://github.com/gloggi/abteilungs-homepages/compare/v1.2.1...HEAD
[1.2.1]: https://github.com/gloggi/abteilungs-homepages/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/gloggi/abteilungs-homepages/compare/v1.1.0...v1.2.0
[1.1.1]: https://github.com/gloggi/abteilungs-homepages/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/gloggi/abteilungs-homepages/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/gloggi/abteilungs-homepages/tree/v1.0.0
