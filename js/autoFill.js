function searchAccounts(accountName) {
  // Validate input
  if (!accountName) {
    console.warn("searchAccounts called with empty accountName.");
    return;
  }

  let url = `../php/fillAccount.php?accountName=${encodeURIComponent(
    accountName
  )}`;

  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw new Error(
          `Server error: ${response.statusText} (${response.status})`
        );
      }
      return response.json();
    })
    .then((accountData) => {
      // Pass the account data to another function
      handleAccountData(accountData);
    })
    .catch((error) => console.error("Error in searchAccounts:", error));
}

function fetchAccountExecutives() {
  let url = `../php/fillAccount.php`;

  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw new Error(
          `Server error: ${response.statusText} (${response.status})`
        );
      }
      return response.json();
    })
    .then((data) => {
      if (!Array.isArray(data)) {
        throw new Error("Invalid data format received from server.");
      }
      populateAccountExecutiveDropdown(data);
    })
    .catch((error) => console.error("Error in fetchAccountExecutives:", error));
}

function populateAccountExecutiveDropdown(accounts) {
  let accountExecSelect = document.getElementById("accountExecutiveSearch");
  accountExecSelect.innerHTML =
    '<option value="">Select Account Executive</option>';

  if (!Array.isArray(accounts) || accounts.length === 0) {
    console.warn("No account executives received or data is not an array.");
    return;
  }

  accounts.forEach((account) => {
    if (!account.accExec) {
      console.warn("Account entry missing 'accExec' field:", account);
      return;
    }
    let option = document.createElement("option");
    option.value = account.accExec;
    option.textContent = account.accExec;
    accountExecSelect.appendChild(option);
  });
}

// Populate Account Executive dropdown on page load
document.addEventListener("DOMContentLoaded", function () {
  fetchAccountExecutives();

  document.body.addEventListener("click", function (event) {
    if (!event.target.closest(".dropdown-container")) {
      document.getElementById("accountList").style.display = "none";
    }
  });
});
