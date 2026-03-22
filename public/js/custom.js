/**
 * Set Cookie
 * @param cname
 * @param cvalue
 * @param exdays
 */
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

/**
 * Get Cookie by Name
 * @param cname
 * @returns {string}
 */
function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return decodeURIComponent(c.substring(name.length, c.length));
        }
    }
    return "";
}

document.addEventListener("DOMContentLoaded", function() {
    const contentModal = document.querySelector('#contentModal');
    const closeButton = document.querySelector('#closeButton');

    closeButton.addEventListener("click", function() {
        contentModal.style.display = "none";
    })

    const confModal = document.querySelector('#confirmModal');
    const cancelButton = document.querySelector('#cancelButton');

    cancelButton.addEventListener("click", function() {
        confModal.style.display = "none";
    })

    const flashModal = document.querySelector('#flashModal');
    const flashBox = document.querySelector('#flashBox');
    const flashContent = document.querySelector('#flashContent');
    let flashCookie = getCookie('flashCookie');
    if(flashCookie !== "") {
        let flash = flashCookie.split(';')
        flashBox.classList.add(flash[0]);
        flashContent.innerHTML = flash[1];
        flashModal.style.display="flex";

        setCookie('flashCookie','',0);
        setTimeout(closeFlash,2000);
    }

    function closeFlash() {
        flashModal.style.display="none";
    }
})
