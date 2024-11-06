<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (Auth::user()->role === 'admin')
                Assign Projects to Project Managers
                @else
                 Your Projects
                @endif
            </h2>
            @if (Auth::user()->role === 'admin')
                <button class="px-3 py-1 bg-red-700 rounded text-white"
                    onclick="document.getElementById('createDepartmentModal').classList.remove('hidden')">
                    Create Project
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="departmentsTable" class="w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                      Project Manager</th>
                                @endif
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project Name</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Start Date</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    End Date</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($projects as $project)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->id }}</td>
                                    @if (Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->user->name }}</td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('show_project_tasks', $project->id) }}" class="text-blue-500 hover:underline">{{ $project->name }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->start_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->end_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->status }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            onclick="openUpdateModal({{ $project->id }}, '{{ $project->name }}', '{{ $project->status }}')"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fa-solid fa-pen-to-square"></i> <!-- Update icon -->
                                        </button>

                                        @if (Auth::user()->role === 'admin')
                                            <form action="{{ route('delete_project', $project->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ms-4">
                                                    <i class="fa-solid fa-trash"></i> <!-- Delete icon -->
                                                </button>
                                            </form>
                                        @endif
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
        class="flex fixed inset-0 bg-black bg-opacity-50  items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Create Project</h2>
            <form action="{{ route('add_project') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Project Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="start_date" :value="__('Start Date')" />
                    <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                        :value="old('start_date')" required />
                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="end_date" :value="__('End Date')" />
                    <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date"
                        :value="old('end_date')" required />
                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description')" required />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="manager_id" :value="__('Manager')" />
                    <select name="manager_id" id="manager_id"
                        class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full"
                        required>
                        <option value="" selected disabled>Select Manager</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="status" :value="__('Status')" />
                    <select name="status" id="status"
                        class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full"
                        required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="completed">Completed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="not_started">Not Started</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2"
                        onclick="document.getElementById('createDepartmentModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Create</button>
                </div>
            </form>
        </div>
    </div>


    <div id="updateDepartmentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Update Project Status</h2>
            <form id="updateDepartmentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" class="text-black" name="id" id="updateDepartmentId">
                <div class="mt-4">
                    <x-input-label for="status" :value="__('Status')" />
                    <select name="status" id="update_status"
                        class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full"
                        required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="completed">Completed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="not_started">Not Started</option>
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

            function openUpdateModal(id, name, status) {
                document.getElementById('updateDepartmentModal').classList.remove('hidden');
                document.getElementById('updateDepartmentId').value = id;

                document.getElementById('update_status').value = status;
                document.getElementById('updateDepartmentForm').action = `/projects/update/status/${id}`;
            }
        </script>
    @endpush
</x-app-layout>
