// -----------------------------
// URL helpers (DRY + safe)
// -----------------------------
function getURL() {
  return new URL(window.location.href);
}
function pushURL(url) {
  window.history.pushState({ path: url.toString() }, "", url.toString());
}
function setParam(url, name, value) {
  url.searchParams.set(name, value);
}
function addParam(url, name, value) {
  url.searchParams.append(name, value);
}
function removeParam(url, name) {
  url.searchParams.delete(name);
}
function removeParams(url, names = []) {
  names.forEach((n) => url.searchParams.delete(n));
}

// Fetch wrapper
function pushAndFetch(url) {
  pushURL(url);
  getData(url.toString());
}

// -----------------------------
// Main update handlers
// -----------------------------
function updateURL(data)
{
  $(".request-loader").addClass("show");

  const [rawName, rawVal = ""] = String(data).split("=");
  const name = decodeURIComponent(rawName);
  const value = decodeURIComponent(rawVal);

  let url = getURL();

  if (name === "type") {
    reset(); // preserves original behavior
    getCategories(value);
  } else if (name === "category") {
    // keep type, reset others (as your original logic intended)
    reset();
    const current = getURL();
    const typeVal = current.searchParams.get("type");
    url = new URL(current.origin + current.pathname);
    if (typeVal) setParam(url, "type", typeVal);
  } else if (name === "country") {
    // remove dependent filters: state, city, listArea
    removeParams(url, ["state", "city", "listArea"]);
  } else if (name === "state") {
    requestArrayRmvfromUrl("city");
  } else if (name === "min" || name === "max") {
    requestArrayRmvfromUrl("price");
    $('#pricetype input:radio[value="all"]').prop("checked", true);
  } else if (name === "price") {
    requestArrayRmvfromUrl("min");
    requestArrayRmvfromUrl("max");
  } else if (name === "sort") {
    if (value === "high-to-low" || value === "low-to-high") {
      $('#pricetype input:radio[value="fixed"]').prop("checked", true);
      requestArrayRmvfromUrl("min");
      requestArrayRmvfromUrl("max");
      // Ensure price=fixed in URL
      const u = getURL();
      setParam(u, "price", "fixed");
      pushURL(u);
    }
  }

  // Apply/replace the incoming param
  if (name && rawVal !== undefined) {
    setParam(url, name, value);
  }

  pushAndFetch(url);
}

// Remove a single query param everywhere and push state
function requestArrayRmvfromUrl(requestName) {
  const url = getURL();
  removeParam(url, requestName);
  pushURL(url);
}

// Amenities toggler (multi-value, idempotent)
function updateAmenities(data, checkbox) {
  const url = getURL();
  const [rawName, rawVal = ""] = String(data).split("=");
  const name = decodeURIComponent(rawName);
  const value = decodeURIComponent(rawVal); 
  // Collect current values
  const existing = url.searchParams.getAll(name).filter(Boolean);

  if (checkbox.checked) {
    // add if missing
    if (!existing.includes(value)) {
      addParam(url, name, value);
    }
  } else {
    // remove only this value
    const next = existing.filter((v) => v !== value);
    removeParam(url, name);
    next.forEach((v) => addParam(url, name, v));
  }

  $(".request-loader").addClass("show");
  pushAndFetch(url);

  eventCapture();
}

// -----------------------------
// Data + dependent fetchers
// -----------------------------
function getData(currentURL, page) {
  $.ajax({
    type: "GET",
    url: currentURL,
    data: { page: page },
    success: function (data) {
      // Replace properties HTML
      $(".properties").html(data.propertyContents);

      // If you need the list:
      // const properties = data?.properties?.data || [];
      // mapInitialize(properties); // kept commented as in original
    },
    complete: function () {
      $(".request-loader").removeClass("show");
      $("html, body").animate({ scrollTop: 0 });
    },
  });
}

function getCategories(type) {
  $.ajax({
    type: "GET",
    url: baseURL + "/categories",
    data: { type: type },
    success: function (data) {
      const ul = $("#catogoryul");
      ul.empty();

      $("#categories .list-item a").removeClass("active");

      const urlParams = new URLSearchParams(window.location.search);
      const scategory = urlParams.get("category");

      if (scategory === "all") {
        $("#categories .list-item a").addClass("active");
      }

      data.categories.forEach((item) => {
        const slug = item.category_content.slug;
        const name = item.category_content.name;

        ul.append(
          `<li class="list-item">
            <a class="${slug === scategory ? "active" : ""}"
               onclick="updateURL('category=${encodeURIComponent(slug)}')">
              ${name}
            </a>
          </li>`
        );
      });
    },
    complete: function () {
      $(".request-loader").removeClass("show");
    },
  });
}

// -----------------------------
// Resets (keep original behavior)
// -----------------------------
function resetURL() {
  // 1) Capture current purpose BEFORE reset (from URL or form)
  const currentUrl = new URL(
    typeof getURL === "function" ? getURL() : window.location.href
  );
  const form = document.getElementById("searchForm");
  const purposeEl = document.getElementById("purpose");
  const purposeBeforeReset =
    currentUrl.searchParams.get("purpose") ||
    (purposeEl ? purposeEl.value : "");

  // 2) Uncheck all checkboxes inside the form (safer scope)
  if (form) {
    form
      .querySelectorAll('input[type="checkbox"]')
      .forEach((cb) => (cb.checked = false));
    form.reset();
  }

  // 3) Reset price UI if available
  if (typeof priceRest === "function") {
    priceRest();
  }

  // 4) Hide dependent selects
  $(".state, .city, .area").hide();

  // 5) Build a clean URL (origin + path only)
  const clean = new URL(currentUrl.origin + currentUrl.pathname);

  // 6) Preserve purpose only for these two values
  if (["business_for_sale", "franchiese"].includes(purposeBeforeReset)) {
    clean.searchParams.set("purpose", purposeBeforeReset);
    // Also re-apply to the form control (optional)
    if (purposeEl) purposeEl.value = purposeBeforeReset;
  }

  // 7) Push state + fetch results
  pushAndFetch(clean);
  eventCapture();
}


function reset() { 
  // Uncheck all checkboxes
  document
    .querySelectorAll('input[type="checkbox"]')
    .forEach((cb) => (cb.checked = false));

  // Reset form + price UI + sort
  document.getElementById("searchForm").reset();
  priceRest();
  $('select[name="sort"]').val($('select[name="sort"] option:first').val());

  // Reset URL without fetching (your original reset did not fetch here)
  const url = getURL();
  const clean = new URL(url.origin + url.pathname);
  pushURL(clean);  
}

function priceRest() {
  const omin = document.getElementById("o_min")?.value;
  const omax = document.getElementById("o_max")?.value;
  const slider = document.querySelector("[data-range-slider='priceSlider']");
  if (slider?.noUiSlider && omin != null && omax != null) {
    slider.noUiSlider.set([omin, omax]);
  }
}

// -----------------------------
// Dependent selects (cities/areas)
// -----------------------------
function getCities(e) {
  $(".request-loader").addClass("show");
  const addedCity = "city_id";
  const id = $(e).find(":selected").data("id");
  $.ajax({
    type: "GET",
    url: baseURL + "/cities",
    data: { state_id: id },
    success: function (data) {
      if (data.cities.length > 0) {
        $(".city").show();
        const $target = $("." + addedCity);
        $target
          .empty()
          .append($('<option data-id="0"></option>').val("all").text("All"));
        data.cities.forEach((v) => {
          $target.append(
            $("<option></option>")
              .attr("data-id", v.id)
              .val(v.city_content.name)
              .text(v.city_content.name)
          );
        });
      } else {
        $("." + addedCity).empty();
        $(".city").hide();
      }
    },
    complete: function () {
      $(".request-loader").removeClass("show");
    },
  });
}

function getAreas(e) {
  $(".request-loader").addClass("show");
  const addedArea = "area_id";
  const id = $(e).find(":selected").data("id");
  $.ajax({
    type: "GET",
    url: baseURL + "/areas",
    data: { city_id: id },
    success: function (data) {
      if (data.areas.length > 0) {
        $(".area").show();
        const $target = $("." + addedArea);
        $target
          .empty()
          .append($('<option data-id="0"></option>').val("all").text("All"));
        data.areas.forEach((v) => {
          $target.append(
            $("<option></option>")
              .attr("data-id", v.id)
              .val(v.name)
              .text(v.name)
          );
        });
      } else {
        $("." + addedArea).empty();
        $(".area").hide();
      }
    },
    complete: function () {
      $(".request-loader").removeClass("show");
    },
  });
}

// -----------------------------
// DOM Ready
// -----------------------------
$(document).ready(function () {
  "use strict";

  // Category active state
  $("#categories").on("click", "li a", function () {
    $("#categories li a").removeClass("active");
    $(this).addClass("active");
  });

  // AJAX pagination (preserves current filters)
  $("body").on("click", ".customPaginagte a", function (event) {
    event.preventDefault();
    const page = ($(this).attr("href") || "").split("page=")[1];
    if (!page) return;
    const url = getURL();
    // keep all current params, set page
    setParam(url, "page", page);
    pushAndFetch(url);
  });

  // Prevent back navigation (as in your original)
  history.pushState(null, document.title, location.href);
  window.addEventListener("popstate", function () {
    history.pushState(null, document.title, location.href);
  });
});
