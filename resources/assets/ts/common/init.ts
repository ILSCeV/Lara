import "../../../../node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4"

let checkInputSupport = (type: String) => {
  let input = document.createElement("input");
  input.setAttribute("type", type);
  return input.type === type;
};

$(window).on({
  load: () => {
    // $('#userGroupDevelop').selectpicker({
    //   style: 'btn-sm',
    // });

    // Show/hide comments
    $('.showhide').on({
      click: function (e) {
        let comment = $(e.target).closest('.shiftRow').find('[name^=comment]');
        comment.toggleClass("hide", comment.is(":visible"));
        // isotope.layout();
      }
    });
    if (!checkInputSupport("datetime-local")) {
      let targetElements = $("input[type='datetime-local']");
      targetElements.each((index, element) => {
          let targetElement = $(element);
          let targetId = '#' + targetElement.attr('id');
          targetElement.attr('type','text');
          targetElement.attr('data-bs-toggle', 'datetimepicker');
          targetElement.attr('data-bs-target', targetId);
          targetElement.datetimepicker({
            icons: {
              time: 'fas fa-clock',
            },
            format:'YYYY-MM-DDThh:mm'
          });
        }
      );
    }
  }
});

