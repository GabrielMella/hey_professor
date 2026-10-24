<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __('My Questions') }}
        </x-header>
    </x-slot>

    <x-container>
        @if ($questions->isEmpty())
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('You have not asked any questions yet.') }}
            </p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($questions as $question)
                    <li class="py-4">
                        <p class="text-gray-800 dark:text-gray-200">{{ $question->question }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $question->created_at->diffForHumans() }}
                        </p>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4">
                {{ $questions->links() }}
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                &larr; {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-container>
</x-app-layout>
