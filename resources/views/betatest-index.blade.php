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
			Und das ist der zweite Betatest.<br>
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
				<li>Erstelle Veranstaltungen für kommende Woche(n), inkl. Werbetext und interne Details, <br>
				und trage da Dienste ein (aus dem "alten" DP kopieren)</li>
				<li>Erstelle Veranstaltungen der vergangenen Wochen, inkl. Werbetext (von der Webseite/FB) und interne Details (aus "altem" Kalender) <br>
				und trage da Dienste ein (aus dem alten DP kopieren) - brauche ich für Statistik</li>
				<li>Probiere mal eine Testveranstaltung zu erstellen und danach zu löschen usw.</li>
				<li>Spiele noch ein bisschen rum - mir sind keine weitere Aufgaben eingefallen, aber vielleicht dir? <br>
				Also wenn du einen Knopf siehst, und nicht weisst, was er tut - drücke ihn! Vielleciht ist dahinter was tolles versteckt :)</li>
				<li>Schreibe mir eine Nachricht mit deinem Feedback - was hat nicht funktioniert, was sollte man ändern usw.</li>
			</ol> 
		</p>
		<p>
			Danke für deine Hilfe! 
		</p>
	</div>
</div>




@stop