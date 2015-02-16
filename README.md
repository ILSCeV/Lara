## Studentenclub-Verwaltung

Softwareprojekt WS14/15 an der TU Ilmenau (Codename: Lara-VedSt)

Auftraggeber: [bc-Studentenclub](http://www.bc-club.de) (Sektion des Ilmenauer Studentenclub e.V.)

## Was ist das? 
Ziel des Projektes war es, die im Umfeld eines Studentclubs die bereits eingesetze Software und eine Reihe technologisch breit aufgestellter Softwarekomponenten nach Möglichkeit in einem einzigen Projekt zusammen zu fassen. Es sollte somit ermöglicht werden, die Daten einzelner Komponenten zentral abzulegen, um sie insbesondere zu statistischen Auswertungen heran ziehen zu können. Gleichzeitig sollte die Benutzerfreundlichkeit der Komponenten deutlich erhöht werden und 
sicher gestellt werden, dass die resultierende Umsetzung in einem höheren Maße zu einer Aktzeptanz durch Betreiber und Anwender führen sollte. 

Der angestrebte Funktionsumfang sollte dabei insbesondere die folgenden **Komponenten** umfassen:
- **Veranstaltungskalender**, inkl. detaillierter Informationen zur Veranstaltung
- **Dienstplan**, inkl. Unterscheidung zwischen Mitgliedern und externen Helfern
- **Dienstestatistik** und **Veranstaltungesstatistik**
- **Finanzen** und **Controlling**

Dazu war es nötig zwischen authentifizierten Nutzern und "Gästen" unterscheiden zu können und in einem entsprechenden Authentifizeirungsprozess umzusetzen. Dies sollte den Zugriff auf weitere Funktionen, wie z.B. das Erfassen, Bearbeiten und Löschen von Veranstaltungen und Aufgaben sowie deren zugehörigen Dienstplänen nur bestimmten Nutzergruppen möglich machen. 
Hierzu war ein bereits existierendes, auf LDAP basierendes Rollenmodell zu nutzen.

In der uns zur Verfügung stehenden Zeit konnten wir die uns gegebene Situation analysieren und den Soll-Zustand planen. 
Da das Projekt von Anfang an als sehr umfangreich eingeschätz wurde, wurde bereits zu Begin eine Umsetzung in 2 Phasen vergesehen: 
- **Phase 1** beinhaltete Erstellung von Komponenten Kalender, Dienstpläne (bestehend aus Veranstaltungsdienstplänen und internen Aufgaben), Dienstestatistik sowie der Verbindung zur Mitgliederdatenbank via LDAP.
- **Phase 2** beinhaltet die Bereiche Finanzen, Veranstaltungsstatistik und Controlling.
	
**Inhalt dieses Softwareprojektes war die Umsetzung der Phase 1.**

## Aktueller Stand:
Das Softwareprojekt bei der Uni und somit die erste Phase sind offiziell abgeschlossen.
**Status der Komponenten:**
- Kalender: **OK**
- Dienstpläne:
  - Veranstaltungsdienstplan: **OK**
  - Aufgabendienstplan: **OK**
- Dienstestatistik: *in Arbeit*
- Veranstaltungsstatistik: tba
- Finanzen: tba
- Controlling: tba

In Zukunft sollen die verbleibenden offenen Anforderungen, z.B. aus den Bereichen Finanzen und Controlling, sowie Änderungswünsche, welche seitens der Nutzer geäußert werden, analysiert und gegebenen Falls
umgesetzt werden.

## Ausblick in die Phase 2
- Dienstestatistik implementieren
- Change-Requests vom Betatest implementieren
- parallel einen zweiten Betatest durchführen
- Finanzen und Controlling hinzufügen und testen
- Change-Requests vom Betatest 2 implementieren
- Kompatibilität für andere Clubs gewährleisten

## Installationsanleitung
- Siehe **Installationsanleitung-SWP-WS1415-Studentenclub-Verwaltung.pdf**
 
## Benutzerhandbuch
- Siehe **Benutzerhandbuch-SWP-WS1415-Studentenclub-Verwaltung.pdf**
