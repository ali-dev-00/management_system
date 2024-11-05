<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Documents') }}
                @if (Auth::user()->role === "admin" && $documents->count() > 0)
                of <span class="text-capitalize text-gray-400">{{ $documents->first()->user->name ?? '' }}</span>
            @endif
            </h2>
            <button class="px-3 py-1 bg-red-700 rounded text-white"
                onclick="document.getElementById('createDepartmentModal').classList.remove('hidden')">
                Create Document
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
                                <th
                                    class="px-6 py-3 text-xs text-center font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-xs text-center font-medium text-gray-500 uppercase tracking-wider">
                                    Document</th>

                                <th
                                    class="px-6 py-3 text-xs text-center font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-xs text-center font-medium text-gray-500 uppercase tracking-wider">
                                    Purpose</th>

                                <th
                                    class="px-6 py-3 text-xs text-center font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($documents as $document)
                                <tr class="text-center">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $document->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['png', 'jpg', 'jpeg', 'gif']))
                                            <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->name }}" class="w-12 h-12 object-cover" />
                                        @else
                                            <span>Not an image</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $document->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $document->purpose }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="openUpdateModal({{ $document->id }}, '{{ $document->name }}', '{{ $document->purpose }}')" class="text-blue-600 hover:text-blue-900">Update</button>
                                        <form action="{{ route('delete_document', $document->id) }}" method="POST"
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

    <!-- Create Department Modal -->
    <div id="createDepartmentModal"
        class="flex fixed inset-0 bg-black bg-opacity-50  items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Create Document</h2>
            <form action="{{ route('add_document') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Document Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="purpose" :value="__('Purpose')" />
                    <x-text-input id="purpose" class="block mt-1 w-full" type="text" name="purpose"
                        :value="old('purpose')" required />
                    <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="file" :value="__('Document')" />
                    <input id="file" class="block mt-1 w-full" type="file" name="file" required />

                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2"
                        onclick="document.getElementById('createDepartmentModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Create</button>
                </div>
            </form>
        </div>
    </div>


    <div id="updateDepartmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-lg dark:text-white font-semibold">Update Document</h2>
            <form id="updateDepartmentForm" method="POST" enctype="multipart/form-data" >
                @csrf
                @method('PUT')
                <input type="hidden" class="text-black" name="id" id="updateDepartmentId">
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Document Name')" />
                    <x-text-input id="updateName" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="purpose" :value="__('Purpose')" />
                    <x-text-input id="updatePurpose" class="block mt-1 w-full" type="text" name="purpose"
                        :value="old('purpose')" required />
                    <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="file" :value="__('Document')" />
                    <input id="updateFile" class="block mt-1 w-full" type="file" name="file" />
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
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
        function openUpdateModal(id, name, purpose) {
            document.getElementById('updateDepartmentModal').classList.remove('hidden');
            document.getElementById('updateDepartmentId').value = id;
            document.getElementById('updateName').value = name;
            document.getElementById('updatePurpose').value = purpose;
            document.getElementById('updateDepartmentForm').action = `/documents/update/${id}`;
        }
    </script>

</x-app-layout>
