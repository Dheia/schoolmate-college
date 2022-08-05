window.canvas_front = new fabric.Canvas('canvas_front', { preserveObjectStacking: true });

// display/hide text controls
canvas_front.on('object:selected', function(e) {
   if (e.target.type === 'i-text') {
      document.getElementById('textControlsFront').hidden = false;
      document.getElementById('text-font-size-front').value = e.target.fontSize;
   }

   if (e.target.type === 'textbox') {
      document.getElementById('textControlsFront').hidden = false;
      document.getElementById('text-font-size-front').value = e.target.fontSize;
   }

   if(e.target.type === 'rect' || e.target.type === 'triangle' || e.target.type === 'circle')
   {
      document.getElementById('shapeControlsFront').hidden = false;
      document.getElementById('shape-fill-back').value = e.target.fill;
   }
});

canvas_front.on('before:selection:cleared', function(e) {
   if (e.target.type === 'i-text') {
      document.getElementById('textControlsFront').hidden = true;
   }

   if (e.target.type === 'rect' || e.target.type === 'triangle' || e.target.type === 'circle') {
      document.getElementById('shapeControlsFront').hidden = true;
   }
});

canvas_front.on('text:changed', function(opt) {
   console.log('changed');
  var t1 = opt.target;
  if (t1.width > t1.fixedWidth) {
    t1.fontSize *= t1.fixedWidth / (t1.width + 1);
    t1.width = t1.fixedWidth;
  }
});




// document.getElementById('file').addEventListener("change", function(e) {
//    var file = e.target.files[0];
//    var reader = new FileReader();
//    reader.onload = function(f) {
//       var data = f.target.result;
//       fabric.Image.fromURL(data, function(img) {
//       var oImg = img.set({
//          left: 0,
//          top: 0,
//          angle: 0,
//          border: '#000',
//          stroke: '#F0F0F0', //<-- set this
//          strokeWidth: 40 //<-- set this
//       }).scale(0.2);
//       canvas.add(oImg).renderAll();
//       //var a = canvas.setActiveObject(oImg);
//       var dataURL = canvas.toDataURL({
//          format: 'png',
//          quality: 1
//       });
//       });
//    };
//    reader.readAsDataURL(file);
// });


document.getElementById('file2').addEventListener("change", function(e) {
   var file = e.target.files[0];
   var reader = new FileReader();
   reader.onload = function(f) {
      var data = f.target.result;
      fabric.Image.fromURL(data, function(img) {
         // add background image
         canvas_front.setBackgroundImage(img, canvas_front.renderAll.bind(canvas_front), {
            scaleX: canvas_front.width / img.width,
            scaleY: canvas_front.height / img.height
         });
      });
   };
   reader.readAsDataURL(file);
});

// Delete selected object
window.deleteObjectFront = function() {
  canvas_front.remove(canvas_front.getActiveObject())
}
   // Refresh page
function refreshFront () {
   setTimeout(function() {
      canvas_front.clear()
   }, 100);
}
// Add Photo Clipping Path
function  addClippingPath() {

}

function textAlignFront(value) {
   canvas_front.getActiveObject().set("textAlign", value);
   canvas_front.renderAll();
}

// Add Text
function addTextFront() {
   canvas_front.add(new fabric.Textbox('Text Here', {
      left: 50,
      id: 'frontText', 
      top: 100,
      fontFamily: document.getElementById('font-family-front').value,
      fill: document.getElementById('text-color-front').value,
      stroke: document.getElementById('text-color-front').value,
      strokeWidth: 0,
      fontSize: 45
   }));
}

// Add Name
function addTextFieldFront() {
var value = $('#fields option:selected').html().toUpperCase();
   canvas_front.add(new fabric.Textbox(value, {
      id: $('#fields option:selected').val(),
      left: 50,
      top: 100,
      textAlign: 'left',
      fixedWidth: 638,
      fw: 638,
      fontFamily: document.getElementById('font-family-front').value,
      fill: document.getElementById('text-color-front').value,
      stroke: document.getElementById('text-color-front').value,
      strokeWidth: 0,
      fontSize: 45
   }));
}

// Edit Text
document.getElementById('text-color-front').onchange = function() {
   canvas_front.getActiveObject().set('fill', this.value);
   canvas_front.renderAll();
};
// document.getElementById('text-bg-color').onchange = function() {
//    canvas_front.getActiveObject().set('backgroundColor', this.value);
//    canvas_front.renderAll();
// };
// document.getElementById('text-lines-bg-color').onchange = function() {
//    canvas_front.getActiveObject().set('textBackgroundColor', this.value);
//    canvas_front.renderAll();
// };
// document.getElementById('text-stroke-color').onchange = function() {
//    canvas_front.getActiveObject().set('strokeColor', this.value);
//    canvas_front.renderAll();
// };
// document.getElementById('text-stroke-width').onchange = function() {
//    canvas_front.getActiveObject().setStrokeWidth(this.value);
//    canvas_front.renderAll();
// };
// document.getElementById('font-family-front').onchange = function() {
//    console.log('adf - ', this.value);
//    // setTimeout(function () {
//       canvas_front.getActiveObject().set('fontFamily', this.value);
//       canvas_front.renderAll();
//    // }, 500);
// };

document.getElementById('font-family-front').onchange = function () {
   console.log('ff-front - ', this.value);
   canvas_front.getActiveObject().set('fontFamily', this.value);
   canvas_front.renderAll();
};

document.getElementById('text-font-size-front').onchange = function() {
   canvas_front.getActiveObject().set('fontSize', this.value);
   canvas_front.renderAll();
};
// document.getElementById('text-line-height').onchange = function() {
//    canvas_front.getActiveObject().setLineHeight(this.value);
//    canvas_front.renderAll();
// };
// document.getElementById('text-align').onchange = function() {
//    canvas_front.getActiveObject().setTextAlign(this.value);
//    canvas_front.renderAll();
// };

/********************************/
/******* SHAPE CONTROLS ********/
/******************************/
document.getElementById('shape-fill-front').onchange = function() {
   canvas_front.getActiveObject().set('fill', this.value);
   canvas_front.renderAll();
};

radios5 = document.getElementsByName("fonttype-front"); // wijzig naar button
for (var i = 0, max = radios5.length; i < max; i++) {
   radios5[i].onclick = function() {
      if (document.getElementById(this.id).checked == true) {
         if (this.id == "text-cmd-bold") {
            canvas_front.getActiveObject().set("fontWeight", "bold");
         }
         if (this.id == "text-cmd-italic") {
            canvas_front.getActiveObject().set("fontStyle", "italic");
         }
         if (this.id == "text-cmd-underline") {
            canvas_front.getActiveObject().set("textDecoration", "underline");
         }
         if (this.id == "text-cmd-linethrough") {
            canvas_front.getActiveObject().set("textDecoration", "line-through");
         }
         if (this.id == "text-cmd-overline") {
            canvas_front.getActiveObject().set("textDecoration", "overline");
         }
      } else {
         if (this.id == "text-cmd-bold") {
            canvas_front.getActiveObject().set("fontWeight", "");
         }
         if (this.id == "text-cmd-italic") {
            canvas_front.getActiveObject().set("fontStyle", "");
         }
         if (this.id == "text-cmd-underline") {
            canvas_front.getActiveObject().set("textDecoration", "");
         }
         if (this.id == "text-cmd-linethrough") {
            canvas_front.getActiveObject().set("textDecoration", "");
         }
         if (this.id == "text-cmd-overline") {
            canvas_front.getActiveObject().set("textDecoration", "");
         }
      }
      canvas_front.renderAll();
   }


}
// Send selected object to front or back
var selectedObject;
canvas_front.on('object:selected', function(event) {
   selectedObject = event.target;
});
var sendSelectedObjectBack = function() {
   canvas_front.sendToBack(selectedObject);
}
var sendSelectedObjectToFront = function() {
      canvas_front.bringToFront(selectedObject);
   }
   // Download
var imageSaverFront = document.getElementById('lnkDownloadFront');
imageSaverFront.addEventListener('click', saveImageFront, false);

function saveImageFront(e) {
   this.href = canvas_front.toDataURL({
      format: 'png',
      quality: 0.8
   });
   this.download = 'front_card.png'
}
// Do some initializing stuff
// fabric.Object.prototype.set({
//    transparentCorners: true,
//    cornerColor: '#22A7F0',
//    borderColor: '#22A7F0',
//    cornerSize: 12,
//    padding: 5
// });

function addAvatarFront(shape) {
   var shapeObj = null;

   // RECT
   if(shape == 'rect') 
   {
      shapeObj = new fabric.Rect({
         originX: 'left',
         originY: 'top',
         left: 180,
         top: 10,
         width: 200,
         height: 200,
         strokeWidth: 0,
         id: 'photo',
         fill: document.getElementById('shape-fill-front').value,
         styles: {
            whiteSpace: 'nowrap'
         }
      });
   }

   // CIRCLE
   if(shape == 'circle') 
   {
      shapeObj = new fabric.Circle({
         width: 100,
         height: 100,
         radius: 20,
         left: 0,
         top: 0,
         strokeWidth: 0,
         id: 'photo',
         fill: document.getElementById('shape-fill-front').value,
      });
   }

   // TRIANGLE
   if(shape == 'triangle') 
   {
      shapeObj = new fabric.Triangle({
         width: 100,
         height: 100,
         left: 0,
         top: 0,
         strokeWidth: 0,
         id: 'photo',
         fill: document.getElementById('shape-fill-front').value,
      });
   }

   canvas_front.add(shapeObj);
}