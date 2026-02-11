<form action="{!! $url !!}" {!! $attributes !!}>
    @csrf
    <button type="submit" class="btn btn-color-1 w-100">
        {{ $label }}
    </button>
</form>
