// alert("test");

class ToggleClass
{
  constructor(selector, toggle)
  {
    this.selector = document.querySelectorAll(selector);
    this.toggle = document.querySelectorAll(toggle);
    this.overlay = document.querySelector('.overlay');
  }

  // init function
  init() {
    this.showElement();
    // this.hideElement();
    this.closeElement();
  }

  // show overlay on click - selector
  toggledElement(id) {
    for (let element of this.toggle) {
      let targetId = element.dataset.toggleselector;
      console.log(targetId);
      if (targetId === id) {
        element.classList.toggle('active');
        this.overlay.classList.toggle('active');
        // console.log(targetId + " is equal to " + id);
      }
    }
  }

  // button - toggle
  showElement() {
    for (let element of this.selector) {
      element.addEventListener('click', (event) => {

        let targetId = event.target.dataset.toggle;
        console.log(event.target);
        this.toggledElement(targetId);
      }, false);
    }
  }

  // close toggle - element
  closeElement() {
    for (let element of this.toggle) {
      element.addEventListener('click', (event) => {
        let target = event.target?.closest('[data-toggle-close]');
        // console.log(element);
        if (target) {
          element.classList.remove('active');
          this.overlay.classList.remove('active');
        }
      }, false);
    }
  }

  // hide element on click - overlay
  // hideElement() {
  //   for (let element of this.toggle) {
  //
  //     element.addEventListener('click', (event) => {
  //       let targetId = event.target.dataset.toggleselector;
  //       console.log(targetId);
  //       this.toggledElement(targetId);
  //     }, false);
  //   }
  // }
}
// let toggleSlider = new ToggleClass('.toggle-slider', '.slide-container', '.slider-close').init();

let toggleSlider = new ToggleClass('.toggle-slider', '.slide-container').init();
let toggleModal = new ToggleClass('.toggle-modal', '.delete-user-modal').init();
let toggleTaskModal = new ToggleClass('.toggle-hour-modal', '.hours-user-modal').init();


console.log(toggleTaskModal);