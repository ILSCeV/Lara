<a class="btn-small btn-default dropdown-toggle hidden-print" 
   data-toggle="dropdown" 
   href="javascript:void(0);">
    <span class="caret"></span>
</a>
<ul class="dropdown-menu">
    @foreach($clubs as $club)
        <li> 
            <a href="javascript:void(0);" 
               onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}';
                        document.getElementById('btn-submit-changes{{ ''. $entry->id }}').click();">
                {{$club}}
            </a>
        </li>
    @endforeach
</ul>   
