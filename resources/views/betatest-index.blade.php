@extends('layouts.master')

@section('title')
	Beta
@stop

@section('content')

	<div class="panel">
		<div class="panel-heading"><h5>Du siehst jetzt die Betaversion von Lara.</h5></div>
		<div class="panel-body">
			<p>
				Das ist teils noch "work in progress" - einige Sachen funktionieren nicht ganz, nicht immer, oder können sich noch ändern.
			</p>
			<p>
				Falls du einen Fehler findest oder einen Änderungswunsch hast, 
				sende bitte eine Nachricht an <a href="mailto:maxim.drachinskiy@bc-studentenclub.de">Maxim</a>.
			</p>
			<p>
				Bei Fehlern strukturiere dein Feedback nach folgendem Muster:<br>
				<ol type="A">
					<li>Was hast du gemacht? </li>
					<li>Was ist passiert? </li>
					<li>Was hast du stattdessen erwartet?</li>
					<li>Datum und Zeit wenn es passiert ist - damit wir den Fehler in Logs finden.</li>
				</ol>
			</p>
			<p>
				Danke für deine Hilfe! 
			</p>
		</div>
	</div>

@stop