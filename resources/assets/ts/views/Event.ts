import * as $ from "jquery"

// Show/hide more button for infos
$(function(){
    $('.moreless-more-info').click(function(e) {
        $(this).parent().children('.more-info').toggleClass('moreshow-info');
        $(this).parent().children('.more-info').css('height','auto');
        $(this).parent().children('.moreless-less-info').show();
        $(this).parent().children('.moreless-more-info').hide();
    });
});

$(function(){
    $('.moreless-less-info').click(function(e) {
        $(this).parent().children('.more-info').toggleClass('moreshow-info');
        $(this).parent().children('.more-info').css('height','100');
        $(this).parent().children('.more-info').height(100);
        $(this).parent().children('.moreless-less-info').hide();
        $(this).parent().children('.moreless-more-info').show();
    });
});

$(function(){
    $('.moreless-more-info').hide();
    $('.moreless-less-info').hide();
    if ($('.more-info').height() > 100) {
        $('.more-info').height(100);
        $('.moreless-more-info').show();
    };
});

$(function(){
    $('.moreless-more-details').click(function(e) {
        $(this).parent().children('.more-details').toggleClass('moreshow-details');
        $(this).parent().children('.more-details').css('height','auto');
        $(this).parent().children('.moreless-less-details').show();
        $(this).parent().children('.moreless-more-details').hide();
    });
});

$(function(){
    $('.moreless-less-details').click(function(e) {
        $(this).parent().children('.more-details').toggleClass('moreshow-details');
        $(this).parent().children('.more-details').css('height','100');
        $(this).parent().children('.more-details').height(100);
        $(this).parent().children('.moreless-less-details').hide();
        $(this).parent().children('.moreless-more-details').show();
    });
});

$(function(){
    $('.moreless-more-details').hide();
    $('.moreless-less-details').hide();
    if ($('.more-details').height() > 100) {
        $('.more-details').height(100);
        $('.moreless-more-details').show();
    };

});



// Show/hide change history
$(function(){
    $('#show-hide-history').click(function(e) {
        e.preventDefault();
        if ($('#change-history').hasClass("hide"))
        {
            // change state, change button
            $('#change-history').removeClass('hide');
            $('#arrow-icon').removeClass('fa-caret-right');
            $('#arrow-icon').addClass('fa-sort-desc');
        }
        else
        {
            // change state, change button
            $('#change-history').addClass('hide');
            $('#arrow-icon').addClass('fa-caret-right');
            $('#arrow-icon').removeClass('fa-sort-desc');
        };
    });
});

//////////////////////
// Create/edit view //
//////////////////////



// Shows dynamic form fields for new job types
$(document).ready(function() {
    // initialise counter
    var iCnt = parseInt($('#counter').val());

    if (iCnt < 2) {
        $(".btnRemove").hide();
    };

    // Add one more job with every click on "+"
    $('.btnAdd').click(function() {
        var elementToCopy = $(this).closest('.box');
        elementToCopy.find(".dropdown-menu").hide();
        var clone = elementToCopy.clone(true);
        clone.insertAfter(elementToCopy);
        clone.find('.shiftId').val("");
    });

    // Remove selected job
    $('.btnRemove').click(function(e) {
        $(this).closest('.box').remove();
    });

    // populate from dropdown select
    (<any>$.fn).dropdownSelect = function(shiftType, timeStart, timeEnd, weight) {

        $(this).closest('.box').find("[name^=jbtyp_title]").val(shiftType);
        $(this).closest('.box').find("[name^=jbtyp_time_start]").val(timeStart);
        $(this).closest('.box').find("[name^=jbtyp_time_end]").val(timeEnd);
        $(this).closest('.box').find("[name^=jbtyp_statistical_weight]").val(weight);
    };
});

