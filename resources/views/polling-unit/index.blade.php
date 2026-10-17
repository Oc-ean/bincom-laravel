@extends('layouts.app')

@section('title', 'Polling Unit Result')
@section('subtitle', 'Question 1 · Individual polling unit breakdown')

@section('content')
<div class="space-y-6">

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="px-6 pt-5 pb-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold">Select Polling Unit</h3>
            <p class="text-xs text-gray-500 mt-1">Use the chained selectors — LGA → Ward → Polling Unit.</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Local Government Area</label>
                    <select id="pu-lga" class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                        <option value="">Select LGA</option>
                        @foreach($lgas as $l)
                            <option value="{{ $l->lga_id }}">{{ $l->lga_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Ward</label>
                    <select id="pu-ward" disabled class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400">
                        <option value="">Select LGA first</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Polling Unit</label>
                    <select id="pu-polling-unit" disabled class="w-full h-10 px-3 rounded-lg border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-400">
                        <option value="">Select Ward first</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="result-area"></div>
</div>
@endsection

@push('scripts')
<script>
const base = '{{ url('/') }}';

async function getJson(url) {
    const res = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('Request failed');
    return res.json();
}

function fill(el, items, labelFn, valueFn, placeholder) {
    el.innerHTML = `<option value="">${placeholder}</option>` +
        items.map(i => `<option value="${valueFn(i)}">${labelFn(i)}</option>`).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    const lgaEl    = document.getElementById('pu-lga');
    const wardEl   = document.getElementById('pu-ward');
    const puEl     = document.getElementById('pu-polling-unit');
    const resultEl = document.getElementById('result-area');

    lgaEl.addEventListener('change', async () => {
        wardEl.disabled = true;
        wardEl.innerHTML = '<option value="">Loading…</option>';
        puEl.disabled = true;
        puEl.innerHTML = '<option value="">Select Ward first</option>';
        resultEl.innerHTML = '';
        if (!lgaEl.value) return;

        const wards = await getJson(`${base}/polling-unit/wards/${lgaEl.value}`);
        fill(wardEl, wards, w => w.ward_name, w => w.ward_id, 'Select Ward');
        wardEl.disabled = false;
    });

    wardEl.addEventListener('change', async () => {
        puEl.disabled = true;
        puEl.innerHTML = '<option value="">Loading…</option>';
        resultEl.innerHTML = '';
        if (!wardEl.value) return;

        const pus = await getJson(`${base}/polling-unit/pus/${wardEl.value}`);
        fill(puEl, pus, p => `${p.polling_unit_name} · #${p.uniqueid}`, p => p.uniqueid, 'Select Polling Unit');
        puEl.disabled = false;
    });

    puEl.addEventListener('change', async () => {
        resultEl.innerHTML = '';
        if (!puEl.value) return;

        resultEl.innerHTML = '<div class="px-4 py-3 bg-blue-50 text-blue-700 text-sm rounded-xl border border-blue-200">Loading…</div>';

        try {
            const data = await getJson(`${base}/polling-unit/result/${puEl.value}`);
            renderResult(resultEl, data);
        } catch (e) {
            resultEl.innerHTML = `<div class="px-4 py-3 bg-red-50 text-red-700 text-sm rounded-xl border border-red-200">${e.message}</div>`;
        }
    });
});

function renderResult(el, data) {
    const total = data.results.reduce((s, r) => s + Number(r.party_score || 0), 0);
    const rows = data.results.length
        ? data.results.map(r => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3"><span class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs font-semibold">${r.party_abbreviation}</span></td>
                <td class="px-4 py-3 text-right tabular-nums">${Number(r.party_score).toLocaleString()}</td>
                <td class="px-4 py-3 text-right text-gray-500 tabular-nums">${total ? ((r.party_score / total) * 100).toFixed(1) + '%' : '0%'}</td>
            </tr>`).join('')
        : `<tr><td colspan="3" class="p-6 text-center text-sm text-gray-500">No results recorded for this polling unit.</td></tr>`;

    el.innerHTML = `
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold">${data.polling_unit.polling_unit_name || 'Unnamed Polling Unit'}</h3>
                <p class="text-xs text-gray-500 mt-1">Polling Unit ID: #${data.polling_unit.uniqueid}</p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Party</th>
                        <th class="px-4 py-3 text-right font-semibold">Score</th>
                        <th class="px-4 py-3 text-right font-semibold">Share</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td class="px-4 py-3 font-semibold">Total</td>
                        <td class="px-4 py-3 text-right font-semibold tabular-nums">${total.toLocaleString()}</td>
                        <td class="px-4 py-3 text-right text-gray-500">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>`;
}
</script>
@endpush