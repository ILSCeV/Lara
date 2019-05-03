$(window).on('load',()=>{
  (<any>window).cookieconsent.initialise({
    "palette": {
      "popup": {
        "background": "#edeff5",
        "text": "#838391"
      },
      "button": {
        "background": "#4b81e8"
      }
    },
    "theme": "edgeless",
    "position": "bottom-right",
  })
});
