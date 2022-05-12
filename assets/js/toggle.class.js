// alert("test");

class ToggleClass
{
  constructor(selector, toggle, close)
  {
    this.selector = document.querySelectorAll(selector);
    this.toggle = document.querySelectorAll(toggle);
    this.close = document.querySelectorAll(close);
  }
}


let toggleModal = new ToggleClass('.toggle-modal', '.modal-overlay', '.modal-close');
console.log(toggleModal);