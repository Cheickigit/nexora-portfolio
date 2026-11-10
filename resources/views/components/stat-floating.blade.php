@props(['icon', 'value', 'label'])

<div class="holo w-full max-w-[250px]">
    <div class="holo-in flex items-center gap-4 p-5">
        <span class="ic-chip"><x-icon :name="$icon" /></span>
        <div>
            <div class="text-3xl font-bold leading-none text-white"><span data-count="{{ $value }}">0</span></div>
            <div class="mt-1.5 text-sm text-slate-300">{{ $label }}</div>
        </div>
    </div>
    <div class="hs"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
</div>