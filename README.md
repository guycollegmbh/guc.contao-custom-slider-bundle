# GUC Contao Custom Slider Bundle

Ein Slider-Bundle für [Contao CMS](https://contao.org), entwickelt von [GUYCOLLE GMBH](https://www.guycolle.com/).

Ermöglicht die Verwaltung von Slides im Contao-Backend mit Unterstützung für Bilder und Vimeo-Videos.

---

## Voraussetzungen

- Contao `^5.3`
- PHP `^8.2`

---

## Installation

```bash
composer require guycollegmbh/contao-custom-slider-bundle
```

Danach Datenbank migrieren und Cache leeren:

```bash
php vendor/bin/contao-console contao:migrate
php vendor/bin/contao-console cache:clear
```

---

## Features

### Slides verwalten
- Eigenes Backend-Modul unter **Slider** zur Verwaltung aller Slides
- Felder pro Slide: Bezeichnung, Alias, Titel, Untertitel, Text, Textfarbe, Link, Linktext, Link-Title-Tag, Reihenfolge, Aktiv/Inaktiv
- Seiten-basierte Platzierung: Slides können gezielt für bestimmte Contao-Seiten aktiviert werden

### Medientypen
Jeder Slide unterstützt entweder ein **Bild** oder ein **Vimeo-Video**:

| Typ | Konfiguration |
|-----|---------------|
| Bild | Auswahl aus der Contao-Dateiverwaltung |
| Vimeo | Numerische Vimeo-Video-ID (z.B. `123456789`) |

### Vimeo-Video-Player
- Autoplay beim Anzeigen des Slides
- Loop (Endloswiederholung)
- Standardmässig stumm geschaltet (Browser-Autoplay-Policy konform)
- Mute/Unmute-Button als Overlay
- Klick auf Video → Pause / Play
- Automatische Pause beim Slide-Wechsel

### Frontend-Template
- Slider basiert auf **Foundation Orbit**
- Text-Overlays (Titel, Untertitel, Text, Link-Button) funktionieren bei Bild- und Video-Slides
- Vimeo Player API wird nur geladen wenn ein Video-Slide auf der Seite aktiv ist

---

## Einbindung im Contao-Projekt

### 1. Repository in `composer.json` registrieren

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/guycollegmbh/guc.contao-custom-slider-bundle.git"
        }
    ],
    "require": {
        "guycollegmbh/contao-custom-slider-bundle": "dev-main"
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### 2. Frontend-Modul einbinden

Im Contao-Backend unter **Themes → Frontend-Module** ein neues Modul vom Typ **Slider** erstellen und im Layout einbinden.

---

## Update

```bash
composer update guycollegmbh/contao-custom-slider-bundle
php vendor/bin/contao-console contao:migrate
php vendor/bin/contao-console cache:clear
```

---

## Datenbankstruktur

Tabelle: `tl_customslider`

| Feld                | Typ          | Beschreibung                              |
|---------------------|--------------|-------------------------------------------|
| `Bezeichnung`       | varchar(255) | Interner Name (nur Backend)               |
| `alias`             | varchar(255) | Auto-generierter URL-Alias                |
| `mediaType`         | varchar(10)  | `image` oder `video`                      |
| `sliderBild`        | binary(16)   | Bild (Contao Files UUID)                  |
| `sliderVimeoId`     | varchar(20)  | Numerische Vimeo Video-ID                 |
| `sliderTitel`       | varchar(255) | Sichtbarer Titel                          |
| `sliderUntertitel`  | varchar(255) | Untertitel                                |
| `sliderText`        | varchar(255) | Fliesstext                                |
| `sliderColor`       | varchar(6)   | Textfarbe (HEX ohne #, z.B. `ffffff`)     |
| `sliderLinkURL`     | int(10)      | Verlinkung (Contao Page-ID)               |
| `target`            | char(1)      | Link in neuem Fenster öffnen              |
| `sliderLinkText`    | varchar(255) | Button-Text                               |
| `sliderLinkTitle`   | varchar(255) | Title-Attribut des Links (Tooltip)        |
| `sliderPlazierung`  | blob         | Seiten-Platzierung (serialisiertes Array) |
| `sliderReihenfolge` | int(10)      | Anzeigereihenfolge                        |
| `active`            | char(1)      | Slide aktiv/inaktiv                       |

---

## Changelog

Siehe [CHANGELOG.md](CHANGELOG.md)

---

## Lizenz

LGPL-3.0-or-later — siehe [LICENSE](LICENSE)

---

## Autor

**GUYCOLLE GMBH** — [www.guycolle.com](https://www.guycolle.com/)
