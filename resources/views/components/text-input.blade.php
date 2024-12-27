@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-blue-700 bg-blue-900 text-blue-300 focus:border-blue-600 focus:ring-blue-500 focus:ring-blue-600 rounded-md shadow-sm']) !!}>
