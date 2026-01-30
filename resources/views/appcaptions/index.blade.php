<x-app-layout>
    <x-slot name="header">
        <h2 class="text-l">
            {{ __('messages.system_settings') }} / {{ __('messages.translates') }}
            | 
            <a href="{{ route('appcaptions.searchtranslations') }}" 
               onclick="return confirm('{{ __('messages.are_you_sure_resert_translations') }}')"
               class="px-2 py-1 rounded text-l font-medium bg-gray-100 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                + {{ __('messages.search_tranlastion') }}
            </a>                
            | 
            <a href="{{ route('appcaptions.exportlangfiles') }}" 
               class="px-2 py-1 rounded text-l font-medium bg-gray-100 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                + {{ __('messages.save_translations') }}
            </a>            
            | 
            <a href="{{ route('appcaptions.create') }}" 
               class="px-2 py-1 rounded text-l font-medium bg-gray-100 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                + {{ __('messages.new_caption') }}
            </a>            
        </h2>
    </x-slot>
    
    <div class="p-6">
        <form method="GET" class="mb-4 flex gap-2 flex-wrap">
            <input type="text" name="expression" value="{{ request('expression') }}"
                   placeholder="{{ __('messages.key') }}..."
                   class="border px-2 py-1 rounded">

            <input type="text" name="hu" value="{{ request('hu') }}"
                   placeholder="HU..."
                   class="border px-2 py-1 rounded">

            <input type="text" name="en" value="{{ request('en') }}"
                   placeholder="EN..."
                   class="border px-2 py-1 rounded">

            <input type="text" name="de" value="{{ request('de') }}"
                   placeholder="DE..."
                   class="border px-2 py-1 rounded">

            <button 
                class="px-3 py-1 rounded bg-gray-200 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                {{ __('messages.search') }}
            </button>

            <a href="{{ route('appcaptions.index') }}" 
               class="px-3 py-1 bg-gray-200 rounded bg-gray-100 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                {{ __('messages.reset') }}
            </a>
        </form> 
        <div class="mt-4">
            {{ $appcaptions->links() }}
        </div>        
        <table class="w-full border bg-white p-6 rounded shadow">    
            <thead>
                <tr class="bg-gray-100">
<!--                     <th class="p-2">{{ __('messages.langfile') }}</th>     -->
<!--                     <th class="p-2">{{ __('messages.placeholder') }}</th>  -->
                    <th class="p-2">{{ __('messages.expression') }}</th>
                    <th class="p-2">{{ __('messages.foundincode') }}</th>
                    <th class="p-2">{{ __('messages.foundinfile') }}</th>
                    <th class="p-2">HU</th>
                    <th class="p-2">EN</th>
                    <th class="p-2">DE</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($appcaptions as $c)
                    <tr class="border-t">
<!--                     <td class="p-2">{{ $c->langfile }}</td>    -->
<!--                     <td class="p-2">{{ $c->placeholder }}</td> -->
                        <td class="p-2">{{ $c->langfile }}.{{ $c->placeholder }}</td>
                        <td class="p-2">{{ ($c->foundincode == 0) ? __('messages.not_found') : '' }}</td>
                        <td class="p-2">{{ ($c->foundinfile == 0) ? __('messages.not_found') : '' }}</td>
                        <td class="p-2">{{ $c->hu }}</td>
                        <td class="p-2">{{ $c->en }}</td>
                        <td class="p-2">{{ $c->de }}</td>
                        <td class="p-2">
                            <a href="{{ route('appcaptions.edit', $c) }}" class="px-2 py-1 rounded text-l font-medium bg-gray-200 text-gray-600 hover:text-indigo-600 hover:bg-gray-50">
                                {{ __('messages.edit') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $appcaptions->links() }}
        </div>           
    </div>
</x-app-layout>