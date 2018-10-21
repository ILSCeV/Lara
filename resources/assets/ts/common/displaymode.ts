import * as Cookies from 'es-cookie';

$('#darkmodeToggle').click((e) => {
  e.preventDefault();
  var storedDisplayMode = Cookies.get('displaymode');
  if (storedDisplayMode === undefined) {
    storedDisplayMode = "light";
  }
  console.log(storedDisplayMode);
  var newDisplayMode = undefined;
  switch (storedDisplayMode) {
    case "dark":
      newDisplayMode = "light";
      break;
    case "light":
    default:
      newDisplayMode = "dark";
      break;
  }
  console.log(newDisplayMode);
  Cookies.set('displaymode',newDisplayMode);
  window.location.reload();
});
