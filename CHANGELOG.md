# Changelog

Alle wesentlichen Änderungen am Bundle werden hier dokumentiert.

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
| `sliderLinkURL`     | varchar(255) | Verlinkung (Contao Page-ID)                     | 1.0.0   |
| `target`            | char(1)      | Neues Fenster öffnen                            | 1.0.0   |
| `sliderLinkText`    | varchar(255) | Button-Text                                     | 1.0.0   |
| `sliderLinkTitle`   | varchar(255) | Title-Attribut für den Link (Tooltip)           | **1.1.0** |
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
