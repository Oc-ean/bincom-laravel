@props(['showPollingUnit' => true, 'prefix' => 'pu'])

<div id="{{ $prefix }}-chain" class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">Local Government Area</label>
        <select id="{{ $prefix }}-lga" class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
            <option value="">Select LGA</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">Ward</label>
        <select id="{{ $prefix }}-ward" disabled class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400">
            <option value="">Select LGA first</option>
        </select>
    </div>
    @if($showPollingUnit)
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">Polling Unit</label>
        <select id="{{ $prefix }}-polling-unit" disabled class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400">
            <option value="">Select Ward first</option>
        </select>
    </div>
    @endif
</div>