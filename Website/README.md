# Bbc MVC

Willkommen beim MVC des Bbc. In diesem Repository findest du den Code sowie eine Anleitung dazu.

## Aufbau

Über verschiedene Branches wird das MVC Framework Schritt für Schritt aufgebaut und auf der letzten Branch findest du dann das vollständige Framework mit ein Paar Beispielseiten. Hier ein kleiner Überblick.

| Reihenfolge | Branch      | Inhalt                                                          |
|-------------|-------------|-----------------------------------------------------------------|
|             | master      | Einleitung                                                      |
| 1           | controller  | Grundsetup. Dispatcher und Controller                           |
| 2           | view        | Alles zum Thema HTML                                            |
| 3           | model       | Dann noch die Werkzeuge für den Datenbankzugriff                |
| 4           | formbuilder | Für alle die noch nicht genug haben, noch ein Formulargenerator |

## Installation unter Windows

### Vorbereitung

Bevor du mit der Installation beginnen kannst, ist es wichtig, dass du auf deinem PC einen funktionierenden XAMPP Stack am laufen hast. Sollte das nicht der Fall sein, findest du auf dem Share oder im Internet eine Anleitung dazu.

## Installation (Windows)

Kopiere zuert alle Dateien aus der Branch, welche du verwenden möchtest, in den Ordner in dem du Dein Projekt entwickelst.

Um später mit einem DNS Namen auf die Seite zugreifen zu können, musst du den gewünschten DNS Namen in der hosts-Datei eintagen.

`C:\Windows\System32\drivers\etc\hosts`
```
# [...]

127.0.0.1    mvc.local
```

Damit der Apache Webserver aus dem XAMPP Stack weiss, welcher DNS Namen zu welchem Ordner auf dem Dateisystem gehört, musst du einen VirtualHost erstellen. Dazu musst du die Datei `C:\xampp\apache\conf\extra\httpd-vhosts.conf` folgendermassen anpassen.

```apache
# [...]

# Wird benötigt um VirtualHosts für alle Requests auf Port 80 zu aktivieren
NameVirtualHost *:80

# [...]

# Eigentliche VHost Konfiguration
<VirtualHost 127.0.0.1>
    # DNS Name auf den der VHost hören soll
    ServerName www.mvc.local
    # Weitere DNS Namen
    ServerAlias mvc.local

    AddType text/html .shtml
    AddHandler server-parsed .shtml
    ServerAdmin webmaster@mvc.local

    # Ort an dem Das Projekt zu finden ist
    DocumentRoot "d:/dev/web/mvc"

    # Nochmals
    <Directory "d:/dev/web/mvc">
        Options Indexes FollowSymLinks
        Options +Includes
        AllowOverride All
        Order allow,deny
        Require all granted
        Allow from All
        DirectoryIndex index.php
    </Directory>
</VirtualHost>
```

Nun starte den Apache über das XAMPP Control Panel neu und du solltest mit dem Browser deines Vertrauens auf die Seite zugreifen können.
