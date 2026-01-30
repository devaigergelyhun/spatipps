<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            {{ __('messages.system_settings') }} / 
            <a href="{{ route('appcaptions.index') }}" class="px-2 py-1 rounded text-l font-medium bg-gray-100 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                {{ __('messages.translates') }}
            </a>
             / 
            {{ __('messages.edit') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('appcaptions.update', $appcaption) }}" class="bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-input-label value="Langfile" />
                <x-text-input name="langfile" :value="$appcaption->langfile" />
            </div>

            <div class="mb-4">
                <x-input-label value="Placeholder" />
                <x-text-input name="placeholder" :value="$appcaption->placeholder" />
            </div>

            <div class="mb-4">
                <x-input-label value="Magyar" />
<!--                <x-text-input rows="4" name="hu" :value="$appcaption->hu" /> -->
                <textarea 
                    name="hu"
                    rows="1"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('hu', $appcaption->hu) }}</textarea>
            </div>

            <div class="mb-4">
                <x-input-label value="English" />
<!--                <x-text-input rows="4" name="en" :value="$appcaption->en" />-->
                <textarea 
                    name="en"
                    rows="1"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('en', $appcaption->en) }}</textarea>
            </div>

            <div class="mb-4">
                <x-input-label value="Deutsch" />
<!--                 <x-text-input name="de_old" :value="$appcaption->de" /> -->
                <textarea 
                    name="de"
                    rows="1"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('de', $appcaption->de) }}</textarea>
            </div>

            <x-primary-button>{{ __('messages.save')}}</x-primary-button>
        </form>
    </div>
</x-app-layout>