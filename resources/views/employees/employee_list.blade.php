<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('create_employee') }}" class="px-3 py-1 bg-red-700 rounded text-white"
                onclick="document.getElementById('createDepartmentModal').classList.remove('hidden')">
                Create Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800  shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="departmentsTable" class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cnic</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $employee->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $employee->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $employee->user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $employee->department->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $employee->address }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $employee->cnic }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative">
                                            <!-- 3-Dots Button -->
                                            <button class="text-gray-600 hover:text-gray-900 focus:outline-none" onclick="toggleDropdown({{ $employee->id }})">
                                                &#x22EE; <!-- Vertical 3 dots icon -->
                                            </button>
                                            <!-- Dropdown Menu -->
                                            <div id="dropdown-{{ $employee->id }}" class="hidden absolute right-0 mt-2 w-48 bg-gray-700 border border-gray-500 rounded-md shadow-lg z-10">
                                                <a href="{{ route('update_employee', $employee->id) }}" class="block px-4 py-2 text-sm text-blue-600">Update</a>
                                                <form action="{{ route('delete_employee', $employee->id) }}" method="POST" class="block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600">Delete</button>
                                                </form>
                                                <a href="{{ route('view_attendance_history', $employee->user_id) }}" class="block px-4 py-2 text-sm text-white">View Attendance</a>
                                                <a href="{{ route('documents_history', $employee->user_id) }}" class="block px-4 py-2 text-sm text-green-700">View Documents</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#departmentsTable').DataTable({
                responsive: true,
            });
        });

        function toggleDropdown(id) {
            document.getElementById(`dropdown-${id}`).classList.toggle('hidden');
        }

        // Close dropdown if clicked outside
        document.addEventListener('click', function(e) {
            const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target) && !e.target.closest('button')) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
