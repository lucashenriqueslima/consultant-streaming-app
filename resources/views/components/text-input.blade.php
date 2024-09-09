@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-green-700 bg-gray-900 text-gray-300 focus:border-green-600 focus:ring-green-500 focus:ring-green-600 rounded-md shadow-sm']) !!}>
