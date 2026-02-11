<script>
    document.addEventListener("DOMContentLoaded", () => {
        @if (session('sidebar-collapsed') == '1')
            collapseSidebar(1);
        @endif
    });
</script>