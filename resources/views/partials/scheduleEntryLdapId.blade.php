<!-- Needs variables: none -->

@if( is_null($entry->getPerson) )

    {!! Form::hidden('ldapId' . $entry->id, 
                     '', 
                     array('id'=>'ldapId' . $entry->id) ) 
    !!}

@else

    {!! Form::hidden('ldapId' . $entry->id, 
                     $entry->getPerson->prsn_ldap_id, 
                     array('id'=>'ldapId' . $entry->id) ) 
    !!}

@endif
