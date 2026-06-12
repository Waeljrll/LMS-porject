@props(['wireModel'])

@php
    $editorId = 'trix-' . str_replace(['.', '[', ']'], '-', $wireModel) . '-' . uniqid();
@endphp

<div wire:ignore>
    <input id="{{ $editorId }}" type="hidden" value="{{ $this->get($wireModel) ?? '' }}">

    <trix-editor id="trix-editor-{{ $editorId }}" input="{{ $editorId }}" x-data="{
        init() {
            const editor = document.getElementById('trix-editor-{{ $editorId }}');
            const input = document.getElementById('{{ $editorId }}');
            const wireModel = '{{ $wireModel }}';

            // Load initial value
            const initialValue = $wire.get(wireModel) || '';
            if (initialValue) {
                editor.editor.loadHTML(initialValue);
            }

            // Sync on change
            editor.addEventListener('trix-change', () => {
                $wire.set(wireModel, input.value);
            });

            // Watch for external changes using Livewire hooks
            Livewire.hook('message.processed', (message, component) => {
                if (component.id === $wire.id) {
                    const newValue = component.get(wireModel) || '';
                    if (input.value !== newValue) {
                        input.value = newValue;
                        editor.editor.loadHTML(newValue);
                    }
                }
            });
        }
    }"
        {{ $attributes->merge(['class' => 'trix-content form-control']) }}>
    </trix-editor>
</div>
