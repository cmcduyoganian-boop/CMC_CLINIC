@php
    $medicalItemsLeft = [
        ['key' => 'allergy', 'label' => 'Allergy - please specify:', 'specify' => 'allergy_specify'],
        ['key' => 'asthma', 'label' => 'Asthma'],
        ['key' => 'cancer', 'label' => 'Cancer'],
        ['key' => 'cerebrovascular_disease', 'label' => 'Cerebrovascular Disease'],
        ['key' => 'diabetes', 'label' => 'Diabetes Mellitus - maintenance'],
        ['key' => 'epilepsy', 'label' => 'Epilepsy/Seizure Disorder'],
        ['key' => 'emphysema', 'label' => 'Emphysema'],
        ['key' => 'hepatitis', 'label' => 'Hepatitis - please specify the type:', 'specify' => 'hepatitis_type'],
        ['key' => 'hypertension', 'label' => 'Hypertension'],
    ];
    $medicalItemsRight = [
        ['key' => 'hyperlipidemia', 'label' => 'Hyperlipidemia'],
        ['key' => 'peptic_ulcer', 'label' => 'Peptic Ulcer'],
        ['key' => 'pneumonia', 'label' => 'Pneumonia'],
        ['key' => 'thyroid_disease', 'label' => 'Thyroid Disease'],
        ['key' => 'pulmonary_tb', 'label' => 'Pulmonary Tuberculosis'],
        ['key' => 'urinary_tract_infection', 'label' => 'Urinary Tract Infection'],
        ['key' => 'mental_illness', 'label' => 'Mental Illness'],
        ['key' => 'others_medical', 'label' => 'Others', 'specify' => 'others_medical_specify'],
        ['key' => 'none_medical', 'label' => 'None'],
    ];
    $familyItemsLeft = [
        ['key' => 'allergy', 'label' => 'Allergy - please specify:', 'specify' => 'allergy_specify'],
        ['key' => 'asthma', 'label' => 'Asthma'],
        ['key' => 'cancer', 'label' => 'Cancer'],
        ['key' => 'cerebrovascular_disease', 'label' => 'Cerebrovascular Disease'],
        ['key' => 'diabetes', 'label' => 'Diabetes Mellitus - maintenance'],
        ['key' => 'epilepsy', 'label' => 'Epilepsy/Seizure Disorder'],
        ['key' => 'emphysema', 'label' => 'Emphysema'],
        ['key' => 'hepatitis', 'label' => 'Hepatitis - please specify the type:', 'specify' => 'hepatitis_type'],
        ['key' => 'hypertension', 'label' => 'Hypertension'],
    ];
    $familyItemsRight = [
        ['key' => 'hyperlipidemia', 'label' => 'Hyperlipidemia'],
        ['key' => 'peptic_ulcer', 'label' => 'Peptic Ulcer'],
        ['key' => 'pneumonia', 'label' => 'Pneumonia'],
        ['key' => 'thyroid_disease', 'label' => 'Thyroid Disease'],
        ['key' => 'pulmonary_tb', 'label' => 'Pulmonary Tuberculosis'],
        ['key' => 'urinary_tract_infection', 'label' => 'Urinary Tract Infection'],
        ['key' => 'mental_illness', 'label' => 'Mental Illness'],
        ['key' => 'others', 'label' => 'Others', 'specify' => 'others_specify'],
        ['key' => 'none', 'label' => 'None'],
    ];
    $civilStatusOptions = ['Single', 'Married', 'Annuled', 'Widowed', 'Separated', 'Co-habitation'];
@endphp

<style>
    .cmc-form table { border-collapse: collapse; width: 100%; }
    .cmc-form td, .cmc-form th { border: 1px solid #1f2937; padding: 4px 8px; font-size: 12.5px; vertical-align: top; }
    .cmc-form .lbl { font-weight: 600; white-space: nowrap; background: #f9fafb; width: 1%; }
    .cmc-form input[type="text"],
    .cmc-form input[type="date"],
    .cmc-form select,
    .cmc-form textarea {
        width: 100%; border: none; outline: none; background: transparent; font-size: 12.5px; padding: 1px 2px;
    }
    .cmc-form input[type="text"]:focus,
    .cmc-form input[type="date"]:focus,
    .cmc-form select:focus,
    .cmc-form textarea:focus { background: #eef2ff; }
    .cmc-form .section-title { background: #e5e7eb; font-weight: 700; text-transform: uppercase; letter-spacing: .02em; }
    .cmc-form .sub-title { background: #f3f4f6; font-weight: 600; font-size: 12px; }
    .cmc-form .check-col { width: 24px; text-align: center; }
    .cmc-form .center { text-align: center; }

    /* ===== Print: only the form sheet shows, fit to one page ===== */
    @media print {
        body * { visibility: hidden; }
        #student-health-print-area, #student-health-print-area * { visibility: visible; }
        #student-health-print-area {
            position: absolute; top: 0; left: 0; width: 100%; margin: 0; padding: 0;
        }
        .no-print { display: none !important; }
        .cmc-form input, .cmc-form select, .cmc-form textarea { border: none !important; }
        @page { size: portrait; margin: 8mm; }
        .cmc-form td, .cmc-form th { font-size: 10.5px; padding: 3px 6px; }
    }
</style>

<div class="max-w-5xl mx-auto cmc-form">

    @if (session('message'))
        <div class="mb-3 p-2 rounded bg-green-100 border border-green-300 text-green-800 text-sm no-print">
            {{ session('message') }}
        </div>
    @endif

    {{-- Top action bar --}}
    <div class="flex justify-end items-center gap-3 mb-3 no-print">
        <a href="{{ route('forms.index') }}" wire:navigate
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-400 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
            Cancel
        </a>
        <button type="button" onclick="window.print()"
            class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
            Print Form
        </button>
        <button type="submit" form="student-health-form"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            {{ $isEdit ? 'Update' : 'Save' }}
        </button>
    </div>

    <form wire:submit.prevent="submit" id="student-health-form" class="bg-white">
        <table id="student-health-print-area">
            {{-- ===================== HEADER ===================== --}}
            <tr>
                <td colspan="2" style="width:70%;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC"
                             width="44" height="44"
                             style="width:44px;height:44px;min-width:44px;object-fit:contain;border-radius:9999px;"
                             onerror="this.style.display='none'">
                        <div>
                            <div style="font-weight:700;font-size:15px;line-height:1.1;">Carmen Municipal College</div>
                            <div style="color:#4b5563;font-size:12.5px;">Carmen, Bohol</div>
                        </div>
                    </div>
                </td>
                <td colspan="2" style="width:30%;">
                    <div class="center" style="font-weight:700;margin-bottom:4px;">Student Code</div>
                    <input type="text" wire:model="studentCode" maxlength="20"
                           class="center" style="letter-spacing:.3em;font-family:monospace;">
                </td>
            </tr>
            <tr>
                <td colspan="4" class="center" style="font-style:italic;background:#f9fafb;">
                    Instructions: Please print legibly and mark appropriate boxes with "&#10003;"
                </td>
            </tr>

            {{-- ===================== I. STUDENT'S INFORMATION ===================== --}}
            <tr><td colspan="4" class="section-title">I. Student's Information</td></tr>

            <tr>
                <td class="lbl">Last Name</td>
                <td><input type="text" wire:model="last_name"></td>
                <td class="lbl">Suffix</td>
                <td><input type="text" wire:model="suffix" placeholder="Jr., III, etc."></td>
            </tr>
            <tr>
                <td class="lbl">First Name</td>
                <td><input type="text" wire:model="first_name"></td>
                <td colspan="2">
                    <div style="font-size:11.5px;color:#6b7280;">Please write Maiden Name if Married</div>
                    <input type="text" wire:model="maiden_name">
                </td>
            </tr>
            <tr>
                <td class="lbl">Middle Name</td>
                <td><input type="text" wire:model="middle_name"></td>
                <td class="lbl">Mother's Complete Name</td>
                <td><input type="text" wire:model="mother_name"></td>
            </tr>
            <tr>
                <td class="lbl">Sex</td>
                <td>
                    <select wire:model="sex">
                        <option value="">-- Select --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
                <td class="lbl">Father's Complete Name</td>
                <td><input type="text" wire:model="father_name"></td>
            </tr>
            <tr>
                <td class="lbl">Birthday (mm/dd/yy)</td>
                <td><input type="date" wire:model="birthday"></td>
                <td class="lbl" rowspan="2">Residential Address</td>
                <td rowspan="2"><textarea wire:model="residential_address" rows="2" style="resize:none;"></textarea></td>
            </tr>
            <tr>
                <td class="lbl">Birthplace</td>
                <td><input type="text" wire:model="birthplace"></td>
            </tr>
            <tr>
                <td class="lbl">Blood Type</td>
                <td><input type="text" wire:model="blood_type" placeholder="e.g. O+"></td>
                <td class="lbl">Height</td>
                <td><input type="text" wire:model="height" placeholder="cm"></td>
            </tr>

            {{-- Civil Status (rowspan 6) paired with Weight / Course / Year & Section --}}
            <tr>
                <td class="lbl" rowspan="6" style="vertical-align:middle;">Civil Status</td>
                <td style="padding:0;">
                    <table style="border:none;">
                        <tr><td style="border:none;border-bottom:1px solid #1f2937;">{{ $civilStatusOptions[0] }}</td>
                            <td class="check-col" style="border:none;border-bottom:1px solid #1f2937;border-left:1px solid #1f2937;">
                                <input type="radio" wire:model="civil_status" value="{{ $civilStatusOptions[0] }}"></td></tr>
                    </table>
                </td>
                <td class="lbl">Weight</td>
                <td><input type="text" wire:model="weight" placeholder="kg"></td>
            </tr>
            <tr>
                <td style="padding:0;">
                    <table style="border:none;">
                        <tr><td style="border:none;border-bottom:1px solid #1f2937;">{{ $civilStatusOptions[1] }}</td>
                            <td class="check-col" style="border:none;border-bottom:1px solid #1f2937;border-left:1px solid #1f2937;">
                                <input type="radio" wire:model="civil_status" value="{{ $civilStatusOptions[1] }}"></td></tr>
                    </table>
                </td>
                <td class="lbl">Course</td>
                <td><input type="text" wire:model="course"></td>
            </tr>
            <tr>
                <td style="padding:0;">
                    <table style="border:none;">
                        <tr><td style="border:none;border-bottom:1px solid #1f2937;">{{ $civilStatusOptions[2] }}</td>
                            <td class="check-col" style="border:none;border-bottom:1px solid #1f2937;border-left:1px solid #1f2937;">
                                <input type="radio" wire:model="civil_status" value="{{ $civilStatusOptions[2] }}"></td></tr>
                    </table>
                </td>
                <td class="lbl">Year &amp; Section</td>
                <td><input type="text" wire:model="year_section"></td>
            </tr>
            <tr>
                <td style="padding:0;">
                    <table style="border:none;">
                        <tr><td style="border:none;border-bottom:1px solid #1f2937;">{{ $civilStatusOptions[3] }}</td>
                            <td class="check-col" style="border:none;border-bottom:1px solid #1f2937;border-left:1px solid #1f2937;">
                                <input type="radio" wire:model="civil_status" value="{{ $civilStatusOptions[3] }}"></td></tr>
                    </table>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="padding:0;">
                    <table style="border:none;">
                        <tr><td style="border:none;border-bottom:1px solid #1f2937;">{{ $civilStatusOptions[4] }}</td>
                            <td class="check-col" style="border:none;border-bottom:1px solid #1f2937;border-left:1px solid #1f2937;">
                                <input type="radio" wire:model="civil_status" value="{{ $civilStatusOptions[4] }}"></td></tr>
                    </table>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="padding:0;">
                    <table style="border:none;">
                        <tr><td style="border:none;">{{ $civilStatusOptions[5] }}</td>
                            <td class="check-col" style="border:none;border-left:1px solid #1f2937;">
                                <input type="radio" wire:model="civil_status" value="{{ $civilStatusOptions[5] }}"></td></tr>
                    </table>
                </td>
                <td colspan="2"></td>
            </tr>

            <tr>
                <td class="lbl">Contact Number</td>
                <td colspan="3"><input type="text" wire:model="contact_number"></td>
            </tr>
            <tr>
                <td class="lbl">Spouse's Name</td>
                <td colspan="3"><input type="text" wire:model="spouse_name"></td>
            </tr>
            <tr>
                <td colspan="4">
                    @error('last_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    @error('first_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    @error('sex') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </td>
            </tr>

            {{-- ===================== II. PAST MEDICAL & SURGICAL HISTORY ===================== --}}
            <tr><td colspan="4" class="section-title">II. Past Medical &amp; Surgical History</td></tr>
            <tr><td colspan="4" class="sub-title">Past Medical History</td></tr>

            @foreach ($medicalItemsLeft as $i => $item)
                <tr>
                    <td colspan="2" style="padding:0;">
                        <table style="border:none;">
                            <tr>
                                <td style="border:none;{{ $i < count($medicalItemsLeft) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    {{ $item['label'] }}
                                    @if (isset($item['specify']))
                                        <input type="text" wire:model="pastMedicalHistory.{{ $item['specify'] }}"
                                               style="width:45%;border-bottom:1px solid #9ca3af;display:inline-block;">
                                    @endif
                                </td>
                                <td class="check-col" style="border-left:1px solid #1f2937;{{ $i < count($medicalItemsLeft) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    <input type="checkbox" wire:model="pastMedicalHistory.{{ $item['key'] }}">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" style="padding:0;">
                        @php $r = $medicalItemsRight[$i]; @endphp
                        <table style="border:none;">
                            <tr>
                                <td style="border:none;{{ $i < count($medicalItemsRight) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    {{ $r['label'] }}
                                    @if (isset($r['specify']))
                                        <input type="text" wire:model="pastMedicalHistory.{{ $r['specify'] }}"
                                               style="width:45%;border-bottom:1px solid #9ca3af;display:inline-block;">
                                    @endif
                                </td>
                                <td class="check-col" style="border-left:1px solid #1f2937;{{ $i < count($medicalItemsRight) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    <input type="checkbox" wire:model="pastMedicalHistory.{{ $r['key'] }}">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td class="lbl">Maintenance:</td>
                <td colspan="3"><input type="text" wire:model="pastMedicalHistory.maintenance"></td>
            </tr>

            {{-- PAST SURGICAL HISTORY --}}
            <tr><td colspan="4" class="sub-title">Past Surgical History</td></tr>
            <tr>
                <td colspan="2" class="center" style="font-weight:700;">OPERATION</td>
                <td colspan="2" class="center" style="font-weight:700;">DATE (mm/dd/yy)</td>
            </tr>
            @foreach ($pastSurgicalHistory as $i => $row)
                <tr>
                    <td colspan="2"><input type="text" wire:model="pastSurgicalHistory.{{ $i }}.operation"></td>
                    <td colspan="2"><input type="text" wire:model="pastSurgicalHistory.{{ $i }}.date"></td>
                </tr>
            @endforeach

            {{-- FAMILY HISTORY --}}
            <tr><td colspan="4" class="section-title">Family History</td></tr>

            @foreach ($familyItemsLeft as $i => $item)
                <tr>
                    <td colspan="2" style="padding:0;">
                        <table style="border:none;">
                            <tr>
                                <td style="border:none;{{ $i < count($familyItemsLeft) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    {{ $item['label'] }}
                                    @if (isset($item['specify']))
                                        <input type="text" wire:model="familyHistory.{{ $item['specify'] }}"
                                               style="width:45%;border-bottom:1px solid #9ca3af;display:inline-block;">
                                    @endif
                                </td>
                                <td class="check-col" style="border-left:1px solid #1f2937;{{ $i < count($familyItemsLeft) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    <input type="checkbox" wire:model="familyHistory.{{ $item['key'] }}">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" style="padding:0;">
                        @php $r = $familyItemsRight[$i]; @endphp
                        <table style="border:none;">
                            <tr>
                                <td style="border:none;{{ $i < count($familyItemsRight) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    {{ $r['label'] }}
                                    @if (isset($r['specify']))
                                        <input type="text" wire:model="familyHistory.{{ $r['specify'] }}"
                                               style="width:45%;border-bottom:1px solid #9ca3af;display:inline-block;">
                                    @endif
                                </td>
                                <td class="check-col" style="border-left:1px solid #1f2937;{{ $i < count($familyItemsRight) - 1 ? 'border-bottom:1px solid #1f2937;' : '' }}">
                                    <input type="checkbox" wire:model="familyHistory.{{ $r['key'] }}">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td class="lbl">Maintenance:</td>
                <td colspan="3"><input type="text" wire:model="familyHistory.maintenance"></td>
            </tr>

            {{-- SIGNATURE --}}
            <tr>
                <td colspan="2" class="center" style="font-weight:700;">SIGNATURE OVER PRINTED NAME/ DATE</td>
                <td colspan="2" class="center" style="font-weight:700;">NAME OF HEALTHCARE PROVIDER</td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="text" wire:model="signature_name" placeholder="Printed Name" style="margin-bottom:4px;">
                    <input type="date" wire:model="signature_date">
                </td>
                <td colspan="2">
                    <input type="text" wire:model="healthcare_provider_name">
                </td>
            </tr>
        </table>
    </form>

    {{-- Bottom action bar (mirrors the top one, handy after scrolling the long form) --}}
    <div class="flex justify-end items-center gap-3 mt-3 no-print">
        <a href="{{ route('forms.index') }}" wire:navigate
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-400 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
            Cancel
        </a>
        <button type="button" onclick="window.print()"
            class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
            Print Form
        </button>
        <button type="submit" form="student-health-form"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            {{ $isEdit ? 'Update' : 'Save' }}
        </button>
    </div>
</div>