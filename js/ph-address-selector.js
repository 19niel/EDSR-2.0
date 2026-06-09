/**
 * __________________________________________________________________
 *
 * Phillipine Address Selector
 * __________________________________________________________________
 *
 * MIT License
 *
 * Copyright (c) 2020 Wilfred V. Pine
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package Phillipine Address Selector
 * @author Wilfred V. Pine <only.master.red@gmail.com>
 * @copyright Copyright 2020 (https://dev.confired.com)
 * @link https://github.com/redmalmon/philippine-address-selector
 * @license https://opensource.org/licenses/MIT MIT License
 */

var my_handlers = {
  // Fill province based on selected region
  fill_provinces: function () {
    var region_code = $(this).val();
    var region_text = $(this).find("option:selected").text();

    $("#region-text").val(region_text);
    $("#province-text, #city-text, #barangay-text").val("");

    let provinceDropdown = $("#province");
    let cityDropdown = $("#city");
    let barangayDropdown = $("#barangay");

    // Reset dropdowns
    provinceDropdown
      .empty()
      .append('<option selected="true" disabled>Choose State/Province</option>')
      .prop("selectedIndex", 0);
    cityDropdown
      .empty()
      .append(
        '<option selected="true" disabled>Choose city/municipality</option>'
      )
      .prop("selectedIndex", 0);
    barangayDropdown
      .empty()
      .append('<option selected="true" disabled>Choose barangay</option>')
      .prop("selectedIndex", 0);

    var url = "../ph-json/province.json";

    $.getJSON(url, function (data) {
      // If region 13 is selected, set province_code to 1339 automatically
      if (region_code === "13") {
        let selectedProvince = data.find((p) => p.province_code === "1339");
        if (selectedProvince) {
          provinceDropdown.append(
            $("<option></option>")
              .attr("value", "1339")
              .text(selectedProvince.province_name)
              .prop("selected", true)
          );
          $("#province-text").val(selectedProvince.province_name);

          // Trigger city population
          my_handlers.fill_cities.call(provinceDropdown);
        }
        return;
      }

      // Otherwise, filter and populate provinces normally
      let result = data.filter((value) => value.region_code == region_code);
      result.sort((a, b) => a.province_name.localeCompare(b.province_name));

      $.each(result, function (key, entry) {
        provinceDropdown.append(
          $("<option></option>")
            .attr("value", entry.province_code)
            .text(entry.province_name)
        );
      });
    });
  },

  // Fill cities based on selected province
  fill_cities: function () {
    var province_code = $(this).val();
    var province_text = $(this).find("option:selected").text();

    $("#province-text").val(province_text);
    $("#city-text, #barangay-text").val("");

    let cityDropdown = $("#city");
    let barangayDropdown = $("#barangay");

    cityDropdown
      .empty()
      .append(
        '<option selected="true" disabled>Choose city/municipality</option>'
      )
      .prop("selectedIndex", 0);
    barangayDropdown
      .empty()
      .append('<option selected="true" disabled>Choose barangay</option>')
      .prop("selectedIndex", 0);

    var url = "../ph-json/city.json";

    $.getJSON(url, function (data) {
      let result = data.filter((value) => value.province_code == province_code);
      result.sort((a, b) => a.city_name.localeCompare(b.city_name));

      $.each(result, function (key, entry) {
        cityDropdown.append(
          $("<option></option>")
            .attr("value", entry.city_code)
            .text(entry.city_name)
        );
      });
    });
  },

  // Fill barangays based on selected city
  fill_barangays: function () {
    var city_code = $(this).val();
    var city_text = $(this).find("option:selected").text();

    $("#city-text").val(city_text);
    $("#barangay-text").val("");

    let barangayDropdown = $("#barangay");
    barangayDropdown
      .empty()
      .append('<option selected="true" disabled>Choose barangay</option>')
      .prop("selectedIndex", 0);

    var url = "../ph-json/barangay.json";

    $.getJSON(url, function (data) {
      let result = data.filter((value) => value.city_code == city_code);
      result.sort((a, b) => a.brgy_name.localeCompare(b.brgy_name));

      $.each(result, function (key, entry) {
        barangayDropdown.append(
          $("<option></option>")
            .attr("value", entry.brgy_code)
            .text(entry.brgy_name)
        );
      });
    });
  },

  // Update barangay input text
  onchange_barangay: function () {
    var barangay_text = $(this).find("option:selected").text();
    $("#barangay-text").val(barangay_text);
  },
};

$(function () {
  // Attach event listeners
  $("#region").on("change", my_handlers.fill_provinces);
  $("#province").on("change", my_handlers.fill_cities);
  $("#city").on("change", my_handlers.fill_barangays);
  $("#barangay").on("change", my_handlers.onchange_barangay);

  // Load regions
  let dropdown = $("#region");
  dropdown
    .empty()
    .append('<option selected="true" disabled>Choose Region</option>')
    .prop("selectedIndex", 0);

  $.getJSON("../ph-json/region.json", function (data) {
    $.each(data, function (key, entry) {
      dropdown.append(
        $("<option></option>")
          .attr("value", entry.region_code)
          .text(entry.region_name)
      );
    });
  });
});
