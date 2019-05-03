<div class="card-footer rounded-bottom p-0">
{{-- Show a "hide" button for management, that allows removal of an event from current view - needed for printing --}}
@is('marketing', 'clubleitung', 'admin')
<div class="row hidden-print justify-content-end">
    <div class="col-3">
        <small><a href="#" class="hide-event">{{ trans('mainLang.hide') }}</a></small>
    </div>
</div>
@endis
</div>