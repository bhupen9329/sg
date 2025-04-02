<script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-button')) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Once deleted, you will not be able to recover this record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('approve-button')) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Once approved, this action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        $("#select_loader").css("display", "none");
        $(".loader_container").css("display", "none");

        // Function to show loader
        function showLoader() {
            $(".loader_container").css("display", "block");
            $("#select_loader").css("display", "block");
            setTimeout(function() {
                $(".loader_container").css("display", "none");
                $("#select_loader").css("display", "none");
            }, 2000);
        }

        // Attach click event listener to the button
        $(".btn.btn-primary[type='submit']").click(function() {
            showLoader(); // loader when button is clicked
        });
    });
    $(document).ready(function() {
        $("#select_loader").css("display", "none");
        $(".loader_container").css("display", "none");

        // Function to show loader
        function showLoader() {
            $(".loader_container").css("display", "block");
            $("#select_loader").css("display", "block");
            setTimeout(function() {
                $(".loader_container").css("display", "none");
                $("#select_loader").css("display", "none");
            }, 1000);
        }

        // Attach click event listener to the button
        $(".item-select-").change(function() {
            showLoader(); // loader when button is clicked
        });
    });
    $(document).ready(function() {
        $("#select_loader").css("display", "none");
        $(".loader_container").css("display", "none");

        // Function to show loader
        function showLoader() {
            $(".loader_container").css("display", "block");
            $("#select_loader").css("display", "block");
            setTimeout(function() {
                $(".loader_container").css("display", "none");
                $("#select_loader").css("display", "none");
            }, 1000);
        }

        // Attach click event listener to the button
        $(document).on('change', '.select_item_category', function() {
            showLoader(); // loader when select element value is changed
        });
    });
</script>

{{-- <script>
    
    document.addEventListener('DOMContentLoaded', function () {
    const raisedDateInput = document.getElementById('raised_date_input');

    if (raisedDateInput) {
        raisedDateInput.addEventListener('blur', function () {
            const inputDate = new Date(this.value);
            const startDate = new Date("{{ date('Y-m-d', strtotime('01-04-' . date('Y'))) }}");
            const endDate = new Date("{{ date('Y-m-d', strtotime('31-03-' . (date('Y') + 1))) }}");

            if (inputDate < startDate || inputDate > endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: `Please select a date between {{ date('d-m-Y', strtotime('01-04-' . date('Y'))) }} and {{ date('d-m-Y', strtotime('31-03-' . (date('Y') + 1))) }}.`,
                    confirmButtonText: 'OK'
                });
                this.value = "{{ date('Y-m-d') }}"; // Reset to default
            }
        });
    }
});

</script> --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    const raisedDateInput = document.getElementById('raised_date_input');

    if (raisedDateInput) {
        raisedDateInput.addEventListener('blur', function () {
            const inputDate = new Date(this.value);

            // Determine the current financial year dynamically
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1; // Months are 0-based

            let startDate, endDate, financialYear;

            if (currentMonth >= 4) { 
                // If it's April or later, current FY is (currentYear)-(currentYear+1)
                financialYear = `${currentYear}-${currentYear + 1}`;
                startDate = new Date(currentYear - 1, 3, 1); // 1st April previous year
                endDate = new Date(currentYear + 1, 2, 31, 23, 59, 59); // 31st March next year
            } else { 
                // If it's Jan to March, current FY is (currentYear-1)-(currentYear)
                financialYear = `${currentYear - 1}-${currentYear}`;
                startDate = new Date(currentYear - 2, 3, 1); // 1st April two years ago
                endDate = new Date(currentYear, 2, 31, 23, 59, 59); // 31st March this year
            }

            if (inputDate < startDate || inputDate > endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: `Please select a date within the financial year (${startDate.toLocaleDateString()} to ${endDate.toLocaleDateString()}).`,
                    confirmButtonText: 'OK'
                });
                this.value = ""; // Clear the input value
            }
        });
    }
});



</script>



<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>




{{-- csv --}}



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
<!-- DataTables Buttons JS -->
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js">
</script>





<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>

{{-- select2 --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
{{-- sweet alert --}}
<script src="{{ asset('sweet_alert/sweetalert.js') }}"></script>

<script src="{{ asset('select2/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select_seller_name').select2({
            dropdownParent: "#PurchaseinwardModal"
        });
        $('.select_po_number').select2({
            dropdownParent: "#PurchaseinwardModal"
        });
        $('.js-example-basic-single').select2({
            dropdownParent: "#exampleModal"
        });
        $('.Supplier-Company-select').select2({
            dropdownParent: "#company_modal2"
        });
        $('.Buyer-Company-select').select2({
            dropdownParent: "#company_modal2"
        });
        $('#selectcompany_id').select2({
            dropdownParent: "#select_company_modal_for_outward"
        });
        $('#selectso_id').select2({
            dropdownParent: "#select_company_modal_for_outward"
        });
        $('#selectsupplier_id').select2({
            dropdownParent: "#select_company_modal_for_outward"
        });


        $('.select2-container').css('width', '100%');


        $('.js-example-theme-multipl').select2({
            theme: "classic" // You can change the theme as needed
        });
    });


    // $(document).ready(function() {
    //     $('.js-example-basic-single').select2({});
    //     $('.select2-container').css('width', '100%');
    // });
</script>

<script>
    $(document).ready(function() {
        var isEdited = false;

        @can('Dashboard-set-notes')
            var isEditable = true;
        @else
            var isEditable = false;
        @endcan

        $('#summernote').summernote({
            placeholder: 'Enter your text here',
            tabsize: 2,
            height: 340,
            width: 650,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    if (isEditable) {
                        isEdited = true; // Set the flag to true when content is edited
                    }
                }
            },
            disabled: !isEditable // Set the editor to read-only if the user does not have permission

        });

        $(document).on('click', function(event) {
            if (isEditable) {
                var $target = $(event.target);

                // Check if the click is outside the summernote editor and its toolbar
                if (isEdited && !$target.closest('.note-editor, .note-toolbar').length) {
                    var content = $('#summernote').summernote('code');
                    $('#note_content').val(content);
                    $('#noteForm').submit(); // Submit the form programmatically
                    isEdited = false; // Reset the flag after submission
                }
            }
        });
    });
</script>


<script>
    setTimeout(function() {
        var element = document.querySelector('.tt');
        if (element) {
            element.style.display = 'none';
        }
    }, 5100);
</script>


@livewireScripts
</body>

</html>
