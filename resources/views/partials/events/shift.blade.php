<div id={{ "box" . $counter }} class="box">
    <div class="glyphicon glyphicon-menu-hamburger"></div>
    <div class="input-append btn-group">
        <input type="text"
               name={{ "jbtyp_title" . $counter }}
                       class="input"
               id={{ "jbtyp_title" . $counter }}
                       value="{{ $title }}"
               placeholder="{{ trans('mainLang.serviceTypeEnter') }}"/>

        <ul class="dropdown-menu dropdown-jobtypes" style="position: absolute;">
        </ul>

    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;

    <input type="time" class="input"
           name={{ "jbtyp_time_start" . $counter }}
                   id={{ "jbtyp_time_start" . $counter }}
                   value="{{ $startTime }}"
           required />

    <input type="time"
           class="input"
           name={{ "jbtyp_time_end" . $counter }}
                   id={{ "jbtyp_time_end" . $counter }}
                   value="{{ $endTime }}"
           required />


    &nbsp;<br class="visible-xs">{{ trans('mainLang.weight') }}:&nbsp;
    <input type="number"
           step="0.1"
           min="0"
           class="input"
           name={{ "jbtyp_statistical_weight" . $counter }}
                   id={{ "jbtyp_statistical_weight" . $counter }}
                   value="{{ $weight }}"
           onkeypress="return event.charCode >= 48"
           min="0"
           required />


    <input type="button" value="+" class="btn btn-small btn-success btnAdd" />
    &nbsp;&nbsp;
    <input type="button" value="&#8211;" class="btn btn-small btn-danger btnRemove" />
</div>
