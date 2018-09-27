<footer class="navbar-default navbar-static-bottom hidden-print" id="footer">
    <div class="container text-dark-grey text-center">
        <br>
        <span class="col" style="text-align: center;">
            <small>
                {{ File::exists("gitrevision.txt") ? File::get("gitrevision.txt") : "&nbsp;" }}
            </small>
        </span>
        <span class="col">
            <small>
                <a href="mailto:lara@il-sc.de">
                    {{ trans('mainLang.notWorkingMail',['Name' => 'Lara']) }}
                </a>
            </small>
        </span>
        <span class="col" >
            <small>
                <a href="https://github.com/ILSCeV/Lara">
                    {{ trans('mainLang.moreInfosProjectsite') }}
                </a>
            </small>
        </span>
        <span class="col" >
            <small>
                <a href=" {{route('lara.privacy')}}">
                    {{ trans('mainLang.privacyPolicy') }}
                </a>
            </small>
        </span>
        <span class="col-xs-12 col-sm-12 col-md-1 text-dark-grey" style="text-align: center;">
            <small>
                <a href="{{route('lara.impressum')}}">
                    {{ trans('mainLang.impressum') }}
                </a>
            </small>
        </span>
        <br class="d-block d-sm-none d-none d-sm-block d-md-none">
        <br class="d-block d-sm-none d-none d-sm-block d-md-none">
        <br>
        <br>
    </div>
</footer>
