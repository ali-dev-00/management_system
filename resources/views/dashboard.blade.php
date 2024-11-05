<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (Auth::user()->role === 'admin')

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        Admin Dashboard
                    </div>
                </div>
            </div>
        </div>

    @else


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 items-center flex justify-between text-gray-900 dark:text-gray-100">
                        <div>
                            Welcome , {{ Auth::user()->name ?? 'Employee' }} 👋😊
                        </div>
                        <div class="text-center">
                            @if ($attendance && !$attendance->punch_out)
                                <button class="px-4 py-2 bg-red-500 text-white rounded-md"
                                    onclick="openPunchModal('out')">Punch
                                    Out</button>
                            @else
                                <button class="px-4 py-2 bg-green-500 text-white rounded-md"
                                    onclick="openPunchModal('in')">Punch
                                    In</button>
                            @endif
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Punch Modal -->
        <div id="punchModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-lg mb-4 text-gray-900 dark:text-gray-100" id="modalTitle"></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Current Time: <span id="currentTime"></span></p>

                <!-- Description Input -->
                <div class="mt-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-300">Description</label>
                    <input type="text" id="description"
                        class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-300"
                        placeholder="Enter a description" required>
                </div>

                <!-- Buttons -->
                <div class="mt-6 flex justify-end">
                    <button onclick="closePunchModal()"
                        class="px-4 py-2 mr-2 bg-gray-500 text-white rounded-md">Cancel</button>
                    <button onclick="submitPunch()" class="px-4 py-2 bg-blue-500 text-white rounded-md">Submit</button>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            function openPunchModal(type) {
                const modalTitle = document.getElementById('modalTitle');
                const currentTime = document.getElementById('currentTime');
                const punchModal = document.getElementById('punchModal');
                modalTitle.innerText = type === 'in' ? 'Punch In' : 'Punch Out';
                currentTime.innerText = new Date().toLocaleString();
                punchModal.classList.remove('hidden');
            }

            function closePunchModal() {
                document.getElementById('punchModal').classList.add('hidden');
            }

            function submitPunch() {
                const description = document.getElementById('description').value;
                const modalTitle = document.getElementById('modalTitle').innerText;
                const punchType = modalTitle === 'Punch In' ? 'in' : 'out';

                fetch("{{ route('attendance.punch') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            description,
                            type: punchType
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        closePunchModal();
                        window.location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>
    @endpush
</x-app-layout>
