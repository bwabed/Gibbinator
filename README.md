# Gibbinator
IDPA-Projekt - Gibbinator by Dimitri, Petar und David

## Produkt
Es handelt sich um eine Managementwebsite für Schulen. Es soll die Kommunikation zwischen Lehrpersonen und Lernenden erleichtert werden. Zudem soll die Verteilung des Semesterplans digital über diese Plattform erfolgen.

## Funktionen
### Admin
* Man kann Benutzer, Infrastruktur und Klassen pflegen.
### Lehrperson
* Auf der Startsicht wird ein Kalender in der Monatsansicht ausgegeben und darin sind alle Lektionen eingetragen, die diese Lehrperson hat.
* Es können Nachrichten erstellt werden, die entweder einer Klasse, einem Fach, einer Lektion oder allen angezeigt werden.
* Der Semesterplan kann als Vorlage heruntergeladen werden.
* Der Semesterplan kann als .csv Datei mit ';' als Trennzeichen hochgeladen werden und wird automatisch verarbeitet und zu Lektionen umgestellt.
* Lehrpersonen können Klassen verwalten und neue erstellen bzw. Benutzer hinzufügen oder wieder löschen.
* Fächer können in der Klassenübersicht erstellt werden.
### Lernende
* In der Startansicht erhält der Benutzer einen Kalender in der Wochenansicht.
* Es wird angezeigt welcher Tag und welche Uhrzeit gerade ist (Now-Indicator).
* Auf der Startseite werden die ersten 6 Nachrichten, nach Datum absteigent sortiert angezeigt.
* In der Nachrichtenansicht hat der Benutzer alle Nachrichten, die ihn betreffen aufgelistet.
* Der Benutzer hat eine Übersicht mit allen Lektionen in einer Tabelle und den nächsten 6 in Detailansicht.
* Der Benutzer kann auf eine Klassen- und eine Lehrerliste zugreiffen. In der Klassenliste sieht er seine Klassen und die Mitglieder dieser Klassen. In der Lehrerliste erhält er einen üUberblick auf alle Lehrpersonen, mit denen er Fächer oder Lektionen hat und deren Kontaktdaten.

# Bbc MVC

Willkommen beim MVC des Bbc. In diesem Repository findest du den Code sowie eine Anleitung dazu.

## Installation unter Windows

### Vorbereitung

Bevor du mit der Installation beginnen kannst, ist es wichtig, dass du auf deinem PC einen funktionierenden XAMPP Stack am laufen hast. Sollte das nicht der Fall sein, findest du im Internet eine Anleitung dazu.

### Installation

Kopiere zuert alle Dateien aus der Branch, welche du verwenden möchtest, in den Ordner in dem du Dein Projekt entwickeln möchtest. Hier wird das Verzeichnis `C:\dev\my-project` verwendet.

Um später mit einem DNS Namen auf die Seite zugreifen zu können, musst du den gewünschten DNS Namen in der `hosts`-Datei eintagen. Wir verwenden in diesem Beispiel den Namen `my-project.local`.

`C:\Windows\System32\drivers\etc\hosts`
```
# [...]

127.0.0.1    my-project.local
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
    ServerName my-project.local

    # Ort an dem Das Projekt zu finden ist
    DocumentRoot "c:/dev/my-project/public"

    # Nochmals
    <Directory "c:/dev/my-project/public">
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

Nun starte den Apache über das XAMPP Control Panel neu und du solltest mit dem Browser deines Vertrauens auf die Seite `http://my-project.local` zugreifen können.

### Composer Autoload

```
composer install
```
