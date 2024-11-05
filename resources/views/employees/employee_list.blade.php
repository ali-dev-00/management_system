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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="departmentsTable" class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Department</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Address</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cnic</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
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
                                        <a href="{{ route('update_employee', $employee->id) }}"
                                            class="text-blue-600 hover:text-blue-900">Update</a>
                                        <form action="{{ route('delete_employee', $employee->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
