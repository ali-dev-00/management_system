<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('create_employee_form') }}" method="POST">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>


                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>


                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text"
                                name="address" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="cnic" :value="__('CNIC')" />
                            <x-text-input id="cnic" class="block mt-1 w-full" type="number"
                                name="cnic" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('cnic')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="department_id" :value="__('Department')" />
                            <select name="department_id" id="department_id"
                                class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300  rounded-md shadow-sm' w-full"
                                required>
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($departments as $department)

                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach


                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2"
                                onclick="document.getElementById('createDepartmentModal').classList.add('hidden')">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>