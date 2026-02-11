@extends("frontend.layouts.inner")
@section("content")
    @if ($permission)
        @include("frontend.file.sections.file")
    @else
        @include("frontend.file.sections.password") 
    @endif
    @include("frontend.file.sections.modals") 
@stop