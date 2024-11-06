<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                    All Tasks of <span class="text-gray-500" > {{ $project->name }}</span>
                @else
                    Your Tasks
                @endif
            </h2>
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
                            @forelse ($project->tasks as $task)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->id }}</td>
                                    @if (Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignedBy->name }}</td>
                                    @endif
                                    @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignedTo->name }}</td>
                                    @endif

                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->due_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->status }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">


                                        @if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin')
                                            <form action="{{ route('delete_task', $task->id) }}" method="POST"
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
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No tasks found for this project.</td>
                                </tr>
                            @endforelse
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
        </script>
    @endpush
</x-app-layout>
