<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   

<ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400  justify-center">
    @foreach ($categories as $category)
        <li class="me-2">
            <a href="#"
               class="inline-block px-4 py-3 rounded-lg {{ $loop->first ? 'text-white bg-blue-600 active' : 'hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white' }}"
               @if ($loop->first) aria-current="page" @endif>{{ $category->name }}</a>
        </li>
    @endforeach
</ul>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
