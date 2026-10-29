<x-app-layout>
    <div class="py-4 max-w-7xl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">


                    <ul
                        class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400  justify-center">
                        <li class="me-2">
                            <a href="#" class="inline-block px-4 py-2 rounded-lg text-white bg-blue-600 active"
                                aria-current="page">All</a>
                        </li>
                        @foreach ($categories as $category)
                            <li class="me-2">
                                <a href="#"
                                    class="inline-block px-4 py-2 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">{{ $category['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>

            <!-- Section 2-->

            <div class="bg-white max-w-7xl dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-4 space-y-4 text-gray-900 dark:text-gray-100">
                    @forelse ($posts as $post)
                        <div
                            class="flex flex-col sm:flex-row overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">

                            <div class="flex-1 p-6">
                                <a href="#">
                                    <h5
                                        class="mt-6 mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $post->title }}</h5>
                                </a>
                                <p class="mb-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                                    {{ Str::words($post->content, 20) }}</p>
                                <a href="#"
                                    class="inline-flex items-center text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 shadow-sm font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none">
                                    Read more
                                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                    </svg>
                                </a>
                            </div>
                            <a href="#" class="shrink-0 sm:w-64">
                                <img class="w-full h-48 sm:h-full object-cover" src="{{ $post->image }}"
                                    alt="" />
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-900 dark:text-gray-100">No posts found.</p>
                    @endforelse

                    {{ $posts->onEachSide(1)->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
