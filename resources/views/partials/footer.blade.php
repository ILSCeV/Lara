<footer class="navbar sticky-bottom navbar-light bg-light hidden-print" id="footer">
    <div class="container text-dark-grey text-center">
        <span class="col-12 col-sm-12 col-md-3" style="text-align: center;">
            <small>
                {{ File::exists("gitrevision.txt") ? File::get("gitrevision.txt") : "&nbsp;" }}
            </small>
        </span>

        <span class="col-12 col-sm-12 col-md-3">
            <small>
                <a href="mailto:lara@il-sc.de">
                    {{ trans('mainLang.notWorkingMail',['Name' => 'Lara']) }}
                </a>
            </small>
        </span>

        <span class="col-12 col-sm-12 col-md-3" >
            <small>
                <a href="https://github.com/ILSCeV/Lara">
                    {{ trans('mainLang.moreInfosProjectsite') }}
                </a>
            </small>
        </span>

        <span class="col-12 col-sm-12 col-md-2" >
            <small>
                <a href=" {{route('lara.privacy')}}">
                    {{ trans('mainLang.privacyPolicy') }}
                </a>
            </small>
        </span>

        <span class="col-12 col-sm-12 col-md-1">
            <small>
                <a href="{{route('lara.impressum')}}">
                    {{ trans('mainLang.impressum') }}
                </a>
            </small>
        </span>
    </div>
</footer>
