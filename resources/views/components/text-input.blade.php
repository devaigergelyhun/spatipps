@props(['disabled' => false, 'autocomplete' => 'off'])

<input @disabled($disabled) {{ $attributes->merge(['autocomplete' => $autocomplete, 'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
