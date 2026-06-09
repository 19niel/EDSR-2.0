function editUser(id) {
  // Log the ID to the console (you can remove this line in production)
  console.log("Editing user with ID: " + id);

  // Assuming you have jQuery loaded
  $.ajax({
    type: "GET",
    url: "../php/editUser.php",
    data: { id: id },
    dataType: "json",
    success: function (data) {
      // Access userId from the returned data
      var userId = data.id;

      // Set values in the modal
      $("#editId").val(userId);
      $("#editName").val(data.name);
      $("#editUsername").val(data.user_id);
      $("#editDepartment").val(data.dept);
      $("#editCategory").val(data.category);
      $("#editPasswordChange").val(data.pass_change);
      $("#editBranch").val(data.branch);
      $("#editRole").val(data.authority);
      $("#editSubDepartment").val(data.handled);
      $("#editUserModal").modal("show");
    },
    error: function (error) {
      console.error("Error fetching user data: ", error);
    },
  });
}
