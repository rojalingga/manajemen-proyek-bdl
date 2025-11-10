</div>
    </div>

    <script src="/template_admin/assets/static/js/components/dark.js"></script>
    <script src="/template_admin/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script src="/template_admin/assets/compiled/js/app.js"></script>

    <!-- Need: Apexcharts -->
    <script src="/template_admin/assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="/template_admin/assets/static/js/pages/dashboard.js"></script>

    <script src="/template_admin/assets/extensions/jquery/jquery.min.js"></script>
    <script src="/template_admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/template_admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/template_admin/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/template_admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/template_admin/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/template_admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/template_admin/plugins/jszip/jszip.min.js"></script>
    <script src="/template_admin/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/template_admin/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/template_admin/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/template_admin/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/template_admin/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="/template_admin/assets/static/js/pages/datatables.js"></script>

    <script src="/template_admin/assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="/template_admin/assets/extensions/rater-js/index.js?v=2"></script>
    <script src="/template_admin/assets/static/js/pages/rater-js.js"></script>
    <script src="/template_admin/assets/static/js/pages/form-element-select.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Toastr -->
    <script src="/template_admin/plugins/toastr/toastr.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="/template_admin/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDesktop = document.getElementById('toggle-dark');
        const toggleMobile = document.getElementById('toggle-dark-mobile');
        const navbar = document.getElementById('main-navbar');

        function updateTheme(isDark) {
            if (isDark) {
                document.body.classList.add('dark');
                document.body.classList.remove('light');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark');
                document.body.classList.add('light');
                localStorage.setItem('theme', 'light');
            }

            if (navbar) {
                navbar.classList.toggle('navbar-dark', isDark);
                navbar.classList.toggle('bg-dark', isDark);
                navbar.classList.toggle('navbar-light', !isDark);
                navbar.classList.toggle('bg-light', !isDark);
            }

            if (toggleDesktop) toggleDesktop.checked = isDark;
            if (toggleMobile) toggleMobile.checked = isDark;
        }

        const savedTheme = localStorage.getItem('theme') || 'light';
        updateTheme(savedTheme === 'dark');

        if (toggleDesktop) {
            toggleDesktop.addEventListener('change', function() {
                updateTheme(toggleDesktop.checked);
            });
        }

        if (toggleMobile) {
            toggleMobile.addEventListener('change', function() {
                updateTheme(toggleMobile.checked);
            });
        }
    });
</script>

<script>
    $(document).on('input', '.format-number', function() {
        let input = $(this).val().replace(/[^\d]/g, '');
        let formatted = input.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        $(this).val(formatted);
    });
</script>


</html>