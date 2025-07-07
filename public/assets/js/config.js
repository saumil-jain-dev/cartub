(function () {
  var primary = localStorage.getItem("primary") || "#29C5F6";
  var secondary = localStorage.getItem("secondary") || "#838383";
  var success = localStorage.getItem("success") || "#65c15c";

  window.CubaAdminConfig = {
    // Theme Primary Color
    primary: primary,
    // theme secondary color
    secondary: secondary,
    // theme success color
    success: success,
  };
})();
