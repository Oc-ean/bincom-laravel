@extends('layouts.app')

@section('title', 'LGA Total')
@section('subtitle', 'Question 2 · Summed across all polling units')

@section('content')
<div class="space-y-6">

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="px-6 pt-5 pb-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold">Select Local Government Area</h3>
            <p class="text-xs text-gray-500 mt-1">The total is computed by summing individual polling unit results — not from the announced LGA table.</p>
        </div>
        <div class="p-6">
            <div class="max-w-sm">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">LGA</label>
                <select id="lga-select" class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                    <option value="">Select LGA</option>
                    @foreach($lgas as $l)
                        <option value="{{ $l->lga_id }}">{{ $l->lga_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div id="result-area"></div>
</div>
@endsection

@push('scripts')
<script>
const base = '{{ url('/') }}';
const lgaEl = document.getElementById('lga-select');
const resultEl = document.getElementById('result-area');

async function getJson(url) {
    const res = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('Request failed');
    return res.json();
}

lgaEl.addEventListener('change', async () => {
    resultEl.innerHTML = '';
    if (!lgaEl.value) return;
    resultEl.innerHTML = '<div class="px-4 py-3 bg-blue-50 text-blue-700 text-sm rounded-xl border border-blue-200">Loading…</div>';

    try {
        const data = await getJson(`${base}/lga-sum/data/${lgaEl.value}`);
        render(data);
    } catch (e) {
        resultEl.innerHTML = `<div class="px-4 py-3 bg-red-50 text-red-700 text-sm rounded-xl border border-red-200">${e.message}</div>`;
    }
});

function announcedMap(announced) {
    return announced.reduce((m, a) => { m[a.party_abbreviation.trim()] = a.party_score; return m; }, {});
}

function render(data) {
    const ann = announcedMap(data.announced || []);

    const infoBar = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <div><div class="text-[11px] uppercase tracking-wider font-semibold text-gray-500">LGA</div>
                 <div class="text-sm font-semibold mt-1">${data.lga.lga_name}</div></div>
            <div><div class="text-[11px] uppercase tracking-wider font-semibold text-gray-500">Polling Units</div>
                 <div class="text-sm font-semibold mt-1">${data.polling_unit_count}</div></div>
            <div><div class="text-[11px] uppercase tracking-wider font-semibold text-gray-500">Parties</div>
                 <div class="text-sm font-semibold mt-1">${data.summed.length}</div></div>
        </div>`;

    const rows = data.summed.length
        ? data.summed.map(r => {
            const official = ann[r.party_abbreviation] ?? null;
            const diff = official !== null ? r.total - official : null;
            const status = official === null ? '—'
                : (diff === 0
                    ? '<svg width="16" height="16" class="inline text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
                    : '<svg width="16" height="16" class="inline text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4M12 17h.01"/></svg>');

            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><span class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs font-semibold">${r.party_abbreviation}</span></td>
                    <td class="px-4 py-3 text-right tabular-nums">${Number(r.total).toLocaleString()}</td>
                    <td class="px-4 py-3 text-right text-gray-500 tabular-nums">${official !== null ? official.toLocaleString() : '—'}</td>
                    <td class="px-4 py-3 text-right tabular-nums">${diff !== null ? diff.toLocaleString() : '—'}</td>
                    <td class="px-4 py-3 text-right">${status}</td>
                </tr>`;
        }).join('')
        : `<tr><td colspan="5" class="p-6 text-center text-sm text-gray-500">No polling unit results recorded for this LGA.</td></tr>`;

    resultEl.innerHTML = `
        ${infoBar}
        <div class="mt-4 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold">Summed Polling Unit Results</h3>
                <p class="text-xs text-gray-500 mt-1">Computed total vs officially announced figure</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Party</th>
                            <th class="px-4 py-3 text-right font-semibold">Summed</th>
                            <th class="px-4 py-3 text-right font-semibold">Announced</th>
                            <th class="px-4 py-3 text-right font-semibold">Difference</th>
                            <th class="px-4 py-3 text-right font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        </div>`;
}
</script>
@endpush