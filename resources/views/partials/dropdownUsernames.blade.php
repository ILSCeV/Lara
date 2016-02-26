<a class="btn-small btn-default dropdown-toggle hidden-print" 
   data-toggle="dropdown" 
   href="javascript:void(0);">
    <span class="caret"></span>
</a>

<ul class="dropdown-menu">

    <li>
        <a href="javascript:void(0);" 
           onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
                    document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
                    document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'
                        document.getElementById('btn-submit-changes{{ ''. $entry->id }}').click();">
            Ich mach's!
        </a>
    </li>

    <li role="presentation" class="divider"></li>

    @foreach($persons as $person)
        <li> 
            <a href="javascript:void(0);" 
               onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{$person->prsn_name}}';
                        document.getElementById('club{{ ''. $entry->id }}').value='{{$person->getClub->clb_title}}';
                        document.getElementById('ldapId{{ ''. $entry->id }}').value='{{$person->prsn_ldap_id}}';
                        document.getElementById('btn-submit-changes{{ ''. $entry->id }}').click();">
                {{ $person->prsn_name }}
                @if ( $person->prsn_status === 'candidate' ) 
                    (K)
                @elseif ( $person->prsn_status === 'veteran' ) 
                    (V)
                @elseif ( $person->prsn_status === 'retired' ) 
                    (ex)
                @endif
                {{ '(' . $person->getClub->clb_title . ')' }}
            </a>
        </li>
    @endforeach

</ul>