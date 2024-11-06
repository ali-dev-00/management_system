<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                    {{ __('Assign Tasks to Employees') }}
                @else
                    Your Tasks
                @endif
            </h2>
            @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                <button class="px-3 py-1 bg-red-700 rounded text-white"
                    onclick="document.getElementById('createDepartmentModal').classList.remove('hidden')">
                    Create Task
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <table id="departmentsTable" class="w-full divide-y divide-gray-700 ">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned By</th>
                                @endif
                                @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned to</th>
                                @endif

                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task Name</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider ">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->id }}</td>
                                    @if (Auth::user()->role === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignedBy->name }}</td>
                                    @endif
                                    @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignedTo->name }}</td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->project->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->due_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->status }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            onclick="openUpdateModal({{ $task->id }}, '{{ $task->name }}', '{{ $task->status }}')"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fa-solid fa-pen-to-square"></i> <!-- Update icon -->
                                        </button>

                                        @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                                            <form action="{{ route('delete_task', $task->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ms-4">
                                                    <i class="fa-solid fa-trash"></i> <!-- Delete icon -->
                                                </button>
                                            </form>

                                            <button
                                                onclick="openEvaluationModal({{ $task->id }}, {{ $task->assigned_to }})"
                                                class="text-green-600 hover:text-green-900 ms-4">
                                                <i class="fa-solid fa-star"></i> <!-- Performance Evaluation icon -->
                                            </button>
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
            <h2 class="text-lg dark:text-white font-semibold">Create Tasks</h2>
            <form action="{{ route('add_task') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Task Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="due_date" :value="__('Due Date')" />
                    <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date"
                        :value="old('due_date')" required />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description')" required />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="assigned_to" :value="__('Employee')" />
                    <select name="assigned_to" id="assigned_to"
                        class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full"
                        required>
                        <option value="" selected disabled>Select Employee</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="project_id" :value="__('Project')" />
                    <select name="project_id" id="project_id"
                        class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full"
                        required>
                        <option value="" selected disabled>Select Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
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

                <!-- Hidden field for assigned_by -->
                <input type="hidden" name="assigned_by" value="{{ Auth::user()->id }}">

                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2"
                        onclick="document.getElementById('createDepartmentModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Create</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Performance Evaluation Modal -->
    <div id="evaluationModal" class="flex fixed inset-0 bg-black bg-opacity-50  items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Performance Evaluation</h2>
            <form id="evaluationForm" action="{{ route('add_performance') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" id="evaluationTaskId">
                <input type="hidden" name="employee_id" id="evaluationEmployeeId">
                <div class="mt-4">
                    <x-input-label for="score" :value="__('Score')" />

                    <div class="flex items-center gap-3 ">

                        <button type="button" onclick="decrementScore()"
                            class="px-2 py-1 bg-gray-300 text-gray-800 rounded-l">
                            -
                        </button>

                        <x-text-input id="score" class="block w-16 text-center border-gray-300" type="number"
                            name="score" :value="old('score', 1)" min="1" max="10" readonly required />
                        <button type="button" onclick="incrementScore()"
                            class="px-2 py-1 bg-gray-300 text-gray-800 rounded-r">
                            +
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('score')" class="mt-2" />
                </div>


                <div class="mt-4">
                    <x-input-label for="comments" :value="__('Comments')" />
                    <x-text-input id="comments" class="block mt-1 w-full" type="text" name="comments"
                        :value="old('comments')" required />
                    <x-input-error :messages="$errors->get('comments')" class="mt-2" />
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeEvaluationModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Submit Evaluation</button>
                </div>
            </form>
        </div>
    </div>




    <div id="updateDepartmentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Update Task Status</h2>
            <form id="updateDepartmentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" class="text-black" name="id" id="update_task_id">
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
                document.getElementById('update_task_id').value = id;
                document.getElementById('update_status').value = status;
                document.getElementById('updateDepartmentForm').action = `/tasks/update/status/${id}`;
            }

            function openEvaluationModal(taskId, employeeId) {
                document.getElementById('evaluationTaskId').value = taskId;
                document.getElementById('evaluationEmployeeId').value = employeeId;
                document.getElementById('evaluationModal').classList.remove('hidden');
            }

            function closeEvaluationModal() {
                document.getElementById('evaluationModal').classList.add('hidden');
            }

            function incrementScore() {
                let scoreInput = document.getElementById('score');
                let currentValue = parseInt(scoreInput.value);
                if (currentValue < 10) {
                    scoreInput.value = currentValue + 1;
                }
            }

            function decrementScore() {
                let scoreInput = document.getElementById('score');
                let currentValue = parseInt(scoreInput.value);
                if (currentValue > 1) {
                    scoreInput.value = currentValue - 1;
                }
            }
        </script>
    @endpush
</x-app-layout>
