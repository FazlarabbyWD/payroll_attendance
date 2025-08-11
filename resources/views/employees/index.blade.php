@extends('app')
@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">

    @include('employees._stats', ['stats' => $stats])

    @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @include('components.alert', ['type' => 'danger', 'message' => session('error')])

 
    <div class="card">
    @include('employees._filters', ['departments' => $departments, 'employmentTypes' => $employmentTypes, 'bloodGroups' => $bloodGroups])

    @include('employees._table', ['employees' => $employees])
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Get references to the input and select elements
        const employeeIdSearchInput = document.getElementById('employeeIdSearchInput');
        const nameSearchInput = document.getElementById('nameSearchInput');
        const phoneSearchInput = document.getElementById('phoneSearchInput');
        const emailSearchInput = document.getElementById('emailSearchInput');
        const statusFilter = document.getElementById('statusFilter');

        //Get references to the department, employment type, and blood group select elements
        const departmentFilter = document.getElementById('EmployeeDept');
        const employmentTypeFilter = document.getElementById('EMploymentType');
        const bloodGroupFilter = document.getElementById('EmployeeBlood');


        // Get all the table rows
        const tableRows = Array.from(document.querySelectorAll('table tbody tr'));

        // Function to filter the table rows
        function filterTable() {
            const employeeIdSearchTerm = employeeIdSearchInput.value.toLowerCase();
            const nameSearchTerm = nameSearchInput.value.toLowerCase();
            const phoneSearchTerm = phoneSearchInput.value.toLowerCase();
            const emailSearchTerm = emailSearchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            // Get selected values from the new filters
            const departmentValue = departmentFilter.value;
            const employmentTypeValue = employmentTypeFilter.value;
            const bloodGroupValue = bloodGroupFilter.value;



            tableRows.forEach(row => {
                // Get the data from the row. Adjust indices based on actual table structure!
                const employeeId = row.cells[1].textContent.toLowerCase(); // EMP ID
                const name = row.cells[2].querySelector('.fw-medium').textContent.toLowerCase(); // Name
                const phone = row.cells[6].textContent.toLowerCase(); // Phone
                const email = row.cells[2].querySelector('a').textContent.toLowerCase(); // Email, get from <a> tag
                const statusText = row.cells[7].textContent.toLowerCase(); // Status text (Active/Inactive)
                const departmentName = row.cells[3].textContent; // Department
                const employmentType = row.cells[5].textContent; // Employment
                const bloodGroupName = row.cells[6].textContent; // Blood Group


                // Determine status value (1 for Active, 0 for Inactive)
                let status = (statusText === 'active') ? '1' : '0';

                // Check if the row matches the search term and status filter
                const isEmployeeIdMatch = employeeId.includes(employeeIdSearchTerm);
                const isNameMatch = name.includes(nameSearchTerm);
                const isPhoneMatch = phone.includes(phoneSearchTerm);
                const isEmailMatch = emailSearchTerm.includes(emailSearchTerm);
                const isStatusMatch = statusValue === "" || status === statusValue;
                const isDepartmentMatch = departmentValue === "" || row.cells[3].textContent === (departmentFilter.options[departmentFilter.selectedIndex].text); // Compare by text

                const isEmploymentMatch = employmentTypeValue === "" || row.cells[5].textContent === (employmentTypeFilter.options[employmentTypeFilter.selectedIndex].text); // Compare by text

                const isBloodGroupMatch = bloodGroupValue === "" || row.cells[7].textContent === (bloodGroupFilter.options[bloodGroupFilter.selectedIndex].text); // Compare by text

                // Show or hide the row based on whether it matches the search term and status filter
                if (isEmployeeIdMatch && isNameMatch && isPhoneMatch && isEmailMatch && isStatusMatch &&
                    isDepartmentMatch && isEmploymentMatch && isBloodGroupMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Function to reset the filter
        function resetFilter() {
            employeeIdSearchInput.value = "";
            nameSearchInput.value = "";
            phoneSearchInput.value = "";
            emailSearchInput.value = "";
            statusFilter.value = "";

            departmentFilter.value = ""; // Reset department filter
            employmentTypeFilter.value = ""; // Reset employment type filter
            bloodGroupFilter.value = ""; // Reset blood group filter

            filterTable();
        }


        // Attach event listeners to the input and select elements
        employeeIdSearchInput.addEventListener('input', filterTable);
        nameSearchInput.addEventListener('input', filterTable);
        phoneSearchInput.addEventListener('input', filterTable);
        emailSearchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);

        // Attach event listeners to the new select elements
        departmentFilter.addEventListener('change', filterTable);
        employmentTypeFilter.addEventListener('change', filterTable);
        bloodGroupFilter.addEventListener('change', filterTable);



        // Get reference to the reset button and attach event listener
        const resetButton = document.getElementById('resetButton');
        resetButton.addEventListener('click', resetFilter);
    });
</script>
@endpush
