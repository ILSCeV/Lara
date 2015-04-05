<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

	/**
	 * Each table gets its own method
	 */
		$this->call('ClubEventsTableSeeder');
		$this->call('SchedulesTableSeeder');
		$this->call('PersonsTableSeeder');
		$this->call('JobtypesTableSeeder');
		$this->call('PlacesTableSeeder');
		$this->call('ClubsTableSeeder');
		$this->call('ScheduleEntriesTableSeeder');
	}
}

class ClubEventsTableSeeder extends Seeder {

    public function run()
    {
		Eloquent::unguard();
		
		/**
		 * clearing table
		 */
        DB::table('club_events')->delete();

		/**
		 * creating public events
		 */
        ClubEvent::create(array('evnt_title' => 'CGW Weinabend', 
							'evnt_subtitle' => 'Das Leben ist zu kurz, um schlechten Wein zu trinken!',
							'plc_id' => '1',
							'evnt_date_start' => '2015-02-01',
							'evnt_date_end' => '2015-02-02',
							'evnt_time_start' => '21:00',
							'evnt_time_end' => '01:00',
							'evnt_public_info' => '
								Am 11. Januar beginnt unsere Clubgeburtstagswoche 2015! Starten wollen wir die Festwoche anlässlich unseres 46sten Geburstages mit dem allseits beliebten Weinabend!
								Im Rahmen unserer Geburtstagswoche wollen wir dabei den Weinabend noch ein wenig mehr Ambiente verleihen. Freut euch auf einen aufwendiger geschmückten Club, beleuchtet einzig und allein von Kerzen. Dazu kommen üppiger bestückte Käseteller sowie anderen Überraschungen, die wir hier noch nicht verraten wollen!
								Um zu gewährleisten, dass ihr all das auch genießen könnt ohne ständig einen Platz suchen zu müssen oder angerempelt zu werden, haben wir uns dazu entschlossen nur eine begrenzte Anzahl an Leute für diesen Abend zu zu lassen. Damit ihr nicht in feiner Garderrobe am Sonntagabend anstehen müsst und am Ende nicht reinkommt, gibt es die Karten nur im Vorverkauf!
								Der Weinabend: "Das Leben ist zu kurz, um schlechten Wein zu trinken."
								Diesem Motto bleiben wir, wie gewohnt, jeden ersten Sonntag im Monat treu und präsentieren euch unseren Weinabend in entspannter Atmosphäre bei Kerzenschein.
								An diesem Abend bieten wir Euch eine ständige Auswahl aus 30 ausgesuchten Weinen, die durch ein- bis zwei wechselnde Weine des Abends ergänzt werden. Unsere Weinvielfalt ist darauf ausgerichtet ein möglichst großes Geschmacksspektrum abzudecken, um den Gaumen enes jeden Weinliebhabers mit einem
								edlen Tröpfchen zu erfrischen.
								Abgerundet wird das Bouquet des Weines durch unseren reichhaltigen Käseteller.
								Insgesamt ist dieser Abend also die passende Gelegenheit das Wochenende gemütlich bei elegant ruhiger Musik mit einem Gläschen Wein zusammen mit guten Freunden ausklingen zu lassen.
								Unser Wein des Abend: t.b.a.',
							'evnt_private_details' => 'Passwort ist "password".',
							'evnt_is_private' => '0'));
							
        ClubEvent::create(array('evnt_title' => 'Interner Abend', 
							'evnt_subtitle' => 'CV findet wie gewohnt statt.',
							'plc_id' => '1',
							'evnt_date_start' => '2015-02-02',
							'evnt_date_end' => '2015-02-03',
							'evnt_time_start' => '19:00',
							'evnt_time_end' => '03:00',
							'evnt_public_info' => '',
							'evnt_private_details' => '',
							'evnt_is_private' => '1'));
							
        ClubEvent::create(array('evnt_title' => 'Sojus 3000 & below a silent sky', 
							'evnt_subtitle' => 'Live in Concert',
							'plc_id' => '2',
							'evnt_date_start' => '2015-02-04',
							'evnt_date_end' => '2015-02-04',
							'evnt_time_start' => '21:00',
							'evnt_time_end' => '01:00',
							'evnt_public_info' => '
								Instrumentaler Progressive/ Post-Rock live erwartet euch am heutigen Tag. Wir holen die Ilmenauer Baracke in den bc und lassen SOJUS3000 aufspielen. 
								Brachiale Klangwände platziert um psychedelische
								Soundwelten werden euer Gehör passieren und auf Garantie darin auch hängen bleiben. 
								Als Support haben wir zusätzlich noch Below A Silent Sky eingeladen.
								=========
								Sojus 3000
								=========
								http://sojus3000.de/
								SOJUS3000 formen mit ihrer Mischung aus Post- und Stonerrock die perfekten Sitzmöglichkeiten für ein umfangreiches Kopfkino-Programm. Rein instrumental aufgebaut und mit elektronischen Elementen durchdrungen, führen die Songs durch psychedelische Passagen, um die sich brachiale Soundwände aufbauen.
								Was 2009 mit gelegentlichen Sessions während des Studiums begann, hat sich zu einer vorrangig dem Postrock und Doom verschriebenen Formation entwickelt. Seit der Fertigstellung ihrer Debut-EP 6EQUJ5 im Jahr 2011 tourt die Band durch Deutschland. Ende 2013 begannen die Vorbereitungen der ersten LP ATLAS, die im Herbst 2014 erscheint.
								Einen starken Einfluss haben Bands wie My Sleeping Karma, Mogwai, This Will Destroy You, Russian Circles, God Is An Astronaut, Isis, Ufomammut.
								Mitglieder:
								Johannes Bebensee – Electronic/Programming, Bass
								Christian Kehling – Guitar
								Sascha Pälchen – Drums
								Thomas Thron – Bass, Guitar
								===============
								Below A Silent Sky
								===============
								http://belowasilentsky.de/
								Gegründet im Jahr 2012 spielt sich die Ilmenauer Band Below A Silent Sky immer mehr in die Gehörgänge der mitteldeutschen Stromgitarrenfreunde. Was als just 4 fun Studentenband begann, entwickelte sich dabei als engagiertes Progressive/Psychedelic/Postrock Projekt immer weiter. Die Musiker Robin, Christian, Thomas und Diego, die aus ganz Deutschland zueinander gefunden haben, vereinen dabei diverse musikalische Einflüsse. Die Songs von Below A Silent Sky sind dadurch nicht pauschal kategorisierbar, sondern beinhalten Charakterzüge vielseitiger Musik-Genres. Von melodiös verträumten 70er Jahre Grooves über orientalisch anmutende Passagen bis hin zu messerscharfen Metalriffs in ungeraden Taktarten können dabei keinen Grenzen gezogen werden. Der eigene Sound der Band bleibt dabei dennoch konstant vorhanden.
								Live begeistern Below A Silent Sky das Publikum mit ihrer eindrucksvollen Performance. Eine kurzweilige, intensive, instrumentale Erfahrung, die einem Auftritt mit Vokalisten in nichts nachsteht, was nicht zu letzt an der spürbaren Leidenschaft der Band liegt. ',  
							'evnt_private_details' => '
								Sojus: 150 euz 
								Below: 80 euz 
								Keine ÜN 
								Abholung aus b5 
								Plakate kommen 
								Merch wäre gut 
								Rest normal ',
							'evnt_is_private' => '0'));
		
        ClubEvent::create(array('evnt_title' => 'HipHop im <bc>', 
							'evnt_subtitle' => '46 Jahre und immernoch Hip-Hop?',
							'plc_id' => '1',
							'evnt_date_start' => '2015-02-06',
							'evnt_date_end' => '2015-02-07',
							'evnt_time_start' => '22:00',
							'evnt_time_end' => '03:00',
							'evnt_public_info' => '
								Auch in diesem Jahr feiert der bc wieder Geburtstag.
								Nun ist 46 ein hohes Alter, in dem man nicht mehr so über die Strenge schlagen sollte, aber was solls. YOLO!
								Kein Kaffee und Kuchen. Keine Suppe. Keine Enkel. Dafür LyHo am Mix, also Raps und Beats mit Backspins statt Sechsundvierzig Kerzen. Läuft beim bc.',
							'evnt_private_details' => 'DJ: LyHo',
							'evnt_is_private' => '0'));
							
		ClubEvent::create(array('evnt_title' => 'Die Kosmonauten', 
							'evnt_subtitle' => 'Live in Concert',
							'plc_id' => '1',
							'evnt_date_start' => '2015-02-08',
							'evnt_date_end' => '2015-02-09',
							'evnt_time_start' => '21:00',
							'evnt_time_end' => '01:00',
							'evnt_public_info' => '
								Donnerstag ist Surf-Tag: Die Kosmonauten aus Leipzig machen heute eine Landestop bei uns. Die 4-köpfige Band ist zuweilen in ultra-geheimer Raumfahrtsmission unterwegs. Dabei wird euch Instrumentalmusik der Sechziger, Beat, Twang, Twist und Surf gemixt mit
								osteuropäischer, russischer und Zigeuner-Musik und das Ganze noch zusätzlich gepaart mit einer deftigen Portion Rock-n-Roll um die Ohren geworfen. Da bleibt sicher kein Tanzbein ruhig stehen.
								Extraterrestrisch gut!
								==============
								Die Kosmonauten
								==============
								http://kosmonauten-online.de/
								Weltraumbahnhof Baikonur 1964.
								4 russische Raumfahrer betreten in streng geheimer Mission einen streng geheimen Raketentyp (Iwanowo ZK 12), der so streng geheim ist, dass nicht einmal der russische Geheimdienst etwas davon weiß.
								Es geht um die Erforschung und Bergung suborbital stellaren Weltraumnebels im Sternzeichen des Ophiuchus zur Stahlhärtung und Weiterverwendung desselben in den linken Moskwitschtüren. Damals ein revolutionärer Gedanke, der in Zeiten des Kalten Krieges den Seitenaufprallschutz russischer Automobile um Dekaden vor den der westlichen Konkurrenz katapultiert hätte.
								Die vier Kosmonauten betreten die Rakete.
								Die Düsentriebwerke starten mit lautem Getöse. Unter ungeheurem Lärm hebt sich das Gefährt scheinbar schwerelos in den ukrainischen Nachthimmel, und entfleucht – immer kleiner werdend- im Kosmos.
								Doch da… plötzlich, wie aus dem Nichts, ein greller Blitz gleißenden Lichtes!
								Danach … Stille, … Nacht.
								…Was war passiert?
								Eine Sperrholzplatte am unteren Flexbeuger des Innenflügels hatte sich gelöst und eine Zeit-Raum-Anomalie verursacht. Die ZK 12 war verschwunden.
								Bleilochtalsperre, Thüringen 1999.
								Majestätisch liegt der Stausee im flimmernden Abendrot dieses letzten warmen Septembertages. Nichts vermag die Idylle zu trüben, so scheint es zumindest …
								Ein dumpfes Donnern erklingt, der Himmel reißt auf und gibt einen glühenden Feuerball preis, der mit ungeheurer Wucht in die Fluten des Sees schießt.
								Eine Rauchwolke verhüllt das Ufer.
								Aus dem milchigen Qualm erscheinen 4 Silhouetten, die, näherkommend, in ihren edlen weißen Raumanzügen zweifelsfrei als Kosmonauten zu erkennen sind.
								1964 bis 1999. Reisende durch Zeit und Raum.
								Mitgebracht haben sie die Musik aus ihrer Zeit: Instrumentalmusik im Stile der Sechziger, Beat, Twang, Twist, Surf. Und die Musik aus ihrer Heimat: Osteuropa, russische Folklore und Zigeunermusik. Veredelt wird das extraterrestrische Gemisch mit einer deftigen Portion Rock ‘n’ Roll. Inzwischen haben sie die Tanzsäle der Republik mit insgesamt über 500 Konzerten in allen Himmelsrichtungen erobert. Wo auch immer die Raumkapsel der Kosmonauten einschlägt, die Party ist garantiert.
							',
							'evnt_private_details' => 'Kommen aus Leipzig',
							'evnt_is_private' => '0'));

		
		/**
		 * reporting result to console
		 */
		$this->command->info('Table event seeded with events.');
    }

}

class SchedulesTableSeeder extends Seeder {

    public function run()
    {
		Eloquent::unguard();
		
		/**
		 * Clearing table
		 */
        DB::table('schedules')->delete();

		/**
		 * Creating event schedules
		 */
        Schedule::create(array( 'schdl_title' => 'Weinabend', 
								'schdl_time_preparation_start' => '17:00',
								'schdl_password' => Hash::make('password'),
								'evnt_id' => '1',
								'schdl_is_template' => '1'));

		Schedule::create(array( 'schdl_title' => '',
								'schdl_time_preparation_start' => '20:00',
								'evnt_id' => '2',
								'schdl_is_template' => '0'));
							
        Schedule::create(array( 'schdl_title' => 'Live in Concert', 
								'schdl_time_preparation_start' => '21:00',
								'evnt_id' => '3',
								'schdl_is_template' => '1'));					
							
        Schedule::create(array( 'schdl_title' => '',
								'schdl_time_preparation_start' => '20:00',
								'evnt_id' => '4',
								'schdl_is_template' => '0'));

        Schedule::create(array( 'schdl_title' => '', 
								'schdl_time_preparation_start' => '17:00',
								'evnt_id' => '5',
								'schdl_is_template' => '0'));

		
		/* 
		 * Creating tasks
		 */

        Schedule::create(array( 'schdl_title' => 'T-Shirts drucken', 
								'schdl_due_date' => '2015-02-05',
								'schdl_is_template' => '0'));

        Schedule::create(array( 'schdl_title' => 'Flyer austeilen', 
        						'schdl_due_date' => '2015-02-02',
        						'schdl_show_in_week_view' => '1',
								'schdl_is_template' => '1'));	

		Schedule::create(array( 'schdl_title' => 'Zusammen kochen!',
        						'schdl_show_in_week_view' => '1',
								'schdl_due_date' => '2015-02-07'));			
							
		/**
		 * Reporting result to console
		 */
		$this->command->info('Table schedule seeded with schedules.');
    }

}

class PersonsTableSeeder extends Seeder {

    public function run()
    {
		Eloquent::unguard();
		
		/**
		 * Clearing table
		 */
        DB::table('persons')->delete();
	
		/**
		 * Creating persons
		 */
        Person::create(array('prsn_name' => 'Max',
							'prsn_ldap_id' => '1111',
							'prsn_status' => 'kandidat',	
							'clb_id' => '2'));

        Person::create(array('prsn_name' => 'Otto',	
							'prsn_ldap_id' => '1222',
							'prsn_status' => 'aktiv',
							'clb_id' => '2'));							
							
        Person::create(array('prsn_name' => 'Lena',	
							'prsn_ldap_id' => '1333',
							'prsn_status' => 'veteran',
							'clb_id' => '2'));

        Person::create(array('prsn_name' => 'THOR')); 

        Person::create(array('prsn_name' => 'Susi',
							'clb_id' => '4'));

        Person::create(array('prsn_name' => 'Thomas',
							'clb_id' => '3'));					
							
        Person::create(array('prsn_name' => 'Tina', 	
							'clb_id' => '1'));		

        Person::create(array('prsn_name' => 'Max',	
							'clb_id' => '3'));
							
		/**
		 * reporting result to console
		 */
		$this->command->info('table person seeded with 8 persons. S.');
    }

}

class JobtypesTableSeeder extends Seeder {

    public function run()
    {
		Eloquent::unguard();
		
		/**
		 * Clearing table
		 */
        DB::table('jobtypes')->delete();

		/**
		 * Creating regular job types
		 */
        Jobtype::create(array(	'jbtyp_title' => '1. Einlass', 
								'jbtyp_statistical_weight' => '1',
								'jbtyp_is_archived' => '0'));

        Jobtype::create(array(	'jbtyp_title' => '2. Einlass', 
								'jbtyp_statistical_weight' => '1',
								'jbtyp_is_archived' => '0'));								

	    Jobtype::create(array(	'jbtyp_title' => '3. Einlass', 
								'jbtyp_statistical_weight' => '1',
								'jbtyp_is_archived' => '0'));
							
        Jobtype::create(array(	'jbtyp_title' => 'Bar', 
								'jbtyp_statistical_weight' => '2',
								'jbtyp_is_archived' => '0'));
								
		Jobtype::create(array(	'jbtyp_title' => 'Tresen', 
								'jbtyp_statistical_weight' => '2',
								'jbtyp_is_archived' => '0'));	

        Jobtype::create(array(	'jbtyp_title' => 'AV', 
								'jbtyp_statistical_weight' => '2',
								'jbtyp_is_archived' => '0'));
								
        Jobtype::create(array(	'jbtyp_title' => 'Disko', 
								'jbtyp_statistical_weight' => '2',
								'jbtyp_is_archived' => '0'));

        Jobtype::create(array(	'jbtyp_title' => 'Licht', 
								'jbtyp_statistical_weight' => '0,5',
								'jbtyp_is_archived' => '0'));

        Jobtype::create(array(	'jbtyp_title' => 'Vorbereitung', 
								'jbtyp_statistical_weight' => '1',
								'jbtyp_is_archived' => '0'));		

								
        Jobtype::create(array(	'jbtyp_title' => 'Austeilen Montag', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));	

		Jobtype::create(array(	'jbtyp_title' => 'Austeilen Dienstag', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));	
		
		Jobtype::create(array(	'jbtyp_title' => 'Austeilen Mittwoch', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));	

		Jobtype::create(array(	'jbtyp_title' => 'Austeilen Donnerstag', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));	

		Jobtype::create(array(	'jbtyp_title' => 'Austeilen Freitag', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));

		Jobtype::create(array(	'jbtyp_title' => 'Zum Shirtschleuder gehen', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));	

		Jobtype::create(array(	'jbtyp_title' => 'Grilldienst', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));

		Jobtype::create(array(	'jbtyp_title' => 'Weinnachschub', 
								'jbtyp_statistical_weight' => '0',
								'jbtyp_is_archived' => '0'));

		/**
		 * Reporting result to console
		 */
		$this->command->info('Table jobtype seeded with 9 standard types and 1 generic type for tasks. M.');
    }

}

class PlacesTableSeeder extends Seeder {

    public function run()
    {
		Eloquent::unguard();
		
		/**
		 * Clearing table
		 */
        DB::table('places')->delete();

		/**
		 * Creating places
		 */

        Place::create(array('plc_title' => 'bc-Club'));				

        Place::create(array('plc_title' => 'Festhalle'));	
		
		/**
		 * Reporting result to console
		 */
		$this->command->info('Table place seeded with 2 places. M.');
    }

}

class ClubsTableSeeder extends Seeder {

    public function run()
    {
		Eloquent::unguard();
		
		/**
		 * Clearing table
		 */
        DB::table('clubs')->delete();

		/**
		 * Creating clubs
		 */
        Club::create(array('clb_title' => '-'));			// for empty club field. M.
		 
        Club::create(array('clb_title' => 'bc-Club'));				

        Club::create(array('clb_title' => 'bc-Café'));	

        Club::create(array('clb_title' => 'StuRa'));	

        Club::create(array('clb_title' => 'extern'));	
		
		/**
		 * Reporting result to console
		 */
		$this->command->info('Table club seeded with FREI, 3 clubs and extern. M.');
    }

}

class ScheduleEntriesTableSeeder extends Seeder {

	public function run()
    {
		Eloquent::unguard();
		
		/**
		 * Clearing table
		 */
        DB::table('schedule_entries')->delete();

		/**
		 * Creating places
		 */
        ScheduleEntry::create(array('schdl_id' => '1', 
									'jbtyp_id' => '1',
									'prsn_id' => '1'));	
		 
        ScheduleEntry::create(array('schdl_id' => '1', 
									'jbtyp_id' => '2',
									'prsn_id' => '2'));

		ScheduleEntry::create(array('schdl_id' => '1', 
									'jbtyp_id' => '3'));
							
		ScheduleEntry::create(array('schdl_id' => '1', 
									'jbtyp_id' => '4',
									'entry_user_comment' => 'Thou didst not reckon with the might of Thor, knave!',
									'prsn_id' => '4'));
							
		ScheduleEntry::create(array('schdl_id' => '1', 
									'jbtyp_id' => '5',
									'entry_user_comment' => 'komme 10 Min später',
									'prsn_id' => '5'));
							
		ScheduleEntry::create(array('schdl_id' => '1', 
									'jbtyp_id' => '6'));
							
		/**
		 * Creating event schedule for event 2
		 */
        ScheduleEntry::create(array('schdl_id' => '2', 
									'jbtyp_id' => '1'));

        ScheduleEntry::create(array('schdl_id' => '2', 
									'jbtyp_id' => '2',
									'prsn_id' => '2'));							
							
        ScheduleEntry::create(array('schdl_id' => '2', 
									'jbtyp_id' => '3',
									'prsn_id' => '3'));

        ScheduleEntry::create(array('schdl_id' => '2', 
									'jbtyp_id' => '4',
									'entry_user_comment' => 'Thou shalt crave far greater strength if the battle thou desireth comes!',
									'prsn_id' => '4')); 	

        ScheduleEntry::create(array('schdl_id' => '2', 
									'jbtyp_id' => '5',
									'entry_user_comment' => 'komme 10 Min später',
									'prsn_id' => '5'));

        ScheduleEntry::create(array('schdl_id' => '2', 
									'jbtyp_id' => '6',
									'prsn_id' => '6'));						

		/**
		 * Creating event schedule for event 3
		 */
        ScheduleEntry::create(array('schdl_id' => '3', 
									'jbtyp_id' => '1',
									'prsn_id' => '6'));

        ScheduleEntry::create(array('schdl_id' => '3', 
									'jbtyp_id' => '2',
									'prsn_id' => '1'));							
							
        ScheduleEntry::create(array('schdl_id' => '3', 
									'jbtyp_id' => '3',
									'prsn_id' => '2'));

        ScheduleEntry::create(array('schdl_id' => '3', 
									'jbtyp_id' => '4',
									'prsn_id' => '3')); 

        ScheduleEntry::create(array('schdl_id' => '3', 
									'jbtyp_id' => '5',
									'entry_user_comment' => 'komme 10 Min später',
									'prsn_id' => '4'));

        ScheduleEntry::create(array('schdl_id' => '3', 
									'jbtyp_id' => '6',
									'prsn_id' => '5'));
					
		/**
		 * Creating event schedule for event 4
		 */
        ScheduleEntry::create(array('schdl_id' => '4', 
									'jbtyp_id' => '1'));

        ScheduleEntry::create(array('schdl_id' => '4', 
									'jbtyp_id' => '2',
									'entry_user_comment' => 'Dieser Thor ist ziemlich nervig...', 	
									'prsn_id' => '1'));							
							
        ScheduleEntry::create(array('schdl_id' => '4', 
									'jbtyp_id' => '3'));

        ScheduleEntry::create(array('schdl_id' => '4', 
									'jbtyp_id' => '4',
									'prsn_id' => '4',
									'entry_user_comment' => 'I am not the god of reason and understanding. I am the god of Thunder and Lightning!!!')); 	

        ScheduleEntry::create(array('schdl_id' => '4', 
									'jbtyp_id' => '5',
									'prsn_id' => '4'));

        ScheduleEntry::create(array('schdl_id' => '4', 
									'jbtyp_id' => '6'));

		/**
		 * Creating event schedule for event 5
		 */
        ScheduleEntry::create(array('schdl_id' => '5', 
									'jbtyp_id' => '1'));	

        ScheduleEntry::create(array('schdl_id' => '5', 
									'jbtyp_id' => '2',
									'prsn_id' => '1',
									'entry_user_comment' => 'Ich bringe Kekse mit  ;) '));							
							
        ScheduleEntry::create(array('schdl_id' => '5', 
									'jbtyp_id' => '3'));

        ScheduleEntry::create(array('schdl_id' => '5', 
									'jbtyp_id' => '4',
									'prsn_id' => '4',
									'entry_user_comment' => 'I can not stand by and let anyone perish!...Even you!')); 

        ScheduleEntry::create(array('schdl_id' => '5', 
									'jbtyp_id' => '5',
									'prsn_id' => '4'));

        ScheduleEntry::create(array('schdl_id' => '5', 
									'jbtyp_id' => '6',
									'prsn_id' => '5'));
					
						
		/**
		 * Creating tasks schedules
		 */
							
        ScheduleEntry::create(array('schdl_id' => '7', 
									'jbtyp_id' => '10',
									'prsn_id' => '6'));
							
        ScheduleEntry::create(array('schdl_id' => '7', 
									'jbtyp_id' => '11'));

        ScheduleEntry::create(array('schdl_id' => '7', 
									'jbtyp_id' => '12',
									'prsn_id' => '2'));		

        ScheduleEntry::create(array('schdl_id' => '7', 
									'jbtyp_id' => '13'));

        ScheduleEntry::create(array('schdl_id' => '7', 
									'jbtyp_id' => '14'));

										
        ScheduleEntry::create(array('schdl_id' => '6', 
									'jbtyp_id' => '15',
									'prsn_id' => '5'));		

        ScheduleEntry::create(array('schdl_id' => '8', 
									'jbtyp_id' => '16',
									'prsn_id' => '7'));

        ScheduleEntry::create(array('schdl_id' => '8', 
									'jbtyp_id' => '16',
									'prsn_id' => '8'));

        ScheduleEntry::create(array('schdl_id' => '8', 
									'jbtyp_id' => '17'));
							
		/**
		 * reporting result to console
		 */
		$this->command->info('table schedule_entry seeded with filling 6 event and 2 task schedules. S.');
    }
}