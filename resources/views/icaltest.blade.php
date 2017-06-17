@extends('layouts.master')

@section('title')
	{{ trans('mainLang.createNewVEvent') }}
@stop

@section('content')
	Showing instructions for <b><span id="user-platform" value=""></span></b>. Want to switch to <span id="other-platforms"></span>? 

	<h3>Feeds</h3>

	<h3>How to add the feed on your device</h3>

	<p>
		<h3>Disclaimer:</h3>
		Different calendar software update external events' information at different intervals, <b>sometimes only once in a few days.</b><br />
		This means that if, for example, your shift in Lara is moved from 21:00 to 19:00, your calendar software on your laptop or phone may not get this update in time! <br />
		As a result, you might come late to your shift and <b>you will get a "Schüttrunde"</b> for that.<br />
		Sadly, Lara developers <u>can not</u> change that behavior in external software, and therefore <b>we do not take any responsibility</b> in cases like "I was late for my shift because my calendar did not warn me".<br />
		It is your job to check for updates, and it's the job of CL to inform members via email/messages/etc. about all short-time changes.<br />
		tl;dr - <b>Always check exact infos directly in Lara!</b>
	</p>
@stop