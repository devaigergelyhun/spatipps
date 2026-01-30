<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            {{ __('messages.system_settings') }} / 
            <a href="{{ route('appcaptions.index') }}" class="px-2 py-1 rounded text-l font-medium bg-gray-100 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                {{ __('messages.translates') }}
            </a>
             /             
            {{ __('messages.new') }}        
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('appcaptions.store') }}" class="bg-white p-6 rounded shadow">
            @csrf

            <div class="mb-4">
                <x-input-label value="Langfile" />
                <x-text-input name="langfile" :value="old('langfile')" />
            </div>

            <div class="mb-4">
                <x-input-label value="Placeholder" />
                <x-text-input name="placeholder" :value="old('placeholder')" />
            </div>

            <div class="mb-4">
                <x-input-label value="Magyar" />
                <x-text-input name="hu" :value="old('hu')" />
            </div>

            <div class="mb-4">
                <x-input-label value="English" />
                <x-text-input name="en" :value="old('en')" />
            </div>

            <div class="mb-4">
                <x-input-label value="Deutsch" />
                <x-text-input name="de" :value="old('de')" />
            </div>

            <x-primary-button>{{__('messages.save')}}</x-primary-button>
        </form>
    </div>
</x-app-layout>