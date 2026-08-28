<button 
    type="submit" 
    class="btn btn-submit" 
    onclick="this.disabled=true; this.innerHTML='<i class=\"fas fa-spinner fa-spin\"></i> Processing...'; this.closest('form').submit();">
    <i class="fas fa-save"></i> {{ $slot }}
</button>

<style>
    .btn-submit {
        transition: all 0.2s;
        position: relative;
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .fa-spinner {
        margin-right: 6px;
    }
</style>
