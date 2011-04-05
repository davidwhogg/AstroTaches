/*
	AstroTaches v0.1
	A product of astronomy hack day at .Astronomy 2011
	Created by:
		Phil Marshall (Oxford University),
		David W. Hogg (New York University) 
		Stuart R. Lowe (LCOGT)
		Pamela L. Gay (Astrosphere)

	Todo:
		1) Cursor distance travelled.
*/

function AstroTaches(inp){

	this.im = new Image();
	this.cur = {x:0,y:0};
	this.width;
	this.height;
	this.stroke = 12;
	this.src;
	this.holder = { id:'imageholder', img: 'imageholder_small' };
	this.drawing = false;
	this.maxopacity = 100;	// Out of 255
	this.opacitystep = 20;	// How much to change the opacity by (0-255)
	this.dx = 0;
	this.dy = 0;

	// Overwrite defaults with variables passed to the function
	if(typeof inp=="object"){
		if(typeof inp.id=="string"){
			this.id = inp.id;
		}
		if(typeof inp.src=="string") this.src = inp.src;
		if(typeof inp.width=="number") this.width = inp.width;
		if(typeof inp.height=="number") this.height = inp.height;
		if(typeof inp.stroke=="number") this.stroke = inp.stroke;
		if(typeof inp.maxopacity=="number") this.maxopacity = inp.maxopacity;
		if(typeof inp.opacitystep=="number") this.opacitystep = inp.opacitystep;
	}else{
		if(typeof inp=="string") this.src = inp;
	}
	if(typeof inp.callback=="function") this.callback = inp.callback;

	// If we haven't provided the image source as an input value, we look
	// for an image in the div container
	if(!this.src) this.src = $('#'+this.id+' img').attr('src');

	if(this.src){
		// Keep a copy of this so that we can reference it in the onload event
		var _object = this;
		// Define the onload event before setting the source otherwise Opera/IE might get confused
		this.im.onload = function(){ _object.loaded(); if(_object.callback) _object.callback.call(); }
		this.im.src = this.src
		this.pos = $('#'+this.holder.id).position();
	}
}

// We've loaded the image so now we can proceed
AstroTaches.prototype.loaded = function(){
	// Apply width/height depending on what input we have
	if(!this.width) this.width = this.im.width;	// No width defined so get it from the image
	if(!this.height) this.height = this.im.height;	// No height defined so get it from the image

	// Now we need to acquire the element on the page that we are using.
	// We'll allow it to be a <div> or a <canvas>
	var el = document.getElementById(this.id);
	if(el!=null){
		if(typeof el=="object" && el.tagName != "CANVAS"){
			// Looks like the element is a container for our <canvas>
			el.setAttribute('id',this.id+'holder');

			pos = $('#'+this.id+'holder').offset();
			elcanvas = document.createElement('canvas');
			elcanvas.style.display='block';
			elcanvas.setAttribute('width',this.width);
			elcanvas.setAttribute('height',this.height);
			elcanvas.setAttribute('id',this.id);
			el.appendChild(elcanvas);
			$('#'+this.id).css({position:'absolute',left:pos.left,top:pos.top})
			// For excanvas we need to initialise the newly created <canvas>
			if(/*@cc_on!@*/false) el = G_vmlCanvasManager.initElement(elcanvas);
			
		}else{
			// Define the size of the canvas
			this.width = el.getAttribute('width');
			this.height = el.getAttribute('height');
			// Excanvas doesn't seem to attach itself to the existing
			// <canvas> so we make a new one and replace it.
			if(/*@cc_on!@*/false){
				elcanvas = document.createElement('canvas');
				elcanvas.style.display='block';
				elcanvas.setAttribute('width',this.width);
				elcanvas.setAttribute('height',this.height);
				elcanvas.setAttribute('id',this.id);
				el.parentNode.replaceChild(elcanvas,el);
				if(/*@cc_on!@*/false) el = G_vmlCanvasManager.initElement(elcanvas);
			}
		}
	}else{
		// No appropriate ID or <canvas> exists. So we'll make one.
		elcanvas = document.createElement('canvas');
		elcanvas.style.display='block';
		elcanvas.setAttribute('width',this.width);
		elcanvas.setAttribute('height',this.height);
		elcanvas.setAttribute('id',this.id);
		document.body.appendChild(elcanvas);
		el = elcanvas;
		// For excanvas we need to initialise the newly created <canvas>
		if(/*@cc_on!@*/false) G_vmlCanvasManager.initElement(elcanvas);
	}

	// Now set up the canvas
	this.canvas = document.getElementById(this.id);

	if(this.canvas && this.canvas.getContext){  
		this.ctx = this.canvas.getContext('2d');
		this.wide = this.canvas.getAttribute("width");
		this.tall = this.canvas.getAttribute("height");

		$("#"+this.id).bind('mousemove',{data:this},function(e){
			data = e.data.data
			// Capture the cursor position
			// We don't need scrollX/scrollY as pageX/pageY seem to include this
			data.x = e.pageX - $(this).position().left;
			data.y = e.pageY - $(this).position().top;
			// Are we drawing?
			if(data.drawing){
				// Draw the pixel
				data.paint(data.x,data.y);
				// Store our pixel distance moved whilst drawing
				if(data.oldx && e.data.data.oldy){
					data.dx += Math.abs(data.x-data.oldx);
					data.dy += Math.abs(data.y-data.oldy);
				}
				data.oldx = data.x;
				data.oldy = data.y;
			}
		}).bind('mousedown',{data:this},function(e){
			data = e.data.data;
			data.x = e.pageX - $(this).position().left - window.scrollX;
			data.y = e.pageY - $(this).position().top - window.scrollY;
			data.oldx = data.x;
			data.oldy = data.y;
			data.drawing = true;
			data.paint(this.x,this.y);
			$(e.data.data.canvas).css({cursor:'pointer'});
		}).bind('mouseup',{data:this},function(e){
			e.data.data.drawing = false;
			$(e.data.data.canvas).css({cursor:'default'});
		}).bind('mouseout',{data:this},function(e){
			e.data.data.dragging = false;
			e.data.data.mouseover = false;
			e.data.data.x = "";
		}).bind('mouseenter',{data:this},function(e){
			e.data.data.mouseover = true;
		});
		this.draw();
	}
}

AstroTaches.prototype.draw = function(){

	if(this.canvas && this.canvas.getContext){ 
		this.ctx.moveTo(0,0);
		this.ctx.clearRect(0,0,this.wide,this.tall);
		this.ctx.fillStyle = "rgba(0,0,0,0)";
		this.ctx.fillRect(0,0,this.wide,this.tall);
		this.ctx.fill();

		// create a new batch of pixels with the same
		// dimensions as the image:
		this.scribbles = this.ctx.createImageData(this.wide, this.tall);	
	}
}

/*AstroTaches.prototype.drawStroke = function(x,y){
	this.ctx.beginPath();
	this.ctx.fillStyle = "rgb(255,0,0)";
	this.ctx.arc(x, y, this.stroke/2, 0, Math.PI * 2, false);
	this.ctx.closePath();
}*/

AstroTaches.prototype.paint = function(x,y){
	if(this.eraser) this.paintEraser(x,y);
	else this.paintPixel(x,y);
}

AstroTaches.prototype.paintPixel = function(x,y){
	var x_c = x-this.stroke/2;
	var y_c = y-this.stroke/2;
	var wide = this.stroke;
	var tall = this.stroke;
	var f = 0.2;

	if(x_c < 0){
		x_c = 0;
		this.wide = x+this.stroke;
	}
	if(y_c < 0){
		y_c = 0;
		this.tall = y+this.stroke;
	}

	this.imageData = this.ctx.getImageData(x_c, y_c, wide, tall);

	for(x = 0 ; x < wide ; x++){
		for(y = 0 ; y < tall ; y++){
			// Calculate the image index
			pos = (y*wide+x)*4;
			this.imageData.data[pos] = 0;
			this.imageData.data[pos+1] = 226;
			this.imageData.data[pos+2] = 255;
			op = this.imageData.data[pos+3]+this.opacitystep;
			this.imageData.data[pos+3] = (op < this.maxopacity) ? op : this.maxopacity; // alpha
			this.ctx.putImageData(this.imageData, x_c, y_c);
		}
	}
}

AstroTaches.prototype.paintEraser = function(){
	var x_c = x-this.stroke/2;
	var y_c = y-this.stroke/2;
	var wide = this.stroke;
	var tall = this.stroke;

	if(x_c < 0){
		x_c = 0;
		this.wide = x+this.stroke;
	}
	if(y_c < 0){
		y_c = 0;
		this.tall = y+this.stroke;
	}

	this.imageData = this.ctx.getImageData(x_c, y_c, wide, tall);

	// Draw a rectangle around the cursor
	for(x = 0 ; x < wide ; x++){
		for(y = 0 ; y < tall ; y++){
			// Calculate the image index
			pos = (y*wide+x)*4;
			this.imageData.data[pos] = 0;
			this.imageData.data[pos+1] = 226;
			this.imageData.data[pos+2] = 255;
			op = this.imageData.data[pos+3]-this.opacitystep;
			this.imageData.data[pos+3] = (op > 0) ? op : 0; // alpha
			this.ctx.putImageData(this.imageData, x_c, y_c);
		}
	}
}

AstroTaches.prototype.getImage = function(){
	return this.ctx.getImageData(0, 0, this.wide, this.tall);
}


