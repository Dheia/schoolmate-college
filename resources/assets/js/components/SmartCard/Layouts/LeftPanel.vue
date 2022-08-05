<template>
	<div id="leftPanel">
		
		<div class="addBackgroundImage">
			<label title="Add a background" for="file">Add Background Image</label>
			<input type="file" ref="backgroundImageFile" v-on:change="uploadBackgroundImage()">
		</div>

		<br>
		<div class="addAvatarPlaceholder">
			<label title="Add a background" for="file">Add Avatar Placeholder</label>
			<br>
			<a href="javascript:void(0)" class="" @click="createAvatarShape('square')"><i class="fa fa-square-o"></i> Square</a><br>
			<a href="javascript:void(0)" class="" @click="createAvatarShape('circle')"><i class="fa fa-circle-o"></i> Circle</a><br>
			<a href="javascript:void(0)" class="" @click="createAvatarShape('triangle')"><i class="fa fa-exclamation-triangle"></i> Triangle</a>
		</div>

		<br>
		<div class="addStudentField">
			<label title="Add a background" for="file">Add Student Field</label>
			<select class="browser-default">
				<option value="" disabled selected>-</option>
				<option v-for="column in studentColumns" :value="column.column_key">{{ column.column_name }}</option>
			</select>
			<button class="btn waves-effect waves-light btn-small" name="addStudentField" style="width: 100%;">Add Field
			    <i class="material-icons right">add</i>
		  	</button>
		</div>
	</div>
</template>

<style>

#leftPanel a {
	font-size: 12px;
	color: #ccc;
}

#leftPanel label {
	font-size: .8rem;
    color: #FFF;
    font-weight: bold;
}

.addStudentField .browser-default {
	height: auto !important;
	padding: 3px !important;
}

.addStudentField button {
    height: 24.4px;
    line-height: 24.4px;
    font-size: 11px;
}

</style>

<script>

export default {
	data () {
		return {
			baseUrl: location.protocol + '//' + location.host,
			studentColumns: null,
			backgroundImageFile: null,
		}
	},
	beforeCreate () {
		self = this;
		axios.get( window.location.protocol + '//' + window.location.host + '/admin/smartcard/api/student-columns').then(function (response) {

			self.studentColumns = response.data;
		});
	},
	methods: {
		uploadBackgroundImage () {
			var self = this;
			var file = self.$refs.backgroundImageFile.files[0];

			if(file['type'] === 'image/jpeg' || file['type'] === 'image/png') {

				var reader = new FileReader();
				reader.onload = function(f) {
					var data = f.target.result;
					fabric.Image.fromURL(data, function(img) {
						// add background image
						img.scaleX = self.$store.state[self.$store.state.cardType + 'CardRef'].width / img.width;
						img.scaleY = self.$store.state[self.$store.state.cardType + 'CardRef'].height / img.height;
						self.$store.state[self.$store.state.cardType + 'CardRef'].setBackgroundImage(img, self.$store.state[self.$store.state.cardType + 'CardRef'].renderAll.bind(self.$store.state.frontCardRef));
					});
				};
				reader.readAsDataURL(file);
				self.$store.state[self.$store.state.cardType + 'CardRef'].renderAll();
			} else {
				alert('file type error...');
			}
		},

		createAvatarShape (shape) {
			var self = this, 
				shapeObj = null;

		    if(shape == 'square') shapeObj = self.$store.state[self.$store.state.cardType + 'CardRef'].createRect({ left: 50, top: 50, width: 50, height: 50, fill: 'rgba(255,127,39,1)', stroke: 'rgba(34,177,76,1)', strokeWidth: 1 });
		    if(shape == 'circle') shapeObj = self.$store.state[self.$store.state.cardType + 'CardRef'].createCircle({ radius: 15, fill: 'blue', left: 100, top: 100 });
		    if(shape == 'triangle') shapeObj = self.$store.state[self.$store.state.cardType + 'CardRef'].createTriangle({ top: 450, left: 500, width: 200, height: 200, fill: 'rgb(204,0,107)' });

		}
	},
	mounted () {

	},
	components: {}
}

</script>