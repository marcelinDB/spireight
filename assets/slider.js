//GESTION SLIDER
const left = document.querySelector('.left');
const right = document.querySelector('.right');

const slider = document.querySelector('.slider');
let timer = 7000
const indicatorParent = document.querySelector('.control ul'); 
const indicators = document.querySelectorAll('.control li');
let index = 0;

indicators.forEach((indicator, i) => {
  indicator.addEventListener('click', () => {
    document.querySelector('.control .selected').classList.remove('selected');
    indicator.classList.add('selected');
    slider.style.transform = 'translateX(' + (i) * -25 + '%)';  
    index = i;
    
  });
});


left.addEventListener('click', function() {
  clearInterval(loopCarrousel);
  loopCarrousel = setInterval(function(){
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
},timer)
  index = (index > 0) ? index -1 : 3;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
});

let leftFct = function(){
  clearInterval(loopCarrousel);
  loopCarrousel = setInterval(function(){
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
},timer)
  index = (index > 0) ? index -1 : 3;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
};

let rightFct = function() {
  clearInterval(loopCarrousel);
  loopCarrousel = setInterval(function(){
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
},timer)
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
};

right.addEventListener('click', function() {
  clearInterval(loopCarrousel);
  loopCarrousel = setInterval(function(){
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
},timer)
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
});

let loopCarrousel = setInterval(function(){
  index = (index < 4 - 1) ? index+1 : 0;
  document.querySelector('.control .selected').classList.remove('selected');
  indicatorParent.children[index].classList.add('selected');
  slider.style.transform = 'translateX(' + (index) * -25 + '%)';
},timer)


let clickStatus = false
var mouse = {
  x: undefined,
  y: undefined
}
let blockPosition = false
document.getElementById('slider').addEventListener('mousedown', (event) => {
  clickStatus = true
  positionXLast = 0
  blockPosition = false
  document.getElementById('slider').style.cursor = "grab";
})

//Event Mouse unpress
document.getElementById('slider').addEventListener('mouseup', (event) => {
  clickStatus = false
  positionXLast = 0
  blockPosition = false
  document.getElementById('slider').style.cursor = "grabbing";
})

//Event mouse position
document.getElementById('slider').addEventListener('mousemove', (event) => {
  if (clickStatus != false) {
      if (positionXLast != 0 && positionXLast > event.x && blockPosition == false) {
        rightFct();
          blockPosition = true  
      } else if (positionXLast != 0 && positionXLast < event.x && blockPosition == false) {
          leftFct();
          blockPosition = true
      }
      positionXLast = event.x
  }else{
      mouse.x = event.x
      mouse.y = event.y
  }
})

//Event TouchPress
document.getElementById('slider').addEventListener('touchstart', (event) => {
  eventX = event.touches[0].clientX
  eventY = event.touches[0].clientY
  mouse.x = eventX
  mouse.y = eventY
  clickStatus = true
  positionXLast = 0
  blockPosition = false
})

//Event TouchUnress
document.getElementById('slider').addEventListener('touchend', (event) => {
  clickStatus = false
  positionXLast = 0
  blockPosition = false
})

//Event TouchMove
document.getElementById('slider').addEventListener('touchmove', (event) => {
  eventX = event.touches[0].clientX
  eventY = event.touches[0].clientY
  if (clickStatus != false) {
      if (positionXLast != 0 && positionXLast > eventX && blockPosition == false) {
        blockPosition = true
        rightFct();
      } else if (positionXLast != 0 && positionXLast < eventX && blockPosition == false) {
        blockPosition = true
        leftFct();
      }
      positionXLast = eventX
  } else {
      mouse.x = eventX
      mouse.y = eventY
  }
})