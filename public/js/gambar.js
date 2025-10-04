//awal rotasi gambar

let rotation = 0;
let scale = 1;

function rotateImage(element,angle) {
  rotation = (rotation + angle) % 360;
  updateTransform(element);
}

function zoomImage(element, factor) {
  scale *= factor;
  updateTransform(element);
}

function updateTransform(element) {
  const img = document.getElementById(element);
  if (img) {
    img.style.transform = `rotate(${rotation}deg) scale(${scale})`;
  }
}

//end rotasi gambar