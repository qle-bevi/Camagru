(function() {
    var $userToggler = document.querySelector("#user-toggler");
    var $userActions = document.querySelector("#user-actions");

    $userToggler.addEventListener("click", function (ev) {
        $userActions.classList.toggle("hide");
    });
})();