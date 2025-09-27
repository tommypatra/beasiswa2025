//awal rotasi gambar

let rotation = 0;
let scale = 1;

function rotateImage(angle) {
  rotation = (rotation + angle) % 360;
  updateTransform();
}

function zoomImage(factor) {
  scale *= factor;
  updateTransform();
}

function updateTransform() {
  const img = document.getElementById('preview-img');
  if (img) {
    img.style.transform = `rotate(${rotation}deg) scale(${scale})`;
  }
}

//end rotasi gambar