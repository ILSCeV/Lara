@extends('layouts.master')

@section('title')
    Lara Update
@stop

@section('content')
<pre>
    <?php
$stream = fopen('php://output', 'w');
\Artisan::call('lara:update', array(), new \Symfony\Component\Console\Output\StreamOutput($stream));
?>
</pre>
<p>DONE</p>
<a href="/">Back to main site</a>
@stop