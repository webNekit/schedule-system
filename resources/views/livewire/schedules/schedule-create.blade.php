<div class="p-4">
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label for="workday_id" class="block text-sm font-medium text-gray-700">Рабочий день</label>
            <select id="workday_id" wire:model.live="workday_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Выберите рабочий день</option>
                @foreach($workdays as $workday)
                    <option value="{{ $workday->id }}">{{ \Carbon\Carbon::parse($workday->date)->format('d.m.y') }}</option>
                @endforeach
            </select>
            @error('workday_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="department_id" class="block text-sm font-medium text-gray-700">Кафедра</label>
            <select id="department_id" wire:model.live="department_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Выберите кафедру</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            @error('department_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="group_id" class="block text-sm font-medium text-gray-700">Группа</label>
            <select id="group_id" wire:model.live="group_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Выберите группу</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
            @error('group_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            @if ($noSubjectsMessage)
            <div class="text-sm text-red-600">
                {{ $noSubjectsMessage }}
            </div>
        @endif
        </div>

        @for ($i = 1; $i <= 7; $i++)
            <div class="border p-4 rounded-md bg-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Пара {{ $i }}</h3>
                <div class="mt-2 flex items-center">
                    <input type="checkbox" wire:model.live="lessons.{{ $i }}.is_empty" class="mr-2">
                    <label class="text-sm font-medium text-gray-700">Пусто</label>
                </div>

                <div class="mt-2">
                    <label for="subject_{{ $i }}" class="block text-sm font-medium text-gray-700">Дисциплина</label>
                    <select id="subject_{{ $i }}" wire:model.live="lessons.{{ $i }}.subject_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Выберите дисциплину</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('lessons.' . $i . '.subject_id') 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror

                    <!-- Вывод оставшихся часов под дисциплиной -->
                    @if (isset($lessons[$i]['subject_id']) && isset($remainingHours[$lessons[$i]['subject_id']]))
                        <div class="mt-1 text-sm text-gray-600">
                            Осталось часов: {{ $remainingHours[$lessons[$i]['subject_id']] }}
                        </div>
                    @endif
                </div>

                <!-- Поля для выбора преподавателя и кабинета -->
                <div class="mt-2">
                    <label for="teacher_{{ $i }}" class="block text-sm font-medium text-gray-700">Преподаватель</label>
                    <select id="teacher_{{ $i }}" wire:model="lessons.{{ $i }}.teacher_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Выберите преподавателя</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('lessons.' . $i . '.teacher_id') 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="room_{{ $i }}" class="block text-sm font-medium text-gray-700">Кабинет</label>
                    <select id="room_{{ $i }}" wire:model="lessons.{{ $i }}.room_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Выберите кабинет</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('lessons.' . $i . '.room_id') 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
        @endfor

        <div class="flex items-center gap-4 mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm">
                Сохранить
            </button>
        </div>
    </form>
</div>
