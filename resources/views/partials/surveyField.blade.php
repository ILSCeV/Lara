<div class="form-group">
    {!! Form::label('questions[]', 'Frage:') !!}
    {!! Form::textarea('questions[]', null, ['placeholder'=>'z.B. Kommst du zur Weihnachtsfeier?',
                                            'size' => '100x3',
                                            'class' => 'form-control'
                                            ]) !!}
</div>