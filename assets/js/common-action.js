$(document).ready(function () {
    //console.log("test");
    $(".nav-show-hide").on("click", function () {
        let main = $("#main-section-container");
        let nav = $("#side-nav-container");
        if (main.hasClass("paddingleft")) {
            nav.hide();
        } else {
            nav.show();
        }
        // Add or remove one or more class from each element in the set of matched elements,
        main.toggleClass("paddingleft");
    })
});

function notSignedIn(){
    alert("You are not sign in yet");
}