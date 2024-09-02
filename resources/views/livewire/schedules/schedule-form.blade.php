<!-- resources/views/livewire/schedules/schedule-form.blade.php -->
<div class="container mx-auto py-6">
    <!-- Сообщение об успешном сохранении -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Карточка формы -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Создание Расписания</h2>
        <form wire:submit.prevent="save">
            <!-- Выпадающий список для выбора рабочего дня -->
            <div class="mb-4">
                <label for="workday_id" class="block text-sm font-medium text-gray-700">Выберите рабочий день</label>
                <select wire:model="workday_id" id="workday_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">-- Выберите рабочий день --</option>
                    @foreach($workdays as $workday)
                        <option value="{{ $workday->id }}">{{ $workday->date }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Выбор кафедры -->
            <div class="mb-4">
                <label for="department" class="block text-sm font-medium text-gray-700">Кафедра</label>
                <select wire:model.live="department_id" id="department"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('department_id') border-red-500 @enderror">
                    <option value="">Выберите кафедру</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Выбор группы -->
            <div class="mb-4">
                <label for="group" class="block text-sm font-medium text-gray-700">Группа</label>
                <select wire:model="group_id" id="group"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('group_id') border-red-500 @enderror">
                    <option value="">Выберите группу</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Выпадающие списки для 7 пар -->
            <div class="space-y-4">
                @foreach(range(1, 7) as $i)
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Пара №{{ $i }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Дисциплина</label>
                                <select wire:model="lessons.{{ $i }}.subject_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('lessons.'.$i.'.subject_id') border-red-500 @enderror">
                                    <option value="">Выберите дисциплину</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('lessons.'.$i.'.subject_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Преподаватель</label>
                                <select wire:model="lessons.{{ $i }}.teacher_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('lessons.'.$i.'.teacher_id') border-red-500 @enderror">
                                    <option value="">Выберите преподавателя</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                @error('lessons.'.$i.'.teacher_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Кабинет</label>
                                <select wire:model="lessons.{{ $i }}.room_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('lessons.'.$i.'.room_id') border-red-500 @enderror">
                                    <option value="">Выберите кабинет</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                @error('lessons.'.$i.'.room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" wire:model="lessons.{{ $i }}.is_empty" id="emptyCheck{{ $i }}"
                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <label for="emptyCheck{{ $i }}" class="ml-2 block text-sm text-gray-700">Нет пары</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="mt-6 w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">Сохранить расписание</button>
        </form>
    </div>
</div>
