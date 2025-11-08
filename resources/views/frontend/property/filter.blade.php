<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Property Banner</title>

    <style>
    body {
        margin: 0;
        background-color: #f9f9f9;
        font-family: 'Open Sans', sans-serif;
    }

    .navbar {
        background-color: #6c603c;
        color: #fff;
        padding: 30px 20px;
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
        /* margin-top: 40px; */
    }

    .hero-image {
        background: #f0f0f0;
        height: auto;
        position: relative;
        /* padding: 40px 0 40px; */
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

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-weight: bold;
        color: #6c603c;
        font-size: 14px;
    }

    .form-wrapper input,
    .form-wrapper select {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 14px;
        color: #333;
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
        font-family: Arial, sans-serif;
    }

    .price-range-container {
        width: 100%;
        margin: 10px 0;
    }

    .price-label {
        font-weight: bold;
        color: #6c603c;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .price-values {
        margin: 5px 0 10px;
        font-size: 14px;
        color: #333;
    }

    .slider-container {
        position: relative;
        width: 30%;
        height: 25px;
    }

    input[type="range"] {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        pointer-events: none;
        -webkit-appearance: none;
        background: none;
        border: none;
        box-shadow: none;
    }

    input[type="range"]::-webkit-slider-runnable-track {
        height: 4px;
        border-radius: 2px;
        background: #bfb8a2;
        border: none;
    }

    input[type="range"]::-webkit-slider-thumb {
        pointer-events: all;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #6c603c;
        cursor: pointer;
        -webkit-appearance: none;
        margin-top: -8px;
        position: relative;
        z-index: 2;
    }


    .slider-track {
        position: absolute;
        background: #6c603c;
        border-radius: 2px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1;
    }


    @media (max-width: 767px) {
        .navbar {
            padding: 10px 15px 25px 15px;
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
        <!-- <div class="search-btn-container">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search properties..." id="searchInput" />
            </div>
        </div> -->
    </nav>

    <section class="home-banner">
        <div class="hero-image">
            <div class="banner-filter-form">
                <div class="form-wrapper">
                    <form>
                        <div class="grid">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" placeholder="Enter Location">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <select>
                                    <option>Select City</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Property Type</label>
                                <select>
                                    <option>Select Property</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select>
                                    <option>Select Category</option>
                                </select>
                            </div>
                        </div>


                        <div class="home-des-border" style="margin-top:15px; margin-left:5px;">
                            <div class="price-range-container">
                                <div class="price-label">Price:</div>
                                <div class="price-values">
                                    <span id="min-value">0</span> - <span id="max-value">10000000</span>
                                </div>

                                <div class="slider-container">
                                    <div class="slider-track" id="slider-track"></div>
                                    <input type="range" id="min-range" min="0" max="10000000" value="2000000"
                                        step="1000">
                                    <input type="range" id="max-range" min="0" max="10000000" value="8000000"
                                        step="1000">
                                </div>
                            </div>
                        </div>


                        <button type="button" class="form-search-btn" id="searchBtn">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
    // Navbar active button toggle
    const tabButtons = document.querySelectorAll('.nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // Close button
    document.querySelector('.close-btn').addEventListener('click', () => {
        window.history.back();
    });

    // Price slider logic
    const minRange = document.getElementById("min-range");
    const maxRange = document.getElementById("max-range");
    const minValue = document.getElementById("min-value");
    const maxValue = document.getElementById("max-value");
    const sliderTrack = document.getElementById("slider-track");
    const sliderMaxValue = parseInt(minRange.max);
    const minGap = 100000;

    function updateSlider(e) {
        let minVal = parseInt(minRange.value);
        let maxVal = parseInt(maxRange.value);

        if (maxVal - minVal <= minGap) {
            if (e.target.id === "min-range") {
                minRange.value = maxVal - minGap;
            } else {
                maxRange.value = minVal + minGap;
            }
        }

        minValue.textContent = Number(minRange.value).toLocaleString();
        maxValue.textContent = Number(maxRange.value).toLocaleString();

        const percent1 = (minRange.value / sliderMaxValue) * 100;
        const percent2 = (maxRange.value / sliderMaxValue) * 100;

        sliderTrack.style.left = percent1 + "%";
        sliderTrack.style.width = (percent2 - percent1) + "%";
    }

    minRange.addEventListener("input", updateSlider);
    maxRange.addEventListener("input", updateSlider);
    updateSlider();
    </script>
</body>

</html>