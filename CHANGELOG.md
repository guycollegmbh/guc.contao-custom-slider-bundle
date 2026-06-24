# Changelog

Alle wesentlichen Änderungen am Bundle werden hier dokumentiert.

---

## [Unreleased] – 2026-06-24

### Neu
- **Feld `sliderLinkTitle`**: Pro Slide kann jetzt ein Title-Tag für den Link erfasst werden (erscheint als Tooltip beim Hovern über den Button).
  - Neues DB-Feld: `sliderLinkTitle varchar(255)`
  - Im Template wird das Attribut `title="..."` am `<a>`-Tag ausgegeben.
  - **Nach dem Update einmalig `contao:migrate` ausführen.**

### Bugfixes
- `ContainerAwareInterface` aus `services.yml` entfernt – diese Interface existiert in Symfony 6 (Contao 5) nicht mehr und verursachte einen Container-Build-Fehler.
- `utf8_strtoupper()` in `CustomSliderModule.php` durch `mb_strtoupper()` ersetzt (PHP 8.x kompatibel).
- `Database::getInstance()` in `CustomSliderModule.php` durch Doctrine DBAL (`database_connection`) ersetzt.
- `$this->Database` in `tl_customslider::generateAlias()` durch Doctrine DBAL ersetzt.
- Falschen Icon-Pfad (`system/modules/screencast/...`) aus `config.php` entfernt.
- `helloWorld`-Sprachstring (Template-Überrest) aus `languages/de/modules.php` entfernt.
- `src/Resources/contao/dca/tl_module.php` gelöscht – die Datei enthielt fälschlicherweise einen Sprachstring statt DCA-Konfiguration; der Eintrag war ein Duplikat von `modules.php`.

### Abhängigkeiten
- `composer.json`: Abhängigkeit `contao/core-bundle: ^5.3` ergänzt.

---

## Update-Anleitung

### Bestehende Installation aktualisieren

1. Bundle-Dateien aktualisieren (git pull / composer update)
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

| Feld               | Typ              | Beschreibung                                   |
|--------------------|------------------|------------------------------------------------|
| `id`               | int(10) PK       | Primärschlüssel                                |
| `pid`              | int(10)          | Parent-ID                                      |
| `sorting`          | int(10)          | Sortierung intern                              |
| `tstamp`           | int(10)          | Timestamp letzte Änderung                      |
| `Bezeichnung`      | varchar(255)     | Interner Name (Pflichtfeld, unique)             |
| `alias`            | varchar(255)     | Auto-generierter URL-Alias                     |
| `sliderBild`       | binary(16)       | Bild (Contao Files UUID)                       |
| `sliderTitel`      | varchar(255)     | Sichtbarer Titel                               |
| `sliderUntertitel` | varchar(255)     | Untertitel                                     |
| `sliderText`       | varchar(255)     | Fliesstext                                     |
| `sliderColor`      | varchar(6)       | Textfarbe (HEX ohne #)                         |
| `sliderLinkURL`    | varchar(255)     | Verlinkung (Contao Page-ID)                    |
| `target`           | char(1)          | Neues Fenster öffnen                           |
| `sliderLinkText`   | varchar(255)     | Button-Text                                    |
| `sliderLinkTitle`  | varchar(255)     | Title-Attribut für den Link *(neu 2026-06-24)* |
| `sliderPlazierung` | blob             | Seiten-Platzierung (serialisiertes Array)      |
| `sliderReihenfolge`| int(10)          | Anzeigereihenfolge                             |
| `active`           | char(1)          | Slide aktiv/inaktiv                            |
