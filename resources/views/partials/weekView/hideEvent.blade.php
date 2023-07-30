<div class="card-footer rounded-bottom p-0">
    {{-- Show a "hide" button for management, that allows removal of an event from current view - needed for printing --}}
    @is('marketing', 'clubleitung', 'admin')
        <div class="row hidden-print justify-content-end">
            <div class="col-2">
                <a href="#" class="hide-event">
                    <i class="fa fa-eye-slash text-lg" data-bs-toggle="tooltip" data-bs-placement="left"
                        title="{{ __('mainLang.hide') }}">
                    </i>
                </a>
            </div>
        </div>
    @endis
</div>
