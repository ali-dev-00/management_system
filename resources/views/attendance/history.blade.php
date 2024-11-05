<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">Attendance History
                        @if (Auth::user()->role === "admin" && $attendanceHistory->count() > 0)
                        of <span class="text-capitalize text-gray-400">{{ $attendanceHistory->first()->user->name ?? '' }}</span>
                    @endif
                    </h3>

                    <div x-data="{ open: null }">
                        @foreach ($attendanceHistory as $attendance)
                            <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                                <button @click="open === {{ $attendance->id }} ? open = null : open = {{ $attendance->id }}"
                                        class="w-full text-left py-2 px-4 bg-gray-100 dark:bg-gray-700 focus:outline-none">
                                    <span>{{ \Carbon\Carbon::parse($attendance->punch_in)->format('d M Y') }}</span>
                                </button>
                                <div x-show="open === {{ $attendance->id }}" x-transition class="mt-2">
                                    <table class="w-full bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border rounded-lg">
                                        <tr>
                                            <th class="py-2 px-4">Punch In</th>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($attendance->punch_in)->format('h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-2 px-4">Punch Out</th>
                                            <td class="py-2 px-4">{{ $attendance->punch_out ? \Carbon\Carbon::parse($attendance->punch_out)->format('h:i A') : 'Not yet punched out' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-2 px-4">Punch In Description</th>
                                            <td class="py-2 px-4">{{ $attendance->punch_in_description  }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-2 px-4">Punch Out Description</th>
                                            <td class="py-2 px-4">{{ $attendance->punch_out_description ?? "N/A" }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-2 px-4">Total Time</th>
                                            <td class="py-2 px-4">
                                                {{ $attendance->punch_out ? \Carbon\Carbon::parse($attendance->punch_in)->diffForHumans($attendance->punch_out, true) : 'N/A' }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
