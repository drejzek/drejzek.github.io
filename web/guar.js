var message = "Je nám líto ale tato možnost je blokována.";

function rtclickcheck(keyp) {
    if (navigator.appName == "Netscape" && keyp.which == 3) {
        alert(message);
        return false;
    }

    if (navigator.appVersion.indexOf("MSIE") != -1 && keyp.button == 2) {
        alert(message);
        return false;
    }
}

document.onmousedown = rtclickcheck;

document.addEventListener("keydown", function (event) {
    if (event.ctrlKey) {
        if (event.keyCode === 67 || event.keyCode === 86) {
            // Allow Ctrl+C and Ctrl+V
            return true;
        } else {
            event.preventDefault();
        }
    }
    if (event.keyCode === 123) {
        // Disable F12
        event.preventDefault();
    }
    if (ctrlShiftKey(event, 'J') || ctrlShiftKey(event, 'I')) {
        // Disable Ctrl+Shift+J and Ctrl+Shift+I
        event.preventDefault();
    }
});

$(document).ready(function () {
    $('body').bind('cut copy', function (e) {
        e.preventDefault();
    });
});

// Disable right-click
document.addEventListener('contextmenu', (e) => e.preventDefault());

document.onkeydown = (e) => {
    // Disable Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
    if (
        ctrlShiftKey(e, 'I') ||
        ctrlShiftKey(e, 'J') ||
        (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
    )
        return false;
};

function ctrlShiftKey(e, keyCode) {
    return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
}
