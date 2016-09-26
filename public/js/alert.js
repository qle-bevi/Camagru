(function() {
	var alertElem = document.querySelector('.alert');
	if (!alertElem)
		return ;
	var close = alertElem.querySelector('.close');
	var hover = false;
	var closing = false;
    console.log(alertElem.dataset.delay);
	var delay = parseInt(alertElem.dataset.delay);

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

	close.addEventListener("click", function(ev) {
		ev.preventDefault();
		hover = false;
		doClose();
	});

	if (!delay)
		return ;
    console.log(delay);
	alertElem.addEventListener("mouseenter", function over() {
		hover = true;
	});

	alertElem.addEventListener("mouseleave", function leave() {
		hover = false;
		setTimeout(doClose, delay);
	});

	setTimeout(doClose, delay);
})();
