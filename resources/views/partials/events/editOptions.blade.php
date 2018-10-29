<div class="card-footer col-12 hidden-print">
    <span class="float-right">
        {{-- Event publishing only for CL/marketing -> exclude creator
        Disabling iCal until fully functional.

        @is('marketing', 'clubleitung', 'admin')
            <button  id="unPublishEventLnkBtn"
                data-href="{{ URL::route('togglePublishState', $event->id) }}"
                class="btn btn-danger @if($event->evnt_is_published === 0) hidden @endif"
                name="toggle-publish-state"
                data-toggle="tooltip"
                data-placement="bottom"
                title="{{trans("mainLang.unpublishEvent")}}"
                data-token="{{csrf_token()}}"
                >
                <i class="fa fa-bullhorn" aria-hidden="true"></i>
            </button>
            <button  id="pubishEventLnkBtn"
                data-href="{{ URL::route('togglePublishState', $event->id) }}"
                class="btn btn-success @if($event->evnt_is_published === 1) hidden @endif"
                name="toggle-publish-state"
                data-toggle="tooltip"
                data-placement="bottom"
                title="{{trans("mainLang.publishEvent")}}"
                data-token="{{csrf_token()}}"
                >
                <i class="fa fa-bullhorn" aria-hidden="true"></i>
            </button>
            &nbsp;&nbsp;
        @endis

        --}}

        <a href="{{ URL::route('event.edit', $event->id) }}"
           class="btn btn-primary"
           data-toggle="tooltip"
           data-placement="bottom"
           title="{{ trans('mainLang.changeEvent') }}">
           <i class="fas fa-pencil-alt"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ $event->id }}"
           class="btn btn-secondary"
           data-toggle="tooltip"
           data-placement="bottom"
           title="{{ trans('mainLang.deleteEvent') }}"
           data-method="delete"
           data-token="{{csrf_token()}}"
           rel="nofollow"
           data-confirm="{{ trans('mainLang.confirmDeleteEvent') }}">
           <i class="fas fa-trash"></i>
        </a>
    </span>
</div>
