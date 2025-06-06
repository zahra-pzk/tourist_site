document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('cardsContainer');
  
    fetch('/api/countries')
      .then(response => response.json())
      .then(countries => {
        if (Array.isArray(countries)) {
          countries.forEach(country => {
            const card = document.createElement('div');
            card.className = 'country-card';
            card.innerHTML = `
              <div class="glass-card">
                <h5>${country.name}</h5>
                <img src="/pages/img/earth.png" alt="کشور" class="card-icon">
              </div>
            `;
            container.appendChild(card);
          });
        } else {
          container.innerHTML = `<p>مشکلی در دریافت اطلاعات کشورها پیش آمد.</p>`;
        }
      })
      .catch(error => {
        console.error('خطا در دریافت داده:', error);
        container.innerHTML = `<p>مشکل در ارتباط با سرور.</p>`;
      });
  });
  