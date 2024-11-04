<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Departments') }}
            </h2>
            <button class="px-3 py-1 bg-red-700 rounded text-white"
                onclick="document.getElementById('createDepartmentModal').classList.remove('hidden')">
                Create Department
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="departmentsTable" class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($departments as $department)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $department->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $department->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $department->status ? 'Active' : 'Inactive' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="openUpdateModal({{ $department->id }}, '{{ $department->name }}', {{ $department->status }})"
                                            class="text-blue-600 hover:text-blue-900">Update</button>
                                        <form action="{{ route('delete_department', $department->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
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

    <!-- Create Department Modal -->
    <div id="createDepartmentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Create Department</h2>
            <form action="{{ route('add_department') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Department Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2"
                        onclick="document.getElementById('createDepartmentModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Create</button>
                </div>
            </form>
        </div>
    </div>

    =
    <div id="updateDepartmentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white  dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Update Department</h2>
            <form id="updateDepartmentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" class="text-black" name="id" id="updateDepartmentId">
                <div class="mt-4">
                    <x-input-label for="updateName" :value="__('Department Name')" />
                    <x-text-input id="updateName" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="updateStatus" :value="__('Status')" />
                    <select name="status" id="updateStatus"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300  rounded-md shadow-sm' w-full"
                        required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2"
                        onclick="document.getElementById('updateDepartmentModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Update</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openUpdateModal(id, name, status) {
            document.getElementById('updateDepartmentModal').classList.remove('hidden');
            document.getElementById('updateName').value = name;
            document.getElementById('updateDepartmentId').value = id;
            document.getElementById('updateStatus').value = status;
            document.getElementById('updateDepartmentForm').action = `/departments/update/${id}`;
        }
    </script>
</x-app-layout>
