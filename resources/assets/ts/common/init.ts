import "../../../../node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4"

let checkInputSupport = (type: string) => {
  let input = document.createElement("input");
  input.setAttribute("type", type);
  return input.type === type;
};

$(window).on({
  load: () => {
    // Show/hide comments
    $('.showhide').on('click', (e) => {
      let comment = $(e.target).closest('.shiftRow').find('[name^=comment]');
      comment.toggleClass("hide", comment.is(":visible"));
    });
    if (!checkInputSupport("datetime-local")) {
      let targetElements: JQuery<HTMLInputElement> = $("input[type='datetime-local']");
      targetElements.each((index, element) => {
        let targetElement = $(element);
        let targetId = '#' + targetElement.attr('id');
        targetElement.attr('type', 'text');
        targetElement.attr('data-bs-toggle', 'datetimepicker');
        targetElement.attr('data-bs-target', targetId);
        targetElement.datetimepicker({
          icons: {
            time: 'fas fa-clock',
          },
          format: 'YYYY-MM-DDThh:mm'
        });
      }
      );
    }
  }
});

