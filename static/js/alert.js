(function() {
	var alertElem = document.querySelector('.alert');
	if (!alertElem)
		return ;
	var close = alertElem.querySelector('.close');
	var hover = false;
	var closing = false;

	function doClose()
	{
		if (hover || closing)
			return ;
		closing = true;
		alertElem.classList.add("anim-close");
		alertElem.classList.remove("anim-play");
		void alertElem.offsetWidth;
		alertElem.classList.add("anim-play");
		setTimeout(function() {
			alertElem.parentElement.removeChild(alertElem);
		}, 1500);
	}

	alertElem.addEventListener("mouseenter", function over() {
		hover = true;
	});

	alertElem.addEventListener("mouseleave", function leave() {
		hover = false;
		setTimeout(doClose, 5000);
	});

	close.addEventListener("click", function(ev) {
		ev.preventDefault();
		hover = false;
		doClose();
	});

	setTimeout(doClose, 5000);
})();
