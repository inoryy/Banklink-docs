$(document).ready(function() {
   $('.start-transaction').click(function (e) {
      e.preventDefault();

      $('#' + $(this).data('bank')).submit();
   });
});