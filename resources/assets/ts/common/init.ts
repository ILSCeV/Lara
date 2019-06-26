$(window).on({
  load : () => {
    $('#userGroupDevelop').selectpicker({
      style: 'btn-sm',
    });

    // Show/hide comments
    $('.showhide').on({
      click: function (e) {
        let comment = $(e.target).closest('.shiftRow').find('[name^=comment]');
        comment.toggleClass("hide", comment.is(":visible"));
        // isotope.layout();
      }
    });
  }
});

