// Function for managing page graphics
class RMTemplateGraphics {
  constructor() {};
  // Function to turn on/off the arrow to indicate the table extension
  // parameters: table = table to check, button = arrow to check
  // example call: TemplateGraphics.checkScrollbar('tabella_contratti','scroll-button');
  static checkScrollbar(table, button) {
    const scrollButton = document.getElementById(button);
    const tabella_contratti = document.getElementById(table);
    const body = document.getElementsByTagName('body')[0];
    if (!scrollButton || !tabella_contratti || !body) {
      console.error("Required elements are missing in the DOM");
      return;
    }
    if (tabella_contratti.scrollWidth > body.clientWidth) {
      scrollButton.style.display = 'flex';
    } else {
      scrollButton.style.display = 'none';
    }
  }
}
