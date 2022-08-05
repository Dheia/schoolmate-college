window.canvas_back = new fabric.Canvas('card_back', { preserveObjectStacking: true });

// display/hide text controls
canvas_back.on('object:selected', function(e) {

   console.log(e.target);
   if (e.target.type === 'i-text') {
      document.getElementById('textControlsBack').hidden = false;
      document.getElementById('text-font-size-back').value = e.target.fontSize;
   }

   if (e.target.type === 'textbox') {
      document.getElementById('textControlsBack').hidden = false;
      document.getElementById('text-font-size-back').value = e.target.fontSize;
   }

   if(e.target.type === 'rect' || e.target.type === 'triangle' || e.target.type === 'circle')
   {
      document.getElementById('shapeControlsBack').hidden = false;
      document.getElementById('shape-fill-back').value = e.target.fill;
   }
});



canvas_back.on('before:selection:cleared', function(e) {
   if (e.target.type === 'i-text') {
      document.getElementById('textControlsBack').hidden = true;
   }

   if (e.target.type === 'rect' || e.target.type === 'triangle' || e.target.type === 'circle') {
      document.getElementById('shapeControlsBack').hidden = true;
   }
});




// document.getElementById('file').addEventListener("change", function(e) {
// 	var file = e.target.files[0];
// 	var reader = new FileReader();
// 	reader.onload = function(f) {
// 		var data = f.target.result;
// 		fabric.Image.fromURL(data, function(img) {
// 		var oImg = img.set({
// 			left: 0,
// 			top: 0,
// 			angle: 0,
// 			border: '#000',
// 			stroke: '#F0F0F0', //<-- set this
// 			strokeWidth: 40 //<-- set this
// 		}).scale(0.2);
// 		canvas.add(oImg).renderAll();
// 		//var a = canvas.setActiveObject(oImg);
// 		var dataURL = canvas.toDataURL({
// 			format: 'png',
// 			quality: 1
// 		});
// 		});
// 	};
// 	reader.readAsDataURL(file);
// });


document.getElementById('card_back_file').addEventListener("change", function(e) {
   var file = e.target.files[0];
   var reader = new FileReader();
   reader.onload = function(f) {
      var data = f.target.result;
      fabric.Image.fromURL(data, function(img) {
         // add background image
         console.log(window.img = img);
         // img.filters.push(new fabric.Image.filters.RemoveColor({
         //    threshold: .2,
         //    // distance: 140
         // }));
         // img.filters.push(new fabric.Image.filters.Contrast({
         //    contrast: 0,
         //    // distance: 140
         // }));

         // // img.filters.push(new fabric.Image.filters.Brightness(10));
         // img.applyFilters();

         canvas_back.setBackgroundImage(img, canvas_back.renderAll.bind(canvas_back), {
            scaleX: canvas_back.width / img.width,
            scaleY: canvas_back.height / img.height
         });
      });
   };
   reader.readAsDataURL(file);
});

// Delete selected object
window.deleteObjectBack = function() {
  canvas_back.remove(canvas_back.getActiveObject())
}
   // Refresh page
function refreshBack() {
   setTimeout(function() {
      canvas_back.clear()
   }, 100);
}

function textAlignBack(value) {
   canvas_back.getActiveObject().set("textAlign", value);
   canvas_back.renderAll();
}

// Add Text
function addTextBack() {
   canvas_back.add(new fabric.Textbox('Text Here', {
      id: 'backText',
      left: 50,
      top: 100,
      fontFamily: document.getElementById('font-family-back').value,
      fill: document.getElementById('text-color-back').value,
      stroke: document.getElementById('text-color-back').value,
      strokeWidth: 0,
      fontSize: 45
   }));
}

// Add Name
function addTextFieldBack() {
var value = $('#fields_back option:selected').html().toUpperCase();
   canvas_back.add(new fabric.Textbox(value, {
      id: $('#fields_back option:selected').val(),
      left: 50,
      top: 100,
      textAlign: 'left',
      fixedWidth: 638,
      fw: 638,
      fontFamily: document.getElementById('font-family-back').value,
      fill: document.getElementById('text-color-back').value,
      stroke: document.getElementById('text-color-back').value,
      strokeWidth: 0,
      fontSize: 45
   }));
}

// Studentnumber
// function addStudentNumberBack() {
//    canvas_back.add(new fabric.IText('1900011', {
//    		id: 'studentNumber',
// 		left: 50,
// 		top: 100,
// 		fontFamily: 'arial',
// 		fill: '#000',
// 		stroke: document.getElementById('text-color-back').value,
// 		strokeWidth: .1,
// 		fontSize: 45
//    }));
// }

// Studentnumber
// function addYearLevelBack() {
//    canvas_back.add(new fabric.IText('Grade 5', {
//    		id: 'yearLevel',
// 		left: 50,
// 		top: 100,
// 		fontFamily: 'arial',
// 		fill: '#000',
// 		stroke: document.getElementById('text-color-back').value,
// 		strokeWidth: .1,
// 		fontSize: 45
//    }));
// }

/*******************************/
/******* TEXT CONTROLS ********/
/*****************************/
document.getElementById('text-color-back').onchange = function() {
   canvas_back.getActiveObject().set('fill', this.value);
   canvas_back.renderAll();
};
// document.getElementById('text-bg-color').onchange = function() {
//    canvas_back.getActiveObject().set('backgroundColor', this.value);
//    canvas_back.renderAll();
// };
// document.getElementById('text-lines-bg-color').onchange = function() {
//    canvas_back.getActiveObject().set('textBackgroundColor', this.value);
//    canvas_back.renderAll();
// };
// document.getElementById('text-stroke-color').onchange = function() {
//    canvas.getActiveObject().set('strokeColor', this.value);
//    canvas.renderAll();
// };
// document.getElementById('text-stroke-width').onchange = function() {
//    canvas.getActiveObject().setStrokeWidth(this.value);
//    canvas.renderAll();
// };
document.getElementById('font-family-back').onchange = function () {
   console.log('ff-back - ', this.value);
   canvas_back.getActiveObject().set('fontFamily', this.value);
   canvas_back.renderAll();
};

document.getElementById('text-font-size-back').onchange = function() {
   canvas_back.getActiveObject().set('fontSize', this.value);
   canvas_back.renderAll();
};
// document.getElementById('text-line-height').onchange = function() {
//    canvas_back.getActiveObject().setLineHeight(this.value);
//    canvas_back.renderAll();
// };
// document.getElementById('text-align').onchange = function() {
//    canvas.getActiveObject().setTextAlign(this.value);
//    canvas.renderAll();
// };

/********************************/
/******* SHAPE CONTROLS ********/
/******************************/
document.getElementById('shape-fill-back').onchange = function() {
   canvas_back.getActiveObject().set('fill', this.value);
   canvas_back.renderAll();
};



fonttypeBack = document.getElementsByName("fonttypeBack"); // wijzig naar button
for (var i = 0, max = fonttypeBack.length; i < max; i++) {
   fonttypeBack[i].onclick = function() {
      console.log("FF - BACK THIS , ", this);
      console.log("FF - BACK ID , ", this.id);
      console.log("FF - BACK BOOL , ", document.getElementById(this.id).checked);
      if (document.getElementById(this.id).checked == true) {
         if (this.id == "text-cmd-bold-back") {
            canvas_back.getActiveObject().set("fontWeight", "bold");
         }
         if (this.id == "text-cmd-italic-back") {
            canvas_back.getActiveObject().set("fontStyle", "italic");
         }
         if (this.id == "text-cmd-underline-back") {
            canvas_back.getActiveObject().set("textDecoration", "underline");
         }
         if (this.id == "text-cmd-linethrough-back") {
            canvas_back.getActiveObject().set("textDecoration", "line-through");
         }
         if (this.id == "text-cmd-overline-back") {
            canvas_back.getActiveObject().set("textDecoration", "overline");
         }
      } else {
         if (this.id == "text-cmd-bold-back") {
            canvas_back.getActiveObject().set("fontWeight", "");
         }
         if (this.id == "text-cmd-italic-back") {
            canvas_back.getActiveObject().set("fontStyle", "");
         }
         if (this.id == "text-cmd-underline-back") {
            canvas_back.getActiveObject().set("textDecoration", "");
         }
         if (this.id == "text-cmd-linethrough-back") {
            canvas_back.getActiveObject().set("textDecoration", "");
         }
         if (this.id == "text-cmd-overline-back") {
            canvas_back.getActiveObject().set("textDecoration", "");
         }
      }
      canvas_back.renderAll();
   }


}
// Send selected object to front or back
var selectedObject;
canvas_back.on('object:selected', function(event) {
   selectedObject = event.target;
});
var sendSelectedObjectBack = function() {
   canvas_back.sendToBack(selectedObject);
}
var sendSelectedObjectToFront = function() {
      canvas_back.bringToFront(selectedObject);
   }
   // Download
var imageSaverBack = document.getElementById('lnkDownloadBack');
imageSaverBack.addEventListener('click', saveImageBack, false);

function saveImageBack(e) {
   this.href = canvas_back.toDataURL({
      format: 'png',
      quality: 0.8
   });
   this.download = 'custom.png'
}
// Do some initializing stuff
fabric.Object.prototype.set({
   transparentCorners: true,
   cornerColor: '#22A7F0',
   borderColor: '#22A7F0',
   cornerSize: 12,
   padding: 5
});

function addAvatarBack(shape) {
   var shapeObj = null;

   // RECT
   if(shape == 'rect') 
   {
      shapeObj = new fabric.Rect({
         width: 100,
         height: 100,
         id: 'avatar',
         fill: document.getElementById('shape-fill-back').value,
      });
   }

   // CIRCLE
   if(shape == 'circle') 
   {
      shapeObj = new fabric.Circle({
         width: 100,
         height: 100,
         radius: 20,
         id: 'avatar',
         fill: document.getElementById('shape-fill-back').value,
      });
   }

   // TRIANGLE
   if(shape == 'triangle') 
   {
      shapeObj = new fabric.Triangle({
         width: 100,
         height: 100,
         id: 'avatar',
         fill: document.getElementById('shape-fill-back').value,
      });
   }

   canvas_back.add(shapeObj);
   canvas_back.setActiveObject(shapeObj);
   canvas_back.bringToFront(shapeObj);
}