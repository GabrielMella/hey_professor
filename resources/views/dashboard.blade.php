<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __('Dashboard')}}
        </x-header>
    </x-slot>

    <x-container>
        <form method="POST" action="{{route('question.store')}}">
            @csrf
            <x-textarea label="Question" name="question"/>

            <x-btn.primary>
                Save
            </x-btn.primary>

            <x-btn.reset>
                Cancel
            </x-btn.reset>

        </form>
    </x-container>

</x-app-layout>
