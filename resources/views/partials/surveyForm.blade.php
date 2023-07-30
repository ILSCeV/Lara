<div class="panel-group">
    <div class="card col-md-8 col-sm-12 col-12">
        <h4 id="heading_create" style="display:none">{{ __('mainLang.createNewSurvey') }}:</h4>
        <h4 id="heading_edit" style="display:none">{{ __('mainLang.editSurvey') }}:</h4>

        <div class="card-body">

            <div class="form-group">
                {!! Form::label('title', Lang::get('mainLang.placeholderSurveyTitle')) !!}
                {!! Form::text('title', $survey->title, [
                    'placeholder' => Lang::get('mainLang.placeholderTitleSurvey'),
                    'required',
                    'class' => 'form-control',
                ]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', Lang::get('mainLang.placeholderDescription')) !!}
                {!! Form::textarea('description', $survey->description, ['size' => '100x4', 'class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('deadline', Lang::get('mainLang.placeholderActiveUntil')) !!}
                {!! Form::date('deadlineDate', $date, ['class' => 'form-control']) !!}
                {!! Form::time('deadlineTime', $time, ['class' => 'form-control', 'step' => 1]) !!}
            </div>

            <div class="form-group">
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required1" value="1" name="is_private"
                            @if (!Auth::user()->isAn(Roles::PRIVILEGE_MARKETING)) disabled checked @endif class="input_checkboxitem"
                            @if ($survey->is_private) checked @endif>
                        {{ __('mainLang.showOnlyForLoggedInMember') }}
                    </label>
                    @if (!Auth::user()->isAn(Roles::PRIVILEGE_MARKETING))
                        <input hidden checked type="checkbox" id="required1_hidden" name="is_private" value="1">
                    @endif
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required2" value="2" name="is_anonymous"
                            class="input_checkboxitem" @if ($survey->is_anonymous) checked @endif>
                        {{ __('mainLang.showResultsOnlyForCreator') }}
                    </label>
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required3" value="3" name="show_results_after_voting"
                            class="input_checkboxitem" @if ($survey->show_results_after_voting) checked @endif>
                        {{ __('mainLang.showResultsAfterFillOut') }}
                    </label>
                </div>
            </div>

            <hr class="col-12">

            <div class="row mb-3">
                @if (empty($survey->password))
                    <div id="password_note" class="form-text col-12">
                        <small>({{ __('mainLang.passwordSetOptional') }})</small>
                    </div>
                @endif
                <label for="password" class="form-label col-sm col-form-label">
                    {{ __('mainLang.passwordEntry') }}:
                </label>
                <div class="col-sm-5">
                    {!! Form::password('password', ['class'=>'form-control']) !!}
                </div>
            </div>

            <div class="row mb-3">
                <label for="passwordDouble" class="form-label col-sm col-form-label">
                    {{ __('mainLang.passwordRepeat') }}:
                </label>
                <div class="col-sm-5">
                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>
            </div>


            @if (!empty($survey->password))
                <div style="color: #ff9800;">
                    <small>{{ __('mainLang.passwordDeleteMessage') }}
                    </small>
                </div>
            @endif
        </div>
    </div>

    <div class="questions">
        @include('partials.surveyQuestionsEdit')
    </div>
    <div class="card col-md-8 col-sm-12 col-12"></div>
    <div class="card col-md-8 col-sm-12 col-12">
        <div class="card-body">
            <div class="formGroup" id="addButtons">
                <input type="button" id="btnAdd" value="{{ __('mainLang.addQuestion') }}" class="btn btn-success">
                @if ($isEdit)
                    {!! Form::submit(Lang::get('mainLang.editSurvey'), ['class' => 'btn btn-primary']) !!}
                    <br class="d-block.d-sm-none">
                    <a href="javascript:history.back()"
                        class="btn btn-secondary">{{ __('mainLang.backWithoutChange') }}</a>
                    <a href="/survey/{{ $survey->id }}" class="btn btn-secondary" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-method="delete" data-token="{{ csrf_token() }}" rel="nofollow"
                        data-confirm='{{ __('mainLang.confirmDeleteSurvey', ['title' => $survey->title]) }}'>
                        <i class="fa fa-trash"></i>
                    </a>
                @else
                    {!! Form::submit(Lang::get('mainLang.createSurvey'), [
                        'class' => 'btn btn-primary',
                        'id' => 'button-create-survey',
                    ]) !!}
                    <a href="javascript:history.back()"
                        class="btn btn-secondary">{{ __('mainLang.backWithoutChange') }}</a>
                @endif
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="card col-md-8 col-sm-12 col-12" style="color: #b0141a">
            <br>
            @foreach ($errors->all() as $error)
                <ul class="ps-3">
                    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> {{ $error }}
                </ul>
            @endforeach
        </div>
    @endif
</div>
