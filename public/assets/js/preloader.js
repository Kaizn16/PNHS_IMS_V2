window.addEventListener("load", function() {
    if (document.readyState === 'complete') {
        setTimeout(function() {
            document.getElementById("loading-overlay").style.visibility = "hidden";
            document.querySelector(".container").style.visibility = "visible";
        }, 1000);
    }
});