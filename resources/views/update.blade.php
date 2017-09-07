@extends('layouts.master')

@section('title')
    Lara Update
@stop

@section('content')
<pre>
    <?php
passthru('cd .. && php artisan lara:update')
?>
</pre>
<p>DONE</p>
<a href="/">Back to main site</a>
@stop