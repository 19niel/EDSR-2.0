function showPassword() {
  console.log("tae");
  document.getElementById("password").type = "text";
  document.getElementById("editPassword").type = "text";
}

function hidePassword() {
  document.getElementById("password").type = "password";
  document.getElementById("editPassword").type = "password";
}
