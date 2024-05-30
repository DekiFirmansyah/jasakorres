$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $(document).ready(function() {
    $('#notificationDropdown').on('click', function() {
        // Mark notifications as read when dropdown is opened
        $.ajax({
            url: '/markAsRead',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                console.log('All notifications marked as read.');
            }
        });
    });
  });

function confirmDelete(letterId) {
  Swal.fire({
      title: 'Apakah Anda yakin?',
      text: 'Data ini akan dihapus secara permanen!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          document.getElementById('delete-form-' + letterId).submit();
      }
  });
}

document.addEventListener('DOMContentLoaded', (event) => {
  // Select the alert element
  var alert = document.getElementById('status-alert');
  if (alert) {
      // Set timeout to hide the alert after 5 seconds (5000 milliseconds)
      setTimeout(() => {
          alert.classList.remove('show');
          alert.classList.add('hide');
      }, 5000);
  }
});

$(document).ready(function() {
  $('.select2bs4').select2();
});