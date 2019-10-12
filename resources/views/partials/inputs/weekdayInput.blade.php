@php
    /**
     * @var array $weekdays
     * @var String $name
     * @var String $id
     * @var String $class
     */
    $weekdays = ['monday'=> trans('mainLang.monday'),
     'tuesday' => trans('mainLang.tuesday'),
      'wednesday'=> trans('mainLang.wednesday'),
      'thursday' => trans('mainLang.thursday'),
      'friday' => trans('mainLang.friday'),
      'saturday' =>trans('mainLang.saturday'),
      'sunday' => trans('mainLang.sunday')]
@endphp
<select class="{{$class}}" id="{{ $id }}" name="{{ $name }}">
    @foreach($weekdays as $key => $weekday)
        <option value="{{$key}}">{{$weekday}}</option>
    @endforeach
</select>
