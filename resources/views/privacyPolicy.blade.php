@extends('layouts.master')

@section('title')
    {{ __('mainLang.privacyPolicy') }}
@stop

@section('content')

    <br class="d-none d-sm-block d-md-none">
    <br class="d-none d-sm-block d-md-none">


    @auth
        @if(Auth::user()->privacy_accepted == 0)
        <div class="card ">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title">{{ __("mainLang.waitOneSecond") }}</h3>
            </div>

            <div class="card-body">
                <strong>{{ __('mainLang.agreeWithPrivacy') }}</strong>
            </div>
        </div>
        <br>
        @endif
    @endauth

    <div class="card ">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">{{ __("mainLang.privacyPolicy") }}</h3>
        </div>

        <div class="card-body p-0">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" href="#complete" data-bs-toggle="tab" aria-expanded="true">Datenschutzerklärung -
                        deutsche Version</a></li>
                <li class="nav-item"><a class="nav-link" href="#short" data-bs-toggle="tab" aria-expanded="false">Kurze Zusammenfassung - deutsche
                        Version</a></li>
                <li class="nav-item"><a class="nav-link" href="#english" data-bs-toggle="tab" aria-expanded="false">Short Summary - English
                        Version</a></li>
            </ul>

            <div id="myTabContent" class="tab-content p-3" style="max-height: 340px; overflow-y: scroll">
                <div class="tab-pane fade active show" id="complete">
                    <p><strong>Datenschutzerklärung</strong></p>
                    <p><strong></strong></p>
                    <p>
                        Diese Datenschutzerklärung klärt Sie über die Art, den Umfang und Zweck der Verarbeitung von
                        personenbezogenen Daten (nachfolgend kurz „Daten“) innerhalb unseres Onlineangebotes und der mit
                        ihm verbundenen Webseiten, Funktionen und Inhalte sowie externen Onlinepräsenzen, wie z.B. unser
                        Social Media Profile auf. (nachfolgend gemeinsam bezeichnet als „Onlineangebot“). Im Hinblick
                        auf die verwendeten Begrifflichkeiten, wie z.B. „Verarbeitung“ oder „Verantwortlicher“ verweisen
                        wir auf die Definitionen im Art. 4 der Datenschutzgrundverordnung (DSGVO).<br>
                        <br>
                    </p>
                    <p><strong>Verantwortlicher</strong></p>
                    <p>
                        <span class="tsmcontroller">
                            E-Mailadresse: <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a><br>
                            Link zum Impressum (inkl. Verantwortlicher und Adresse): <a href="http://www.il-sc.de/impressum">http://www.il-sc.de/impressum</a>
                        </span>
                    </p>

                    <p><strong>Arten der verarbeiteten Daten:</strong></p>
                    <p>
                        - Bestandsdaten (z.B., Namen, Adressen).<br>
                        - Kontaktdaten (z.B., E-Mail, Telefonnummern).<br>
                        - Inhaltsdaten (z.B., Texteingaben, Fotografien, Videos).<br>
                        - Nutzungsdaten (z.B., besuchte Webseiten, Interesse an Inhalten, Zugriffszeiten).<br>
                        - Meta-/Kommunikationsdaten (z.B., Geräte-Informationen, IP-Adressen).<br>
                    </p>

                    <p><strong>Kategorien betroffener Personen</strong></p>
                    <p>
                        Besucher und Nutzer des Onlineangebotes (Nachfolgend bezeichnen wir die betroffenen Personen
                        zusammenfassend auch als „Nutzer“).<br>
                    </p>

                    <p><strong>Zweck der Verarbeitung</strong></p>
                    <p>
                        - Zurverfügungstellung des Onlineangebotes, seiner Funktionen und Inhalte.<br>
                        - Beantwortung von Kontaktanfragen und Kommunikation mit Nutzern.<br>
                        - Sicherheitsmaßnahmen.<br>
                        - Reichweitenmessung/Marketing<br>
                        <span class="tsmcom"></span>
                    </p>

                    <p><strong>Verwendete Begrifflichkeiten </strong></p>
                    <p>
                        „Personenbezogene Daten“ sind alle Informationen, die sich auf eine identifizierte oder
                        identifizierbare natürliche Person (im Folgenden „betroffene Person“) beziehen; als
                        identifizierbar wird eine natürliche Person angesehen, die direkt oder indirekt, insbesondere
                        mittels Zuordnung zu einer Kennung wie einem Namen, zu einer Kennnummer, zu Standortdaten, zu
                        einer Online-Kennung (z.B. Cookie) oder zu einem oder mehreren besonderen Merkmalen
                        identifiziert werden kann, die Ausdruck der physischen, physiologischen, genetischen,
                        psychischen, wirtschaftlichen, kulturellen oder sozialen Identität dieser natürlichen Person
                        sind.<br>
                        <br>
                        „Verarbeitung“ ist jeder mit oder ohne Hilfe automatisierter Verfahren ausgeführten Vorgang oder
                        jede solche Vorgangsreihe im Zusammenhang mit personenbezogenen Daten. Der Begriff reicht weit
                        und umfasst praktisch jeden Umgang mit Daten.<br>
                        <br>
                        Als „Verantwortlicher“ wird die natürliche oder juristische Person, Behörde, Einrichtung oder
                        andere Stelle, die allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung
                        von personenbezogenen Daten entscheidet, bezeichnet.
                    </p>

                    <p><strong>Maßgebliche Rechtsgrundlagen</strong></p>
                    <p>
                        Nach Maßgabe des Art. 13 DSGVO teilen wir Ihnen die Rechtsgrundlagen unserer Datenverarbeitungen
                        mit. Sofern die Rechtsgrundlage in der Datenschutzerklärung nicht genannt wird, gilt Folgendes:
                        Die Rechtsgrundlage für die Einholung von Einwilligungen ist Art. 6 Abs. 1 lit. a und Art. 7
                        DSGVO, die Rechtsgrundlage für die Verarbeitung zur Erfüllung unserer Leistungen und
                        Durchführung vertraglicher Maßnahmen sowie Beantwortung von Anfragen ist Art. 6 Abs. 1 lit. b
                        DSGVO, die Rechtsgrundlage für die Verarbeitung zur Erfüllung unserer rechtlichen
                        Verpflichtungen ist Art. 6 Abs. 1 lit. c DSGVO, und die Rechtsgrundlage für die Verarbeitung zur
                        Wahrung unserer berechtigten Interessen ist Art. 6 Abs. 1 lit. f DSGVO. Für den Fall, dass
                        lebenswichtige Interessen der betroffenen Person oder einer anderen natürlichen Person eine
                        Verarbeitung personenbezogener Daten erforderlich machen, dient Art. 6 Abs. 1 lit. d DSGVO als
                        Rechtsgrundlage.</p>
                    <p><strong></strong></p>
                    <p></p>

                    <p><strong>Zusammenarbeit mit Auftragsverarbeitern und Dritten</strong></p>
                    <p>
                        Sofern wir im Rahmen unserer Verarbeitung Daten gegenüber anderen Personen und Unternehmen
                        (Auftragsverarbeitern oder Dritten) offenbaren, sie an diese übermitteln oder ihnen sonst
                        Zugriff auf die Daten gewähren, erfolgt dies nur auf Grundlage einer gesetzlichen Erlaubnis
                        (z.B. wenn eine Übermittlung der Daten an Dritte, wie an Zahlungsdienstleister, gem. Art. 6 Abs.
                        1 lit. b DSGVO zur Vertragserfüllung erforderlich ist), Sie eingewilligt haben, eine rechtliche
                        Verpflichtung dies vorsieht oder auf Grundlage unserer berechtigten Interessen (z.B. beim
                        Einsatz von Beauftragten, Webhostern, etc.). <br>
                        <br>
                        Sofern wir Dritte mit der Verarbeitung von Daten auf Grundlage eines sog.
                        „Auftragsverarbeitungsvertrages“ beauftragen, geschieht dies auf Grundlage des Art. 28 DSGVO.
                    </p>

                    <p><strong>Übermittlungen in Drittländer</strong></p>
                    <p>
                        Sofern wir Daten in einem Drittland (d.h. außerhalb der Europäischen Union (EU) oder des
                        Europäischen Wirtschaftsraums (EWR)) verarbeiten oder dies im Rahmen der Inanspruchnahme von
                        Diensten Dritter oder Offenlegung, bzw. Übermittlung von Daten an Dritte geschieht, erfolgt dies
                        nur, wenn es zur Erfüllung unserer (vor)vertraglichen Pflichten, auf Grundlage Ihrer
                        Einwilligung, aufgrund einer rechtlichen Verpflichtung oder auf Grundlage unserer berechtigten
                        Interessen geschieht. Vorbehaltlich gesetzlicher oder vertraglicher Erlaubnisse, verarbeiten
                        oder lassen wir die Daten in einem Drittland nur beim Vorliegen der besonderen Voraussetzungen
                        der Art. 44 ff. DSGVO verarbeiten. D.h. die Verarbeitung erfolgt z.B. auf Grundlage besonderer
                        Garantien, wie der offiziell anerkannten Feststellung eines der EU entsprechenden
                        Datenschutzniveaus (z.B. für die USA durch das „Privacy Shield“) oder Beachtung offiziell
                        anerkannter spezieller vertraglicher Verpflichtungen (so genannte
                        „Standardvertragsklauseln“).
                    </p>

                    <p><strong>Rechte der betroffenen Personen</strong></p>
                    <p>
                        Sie haben das Recht, eine Bestätigung darüber zu verlangen, ob betreffende Daten verarbeitet
                        werden und auf Auskunft über diese Daten sowie auf weitere Informationen und Kopie der Daten
                        entsprechend Art. 15 DSGVO.<br>
                        <br>
                        Sie haben entsprechend. Art. 16 DSGVO das Recht, die Vervollständigung der Sie betreffenden
                        Daten oder die Berichtigung der Sie betreffenden unrichtigen Daten zu verlangen.<br>
                        <br>
                        Sie haben nach Maßgabe des Art. 17 DSGVO das Recht zu verlangen, dass betreffende Daten
                        unverzüglich gelöscht werden, bzw. alternativ nach Maßgabe des Art. 18 DSGVO eine Einschränkung
                        der Verarbeitung der Daten zu verlangen.<br>
                        <br>
                        Sie haben das Recht zu verlangen, dass die Sie betreffenden Daten, die Sie uns bereitgestellt
                        haben nach Maßgabe des Art. 20 DSGVO zu erhalten und deren Übermittlung an andere
                        Verantwortliche zu fordern. <br>
                        <br>
                        Sie haben ferner gem. Art. 77 DSGVO das Recht, eine Beschwerde bei der zuständigen
                        Aufsichtsbehörde einzureichen.
                    </p>

                    <p><strong>Widerrufsrecht</strong></p>
                    <p>
                        Sie haben das Recht, erteilte Einwilligungen gem. Art. 7 Abs. 3 DSGVO mit Wirkung für die Zukunft
                        zu widerrufen
                    </p>

                    <p><strong>Widerspruchsrecht</strong></p>
                    <p>
                        Sie können der künftigen Verarbeitung der Sie betreffenden Daten nach Maßgabe des Art. 21 DSGVO
                        jederzeit widersprechen. Der Widerspruch kann insbesondere gegen die Verarbeitung für Zwecke der
                        Direktwerbung erfolgen.
                    </p>

                    <p><strong>Cookies und Widerspruchsrecht bei Direktwerbung</strong></p>
                    <p>
                        Als „Cookies“ werden kleine Dateien bezeichnet, die auf Rechnern der Nutzer gespeichert werden.
                        Innerhalb der Cookies können unterschiedliche Angaben gespeichert werden. Ein Cookie dient
                        primär dazu, die Angaben zu einem Nutzer (bzw. dem Gerät auf dem das Cookie gespeichert ist)
                        während oder auch nach seinem Besuch innerhalb eines Onlineangebotes zu speichern. Als temporäre
                        Cookies, bzw. „Session-Cookies“ oder „transiente Cookies“, werden Cookies bezeichnet, die
                        gelöscht werden, nachdem ein Nutzer ein Onlineangebot verlässt und seinen Browser schließt. In
                        einem solchen Cookie kann z.B. der Inhalt eines Warenkorbs in einem Onlineshop oder ein
                        Login-Staus gespeichert werden. Als „permanent“ oder „persistent“ werden Cookies bezeichnet, die
                        auch nach dem Schließen des Browsers gespeichert bleiben. So kann z.B. der Login-Status
                        gespeichert werden, wenn die Nutzer diese nach mehreren Tagen aufsuchen. Ebenso können in einem
                        solchen Cookie die Interessen der Nutzer gespeichert werden, die für Reichweitenmessung oder
                        Marketingzwecke verwendet werden. Als „Third-Party-Cookie“ werden Cookies bezeichnet, die von
                        anderen Anbietern als dem Verantwortlichen, der das Onlineangebot betreibt, angeboten werden
                        (andernfalls, wenn es nur dessen Cookies sind spricht man von „First-Party Cookies“).<br>
                        <br>
                        Wir können temporäre und permanente Cookies einsetzen und klären hierüber im Rahmen unserer
                        Datenschutzerklärung auf.<br>
                        <br>
                        Falls die Nutzer nicht möchten, dass Cookies auf ihrem Rechner gespeichert werden, werden sie
                        gebeten die entsprechende Option in den Systemeinstellungen ihres Browsers zu deaktivieren.
                        Gespeicherte Cookies können in den Systemeinstellungen des Browsers gelöscht werden. Der
                        Ausschluss von Cookies kann zu Funktionseinschränkungen dieses Onlineangebotes führen.<br>
                        <br>
                        Ein genereller Widerspruch gegen den Einsatz der zu Zwecken des Onlinemarketing eingesetzten
                        Cookies kann bei einer Vielzahl der Dienste, vor allem im Fall des Trackings, über die
                        US-amerikanische Seite <a href="http://www.aboutads.info/choices/">http://www.aboutads.info/choices/</a>
                        oder die EU-Seite <a
                            href="http://www.youronlinechoices.com/">http://www.youronlinechoices.com/</a> erklärt
                        werden. Des Weiteren kann die Speicherung von Cookies mittels deren Abschaltung in den
                        Einstellungen des Browsers erreicht werden. Bitte beachten Sie, dass dann gegebenenfalls nicht
                        alle Funktionen dieses Onlineangebotes genutzt werden können.
                    </p>

                    <p><strong>Löschung von Daten</strong></p>
                    <p>
                        Die von uns verarbeiteten Daten werden nach Maßgabe der Art. 17 und 18 DSGVO gelöscht oder in
                        ihrer Verarbeitung eingeschränkt. Sofern nicht im Rahmen dieser Datenschutzerklärung
                        ausdrücklich angegeben, werden die bei uns gespeicherten Daten gelöscht, sobald sie für ihre
                        Zweckbestimmung nicht mehr erforderlich sind und der Löschung keine gesetzlichen
                        Aufbewahrungspflichten entgegenstehen. Sofern die Daten nicht gelöscht werden, weil sie für
                        andere und gesetzlich zulässige Zwecke erforderlich sind, wird deren Verarbeitung eingeschränkt.
                        D.h. die Daten werden gesperrt und nicht für andere Zwecke verarbeitet. Das gilt z.B. für Daten,
                        die aus handels- oder steuerrechtlichen Gründen aufbewahrt werden müssen.<br>
                        <br>
                        Nach gesetzlichen Vorgaben in Deutschland erfolgt die Aufbewahrung insbesondere für 6 Jahre
                        gemäß § 257 Abs. 1 HGB (Handelsbücher, Inventare, Eröffnungsbilanzen, Jahresabschlüsse,
                        Handelsbriefe, Buchungsbelege, etc.) sowie für 10 Jahre gemäß § 147 Abs. 1 AO (Bücher,
                        Aufzeichnungen, Lageberichte, Buchungsbelege, Handels- und Geschäftsbriefe, Für Besteuerung
                        relevante Unterlagen, etc.). <br>
                        <br>
                        Nach gesetzlichen Vorgaben in Österreich erfolgt die Aufbewahrung insbesondere für 7 J gemäß §
                        132 Abs. 1 BAO (Buchhaltungsunterlagen, Belege/Rechnungen, Konten, Belege, Geschäftspapiere,
                        Aufstellung der Einnahmen und Ausgaben, etc.), für 22 Jahre im Zusammenhang mit Grundstücken und
                        für 10 Jahre bei Unterlagen im Zusammenhang mit elektronisch erbrachten Leistungen,
                        Telekommunikations-, Rundfunk- und Fernsehleistungen, die an Nichtunternehmer in
                        EU-Mitgliedstaaten erbracht werden und für die der Mini-One-Stop-Shop (MOSS) in Anspruch
                        genommen wird.
                    </p>

                    <p><strong>Hosting</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Die von uns in Anspruch genommenen Hosting-Leistungen dienen der Zurverfügungstellung der folgenden Leistungen: Infrastruktur- und Plattformdienstleistungen, Rechenkapazität, Speicherplatz und Datenbankdienste, Sicherheitsleistungen sowie technische Wartungsleistungen, die wir zum Zwecke des Betriebs dieses Onlineangebotes einsetzen. <br>
                            <br>
                            Hierbei verarbeiten wir, bzw. unser Hostinganbieter Bestandsdaten, Kontaktdaten, Inhaltsdaten, Vertragsdaten, Nutzungsdaten, Meta- und Kommunikationsdaten von Kunden, Interessenten und Besuchern dieses Onlineangebotes auf Grundlage unserer berechtigten Interessen an einer effizienten und sicheren Zurverfügungstellung dieses Onlineangebotes gem. Art. 6 Abs. 1 lit. f DSGVO i.V.m. Art. 28 DSGVO (Abschluss Auftragsverarbeitungsvertrag).
                        </span>
                    </p>

                    <p><strong>Erhebung von Zugriffsdaten und Logfiles</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Wir, bzw. unser Hostinganbieter, erhebt auf Grundlage unserer berechtigten Interessen im Sinne des Art. 6 Abs. 1 lit. f. DSGVO Daten über jeden Zugriff auf den Server, auf dem sich dieser Dienst befindet (sogenannte Serverlogfiles). Zu den Zugriffsdaten gehören Name der abgerufenen Webseite, Datei, Datum und Uhrzeit des Abrufs, übertragene Datenmenge, Meldung über erfolgreichen Abruf, Browsertyp nebst Version, das Betriebssystem des Nutzers, Referrer URL (die zuvor besuchte Seite), IP-Adresse und der anfragende Provider.<br>
                            <br>
                            Logfile-Informationen werden aus Sicherheitsgründen (z.B. zur Aufklärung von Missbrauchs- oder Betrugshandlungen) für die Dauer von maximal 30 Tagen gespeichert und danach gelöscht. Daten, deren weitere Aufbewahrung zu Beweiszwecken erforderlich ist, sind bis zur endgültigen Klärung des jeweiligen Vorfalls von der Löschung ausgenommen.
                        </span>
                    </p>

                    <p><strong>Administration, Finanzbuchhaltung, Büroorganisation, Kontaktverwaltung</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Wir verarbeiten Daten im Rahmen von Verwaltungsaufgaben sowie Organisation unseres Betriebs, Finanzbuchhaltung und Befolgung der gesetzlichen Pflichten, wie z.B. der Archivierung. Hierbei verarbeiten wir dieselben Daten, die wir im Rahmen der Erbringung unserer vertraglichen Leistungen verarbeiten. Die Verarbeitungsgrundlagen sind Art. 6 Abs. 1 lit. c. DSGVO, Art. 6 Abs. 1 lit. f. DSGVO. Von der Verarbeitung sind Kunden, Interessenten, Geschäftspartner und Websitebesucher betroffen. Der Zweck und unser Interesse an der Verarbeitung liegt in der Administration, Finanzbuchhaltung, Büroorganisation, Archivierung von Daten, also Aufgaben die der Aufrechterhaltung unserer Geschäftstätigkeiten, Wahrnehmung unserer Aufgaben und Erbringung unserer Leistungen dienen. Die Löschung der Daten im Hinblick auf vertragliche Leistungen und die vertragliche Kommunikation entspricht den, bei diesen Verarbeitungstätigkeiten genannten Angaben.<br>
                            <br>
                            Wir offenbaren oder übermitteln hierbei Daten an die Finanzverwaltung, Berater, wie z.B., Steuerberater oder Wirtschaftsprüfer sowie weitere Gebührenstellen und Zahlungsdienstleister.<br>
                            <br>
                            Ferner speichern wir auf Grundlage unserer betriebswirtschaftlichen Interessen Angaben zu Lieferanten, Veranstaltern und sonstigen Geschäftspartnern, z.B. zwecks späterer Kontaktaufnahme. Diese mehrheitlich unternehmensbezogenen Daten, speichern wir grundsätzlich dauerhaft.<br>
                        </span>
                    </p>

                    <p><strong>Registrierfunktion</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Nutzer können optional ein Nutzerkonto anlegen. Im Rahmen der Registrierung werden die erforderlichen Pflichtangaben den Nutzern mitgeteilt. Die im Rahmen der Registrierung eingegebenen Daten werden für die Zwecke der Nutzung des Angebotes verwendet. Die Nutzer können über angebots- oder registrierungsrelevante Informationen, wie Änderungen des Angebotsumfangs oder technische Umstände per E-Mail informiert werden. Wenn Nutzer ihr Nutzerkonto gekündigt haben, werden deren Daten im Hinblick auf das Nutzerkonto gelöscht, vorbehaltlich deren Aufbewahrung ist aus handels- oder steuerrechtlichen Gründen entspr. Art. 6 Abs. 1 lit. c DSGVO notwendig. Es obliegt den Nutzern, ihre Daten bei erfolgter Kündigung vor dem Vertragsende zu sichern. Wir sind berechtigt, sämtliche während der Vertragsdauer gespeicherten Daten des Nutzers unwiederbringlich zu löschen.<br>
                            <br>
                            <br>
                            Im Rahmen der Inanspruchnahme unserer Registrierungs- und Anmeldefunktionen sowie der Nutzung der Nutzerkontos, speichern wird die IP-Adresse und den Zeitpunkt der jeweiligen Nutzerhandlung. Die Speicherung erfolgt auf Grundlage unserer berechtigten Interessen, als auch der Nutzer an Schutz vor Missbrauch und sonstiger unbefugter Nutzung. Eine Weitergabe dieser Daten an Dritte erfolgt grundsätzlich nicht, außer sie ist zur Verfolgung unserer Ansprüche erforderlich oder es besteht hierzu eine gesetzliche Verpflichtung gem. Art. 6 Abs. 1 lit. c DSGVO.
                        </span>
                    </p>

                    <p><strong>Kontaktaufnahme</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Bei der Kontaktaufnahme mit uns (z.B. per Kontaktformular, E-Mail, Telefon oder via sozialer Medien) werden die Angaben des Nutzers zur Bearbeitung der Kontaktanfrage und deren Abwicklung gem. Art. 6 Abs. 1 lit. b) DSGVO verarbeitet. Die Angaben der Nutzer können in einem Customer-Relationship-Management System ("CRM System") oder vergleichbarer Anfragenorganisation gespeichert werden.<br>
                            <br>
                            Wir löschen die Anfragen, sofern diese nicht mehr erforderlich sind. Wir überprüfen die Erforderlichkeit alle zwei Jahre; Ferner gelten die gesetzlichen Archivierungspflichten.
                        </span>
                    </p>

                    <p><strong>Kommentare und Beiträge</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Wenn Nutzer Kommentare oder sonstige Beiträge hinterlassen, werden ihre IP-Adressen auf Grundlage unserer berechtigten Interessen im Sinne des Art. 6 Abs. 1 lit. f. DSGVO gespeichert. Das erfolgt zu unserer Sicherheit, falls jemand in Kommentaren und Beiträgen widerrechtliche Inhalte hinterlässt (Beleidigungen, verbotene politische Propaganda, etc.). In diesem Fall können wir selbst für den Kommentar oder Beitrag belangt werden und sind daher an der Identität des Verfassers interessiert.<br>
                        </span>
                    </p>

                    <p><strong>Einbindung von Diensten und Inhalten Dritter</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Wir setzen innerhalb unseres Onlineangebotes auf Grundlage unserer berechtigten Interessen (d.h. Interesse an der Analyse, Optimierung und wirtschaftlichem Betrieb unseres Onlineangebotes im Sinne des Art. 6 Abs. 1 lit. f. DSGVO) Inhalts- oder Serviceangebote von Drittanbietern ein, um deren Inhalte und Services, wie z.B. Videos oder Schriftarten einzubinden (nachfolgend einheitlich bezeichnet als “Inhalte”). <br>
                            <br>
                            Dies setzt immer voraus, dass die Drittanbieter dieser Inhalte, die IP-Adresse der Nutzer wahrnehmen, da sie ohne die IP-Adresse die Inhalte nicht an deren Browser senden könnten. Die IP-Adresse ist damit für die Darstellung dieser Inhalte erforderlich. Wir bemühen uns nur solche Inhalte zu verwenden, deren jeweilige Anbieter die IP-Adresse lediglich zur Auslieferung der Inhalte verwenden. Drittanbieter können ferner so genannte Pixel-Tags (unsichtbare Grafiken, auch als "Web Beacons" bezeichnet) für statistische oder Marketingzwecke verwenden. Durch die "Pixel-Tags" können Informationen, wie der Besucherverkehr auf den Seiten dieser Website ausgewertet werden. Die pseudonymen Informationen können ferner in Cookies auf dem Gerät der Nutzer gespeichert werden und unter anderem technische Informationen zum Browser und Betriebssystem, verweisende Webseiten, Besuchszeit sowie weitere Angaben zur Nutzung unseres Onlineangebotes enthalten, als auch mit solchen Informationen aus anderen Quellen verbunden werden.
                        </span>
                    </p>

                    <p><strong>Youtube</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Wir binden die Videos der Plattform “YouTube” des Anbieters Google LLC, 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA, ein. Datenschutzerklärung: <a href="https://www.google.com/policies/privacy/">https://www.google.com/policies/privacy/</a>, Opt-Out: <a href="https://adssettings.google.com/authenticated">https://adssettings.google.com/authenticated</a>.
                        </span>
                    </p>

                    <p><strong>Google Fonts</strong></p>
                    <p>
                        <span class="ts-muster-content">
                            Wir binden die Schriftarten ("Google Fonts") des Anbieters Google LLC, 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA, ein. Datenschutzerklärung: <a href="https://www.google.com/policies/privacy/">https://www.google.com/policies/privacy/</a>, Opt-Out: <a href="https://adssettings.google.com/authenticated">https://adssettings.google.com/authenticated</a>.
                        </span>
                    </p>

                    <a href="https://datenschutz-generator.de" class="dsg1-5" rel="nofollow"> Erstellt mit Datenschutz-Generator.de von RA Dr. Thomas Schwenke</a>, vom Websiteinhaber angepasst.
                </div>





                <div class="tab-pane fade" id="short">
                    <p><strong>Wichtig:</strong></p>
                    <p>
                        Diese Version bietet eine kurze und leicht verständliche Erklärung der vollständigen deutschen
                        Version der Datenschutzerklärung.<br>
                        Diese Version wird NUR für den Komfort unserer Benutzer bereitgestellt. Die vollständige
                        deutsche Version,
                        die über einen Reiter oben zugänglich ist, ist für alle rechtlichen Angelegenheiten bindend.<br>
                        Sollte ein Unterschied / Fehler in diese Version bestehen, ist die vollständige deutsche Version
                        der Datenschutzerklärung als richtig anzusehen.<br>
                    </p>
                    <br>

                    <p><strong>Warum das ganze?</strong></p>
                    <ul>
                        <li>
                            Im Mai 2018 tritt eine umfassende Aktualisierung der Europäischen Datenschutzgesetze
                            in Kraft.
                        </li>
                        <li>
                            Wir aktualisieren unsere Datenschutzerklärung, um den neuen Gesetzen zu entsprechen.
                            Außerdem informieren wir Dich auch über Deine persönlichen Daten,
                            die wir bei der Nutzung von Lara pflegen und weitergeben.
                        </li>
                        <li>
                            Wir sind bestrebt, nur das absolut erforderliche Mindestmaß an persönlichen Daten zu
                            verwenden,
                            die für den bestimmungsgemäßen Betrieb unserer Dienste erforderlich sind.
                        </li>
                        <li>
                            Wir tun unser Bestes, um Deine Daten zu schützen und verantwortungsvoll und sorgfältig zu
                            behandeln.
                        </li>
                        <li>
                            Die folgenden Abschnitte enthalten einen Überblick darüber, welche Daten von Dir zu
                            welchem Zweck verwendet
                            werden und wie du uns kontaktieren kannst, wenn du Änderungen oder Löschungen wünschst.
                        </li>
                        <li>
                            Danach bitten wir Dich um Erlaubnis, Deine Daten wie beschrieben zu verwenden.
                        </li>
                    </ul>
                    <br>

                    <p><strong>Welche Daten sammeln wir über Nutzer und zu welchen Zwecken?</strong></p>
                    <p>
                        <strong>Von allen, die auf Lara zugreifen, sowohl registrierte Benutzer als auch Gäste:</strong><br>
                        <ul>
                            <li><strong>Wir führen Protokolle aller Anfragen an den Server ("Log"), die
                                    die Uhrzeit / das Datum,
                                    die IP-Adresse,
                                    den Typ des verwendeten Browsers,
                                    die von Dir besuchten Seiten und
                                    die von Dir geposteten Seiten
                                enthalten</strong> - im Grunde alles, was Du uns schickst (plus die Antworten
                                des Servers). Dies wird benötigt, um den Dienst selbst bereitzustellen
                                ("so funktioniert das Web"),
                                und auch für die Fehlersuche, falls irgendwelche Probleme auftreten sollten. Diese
                                Protokolle werden 30 Tage lang aufbewahrt und anschließend gelöscht.
                                Nur Lara Administratoren haben Zugriff darauf.
                            </li>
                            <li><strong>Die IP-Adresse wird länger gespeichert, wenn Du etwas in Lara
                                veröffentlichst</strong> - zu wissen,
                                wer was im Falle von seltsamen / illegalen Nachrichten gepostet hat.
                                Grundsätzlich speichern wir für jeden Eintrag für einen Dienst oder einen Kommentar in Lara
                                die IP-Adresse zusammen mit anderen Daten. Nur Lara Administratoren haben Zugriff darauf.
                            </li>
                        </ul>


                        <strong>Wenn sich ein Benutzer registriert und / oder anmeldet:</strong><br>
                        <ul>
                            <li><strong>Dein Spitzname in der Sektion, auch bekannt als "Clubname"</strong> - benötigt, um
                                zu identifizieren,
                                wer einen Dienst übernimmt oder einen Kommentar schreibt. Jeder kann es sehen.
                            </li>
                            <li><strong>Dein voller Name</strong> - Wir verwenden den Vornamen als Deinen Spitznamen, wenn
                                keiner angegeben wurde,
                                und wir zeigen anderen angemeldeten Benutzern Deinen vollständigen Namen an, um leichter
                                festzustellen, wer einen Dienst macht
                                (z. B. Fabian A und Fabian B sind zwei verschiedene Personen und nur ein Vorname könnte zu
                                Verwirrung führen).
                            </li>
                            <li><strong>Deine Club-ID aka "Clubnummer"</strong> (falls zutreffend) - wird verwendet,
                                um Dich innerhalb des Systems zu identifizieren, wenn Du dich anmeldest oder Einträge
                                vornimmst.
                                Andere Benutzer können Deine ID in Lara nicht sofort sehen, aber sie ist im Code der Webseite
                                sichtbar.
                                Betrachte sie daher als sichtbar für andere Benutzer, wenn sie eingeloggt sind. Gäste
                                (nicht eingeloggt) können Deine Club ID nicht sehen.
                            </li>
                            <li><strong>Deine Emailadresse</strong> (falls zutreffend) - wird anstelle einer Club-ID
                                verwendet, wenn Du keine ID hast.
                                Kann auch verwendet werden, um Dich zu kontaktieren.
                            </li>
                            <li><strong>Dein Passwort ist uns nicht bekannt</strong> - wir speichern nur eine Hash-Version
                                für Authentifizierungszwecke,
                                aber es ist unmöglich, das ursprüngliche Passwort daraus abzuleiten. Du bist dafür
                                verantwortlich,
                                Dein Passwort sicher zu verwahren und solltest es ändern (oder uns bitten, dies zu tun),
                                wenn Du vermutest, dass jemand anderes Zugang dazu erhalten hat.
                            </li>
                            <li><strong>Wir speichern Deinen Namen zusammen mit den Änderungen, die Du an Events
                                (also Veranstaltungen, Info-Nachrichten, usw.) oder Umfragen vornimmst</strong> (z. B.
                                Titeländerung oder hinzugefügte Details / Informationen, Hinzufügen / Entfernen von Diensten
                                usw.)
                                - dies bietet Verantwortlichkeit, so dass jeder weiß, wer wann was geändert hat. Dies hilft,
                                unerwartete Änderungen oder Fehler aufzuspüren.
                                Diese Information existiert, solange das Event oder die Umfrage existiert und wird
                                gelöscht, wenn das Event / die Umfrage gelöscht wird.
                                Alle angemeldeten Benutzer können diese "Änderungsliste" am Ende jedes Events sehen.
                            </li>
                            <li><strong>Wir speichern die Zeiten, in denen Du dich bei Lara anmeldest, in einem
                                Protokoll</strong> (inkl. erfolgloser Versuche, z. B. falsches Passwort)
                                für bis zu 30 Tage. Dies wird für die Fehlersuche benötigt und ist nur auf einer speziellen
                                "Logs"-Seite für Clubmanagement und Lara-Administratoren
                                sichtbar.
                            </li>
                        </ul>
                        <p>Unsere Beta-Testumgebung "Berta" funktioniert nach den gleichen Regeln.</p>
                    </p>

                    <p><strong>"Cookies"</strong></p>
                    <p>
                        An dieser Stelle möchten wir Dich auch informieren, dass wir sogenannte <strong>"Cookies"</strong> einsetzen.
                        Als „Cookies“ werden kleine Dateien bezeichnet, die auf Rechnern der Nutzer gespeichert werden.
                        Innerhalb der Cookies können unterschiedliche Angaben gespeichert werden.<br>
                        So kann z.B. der Login-Status gespeichert werden, wenn die Nutzer nochmal Lara aufmachen. Ebenso können in einem solchen Cookie oder im Browser-Speicher ("local storage") die Interessen der Nutzer gespeichert werden, z.B. welche Sektionen angezeigt oder ausgeblendet werden sollen, usw.<br>
                        Falls Du nicht möchtest, dass Cookies auf Deinem Rechner gespeichert werden, kannst Du die entsprechende Option in den Systemeinstellungen Deines Browsers deaktivieren.<br>
                        Gespeicherte Cookies können in den Systemeinstellungen des Browsers auch gelöscht werden.<br>
                        Der Ausschluss von Cookies kann jedoch zu Funktionseinschränkungen von Lara führen.<br>
                    </p>
                    <br>

                    <p><strong>Wie sind die Daten gespeichert und wer hat Zugriff darauf?</strong></p>
                    <p>
                        - Unser Server wird bereitgestellt von <a href="https://www.fem.tu-ilmenau.de">FeM</a>, aber ihre
                        Administratoren dürfen nur im Notfall
                        auf unsere Software zugreifen (z. B. wenn der Server einem Hackerangriff ausgesetzt war und
                        heruntergefahren werden sollte, oder ähnliche Fälle).
                        Andernfalls dürfen sie nicht auf die nicht öffentlichen Daten der Lara-Software zugreifen.
                    </p>
                    <p>
                        - Die Administratoren von Lara haben vollen Zugriff auf alle Daten - dies wird benötigt, um die
                        Entwicklung der Plattform fortzusetzen.
                        (Sie verwenden auch ein Analysetool, um den Service zu entwickeln und zu warten.)
                    </p>
                    <p>
                        - Die Administratoren von Lara verwalten auch eine lokale Sicherungskopie aller Daten.
                    </p>
                    <p>
                        - Die Benutzer, einschließlich des Sektionsmanagements, erhalten nur Zugriff auf Teile Ihrer
                        Daten,
                        die für die Bereitstellung der Hauptservices von Lara erforderlich sind. Sie planen Personaleinsätze
                        Deiner oder einer anderen Sektion, und teilen Veranstaltung untereinander für eine einfachere
                        Zusammenarbeit zwischen verschiendenen Sektionen.
                    </p>
                    <p>
                        - Wir wollen die Ausbildung unserer Mitglieder und anderer Studenten der TU Ilmenau unterstützen.
                        Daher bieten wir Möglichkeiten,
                        Lara in Softwareprojekten, Bachelor- und Masterarbeiten, sowie anderen wissenschaftlichen
                        Publikationen zu nutzen und zu entwickeln.
                        Zu diesem Zweck erhalten die Studierenden vorübergehend Zugang zu den oben aufgeführten
                        personenbezogenen Daten in dem für ihr
                        Studium erforderlichen Mindestbetrag. Wenn eine Veröffentlichung erfolgt (Vorträge, Thesen
                        usw.), müssen die Daten anonymisiert werden
                        (z. B. Dein vollständiger Name ersetzt durch "Benutzer 123" oder ähnliches).
                    </p>
                    <p>
                        - Wir geben Deine Daten sonst nicht an andere Personen oder Unternehmen weiter.
                    </p>
                    <p>
                        - Wir verwenden keine Social-Media-Plugins oder andere Tracking-Software.
                    </p>
                    <br>

                    <p><strong>Wenn Du sehen möchtest, welche persönlichen Daten wir über Dich haben:</strong></p>
                    <p>
                        Bitte schreibe uns eine E-Mail an <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> mit der
                        Bitte um Deine Informationen.<br>
                        Die Anfrage wird innerhalb von 10 Werktagen oder weniger bearbeitet (normalerweise 1-2
                        Tage).<br>
                        Wir werden Dir alle Daten, die wir über Dich haben, per E-Mail an die uns bekannte Adresse
                        zukommen lassen.<br>
                    </p>
                    <br>

                    <p><strong>Wenn Du einen Fehler in Deinen Daten gefunden hast:</strong></p>
                    <p>
                        Bitte schreibe uns eine E-Mail an <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> mit den
                        Informationen, die Du geändert haben möchtest.<br>
                        Die Anfrage wird innerhalb von 10 Werktagen oder weniger bearbeitet (normalerweise 1-2
                        Tage).<br>
                        Wir senden Dir eine Bestätigung, nachdem Deine Daten aktualisiert wurden.<br>
                    </p>
                    <br>

                    <p><strong> Wenn Du möchtest, dass wir Deine Daten löschen:</strong></p>
                    <p>
                        Bitte schreibe uns eine E-Mail an <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> mit
                        einer Bitte um Löschung Deiner Daten. <br>
                        Die Anfrage wird innerhalb von 10 Werktagen oder weniger bearbeitet.<br>
                        Wir senden Dir eine Bestätigung, nachdem Deine Daten vollständig entfernt wurden.<br>
                    </p>
                    <br>

                    <p><strong>Um Lara weiter nutzen zu können, benötigen wir Deine Zustimmung.</strong></p>
                    <p>
                        - Gemäß dem neuen Gesetz sollte jeder Benutzer über die gesammelten persönlichen Daten, die
                        Zwecke, für die er genutzt wird,
                        informiert werden und dann seine Zustimmung zu der Verwendung seiner Daten für diese Zwecke
                        geben.
                    </p>
                    <p>
                        - Wenn Du diese Datenschutzerklärung ablehnst, kannst Du die von Lara angebotenen Dienste nicht
                        nutzen.
                    </p>
                    <p>
                        - Wenn Du Fragen hast oder unsicher bist, kontaktiere uns bitte unter
                        <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> und wir werden unser Bestes tun, um alles zu
                        klären. Wir sind hier um zu helfen!
                    </p>
                </div>




                <div class="tab-pane fade" id="english">
                    <p><strong>IMPORTANT:</strong></p>
                    <p>
                        This version provides a short and easily readable explanation of the full German version of the
                        privacy policy.<br>
                        This version is provided for our users' convenience ONLY. The full German version accessible
                        through a tab above is binding for any legal matters. <br>
                        If any difference/mistake happens to slip into this version, the full German version of the
                        privacy policy is to be considered the right one.<br>
                    </p>

                    <br>
                    <p><strong> What is this all about?</strong></p>
                    <ul>
                        <li>
                            In May of 2018, a major upgrade to Europe’s data protection laws becomes enforceable.
                        </li>
                        <li>
                            We update our privacy policy to comply with the new laws, and also to inform you about your
                            personal data we maintain and share when you use Lara.
                        </li>
                        <li>
                            We strive to use only the absolutely required minimum of your personal data that is needed
                            for our service to work as intended.
                        </li>
                        <li>
                            We do our best effort to protect your data and handle it responsibly and carefully.
                        </li>
                        <li>
                            The following few paragraphs will provide the overview of what data about you we use, for
                            what purpose, and how to contact us in case you want it altered or removed.
                        </li>
                        <li>
                            Afterwards, we will ask you to give us permission to use your data as described.
                        </li>
                    </ul>

                    <br>
                    <p><strong> What data do we collect about users and for what purpose?</strong></p>
                    <p>
                        <strong>From everyone accessing Lara, both registered users and guests:</strong><br>
                        <ul>
                            <li><strong>We maintain a log of all requests to the server that contains the time/date, IP
                                    address, type of browser used, what pages you visit, what you post</strong> - basically,
                                everything you send our way (plus the responces of the server). This is needed to provide
                                the service itself ("this is how web works"), and also for troubleshooting if any problems
                                should arise. These logs are held for 30 days and deleted afterwards. Only Lara
                                administrators have access to them.
                            </li>
                            <li><strong>IP Address is saved longer if you post anything in Lara</strong> - to know who
                                posted what in case of any weird/illegal messages. Basically, for every entry for a shift or
                                comment in Lara we save the IP address along other data. Only Lara administrators have
                                access to it.
                            </li>
                        </ul>

                        <strong>If a user registers and/or logs in:</strong><br>
                        <ul>
                            <li><strong>Your nickname in the section, aka "Clubname"</strong> - needed to identify who takes
                                a shift or writes a comment. Everyone can see it.
                            </li>
                            <li><strong>Your full name</strong> - we use first name as your nickname if none was provided,
                                and we show your full name to other logged in users to identify who is doing a shift more
                                easily (e.g. Fabian A and Fabian B are two different persons, and just a first name could
                                lead to confusion).
                            </li>
                            <li><strong>Your club ID aka "Clubnummer"</strong> (if applicable) - is used to identify you
                                inside the system as you login or make entries. Other users can not see your ID in Lara
                                right away, but it is visible in the web page code, so consider it visible to other users
                                when logged in. Guests (not logged in) can not see your club ID.
                            </li>
                            <li><strong>Your e-mail address</strong> (if applicable) - is used instead of a club ID if you
                                don't have one. May also be used to contact you.
                            </li>
                            <li><strong>Your password is unknown to us</strong> - we only store a hashed version of it for
                                authentication purposes, but it is impossible to derive the original password from it. You
                                are in charge of keeping your password safe, and should change it (or ask us to do this) if
                                you suspect someone else has gained access to it.
                            </li>
                            <li><strong>We save your name along the changes you make to events or surveys</strong> (e.g.
                                title change or added details/info, add/remove shifts, etc.) - this provides accountability,
                                so everyone knows who changed what and when. This helps to track down unexpected changes or
                                mistakes. This information exists as long as the event or survey exists, and is deleted when
                                event/survey is deleted. All logged in users can see this "list of changes" at the bottom of
                                every event.
                            </li>
                            <li><strong>We save the times you log in to Lara in a log</strong> (incl. unsuccessfull
                                attempts, e.g. wrong password) for up to 30 days. This is needed for troubleshooting errors,
                                and is visible on a special "logs" page for club management and Lara administrators only.
                            </li>
                        </ul>
                        <p>Our beta-test environment "Berta" works with the same rules.</p>
                    </p>
                    <br>

                    <p><strong>"Cookies"</strong></p>
                    <p>
                        At this moment we would like too also inform you that we use so-called "Cookies" to enhance your experience with Lara. "Cookies" are small files stored on your computer that can hold various data inside.
                        For example, we can store your login status inside so you don't have to login every time you access Lara.
                        We can also use them (or browser local storage memory) to save your preferences, e.g. which
                        section's events to hide and which ones to show, etc.<br>
                        If you want to disable this feature, you can do it in the settings of your browser. You can also delete stored "cookies" there.<br>
                        Beware that this action may limit or disable some of the website's features.
                    </p>
                    <br>

                    <br>
                    <p><strong>How is the data stored and who has access to it?</strong></p>
                    <p>
                        - Our server is provided by <a href="https://www.fem.tu-ilmenau.de">FeM</a>, but their
                        administrators are only allowed to access our software in case of emergency (e.g. the server was
                        a subject to a hacking attack and should be powered down, or similar cases). Otherwise they are
                        not allowed to access the insides of Lara software.
                    </p>
                    <p>
                        - The administrators of Lara have full access to all the data - this is needed to
                        continue development of the platform. (They also use an analytics tool to help with developing
                        and troubleshooting the service.)
                    </p>
                    <p>
                        - The administrators of Lara also maintain a local backup copy of all the
                        data.
                    </p>
                    <p>
                        - The users, including section management, only get access to parts of your data needed to
                        provide the main services of Lara - scheduling personnel for your or other section, and sharing
                        events between sections for easier collaboration.
                    </p>
                    <p>
                        - We want to support education of our members and other students of TU Ilmenau. Therefore, we
                        provide opportunities to use and develop Lara in software projects, Bachelor and Masters
                        thesises, or other scientific publications. For that purpose, students will temporarily get
                        access to personal data listed above in the minimal amount needed for their studies. If any
                        publication will be made (papers, thesises, etc.), the data must be anonymized (e.g. your full
                        name substituted for "User 123" or similar).
                    </p>
                    <p>
                        - We do not share your data with any other individuals or companies.
                    </p>
                    <p>
                        - We do not use any social media plugins or any other tracking software.
                    </p>
                    <br>

                    <p><strong>If you want to see what personal data about you we have:</strong></p>
                    <p>
                        Please write us an e-mail at <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> with the request
                        for your information.<br>
                        The request will be processed in 10 business days or less (usually 1-2 days).<br>
                        We will provide you all the data we have on you via e-mail.<br>
                    </p>
                    <br>

                    <p><strong>If you found a mistake in your data:</strong></p>
                    <p>
                        Please write us an e-mail at <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> with the
                        information you want changed.<br>
                        The request will be processed in 10 business days or less (usually 1-2 days). <br>
                        We will send you a confirmation after your data has been updated.<br>
                    </p>
                    <br>

                    <p><strong> If you want us to remove your data:</strong></p>
                    <p>
                        Please write us an e-mail at <a href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> with a request for
                        your data to be deleted.<br>
                        The request will be processed in 10 business days or less. <br>
                        We will send you a confirmation after your data has been fully removed.<br>
                    </p>
                    <br>

                    <p><strong>To continue using Lara, we need you to provide us with your consent.</strong></p>
                    <p>
                        - According to the new law, every user should be informed about the personal data that is
                        collected, the purposes it is used for, and then give their consent to the usage of their data
                        for that purposes.
                    </p>
                    <p>
                        - If you reject this privacy policy, you can not use the services Lara provides.
                    </p>
                    <p>
                        - If you have any questions or are unsure about this decision, please contact us at <a
                            href="mailto:lara@ilmenauer-studentenclub.de">lara@ilmenauer-studentenclub.de</a> and we will do our best to clarify everything.
                        We are here to help.
                    </p>
                </div>
            </div>
            @auth
                @if(Auth::user()->privacy_accepted == 0)
                    <div class="card-footer">
                        <form method="post" action="{{url('userAgreesPrivacy')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-success">{{ __("mainLang.privacyAgree") }}</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
@stop
