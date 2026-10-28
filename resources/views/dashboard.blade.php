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
                    @foreach ($posts as $post)
                        <div
                            class="text-gray-900 bg-neutral-primary-soft flex flex-col sm:flex-row overflow-hidden border border-default rounded-base sm:rounded-r-lg shadow-xs">

                            <div class="flex-1 p-6">
                                <a href="#">
                                    <h5 class="mt-6 mb-2 text-2xl font-semibold tracking-tight text-heading">
                                        {{ $post->title }}</h5>
                                </a>
                                <p class="mb-6 text-body">{{ Str::words($post->content, 20) }}</p>
                                <a href="#"
                                    class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
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
                                <img class="w-full h-48 sm:h-full object-cover sm:rounded-r-lg"
                                    src="{{ $post->image }}" alt="" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
