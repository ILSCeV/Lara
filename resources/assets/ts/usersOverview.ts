import * as $ from "jquery"
import * as bootbox from "bootbox";

import {translate} from "./Translate";

$(()=>{
    $("#userOverviewFilter").on("keyup", (event) => {
        let value = $(event.target).val().toLowerCase();
        $("#userOverviewTable tr").each(function (index, elem) {
            $(elem).toggle($(elem).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $('.selectpicker').selectpicker({
        style: 'btn btn-default',
        liveSearch:true
    });

    $('[name=status]').on('change', (event) =>  {

        const target = event.currentTarget;
        const userId = $(target).data('id');
        const userName = $(target).data('name');

        bootbox.confirm({
            title: '<h4 class="alert alert-danger text-center">' + translate('deleteTemplate') + '</h4>',
            size: 'large',
            message: '<p>' + translate('changeUserStatus') +  '</p><p class="text-danger">'+ userName + '</p>',
            buttons: {
                confirm: {
                    label:'<span class="glyphicon glyphicon-ok" ></span>',
                    className:'btn-danger'
                },
                cancel: {
                    label:'<span class="glyphicon glyphicon-remove" ></span>',
                    className: 'btn-success'
                }
            },
            callback:(result) => {
                if(result){
                    $('#change-user-status-' + userId).submit();
                }
            }
        });
    });
});
