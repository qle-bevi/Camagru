(function () {

	function $dq(selector) {
		return document.querySelector(selector);
	}

	function $dqa(selector) {
		return document.querySelectorAll(selector);
	}

	HTMLElement.prototype.show = function () {
		this.classList.remove('hide');
	}

	HTMLElement.prototype.hide = function () {
		this.classList.add('hide');
	}

	HTMLElement.prototype.active = function () {
		this.classList.add('black');
		this.classList.remove('blue');
	}

	HTMLElement.prototype.inactive = function () {
		this.classList.add('blue');
		this.classList.remove('black');
	}

	NodeList.prototype.forEach = function (cb) {
		return Array.prototype.forEach.call(this, cb);
	}

	function getMeta(name) {
		var metas = document.getElementsByTagName('meta');

	   for (var i=0; i<metas.length; i++) {
	      if (metas[i].getAttribute("name") === name) {
	         return metas[i].getAttribute("content");
	      }
	   }
	   return null;
	}

	function setMeta(name, content) {
		var metas = document.getElementsByTagName('meta');

	   for (var i=0; i<metas.length; i++) {
	      if (metas[i].getAttribute("name") === name) {
	         metas[i].setAttribute("content", content);
			 return true;
	      }
	   }
	   return false;
	}

	function xhrUpload(url, data, onStart, onProgress) {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', url, true);
		xhr.setRequestHeader("csrf-token", getMeta("csrf-token"));
		onStart(xhr);
		xhr.upload.addEventListener("progress", function(ev) {
			var percent = Math.round(ev.loaded/ev.total*100);
			onProgress(percent);
		});
		return new Promise(function (resolve, reject) {
			xhr.onload = function () {
				var token = xhr.getResponseHeader('csrf-token');
				if (token)
					setMeta("csrf-token", token);
				if (xhr.status == 200) {
					resolve(xhr.responseText);
				} else {
					reject(xhr.responseText);
				}
			}
			xhr.send(data);
		});
	}

	/*
	** EDITOR
	*/

	var $editor = $dq("#editor");

	var boost 		= 1;
	var scaleInt	= false;
	var rotInt		= false;

	var ctrls = {
		CTRL:17,
		LEFT: 37,
		UP: 38,
		RIGHT: 39,
		DOWN: 40,
		R_KEY: 82,
		S_KEY: 83
	}

	toggles = [];

	function createToggler (selector, fn) {
		var elem = $dq(selector);
		return function(active) {
			if (active)
				elem.classList.add("active");
			else
				elem.classList.remove("active");
			fn(active);
		}
	}

	toggles[ctrls.CTRL] = createToggler("[data-key='" + ctrls.CTRL + "']", function (active) {
		boost = (active) ? 3 : 1;
	});

	toggles[ctrls.R_KEY] = function (active) {
		rotInt = active;
	}

	toggles[ctrls.S_KEY] = function (active) {
		scaleInt = active;
	}

	var ctrlsAxis = [ctrls.LEFT, ctrls.UP, ctrls.DOWN, ctrls.RIGHT];

	window.addEventListener("keydown", function (ev) {
		if (!isValidKey(ev.keyCode)) return ;
		ev.preventDefault();
		var axis = ctrlsAxis.indexOf(ev.keyCode);
		if (axis >= 0)
			applyAxis(ev.keyCode)
		else if (toggles[ev.keyCode])
			toggles[ev.keyCode](true);
	});

	window.addEventListener("keyup", function (ev) {
		var kc = ev.keyCode;
		ev.preventDefault();
		if (toggles[ev.keyCode])
			toggles[ev.keyCode](false);
	});

	function isValidKey(key) {
		for (var k in ctrls) {
			if (ctrls[k] == key) return true;
		}
		return false;
	}

	function applyAxis(axis) {
		if (scaleInt) applyScale(axis);
		else if (rotInt) applyRotation(axis);
		else if (axis == ctrls.LEFT) assetData.x -= 5 * boost;
		else if (axis == ctrls.RIGHT) assetData.x += 5 * boost;
		else if (axis == ctrls.UP) assetData.y -= 5 * boost;
		else if (axis == ctrls.DOWN)  assetData.y += 5 * boost;
		invalidateAsset();
	}

	function applyRotation(axis) {
		if (axis == ctrls.LEFT || axis == ctrls.DOWN)
			assetData.rotation -= 5 * boost;
		else
			assetData.rotation += 5 * boost;
	}

	function applyScale(axis) {
		if (axis == ctrls.LEFT || axis == ctrls.DOWN)
			assetData.scale -= 0.02 * boost;
		else
			assetData.scale  += 0.02 * boost;
	}


	function invalidateAsset() {
		if (assetData.scale < 0.5)
			assetData.scale = 0.5;
		else if (assetData.scale > 2)
			assetData.scale =  2;
		if (virtualX() > $canvas.width)
			assetData.x = $canvas.width - virtualWidth() / 2;
		else if (virtualX() < 0)
			assetData.x = 0 - virtualWidth() / 2;
		if (virtualY() > $canvas.height)
			assetData.y = $canvas.height - virtualHeight() / 2;
		else if (virtualY() < 0)
			assetData.y = 0 - virtualHeight() / 2;
		render();
	}

	/*
	** CANVAS
	*/

	var $canvas = $dq("#canvas");
	var ctx = $canvas.getContext("2d");
	var canvasImage = null;
	var canvasAssetImage = null;
	var assetData = {
		x: 0,
		y: 0,
		width: 0,
		height: 0,
		rotation: 0,
		scale: 1
	}

	function setAsset(image) {
		canvasAssetImage = image;
		assetData.width = image.width;
		assetData.height = image.height;
		invalidateAsset();
	}

	function virtualWidth() {
		return assetData.width * assetData.scale;
	}

	function virtualHeight() {
		return assetData.height * assetData.scale;
	}

	function virtualX() {
		return assetData.x + virtualWidth() / 2;
	}

	function virtualY() {
		return assetData.y + virtualHeight() / 2;
	}

	function render() {
		ctx.clearRect(0, 0, $canvas.width, $canvas.height);
		if (canvasImage)
			ctx.drawImage(canvasImage, 0, 0, $canvas.width, $canvas.height);
		if (canvasAssetImage)
			renderAsset();
	}

	function renderAsset() {
		ctx.save();
		ctx.translate(virtualX(), virtualY());
		ctx.rotate(assetData.rotation * Math.PI / 180);
		ctx.drawImage(
			canvasAssetImage,
			assetData.x - virtualX(),
			assetData.y - virtualY(),
			virtualWidth(),
			virtualHeight()
		);
		ctx.restore();
	}


	/*
	** ASSETS
	*/

	var $assets = $dqa("[data-asset]");

	$assets.forEach(function ($asset) {
		$asset.addEventListener("click", function (ev) {
			ev.preventDefault();
			var image = new Image();
			image.src = $asset.src;
			image.onload = function () {
				setAsset(image);
			}
		});
	});

	/*
	** WEBCAM BUTTON
	*/

	var $camBtn 	= $dq("#btn-cam");
	var $video 		= $dq("#video");
	var useCamera 	= false;

	navigator.getUserMedia({ video: true }, function () {
		$camBtm.show();
	}, function(){});

	$camBtn.addEventListener("click", function(ev) {
		ev.preventDefault();
		navigator.getUserMedia({
			video: 1280, height: 720
		}, handleStream, function () {
			alert("Erreur avec la webcam !");
			$camBtn.hide();
		})
	});

	function handleStream(stream) {
		useCamera = true;
		$imgBtn.inactive();
		$video.src = window.URL.createObjectURL(stream);
		renderCamera();
		$camBtn.active();
		$editor.show();
	}

	function renderCamera() {
		if (!useCamera)
			return ;
		window.requestAnimationFrame(renderCamera);
		canvasImage = $video;
	}

	/*
	** IMAGE BUTTON
	*/

	var $imgBtn 		= $dq("#btn-image");
	var loadedImage 	= null;

	function useImage() {
		useCamera = false;
		$camBtn.inactive();
		canvasImage = loadedImage;
		$imgBtn.show();
		$imgBtn.active();
		$editor.show();
		render();
	}

	$imgBtn.onclick = function(ev) {
		ev.preventDefault();
		useImage();
	}

	/*
	** UPLOAD BUTTON
	*/

	var $uploadBtn = $dq("#btn-upload");
	var $uploadInfos = $dq("#infos-upload");
	var $uploadPercent = $dq("#percent-upload");
	var isUploading = false;
	$uploadBtn.addEventListener("change", uploadImage);

	function uploadImage(ev) {
		ev.preventDefault();
		if (isUploading || $uploadBtn.value === null)
			return ;
		isUploading = true;
		$uploadBtn.hide();
		$uploadPercent.innerText = "upload 0%";
		var file = $uploadBtn.files[0];
		xhrUpload("/upload-image", file, function (xhr) {
			xhr.setRequestHeader("Content-type", file.type);
	        xhr.setRequestHeader("X_FILE_NAME", file.name);
		}, uploadProgress)
		.then(uploadDone)
		.catch(alert)
		.then(uploadClear);
		$uploadInfos.show();
	}

	function uploadProgress(percent) {
		$uploadPercent.innerText = "upload "+percent+"%";
	}

	function uploadDone(imgData) {
		loadedImage = new Image();
		loadedImage.src = imgData;
		useImage();
	}

	function uploadClear() {
		$uploadInfos.hide();
		$uploadBtn.value = null;
		$uploadBtn.show();
		isUploading = false;
	}

	/*
	** SHOOT BUTTON
	*/

	$shootBtn = $dq("#shoot-btn");
	var isShootUploading = false;

	$shootBtn.addEventListener("click", shoot);

	function shoot(ev) {
		ev.preventDefault();
		if (!canvasImage || !canvasAssetImage)
			return alert("Choisis un asset d'abord !");
		if (isShootUploading)
			return ;
		isShootUploading = true;
		$shootBtn.hide();
		var form = new FormData();
		form.append("image", canvasImage.src);
		form.append("asset", canvasAssetImage.src);
		form.append("shoot-upload", "OK");
		xhrUpload("/upload-shoot", form, function () {}, function (percent) {
			console.log(percent + "%");
		})
		.then(shootDone).catch(alert);
	}

	function shootDone() {
		console.log("over");
	}
})();
