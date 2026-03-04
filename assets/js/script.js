const form = document.getElementById('weatherForm');

form.addEventListener('submit', e => {
    e.preventDefault();
    const city = form.city.value.trim();
    if (!city) return;  

    const btn = form.querySelector('button');
    const loading = document.getElementById('loading');
    const result = document.getElementById('result');

    loading.style.display = 'flex';
    btn.disabled = true;
    btn.innerText = 'Ładowanie...';
    btn.style.cursor = 'not-allowed';
    result.innerHTML = '';

    fetch(`weather.php?city=${encodeURIComponent(city)}`)
        .then(r => r.text())
        .then(data => {
            result.innerHTML = data;
            loading.style.display = 'none';
            btn.disabled = false;
            btn.innerText = 'Sprawdź';
            btn.style.cursor = 'pointer';
        })
        .catch(() => {
            result.innerHTML = '<p class="error">Błąd pobierania danych</p>';
            loading.style.display = 'none';
            btn.disabled = false;
            btn.innerText = 'Sprawdź';
            btn.style.cursor = 'pointer';
        });
});