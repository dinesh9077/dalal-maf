<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Property Banner</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }

    .navbar {
      background-color: #6c603c;
      color: #fff;
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
    }

    .nav-buttons {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .nav-buttons button {
      background: none;
      border: none;
      color: #fff;
      font-size: 15px;
      cursor: pointer;
      padding: 6px 10px;
      transition: 0.3s ease;
    }

    .nav-buttons button.active {
      border-bottom: 2px solid #fff;
      font-weight: bold;
    }

    .nav-buttons button:hover {
      color: #ddd;
    }

    .close-btn {
      background: none;
      border: none;
      color: #fff;
      font-size: 20px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .close-btn:hover {
      color: #ddd;
      transform: scale(1.1);
    }

    .search-btn-container {
      position: absolute;
      left: 51%;
      transform: translateX(-50%);
      bottom: -25px;
      z-index: 5;
      width: 90%;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background-color: #fff;
      border: 1px solid #6c603c;
      border-radius: 6px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
      padding: 8px 12px;
      width: 90%;
    }

    .search-bar i {
      color: #6c603c;
      font-size: 16px;
      margin-right: 8px;
    }

    .search-bar input {
      flex: 1;
      border: none;
      outline: none;
      font-size: 15px;
      color: #333;
    }

    .home-banner {
      position: relative;
      overflow: hidden;
      margin-top: 40px;
    }

    .hero-image {
      background: #f0f0f0;
      height: auto;
      position: relative;
      padding: 40px 0 40px;
    }

    .banner-filter-form {
      margin: 0 auto;
      width: 90%;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 20px 15px;
    }

    .form-wrapper .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .form-wrapper input,
    .form-wrapper select {
      /* width: 90%; */
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
      outline: none;
      font-size: 14px;
    }

    .form-search-btn {
      background-color: #6c603c;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 10px 25px;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 15px;
    }

    .form-search-btn:hover {
      background-color: #5a5231;
    }

    .grid-item.home-des-border {
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 15px;
      background-color: #fff;
      /* width: 100%; */
      font-family: Arial, sans-serif;
    }

    .price-value {
      font-weight: bold;
      color: #6c603c;
      font-size: 15px;
    }

    .price-slider {
      position: relative;
      margin-top: 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .price-slider input[type="range"] {
      /* width: 100%; */
      -webkit-appearance: none;
      height: 0px;
      background: #ddd;
      border-radius: 5px;
      outline: none;
      cursor: pointer;
    }

    .price-slider input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: #6c603c;
      cursor: pointer;
      transition: 0.3s;
    }

    .price-slider input[type="range"]::-webkit-slider-thumb:hover {
      background: #5a5231;
    }

    @media (max-width: 767px) {
      .navbar {
        padding: 10px 15px 50px 15px;
      }

      .nav-buttons button {
        font-size: 14px;
        padding: 5px 8px;
      }

      .close-btn {
        font-size: 18px;
      }

      .search-btn-container {
        bottom: -20px;
      }

      .banner-filter-form {
        width: auto;
        padding: 15px 12px;
      }

      .form-wrapper .grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }

      .form-wrapper input,
      .form-wrapper select {
        font-size: 14px;
      }

      .hero-image {
        height: auto;
        padding-bottom: 20px;
      }
    }
  </style>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <nav class="navbar">
    <div class="nav-buttons">
      <button class="nav-link active" data-tab="buy">Buy</button>
      <button class="nav-link" data-tab="sale">Sale</button>
      <button class="nav-link" data-tab="rent">Rent</button>
      <button class="nav-link" data-tab="lease">Lease</button>
    </div>
    <button class="close-btn"><i class="fas fa-times"></i></button>
    <div class="search-btn-container">
      <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search properties..." id="searchInput" />
      </div>
    </div>
  </nav>

  <section class="home-banner">
    <div class="hero-image">
      <div class="banner-filter-form">
        <div class="tab-content form-wrapper">

          <div id="buy" class="tab-pane active">
            <form>
              <div class="grid">
                <input type="text" placeholder="Enter Location">
                <select>
                  <option>Select City</option>
                </select>
                <select>
                  <option>Select Property</option>
                </select>
                <select>
                  <option>Select Category</option>
                </select>
              </div>

              <!-- ✅ Price Slider moved above Search Button -->
              <div class="grid-item home-des-border" style="margin-top:15px;">
                <label class="price-value">
                  Price:<br>
                  <span id="price-range">₹0 - ₹10,00,000</span>
                </label>
                <div class="price-slider">
                  <input type="range" id="min-price" min="0" max="1000000" value="0" step="10000">
                  <input type="range" id="max-price" min="0" max="1000000" value="1000000" step="10000">
                </div>
              </div>

              <button type="button" class="form-search-btn" id="searchBtn">Search</button>
            </form>
          </div>

          <div id="sale" class="tab-pane" style="display: none;">
            <form>
              <div class="grid">
                <input type="text" placeholder="Enter Location">
                <select>
                  <option>Select City</option>
                </select>
                <select>
                  <option>Select Sale Property</option>
                </select>
                <select>
                  <option>Select Category</option>
                </select>
              </div>
              <button type="button" class="form-search-btn">Search</button>
            </form>
          </div>

          <div id="rent" class="tab-pane" style="display: none;">
            <form>
              <div class="grid">
                <input type="text" placeholder="Enter Location">
                <select>
                  <option>Select City</option>
                </select>
                <select>
                  <option>Select Rent Property</option>
                </select>
                <select>
                  <option>Select Category</option>
                </select>
              </div>
              <button type="button" class="form-search-btn">Search</button>
            </form>
          </div>

          <div id="lease" class="tab-pane" style="display: none;">
            <form>
              <div class="grid">
                <input type="text" placeholder="Enter Location">
                <select>
                  <option>Select City</option>
                </select>
                <select>
                  <option>Select Lease Property</option>
                </select>
                <select>
                  <option>Select Category</option>
                </select>
              </div>
              <button type="button" class="form-search-btn">Search</button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </section>

  <script>
    const tabButtons = document.querySelectorAll('.nav-link');
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabButtons.forEach(button => {
      button.addEventListener('click', () => {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanes.forEach(pane => pane.style.display = 'none');
        button.classList.add('active');
        const target = button.getAttribute('data-tab');
        document.getElementById(target).style.display = 'block';
      });
    });

    document.querySelector('.close-btn').addEventListener('click', () => {
      window.history.back();
    });

    const minSlider = document.getElementById("min-price");
    const maxSlider = document.getElementById("max-price");
    const priceRange = document.getElementById("price-range");

    function updatePrice() {
      let min = parseInt(minSlider.value);
      let max = parseInt(maxSlider.value);
      if (min > max - 10000) {
        min = max - 10000;
        minSlider.value = min;
      }
      priceRange.textContent = `₹${min.toLocaleString()} - ₹${max.toLocaleString()}`;
    }

    minSlider.addEventListener("input", updatePrice);
    maxSlider.addEventListener("input", updatePrice);
    updatePrice();
  </script>
</body>

</html>
