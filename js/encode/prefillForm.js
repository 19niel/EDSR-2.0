document.addEventListener("DOMContentLoaded", function () {
  // Extract the encoded ID from the URL query string
  const urlParams = new URLSearchParams(window.location.search);
  const encodedId = urlParams.get("id"); // The 'id' parameter in the URL

  if (encodedId) {
    // Fetch data based on the encoded ID
    document.getElementById("encodeId").value = encodedId;
    fetchData(encodedId);
  } else {
    console.error("Encoded ID not found in URL");
  }
});

function handleAccountData(accountData) {
  let accountExec = userName;
  let accountName = accountData[0].accName || "";

  console.log("Account Name:", accountName);
  console.log("Account Exec:", accountExec);

  const encodedAccountName = encodeURIComponent(accountName);
  const encodedAccountExec = encodeURIComponent(accountExec);

  const url = `/e-dsr/php/fetchAutofillEncode.php?accountName=${encodedAccountName}&accountExec=${encodedAccountExec}`;

  fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data && data.success) {
        prefillForm(data.data); // Prefill the form with the fetched data
      } else {
        console.error("Failed to fetch data", data);
      }
    })
    .catch((error) => console.error("Error fetching data:", error));
}

// Function to fetch data based on encoded ID
function fetchData(encodedId) {
  fetch(`/e-dsr/php/fetchDataEditEncode.php?id=${encodedId}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data && data.success) {
        prefillForm(data.data); // Prefill the form with the fetched data
      } else {
        console.error("Failed to fetch data");
      }
    })
    .catch((error) => console.error("Error fetching data:", error));
}

// Function to prefill the form with the fetched data
function prefillForm(data) {
  console.log("Prefilling data:", data); // Log the data for debugging
  
  document.getElementById("accountName").value = data.accName || "";
  document.getElementById("arsExpiryDate").value = data.arsExpiryDate || "";
  document.getElementById("address").value = data.address || "";
  
  // FIXED: Handle Contact Information Array Inputs safely using Query Selectors
  const cpInput = document.querySelector('input[name="contactPerson[]"]');
  if (cpInput) cpInput.value = data.contactPerson || "";

  const desInput = document.querySelector('input[name="designation[]"]');
  if (desInput) desInput.value = data.designation || "";

  const numInput = document.querySelector('input[name="contactNumber[]"]');
  if (numInput) numInput.value = data.contactNumber || "";

  const emailInput = document.querySelector('input[name="emailAddress[]"]');
  if (emailInput) emailInput.value = data.email || "";

  // Decision Maker Fields Lookup
  document.getElementById("dmEmail").value = data.decisionMakerEmail || "";
  document.getElementById("decisionMaker").value = data.decisionMaker || "";
  document.getElementById("dmDesignation").value = data.dmDesignation || "";
  
  // Project & Pricing Data Fields
  document.getElementById("proposedPrice").value = data.proposedPrice || "";
  document.getElementById("whatTranspired").value = data.whatTranspired || "";
  document.getElementById("followUpAction").value = data.actionFollow || "";

  // Set dropdown values
  $("#sbu").val(data.sbu || "");
  $("#endUserType").val(data.endUser || "");
  $("#paymentTerms").val(data.paymentTerms || "");
  $("#contractType").val(data.contactType || "");
  $("#callNature").val(data.callNature || "");
  $("#reminderDate").val(data.reminderDate || "");
  $("#deliveryDate").val(data.deliveryDate || "");
  $("#contractEnd").val(data.contractEnd || "");

  // Populate Region and trigger Province, City, and Barangay loading
  $("#region").val(data.region || "").trigger("change");

  setTimeout(() => {
    $("#province").val(data.province || "").trigger("change");
    setTimeout(() => {
      $("#city").val(data.city || "").trigger("change");
      setTimeout(() => {
        $("#barangay").val(data.barangay || "");
      }, 100);
    }, 100);
  }, 100);

  // Populate Segment and trigger Industry Subcategory loading
  $("#segment").val(data.segment || "").trigger("change");

  setTimeout(() => {
    $("#industrySubcategory").val(data.industrySubcategory || "");
  }, 100);

  // Populate Account Source and trigger Account Source Category loading
  $("#accountSource").val(data.accSource || "").trigger("change");

  setTimeout(() => {
    $("#accountSourceCategory").val(data.accountSourceCategory || "");
  }, 100);

  // Populate Account Category and trigger visibility check
  $("#accountCategory").val(data.accCat || "").trigger("change");

  // Handle Existing System & End of Competitor Date visibility
  if (data.accCat === "NEW") {
    document.getElementById("existingSystemContainer").style.display = "block";
    document.getElementById("contractEndCompetitorContainer").style.display = "block";

    document.getElementById("existingSystem").value = data.existingSystem || "";
    document.getElementById("contractEndCompetitor").value = data.endOfContractCompetitor || "";

    document.getElementById("existingSystem").required = true;
    document.getElementById("contractEndCompetitor").required = true;
  } else {
    document.getElementById("existingSystemContainer").style.display = "none";
    document.getElementById("contractEndCompetitorContainer").style.display = "none";

    document.getElementById("existingSystem").required = false;
    document.getElementById("contractEndCompetitor").required = false;
  }

  // Populate Account Status and trigger Reason Subcategory loading
  $("#accountStatus").val(data.accStatus || "").trigger("change");

  setTimeout(() => {
    $("#reasonSubcategory").val(data.reasonSubcategory || "");
  }, 100);

  if (data.accStatus === "230") {
    document.getElementById("deliveryDateContainer").style.display = "block";
    document.getElementById("contractEndContainer").style.display = "block";

    document.getElementById("deliveryDate").value = data.deliveryDate || "";
    document.getElementById("contractEnd").value = data.endOfContract || "";

    document.getElementById("deliveryDate").required = true;
    document.getElementById("contractEnd").required = true;
  } else {
    document.getElementById("deliveryDateContainer").style.display = "none";
    document.getElementById("contractEndContainer").style.display = "none";

    document.getElementById("deliveryDate").required = false;
    document.getElementById("contractEnd").required = false;
  }

  // Prefill product entries
  if (data.products && Array.isArray(data.products)) {
    const productEntriesContainer = document.getElementById("productEntries");
    const addButton = document.getElementById("addProductEntry");

    let firstEntry = productEntriesContainer.querySelector(".product-entry");

    data.products.forEach((product, index) => {
      let entry;

      if (index === 0) {
        entry = firstEntry;
      } else {
        addButton.click();
        const allEntries = productEntriesContainer.querySelectorAll(".product-entry");
        entry = allEntries[allEntries.length - 1];
      }

      // Fill in initial product fields
      const productTypeSelect = entry.querySelector('select[name="productType[]"]');
      const deviceConditionSelect = entry.querySelector('select[name="deviceCondition[]"]');
      const quantityInput = entry.querySelector('input[name="quantity[]"]');
      const subcatSelect = entry.querySelector('select[name="productTypeSubcategory[]"]');

      productTypeSelect.value = product.productTypeID || "";
      deviceConditionSelect.value = product.deviceConditionID || "";
      quantityInput.value = product.quantity || "";

      // Trigger change to populate subcategories
      $(productTypeSelect).trigger("change");

      const targetSubcat = product.productSubcategoryID || "";

      // Retry logic until subcategory is present
      let attempts = 0;
      const maxAttempts = 30; 
      const retryInterval = setInterval(() => {
        const subcatOptions = Array.from(subcatSelect.options).map((opt) => opt.value);
        if (subcatOptions.includes(targetSubcat)) {
          subcatSelect.value = targetSubcat;
          $(subcatSelect).trigger("change");
          clearInterval(retryInterval);
        } else if (++attempts >= maxAttempts) {
          clearInterval(retryInterval);
          console.warn(`Subcategory ID ${targetSubcat} not found for productTypeID ${product.productTypeID}`);
        }
      }, 100);
    });
  }

  // FIXED: MASTER LOCK OVERRIDE BYPASS
  // If the admin master control button was unlocked BEFORE the data finishes pre-filling, 
  // ensure the fields are completely unlocked.
  setTimeout(() => {
    const adminState = document.getElementById('isAdminEdit') ? document.getElementById('isAdminEdit').value : "false";
    if (adminState === "true") {
      var allControls = document.querySelectorAll('#editEncodeForm input, #editEncodeForm select, #editEncodeForm textarea');
      allControls.forEach(function(el) {
        el.disabled = false;
        el.removeAttribute('disabled');
        el.classList.remove('disabled');
      });
      var actionBtns = document.querySelectorAll('#editEncodeForm button, .add-product-btn, .remove-product-btn');
      actionBtns.forEach(function(btn) {
        btn.disabled = false;
        btn.removeAttribute('disabled');
      });
    }
  }, 500); 
}