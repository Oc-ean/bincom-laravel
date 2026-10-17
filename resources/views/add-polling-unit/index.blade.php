@extends('layouts.app')

@section('title', 'Add Polling Unit')
@section('subtitle', 'Question 3 · Record new results for all parties')

@section('content')
<div class="space-y-6 max-w-4xl">

    @if(session('success'))
        <div class="px-4 py-3 bg-emerald-50 text-emerald-700 text-sm rounded-xl border border-emerald-200 flex items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="px-4 py-3 bg-red-50 text-red-700 text-sm rounded-xl border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('add-polling-unit.store') }}">
        @csrf

        {{-- 1. Location --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="px-6 pt-5 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold">1. Location</h3>
                <p class="text-xs text-gray-500 mt-1">Select the ward under which this new polling unit belongs.</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Local Government Area</label>
                    <select id="form-lga" class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                        <option value="">Select LGA</option>
                        @foreach($lgas as $l)
                            <option value="{{ $l->lga_id }}">{{ $l->lga_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Ward *</label>
                    <select id="form-ward" name="ward_id" required disabled class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400">
                        <option value="">Select LGA first</option>
                    </select>
                </div>
                <input type="hidden" id="form-lga-id" name="lga_id" value="{{ old('lga_id') }}">
            </div>
        </div>

        {{-- 2. Details --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mt-6">
            <div class="px-6 pt-5 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold">2. Polling Unit Details</h3>
                <p class="text-xs text-gray-500 mt-1">Identify and describe the polling unit.</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Polling Unit Name *</label>
                    <input type="text" name="polling_unit_name" required value="{{ old('polling_unit_name') }}"
                           placeholder="e.g. Central Primary School Hall"
                           class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    @error('polling_unit_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Polling Unit Number</label>
                        <input type="text" name="polling_unit_number" value="{{ old('polling_unit_number') }}"
                               placeholder="e.g. DT1901001"
                               class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Entered By</label>
                        <input type="text" name="entered_by_user" value="{{ old('entered_by_user', 'web') }}"
                               class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Description</label>
                    <textarea name="polling_unit_description" rows="2"
                              class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">{{ old('polling_unit_description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Latitude</label>
                        <input type="text" name="lat" value="{{ old('lat') }}" placeholder="e.g. 5.5985"
                               class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Longitude</label>
                        <input type="text" name="long" value="{{ old('long') }}" placeholder="e.g. 5.9993"
                               class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Scores --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mt-6">
            <div class="px-6 pt-5 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold">3. Party Scores</h3>
                <p class="text-xs text-gray-500 mt-1">Enter the vote count for every party. Default is 0.</p>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-2 px-3 py-2.5 bg-blue-50 text-blue-700 text-xs rounded-lg mb-5">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    Fill in non-zero scores only — zeros will be stored automatically.
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($parties as $p)
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">{{ $p->partyname }}</label>
                            <input type="number" min="0" name="scores[{{ $p->partyid }}]"
                                   value="{{ old('scores.' . $p->partyid, 0) }}"
                                   class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit"
                    class="inline-flex items-center gap-2 h-10 px-4 rounded-lg bg-blue-600 text-white text-sm font-medium shadow-sm hover:bg-blue-700 transition">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Polling Unit & Results
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const base = '{{ url('/') }}';
const lgaEl  = document.getElementById('form-lga');
const wardEl = document.getElementById('form-ward');
const lgaHidden = document.getElementById('form-lga-id');

async function getJson(url) {
    const res = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('Request failed');
    return res.json();
}

lgaEl.addEventListener('change', async () => {
    lgaHidden.value = lgaEl.value;
    wardEl.disabled = true;
    wardEl.innerHTML = '<option value="">Loading…</option>';
    if (!lgaEl.value) {
        wardEl.innerHTML = '<option value="">Select LGA first</option>';
        return;
    }
    const wards = await getJson(`${base}/polling-unit/wards/${lgaEl.value}`);
    wardEl.innerHTML = '<option value="">Select Ward</option>' +
        wards.map(w => `<option value="${w.ward_id}">${w.ward_name}</option>`).join('');
    wardEl.disabled = false;
});
</script>
@endpush