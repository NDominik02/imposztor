document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebar');
    var toggle = document.getElementById('toggle-btn');

    toggle.addEventListener('click', function (event) {
        event.stopPropagation();
        sidebar.classList.toggle('active');
        toggle.classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
        if (sidebar.classList.contains('active') && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove('active');
            toggle.classList.remove('hidden');
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var opener = document.querySelector("#menu .opener");

    opener.addEventListener("click", function () {
        this.classList.toggle("active");
    });
});

window.onload = function () {
    // Az aktuális URL lekérése
    var url = window.location.href;

    // Ellenőrizzük, hogy az URL tartalmazza-e a kívánt szót, és hozzáadjuk a megfelelő osztályt
    if (url.includes('index')) {
        document.getElementById('menu-fooldal').classList.add('active-fooldal');
    } else if (url.includes('criticism')) {
        document.getElementById('menu-kritika').classList.add('active-kritika');
    } else if (url.includes('recommendation')) {
        document.getElementById('menu-ajanlo').classList.add('active-ajanlo');
    } else if (url.includes('events')) {
        document.getElementById('menu-esemenyek').classList.add('active-esemenyek');
    } else if (url.includes('imprint')) {
        document.getElementById('menu-impresszum').classList.add('active-impresszum');
    } else if (url.includes('prose')) {
        document.getElementById('menu-proza').classList.add('active-proza');
        document.getElementById('menu-irodalom').classList.add('active-irodalom');
    } else if (url.includes('lira')) {
        document.getElementById('menu-lira').classList.add('active-lira');
        document.getElementById('menu-irodalom').classList.add('active-irodalom');
    }
};