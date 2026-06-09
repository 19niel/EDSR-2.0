document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    events: function (fetchInfo, successCallback, failureCallback) {
      $.ajax({
        url: "../php/dashboardDate.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
          let events = [];
          Object.keys(response).forEach((date) => {
            response[date].forEach((event) => {
              events.push({
                title: event.title,
                start: date,
                extendedProps: {
                  status: event.status,
                },
              });
            });
          });
          successCallback(events);
        },
        error: function () {
          failureCallback();
        },
      });
    },
    eventMouseEnter: function (info) {
      $(info.el).tooltip("dispose"); // Remove any existing tooltip

      $(info.el)
        .tooltip({
          title:
            "<strong>Account Name:</strong> " +
            info.event.title +
            "<br><strong>Status:</strong> " +
            info.event.extendedProps.status,
          placement: "top",
          trigger: "manual",
          container: "body",
          html: true, // Enable HTML rendering
        })
        .tooltip("show");
    },
    eventMouseLeave: function (info) {
      $(info.el).tooltip("dispose");
    },
  });

  calendar.render();
});
