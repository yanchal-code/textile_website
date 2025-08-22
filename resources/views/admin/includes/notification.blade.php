{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<style>
    .toast {
        opacity: 0.9 !important;
        box-shadow: none !important;
    }
</style>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "rtl": false
    };

    function showNotification(message, type, messageType = 'text') {
        if (messageType === 'html') {
            toastr.options.escapeHtml = false;
        } else {
            toastr.options.escapeHtml = true;
        }

        if (type === 'success') {
            toastr.success(message);
        } else if (type === 'error') {
            toastr.error(message);
        } else if (type === 'info') {
            toastr.info(message);
        } else if (type === 'warning') {
            toastr.warning(message);
        } else {
            toastr.error(message);
        }
    };
</script>

@if (Session::has('error'))
    <script>
        showNotification("{{ Session::get('error') }}", "error");
    </script>
@endif

@if (Session::has('success'))
    <script>
        showNotification("{{ Session::get('success') }}", "success");
    </script>
@endif
@if (Session::has('warning'))
    <script>
        showNotification("{{ Session::get('warning') }}", "warning");
    </script>
@endif --}}



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script>
    function showNotification(message, type = 'info', messageType = 'text') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: true,
            timer: 4000,
            timerProgressBar: true,
            background: '#fff',
            color: '#333',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        let icon = 'info';
        if (type === 'success') icon = 'success';
        else if (type == 'error' || type == 'danger') icon = 'error';
        else if (type == 'warning') icon = 'warning';
        else if (type == 'info') icon = 'info';

        Toast.fire({
            icon: icon,
            title: messageType === 'html' ? undefined : message,
            html: messageType === 'html' ? message : undefined
        });
    }
</script>

{{-- <script>
    showNotification('Successfully saved!', 'success');
    showNotification('Something went wrong.', 'error');
    showNotification('This is some info.', 'info');
    showNotification('<b>Custom HTML</b> message', 'success', 'html');
</script> --}}


@if (Session::has('error'))
    <script>
        showNotification("{{ Session::get('error') }}", "error");
    </script>
@endif

@if (Session::has('success'))
    <script>
        showNotification("{{ Session::get('success') }}", "success");
    </script>
@endif
@if (Session::has('warning'))
    <script>
        showNotification("{{ Session::get('warning') }}", "warning");
    </script>
@endif
