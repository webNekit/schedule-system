<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Название группы</label>
            <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="department_id" class="block text-sm font-medium text-gray-700">Кафедра</label>
            <select id="department_id" wire:model.defer="department_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Выберите кафедру</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            @error('department_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Дисциплины</label>
            <div class="mb-4">
                @foreach($allSubjects as $subject)
                    <button type="button" wire:click="addSubject({{ $subject->id }})" class="mr-2 mb-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        {{ $subject->name }}
                    </button>
                @endforeach
            </div>

            <div>
                @if(count($addedSubjects))
                    <ul class="list-disc pl-5">
                        @foreach($addedSubjects as $subjectId)
                            @php $subject = $allSubjects->find($subjectId); @endphp
                            @if($subject)
                                <li class="mb-2">
                                    {{ $subject->name }}
                                    <button type="button" wire:click="removeSubject({{ $subject->id }})" class="text-red-500 ml-2">Удалить</button>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Нет добавленных дисциплин</p>
                @endif
            </div>
        </div>

        <!-- Скрытое поле для хранения ID дисциплин -->
        <input type="hidden" wire:model="addedSubjects">

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Сохранить
            </button>
        </div>
    </form>
</div>
