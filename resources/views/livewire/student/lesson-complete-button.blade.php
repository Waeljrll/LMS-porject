<div>
    @if($isCompleted)
        <button class="btn btn-secondary fw-bold px-4 py-2" disabled>
            <i class="fas fa-check-double me-1"></i> Completed
        </button>
    @else
        <button wire:click="complete" class="btn btn-success fw-bold px-4 py-2" wire:loading.attr="disabled">

            <span wire:loading.remove wire:target="complete">
                <i class="fas fa-check-circle me-1"></i> Mark as Complete
            </span>

            <span wire:loading wire:target="complete">
                <i class="fas fa-spinner fa-spin me-1"></i> Saving...
            </span>

        </button>
    @endif
</div>
