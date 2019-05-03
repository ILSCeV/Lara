//This event listener is enabled while the scrolling to today anymation is running. If the user touches the screen, the animation is stopped.
const stopScrollOnTouch = () => {
  $('html, body').stop();
};

// Scroll to current date/event if in mobile view in current month

export const scrollToCurrentWeek = () => {
  // check if we are in month view and if the today-marker exists
  if ($('#month-view-marker').length && $(".scroll-marker").length) {
    //Add event listener to stop scrolling when the user touches the screen.
    document.addEventListener("touchstart", stopScrollOnTouch, false);

    $('html, body').animate({scrollTop: $(".scroll-marker").offset().top - 80}, {
      duration: 1000,
      always: () => {
        //Scroll completed or Aborted, remove the touch listener
        document.removeEventListener("touchstart", stopScrollOnTouch);
      }
    });
  }
};
