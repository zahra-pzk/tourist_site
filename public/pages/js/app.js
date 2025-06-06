const attractionDetails = document.getElementById("attraction-details");
const menuContainer = document.querySelector(".menu-level-1");

async function fetchData(url, options = {}) {
  try {
    const response = await fetch(url, options);
    if (!response.ok) throw new Error("خطا در دریافت داده‌ها");
    return await response.json();
  } catch (error) {
    console.error("خطا:", error);
    return [];
  }
}

async function loadCountries() {
  const countries = await fetchData("/api/countries");

  countries.forEach((country) => {
    const countryItem = document.createElement("div");
    countryItem.className = "country-item";
    countryItem.textContent = country.name;
    countryItem.dataset.countryId = country.id;

    const provinceMenu = document.createElement("div");
    provinceMenu.className = "menu-level-2";
    countryItem.appendChild(provinceMenu);

    countryItem.addEventListener("mouseenter", () => {
      loadProvinces(provinceMenu, country.id);
    });

    menuContainer.appendChild(countryItem);
  });
}

async function loadProvinces(container, countryId) {
  container.innerHTML = "";
  const provinces = await fetchData("/api/provinces?country_id=" + countryId);

  provinces.forEach((province) => {
    const provinceItem = document.createElement("div");
    provinceItem.className = "province-item";
    provinceItem.textContent = province.name;
    provinceItem.dataset.provinceId = province.id;

    const attractionMenu = document.createElement("div");
    attractionMenu.className = "menu-level-3";
    provinceItem.appendChild(attractionMenu);

    provinceItem.addEventListener("mouseenter", () => {
      loadAttractions(attractionMenu, province.id);
    });

    container.appendChild(provinceItem);
  });
}

async function loadAttractions(container, provinceId) {
  container.innerHTML = "";
 const places = await fetchData("/api/places?province_id=" + provinceId);
  places.forEach((place) => {
    const placeItem = document.createElement("div");
    placeItem.className = "attraction-item";
    placeItem.textContent = place.name;
    placeItem.dataset.placeId = place.id;

    placeItem.addEventListener("click", () => {
      showAttractionDetails(place.id);
    });

    container.appendChild(placeItem);
  });
}

async function showAttractionDetails(placeId) {
const place = await fetchData(`/api/places/${placeId}`);

  if (place) {
    attractionDetails.innerHTML = `
      <img src="${place.image_url || '/images/default.jpg'}" alt="${place.name}" />
      <h2>${place.name}</h2>
      <p>${place.description}</p>
    `;
    attractionDetails.classList.add("active");

    window.scrollTo({
      top: attractionDetails.offsetTop - 20,
      behavior: "smooth",
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  loadCountries();
});
