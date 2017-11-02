## Allgemeine Produkt Anforderungen

### User Flow
#### Student
1. Start-Screen mit kurzem Erklärungstext und Anmeldung
    1. Oauth / Shiboleth URZ, aber unklar ob und wie wir das benutzen können
    2. Einfaches Passwort Login, dass nur uni-emails akzeptiert um Fehler zu vermeiden.
2. Allgemeine Informationen und spezifische zu den Kursen
    - Welche Informationen brauchen wir über Kurse?
        - Name, Titel, Dozenten, Zeit/Raum, Minimale Teilnehmer, Maximale Teilnehmer (5 oder 10!), Kurzbeschreibung,  Beschreibung,  Literatur.
3. Wahl der Prioritäten, für *alle?* Kurse? Evtl. egal priorisierung bei der bei Verteilung dann ein belieber Kurs gewählt wird der für die Allgmeinheit am besten ist
    - Jede Priorität maximal einmal
    - Wahl sollte geändert werden können bis die Einschreibungsperiode endet
    - Bei jeder Änderungen sollte eine E-Mail mit der aktuellen Liste geschickt werden
4. Algorithmus zur Verteilung wird zum Ende der Einschreibungsperiode getriggert 
    - Minimierung der Varianz
    - Implementierung meherere Algorithmen?
4.5 Erste manuelle Nachbearbeitung und dann Notification / Veröffentlichung 
    - Anzeigen verschiedener Parameter, ändern der Parameter. 
5. Benachrichtigung über Ergebnis der Studenten via E-Mail 
    - Optional: Auch eine eigene Seite auf der Webseite mit dem Ergebniskurs? Anzeige anderer Teilnehmer? 
    - Studenten sollten alle sehen können
6. *Optional* Tauschsystem (Studenten können gezielt andere Studenten anfragen und annehmen und akzeptieren)?
    - Vor der ersten Vorlesungswoche automatisiert mit CC an den Empraleiter und die Superadmins. 
    - Beenden mit Beginn der ersten Vorlesungswoche
7. Login / Logout beliebig

#### Verantwortlicher 
1. Erstellen eines neuen Praktikums
  - Name, Titel, Beschreibung?, Einschreibungsperiode, Beginn?, Ende? 
2. Erstellen von Kursen für das Praktikum 
3. Kursverantwortlicher erhält E-Mail mit Teilnehmern? --> Ja!
4. Nachbearbeitung des Ergebnis des Algorithmus
    -  Auch über die maximale Teilnehmeranzahl hinaus, eher nicht!
5. Bearbeiten und Löschen von Kursen und Praktikas

#### Superadmin
1. Erstellen, Bearbeiten und Löschen von Verwantworlichen / Betreuen


### Verteilungsalgorithmus
  - Piroritätenliste von 1-Anzahl Kurse (+ egal option?), es gibt ausreichend Plätze
      - Wenn es mehr gibt werden Leute vorher (zufällig) rausgeschmissen, evtl Liste mit Usern die hier nicht gekickt werden könne      
      - Zumindest Fehlermeldung ausgeben! 
      - Benachrichtigung wenn es zu viele werden? 
  - Maximal 24h Lauftzeit
  - **Was ist das Optimum?**

### Endgeräte
  - Mobil unterstützüng
  - Welche Desktopbrowser? Reicht IE11 (neuster auf Win7), dann können bessere Frameworks verwendet werden, wie Bootstrap 4 / Semantic UI
      - Für ältere wird Hinweis angezeigt das sie ihren Browser updaten sollten
      - Ansonsten Bootstrap 3 mit IE8 Unterstützung 

### Software
  - Idealerweise docker-compose Umgebung die wir selbst konfigurieren können
  - Erste Wahl wäre OctoberCMS mit Laravel 5.5, das setzt voraus:
      - PHP version 7.0 or higher
          - PDO PHP Extension
          - cURL PHP Extension
          - OpenSSL PHP Extension
          - Mbstring PHP Library
          - ZipArchive PHP Library
          - GD PHP Library
       - Webserver, am liebsten Nginx, alternativ auch Apache
 - Notfalls: Java mit dem Playframework, dies benötigt Java 1.8
 - Datenbank, PostgreSQL, MariaDB oder MySQL
 - Cache-Server: Redis o. ähnliches
 - Tests für die wesentlichsten Bestandteile (insbesondere des Algorithmus)
 
## Offene Fragen
  - Gibt es mehrere Praktika mit mehreren mit gleicher/ähnlicher Logik? Oder immer nur eins und es wird immer nur das neuste angezeigt?
      - Einschreibung in mehrere Kurse? (über die Jahre hinweg / durchgefallen?)  
  - Werden bestimmte Kurse immer wieder ähnlich angeboten?
  - Sollen für Teilnehmner kursbezogene Informationen / Dateien angeboten werden? -- Nein!
  - Sollen die Daten über einen längeren Zeitraum gespeichert werden? -- ja macht sinn für Dozenten
  - Sollen die weitere Veranstalttungsinformationen öffentlich einsehbar sein? JA!
        - Evtl auch die Kurse im letzten Jahr anzeigen
  - Welche Informationen brauchen wir über Kurse? - s.o.
        - Lehrstühle verwalten
        - Bei der übersicht der Kurse Lehrstuhl anzeigen und filtern können.
        - Filter für Teilnehmer
        - Finanzierung: Haushaltsmittel vorhanden? 
        - Ersters Seminar das vom Dozenten angeboten wird? 
  - Verteilung von Berechtigungen
      - Erstellen, Bearbeiten und Löschen von Praktikas (Soll jeder alle oder nur seine eigenen bearbeien können?) --> Nein
      - Erstellen, Bearbeiten und Löschen von Kurse für ein Praktikum (Auch hier jeder alle, nur bestimmte Praktikas?...) --> nur bestimmte
  - Was passiert, wenn es weniger Plätze gibt als Anmeldungen?
        - Der Fall ist noch nicht vorgekommen. 
        - Aber umgekehrt sollten wenn zu wenige da sind, sollte für jeden Kurs mindestens drei Teilnehmner haben. Idealerweise minimale Teilnehmeranzahl pro Kurs angeben könenn. 
  - Sollen Studenten vor der Verteilung in bestimmte Kurse festgesetzt werden können, bzw. im Nachhinein gelöscht / verschoben / hinzufgefügt werden können?  --> Ja, am ende sollte man verschieben können
  - Tauschsystem für Studenten? --> Wäre nett s.o.
  - Anzeige anderer Studenten in dem Kurs? --> Ja
  - Mitteilung and die Verantwortlichen wer im Kurs ist? -> Ja
  - Was ist das Optimum für die Verteilung? 

## Besprechung mit den Psyschologen
Verteilung von Seminaren, typischer mehr Plätze als Studierende. Bei den Lehrstühlen wird angefragt, wie viele und welche Seminare Angeboten werden und wie viele Studenten in das Seminar passen. Präferenzliste wird erst einige Wochen später angezeigt. 
Ziel bisher, möglichst vielen Studierenden ihrem maximal Kurs zuzuordnen, es wäre aber besser wenn es halt halbwegs gute Kurse bekommen. 
Wir brauchen mindestens 2-level admins, eine die nur Kurse erstellen können und eigene bearbeiten können. 
