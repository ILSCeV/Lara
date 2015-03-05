@extends('layouts.master')

@section('title')
	VedSt Home: current status
@stop

@section('content')

<div class="panel">
	<div class="panel-heading"><h5>Hey,</h5></div>
	<div class="panel-body">
		<p>
			Ich bin Lara, der neue Kalender mit integriertem Dienstplan.<br>
			Wenn du das siehst, nimmst du am Betatest teil. 
			<span class="text-muted"> Oder gehörst zu den Entwicklern - hallo Team! ;) </span><br>
			Wir nähern uns dem Ende von dem Softwareprojekt und wollen dich bitten, 
			Lara zu testen. <br>
			Bitte vergiss aber nicht - das ist teils noch "work in progress" 
			und einige Sachen funktionieren nicht ganz, nicht immer, oder können sich noch ändern.
		</p>
		<p>
			<strong>"Worum geht's denn?"</strong> 
		</p>
		<p>
			Bitte erfülle die Aufgaben unten und gib uns Feedback dazu - ob alles geklappt hat, 
			ob es Probleme gab, ob etwas fehlt oder ganz ungeschickt positioniert ist.<br>
			Bei Fehlern bitte strukturiere dein Feedback nach folgendem Muster:<br>
			<ol type="A">
				<li>Was hast du gemacht? </li>
				<li>Was ist passiert? </li>
				<li>Was hast du stattdessen erwartet?</li>
				<li>Datum und Zeit wenn das passiert ist - damit wir den Fehler in Logs finden.</li>
			</ol>
		</p>
		<p>
			<strong>Deine Aufgaben:</strong>
			<ol type="1">
				<li>Dein Mitbewohner will am nächsten Mittwoch ein Dienst machen - trage ihn als Externen ein.</li>
				<li>Später erfährst du, dass er 15 Minuten später kommt - füge einen Kommentar hinzu</li>
				<li>Logge dich ein, trage sich selbst als Dienst an paar Stellen ein.<br>
					<strong>Während des Betatests musst du keine Clubnummer oder Passwörter eingeben.<br>
					Dir wird statt ClubID ein zufällig gewähles Superheld zugewiesen</strong>, in dessen Namen du agieren kannst.<br>
					Sie machen alle sehr gerne Dienste! Falls dir der Name nicht gefällt logge dich aus und nochmal ein.</li>
				<li>Jetzt entferne einen von diesen Einträgen, ersetze dich mit einem anderen Clubei.</li>
				<li>Du willst mit den Clubmenschen zusammen eine Seifenkiste bauen - erzeuge einen Aufgabendienstplan dafür.</li>
				<li>Wenn du Marketing oder CL bist - bitte erzeuge zwei neue Veranstaltungen, füge diesen Dienstpläne hinzu.</li>
				<li>Jetzt ändere bei einer davon die Startzeit auf 12 Uhr und lösche die zweite.</li>
				<li>Spiele noch ein bisschen rum - mir sind keine weitere Aufgaben eingefallen, aber vielleicht dir? ;)</li>
			</ol> 
		</p>
		<p>
			Rückmeldung bitte <strong>bis Samstag den 25.01.2015 um 11:00</strong> an <strong>maxim.drachinskiy@bc-studentenclub.de</strong> senden.
		</p>
		<p>
			Danke für deine Hilfe! 
		</p>
	</div>
	<div class="panel-header"><h5 align="right">xx Lara</h5></div>
</div>




@stop