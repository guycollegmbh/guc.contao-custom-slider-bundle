# Changelog

Alle wesentlichen Änderungen am Bundle werden hier dokumentiert.

---

## [1.2.2] – 2026-06-29 — Bugfix: Orbit Pause/Resume

### Bugfixes
- **Foundation 5 `resumeOrbit()` aufgerufen mit nicht-existenter Methode** — `restart_timer()` und `start_timer()` existieren nicht im Foundation 5 Orbit-Plugin. Konsolen-Diagnostic ergab: das Plugin ist unter `data('Instance')` (Grossbuchstabe I) gespeichert. Verfügbare Methoden: `create_timer, stop_timer, toggle_timer`. Fix: `findOrbitPlugin()` sucht jetzt direkt unter `data('Instance')` (mit Fallback: alle Keys scannen), `pauseOrbit()` ruft `stop_timer()`, `resumeOrbit()` ruft `create_timer()`.

### Update-Schritte
```bash
composer update guycollegmbh/contao-custom-slider-bundle
php vendor/bin/contao-console cache:clear
```
*(Kein `contao:migrate` nötig — keine DB-Änderungen)*

---

## [1.2.1] – 2026-06-25 — Security & Bugfix Release

### Dokumentation
- `README.md` vollständig neu geschrieben (war noch Contao-Skeleton-Vorlage): Voraussetzungen, Installation, Features, Einbindung, Update-Anleitung, Datenbankstruktur

### Security-Fixes
- **XSS: `$imagePath` unescaped** — `FilesModel->path` wird jetzt mit `StringUtil::specialchars()` ausgegeben (war die einzige ungeschützte Ausgabe im Template).
- **`sliderColor` Hex-Validierung** — Neuer `save_callback` `validateColor()` prüft ob der Wert dem Format `/^[0-9a-fA-F]{6}$/` entspricht. Verhindert CSS-Injection durch ungültige Werte im style-Attribut.
- **SRI für Vimeo CDN** — Nicht umsetzbar: Vimeo liefert `player.js` ohne versionierte URL aus. Ein fixierter SRI-Hash würde bei jedem Vimeo-Update alle Video-Slides brechen. Risiko-Akzeptanz: Der CDN-Load bleibt ohne `integrity`-Attribut; im Kommentar im Template dokumentiert.

### Bugfixes
- **Orbit pausiert aktiven Video-Slide** — `changed.zf.orbit`-Handler pausiert jetzt nur inaktive Slides. Der neu angezeigte Slide wird via `li.is-active` erkannt und aus der Pause-Schleife ausgenommen.
- **Player-ID-Kollision** — iframe-IDs enthalten jetzt die Modul-ID (`$this->id`): `vimeo-{moduleId}-{index}`. Mehrere Slider-Module auf derselben Seite interferieren nicht mehr.
- **Mehrere Orbits auf einer Seite** — `document.querySelector` durch `querySelectorAll` ersetzt. Alle Orbit-Instanzen werden korrekt gebunden.
- **`<style>` und `<script>` Duplizierung** — PHP `static $vimeoAssetsAdded` stellt sicher dass CSS und Vimeo SDK nur einmal in den HTML-Output geschrieben werden, auch bei mehreren Modul-Instanzen.
- **`in_array` ohne strict mode** — Dritter Parameter `true` hinzugefügt. Verhindert loose-comparison Seiteneffekte (`null == 0`).

### Verbesserungen
- **`mb_strtoupper` mit explizitem `'UTF-8'`-Argument** — Verhindert korrumpierte Umlaute wenn `mb_internal_encoding()` serverseitig abweicht.
- **Null-Guard auf `TL_LANG`-Key** — `$GLOBALS['TL_LANG']['FMD']['customSlider'][0] ?? 'Slider'` verhindert `TypeError` bei nicht geladenem Sprachfile.
- **`SELECT * WHERE active='1'`** — Nur aktive Slides werden aus der DB geladen. Inaktive Slides werden nicht mehr als Template-Variable übergeben.
- **`sliderLinkURL` SQL-Typ** — Von `varchar(255)` auf `int(10) unsigned NOT NULL default '0'` geändert. Passt zum `pageTree`-Widget (speichert Integer-Page-IDs). **Erfordert `contao:migrate`.**
- **`active` SQL-Default** — Von `''` auf `'1'` korrigiert. PHP-Default und SQL-Default sind jetzt konsistent.

### Rückwärtskompatibilität
Alle bestehenden Slides bleiben vollständig erhalten. Die Schema-Änderungen (`sliderLinkURL` Typ, `active` Default) werden sicher durch `contao:migrate` migriert:
- `varchar(255)` → `int(10)`: MySQL konvertiert gespeicherte Integer-Strings verlustfrei
- `active` Default: betrifft nur neue Zeilen, bestehende Daten unverändert

### Update-Schritte
```bash
composer update guycollegmbh/contao-custom-slider-bundle
php vendor/bin/contao-console contao:migrate
php vendor/bin/contao-console cache:clear
```

---

## [1.2.0] – 2026-06-25

### Erweiterung: Vimeo Video-Support
- Neues Feld `mediaType` (Auswahl: `image` / `video`) — bestimmt ob ein Slide ein Bild oder ein Video anzeigt.
  - Default: `image` → bestehende Slides funktionieren ohne Änderung weiter.
  - Subpaletten: Im Backend erscheint je nach Auswahl entweder das Bild-Feld oder das Vimeo-ID-Feld.
- Neues Feld `sliderVimeoId` (varchar 20) — numerische Vimeo Video-ID (z.B. `123456789`).
- Vimeo Player via **CDN** (`https://player.vimeo.com/api/player.js`) — kein Build-System nötig.
- Video-Verhalten:
  - Autoplay beim Anzeigen des Slides
  - Loop (Endloswiederholung)
  - Keine Steuerelemente — nur eigener **Mute/Unmute-Button** (Overlay, unten rechts)
  - Video standardmässig **stumm geschaltet** (Browser-Autoplay-Policy konform)
  - Klick auf Video → **Pause / Play** Toggle
  - Slide-Wechsel in Foundation Orbit → Video wird automatisch **pausiert**
- Das Vimeo Player JS wird nur geladen wenn auf der Seite mindestens ein Video-Slide aktiv ist.
- Text-Overlays (Titel, Untertitel, Text, Link-Button) funktionieren auch beim Video-Slide.
- **Nach dem Update `contao:migrate` ausführen** (2 neue DB-Felder).

---

## [1.1.0] – 2026-06-24

### Erweiterung
- **Feld `sliderLinkTitle`**: Pro Slide kann jetzt ein Title-Tag für den Link erfasst werden (erscheint als Tooltip beim Hovern über den Button).
  - Neues DB-Feld: `sliderLinkTitle varchar(255)`
  - Im Template wird das Attribut `title="..."` am `<a>`-Tag ausgegeben.
  - Spracheintrag (DE): `sliderLinkTitle` in `tl_customslider.php` ergänzt.
  - **Nach dem Update einmalig `contao:migrate` ausführen.**

### Bugfixes
- `ContainerAwareInterface` aus `services.yml` entfernt – Interface existiert in Symfony 6 (Contao 5) nicht mehr und verursachte einen Container-Build-Fehler.
- `utf8_strtoupper()` in `CustomSliderModule.php` durch `mb_strtoupper()` ersetzt (PHP 8.x kompatibel).
- `Database::getInstance()` in `CustomSliderModule.php` durch Doctrine DBAL (`database_connection`) ersetzt.
- `$this->Database` in `tl_customslider::generateAlias()` durch Doctrine DBAL ersetzt.
- Falschen Icon-Pfad (`system/modules/screencast/...`) aus `config.php` entfernt.
- `helloWorld`-Sprachstring (Template-Überrest) aus `languages/de/modules.php` entfernt.
- `src/Resources/contao/dca/tl_module.php` gelöscht – enthielt fälschlicherweise einen Sprachstring statt DCA-Konfiguration; war ein Duplikat von `modules.php`.

### Abhängigkeiten / Repository
- `composer.json`: Abhängigkeit `contao/core-bundle: ^5.3` ergänzt.
- Repository-Wechsel von **Bitbucket** (`guycolle-clients/contao-custom-slider-bundle`) auf **GitHub** (`guycollegmbh/guc.contao-custom-slider-bundle`).
- `composer.json` Support-URLs auf GitHub aktualisiert.

### Umgebung
- Getestet auf: **Contao 5.3.47 / PHP 8.2**

---

## [1.0.0] – Ursprüngliche Version (Contao 4.x)

### Initiales Bundle
- Frontend-Modul `customSlider` (Klasse `CustomSliderModule`)
- Backend-Modul `customslider` zur Verwaltung der Slides
- Datenbanktabelle `tl_customslider` mit folgenden Feldern:
  `Bezeichnung`, `alias`, `sliderBild`, `sliderTitel`, `sliderUntertitel`,
  `sliderText`, `sliderColor`, `sliderLinkURL`, `target`, `sliderLinkText`,
  `sliderPlazierung`, `sliderReihenfolge`, `active`
- Template `mod_customSlider.html5` mit Foundation Orbit Slider
- Seiten-basierte Platzierung der Slides via `sliderPlazierung` (pageTree, mehrfach)
- Alias-Generierung im Backend
- Deutsche Sprachübersetzungen

---

## Update-Anleitung

### Von 1.0.0 auf 1.1.0 aktualisieren

1. Bundle aktualisieren:
   ```bash
   composer update guycollegmbh/contao-custom-slider-bundle
   ```
2. Datenbank-Migration ausführen:
   ```bash
   php vendor/bin/contao-console contao:migrate
   ```
   Das neue Feld `sliderLinkTitle` wird automatisch zur Tabelle `tl_customslider` hinzugefügt.
3. Contao Cache leeren:
   ```bash
   php vendor/bin/contao-console cache:clear
   ```

**Bestehende Slider-Daten bleiben vollständig erhalten.** Es werden keine bestehenden Felder geändert oder gelöscht.

---

## Datenbankstruktur (`tl_customslider`)

| Feld                | Typ          | Beschreibung                                    | Seit    |
|---------------------|--------------|-------------------------------------------------|---------|
| `id`                | int(10) PK   | Primärschlüssel                                 | 1.0.0   |
| `pid`               | int(10)      | Parent-ID                                       | 1.0.0   |
| `sorting`           | int(10)      | Sortierung intern                               | 1.0.0   |
| `tstamp`            | int(10)      | Timestamp letzte Änderung                       | 1.0.0   |
| `Bezeichnung`       | varchar(255) | Interner Name (Pflichtfeld, unique)              | 1.0.0   |
| `alias`             | varchar(255) | Auto-generierter URL-Alias                      | 1.0.0   |
| `sliderBild`        | binary(16)   | Bild (Contao Files UUID)                        | 1.0.0   |
| `sliderTitel`       | varchar(255) | Sichtbarer Titel                                | 1.0.0   |
| `sliderUntertitel`  | varchar(255) | Untertitel                                      | 1.0.0   |
| `sliderText`        | varchar(255) | Fliesstext                                      | 1.0.0   |
| `sliderColor`       | varchar(6)   | Textfarbe (HEX ohne #)                          | 1.0.0   |
| `sliderLinkURL`     | int(10)      | Verlinkung (Contao Page-ID) *(varchar→int 1.2.1)* | 1.0.0   |
| `target`            | char(1)      | Neues Fenster öffnen                            | 1.0.0   |
| `sliderLinkText`    | varchar(255) | Button-Text                                     | 1.0.0   |
| `sliderLinkTitle`   | varchar(255) | Title-Attribut für den Link (Tooltip)           | **1.1.0** |
| `mediaType`         | varchar(10)  | Medientyp: `image` oder `video` (default: image) | **1.2.0** |
| `sliderVimeoId`     | varchar(20)  | Numerische Vimeo Video-ID                       | **1.2.0** |
| `sliderPlazierung`  | blob         | Seiten-Platzierung (serialisiertes Array)       | 1.0.0   |
| `sliderReihenfolge` | int(10)      | Anzeigereihenfolge                              | 1.0.0   |
| `active`            | char(1)      | Slide aktiv/inaktiv                             | 1.0.0   |

---

## Repository

| | |
|---|---|
| **GitHub** | https://github.com/guycollegmbh/guc.contao-custom-slider-bundle |
| **Ehemals** | https://bitbucket.org/guycolle-clients/contao-custom-slider-bundle *(archiviert)* |
| **Lizenz** | LGPL-3.0-or-later |
| **Autor** | GUYCOLLE GMBH — https://www.guycolle.com/ |
