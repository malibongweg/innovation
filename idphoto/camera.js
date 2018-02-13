var cam = null;
var image = null;
var img = new Image();
var pos = 0;
var userid_val = null;
var myCrop;// = null;
var ctx = null;
var root_directory = null;
var sysdate = null;
var objState = false;


function snapPic(){
	if (objState == true){
		myCrop.destroy();
	}
	if (webcam != null){
		$('show-busy').setStyle('display','block');
		webcam.capture();
		$('userid').set('value',$('id-srch').get('value'));
		userid_val = $('id-srch').get('value');
		void(0);
	}
}

$j(document).ready(function(){

if (canvas.getContext) {
		ctx = document.getElementById("canvas").getContext("2d");
		ctx.clearRect(0, 0, 320, 240);
		image = ctx.getImageData(0, 0, 320, 240);
}

sysdate = $('sysdate').get('value');
root_directory = $('rootdir').get('value');


$j("#camera").webcam({

	width: 320,
	height: 240,
	mode: "save",
	swffile: "/scripts/idphoto/jscam.swf",

	onTick: function(remain) {},

	onSave: function(data) {
		var cimg = '/scripts/idphoto/images/'+$('sysid').get('value')+'.jpg?'+ new Date().getTime();
		$('captured-pic').set('src',cimg);
		$('show-busy').setStyle('display','none');
	
		myCrop = new uvumiCropper('captured-pic',{
		keepRatio:true,
		//preview:'myPreview',
		handles:[
		['top','left'],
		['top','right'],
		['bottom','left'],
		['bottom','right']
		],
		coordinates:false,
		saveButton: false
		});
		objState = true;
		
	//} else {
	//	myCrop.changeImage('/scripts/idphoto/images/'+$('sysid').get('value')+'.jpg?'+ new Date().getTime());
	//}
	},

	onCapture: function () {
		//myCrop.destroy();
		var x = webcam.save("/scripts/idphoto/pic.php?sysid=" + $('sysid').get('value'));
	},

	debug: function (type, string) { $j("#status").html(string); },

	onLoad: function () { }
});


});

