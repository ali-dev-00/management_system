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
        <div class="py-4">
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

    @if (Auth::user()->role === 'employee')
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between ">
                <h3 class="text-xl font-bold text-white">Performance Evaluation Chart</h3>
            </div>
            <div class="mt-4 bg-gray-800 p-2 rounded-lg shadow-lg w-full max-w-[600px] h-[300px] overflow-auto">
                <canvas id="performanceChart"></canvas>
            </div>


        </div>
    </div>
    @endif




    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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


            const taskNames = @json($taskNames);
            const scores = @json($scores);
            const createdAt = @json($createdAt);
            const comments = @json($comments);

            if (scores.length > 0 && createdAt.length > 0) {
                // Create the chart
                const ctx = document.getElementById('performanceChart').getContext('2d');
                const performanceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: createdAt, // Use createdAt for X-axis labels
                        datasets: [{
                            label: 'Performance Score',
                            data: scores, // Performance scores for each task
                            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Line color (transparent)
                            borderColor: 'rgba(54, 162, 235, 1)', // Line border color
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Point color
                            pointBorderColor: 'rgba(54, 162, 235, 1)', // Point border color
                            pointRadius: 5, // Point size
                            fill: false, // No fill under the line
                            tension: 0.2, // Smooth line
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'category', // X-axis uses the `created_at` as categories
                                labels: createdAt, // This ensures the createdAt dates are shown on X-axis
                            },
                            y: {
                                beginAtZero: true,
                                max: 10, // Maximum score is 10 (you can adjust this based on your score range)
                                ticks: {
                                    stepSize: 1, // Step size for Y-axis (e.g., 1 per score)
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        family: 'Arial', // Font for the legend
                                        size: 14, // Font size for the legend
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    // Customize the tooltip to show only score and comment
                                    title: function() {
                                        return ''; // Avoid showing the title (created_at)
                                    },
                                    label: function(tooltipItem) {
                                        const score = scores[tooltipItem.dataIndex];
                                        const comment = comments[tooltipItem.dataIndex];
                                        return `Score: ${score}, Comment: ${comment || 'No comment'}`; // Show score and comment
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                // If no data is available, display a message instead of the chart
                const chartContainer = document.getElementById('performanceChart').parentElement;
                chartContainer.innerHTML = '<p class="text-white text-center mt-10">No Performance Data Available</p>';
            }
        </script>
    @endpush
</x-app-layout>
