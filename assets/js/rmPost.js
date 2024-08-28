document.addEventListener('DOMContentLoaded', () => {
    fetch('data/posts.json')
        .then(response => response.json())
        .then(data => {
            console.log('JSON adat:', data); // Ellenőrizd az adatokat

            // Az objektum átalakítása tömbbé
            const postsArray = Object.values(data);

            const select = document.getElementById('postSelect');
            postsArray.forEach(post => {
                const option = document.createElement('option');
                option.value = post.title;  // Beállíthatod a cím értékét is
                option.textContent = post.title;  // Az opció szövege
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Hiba a JSON betöltésekor:', error));
});