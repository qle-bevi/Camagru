(function() {
	var toggled = false;
    var $userToggler = document.querySelector("#user-toggler");
    var $userActions = document.querySelector("#user-actions");

    $userToggler.addEventListener("click", function (ev) {
		ev.stopPropagation();
        $userActions.classList.toggle("hide");
		toggled = !toggled;
    });

	document.addEventListener("click", function (ev) {
		if (!toggled)
			return ;
		$userActions.classList.add("hide");
		toggled = false;
	});

})();
