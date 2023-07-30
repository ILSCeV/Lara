<div class="card-footer col-12 hidden-print">
    <span class="float-end">
        {{-- Event publishing only for CL/marketing -> exclude creator
        Disabling iCal until fully functional.

        @is('marketing', 'clubleitung', 'admin')
            <button  id="unPublishEventLnkBtn"
                data-href="{{ URL::route('togglePublishState', $event->id) }}"
                class="btn btn-danger @if($event->evnt_is_published === 0) hidden @endif"
                name="toggle-publish-state"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="{{__("mainLang.unpublishEvent")}}"
                data-token="{{csrf_token()}}"
                >
                <i class="fa fa-bullhorn" aria-hidden="true"></i>
            </button>
            <button  id="pubishEventLnkBtn"
                data-href="{{ URL::route('togglePublishState', $event->id) }}"
                class="btn btn-success @if($event->evnt_is_published === 1) hidden @endif"
                name="toggle-publish-state"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="{{__("mainLang.publishEvent")}}"
                data-token="{{csrf_token()}}"
                >
                <i class="fa fa-bullhorn" aria-hidden="true"></i>
            </button>
            &nbsp;&nbsp;
        @endis

        --}}

        <a href="{{ URL::route('event.edit', $event->id) }}"
           class="btn btn-primary"
           data-bs-toggle="tooltip"
           data-bs-placement="bottom"
           title="{{ __('mainLang.changeEvent') }}">
           <i class="fa-solid  fa-pencil-alt"></i>
        </a>
        &nbsp;&nbsp;
        <a href="{{ $event->id }}"
           class="btn btn-danger"
           data-bs-toggle="tooltip"
           data-bs-placement="top"
           title="{{ __('mainLang.deleteEvent') }}"
           data-method="delete"
           data-token="{{csrf_token()}}"
           rel="nofollow"
           data-confirm="{{ __('mainLang.confirmDeleteEvent') }}">
           <i class="fa-solid  fa-trash"></i>
        </a>
    </span>
</div>
