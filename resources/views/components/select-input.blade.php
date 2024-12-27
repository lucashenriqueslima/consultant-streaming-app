@props(['disabled' => false, 'options' => [], 'selected' => null, 'default' => null])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full border-blue-700 bg-gray-900 text-gray-300 focus:border-blue-500 focus:border-blue-600 focus:ring-blue-500 focus:ring-blue-600 rounded-md shadow-sm']) !!}>
    @foreach($options as $key => $value)
        <option value="{{ $key }}" @selected($selected === $key || $default === $value)>{{ $value }}</option>
    @endforeach
</select>
