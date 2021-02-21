function getWidth(element) {
  return parseFloat(getComputedStyle(element, null).width.replace("px", ""))
}

function getPosition(element) {
  return { left: element.offsetLeft, top: element.offsetTop };
}

function updateSlideLine(element) {
  const sliderLine = document.querySelector('#slide-line');
  sliderLine.style.width = `${getWidth(element)}px`;
  sliderLine.style.left = `${getPosition(element).left}px`;
}

function updateSlideLineWithCurrent() {
  const currentItem = document.querySelector('#navigation li.active');
  if (currentItem) {
    updateSlideLine(currentItem);
  }
}

function onMouseOver(event) {
  const element = event.currentTarget;
  updateSlideLine(element);
}

function onMouseOut() {
  updateSlideLineWithCurrent();
}

function initMenuUnderline() {
  updateSlideLineWithCurrent();

  window.onresize = () => updateSlideLineWithCurrent();

  const nav = document.querySelector('#navigation');
  if (nav) {
    const navItems = nav.querySelectorAll('li');
    navItems.forEach((navItem) => {
      navItem.addEventListener('mouseover', onMouseOver);
      navItem.addEventListener('mouseout', onMouseOut);
    });
  }
}


function init() {
  initMenuUnderline();
}

export default init;
