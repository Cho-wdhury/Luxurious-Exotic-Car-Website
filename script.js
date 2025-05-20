const cars = {
    Ferrari: [
      {
        name: "Ferrari SF90 Stradale",
        img: "images/ferrari1.jpg",
        info: "Plug-in hybrid supercar with 986 hp and 0–100 in 2.5 sec."
      },
      {
        name: "Ferrari Roma",
        img: "images/ferrari2.jpg",
        info: "Elegant grand tourer with 612 hp and timeless design."
      },
    ],
    Lamborghini: [
      {
        name: "Lamborghini Aventador",
        img: "images/lamborghini1.jpg",
        info: "Naturally aspirated V12 with aggressive style."
      },
      {
        name: "Lamborghini Huracán",
        img: "images/lamborghini2.jpg",
        info: "V10 beast perfect for both track and road."
      }
    ]
  };
  
  function showCars(brand) {
    const gallery = document.getElementById("carGallery");
    gallery.innerHTML = `<h2>${brand} Cars</h2>`;
  
    cars[brand].forEach(car => {
      const card = document.createElement("div");
      card.className = "car-card";
      card.innerHTML = `
        <h3>${car.name}</h3>
        <img src="${car.img}" alt="${car.name}" />
      `;
      card.onclick = () => showDetail(car);
      gallery.appendChild(card);
    });
  }
  
  function showDetail(car) {
    document.getElementById("carDetail").classList.remove("hidden");
    document.getElementById("carTitle").textContent = car.name;
    document.getElementById("carImage").src = car.img;
    document.getElementById("carInfo").textContent = car.info;
  }
  
  function closeDetail() {
    document.getElementById("carDetail").classList.add("hidden");
  }
  