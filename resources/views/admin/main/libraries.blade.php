{!! library('assets/plugin/bootstrap/bootstrap.bundle.min.js') !!}
{!! library('assets/plugin/toastify/toastify.min.js') !!}
{!! library('assets/plugin/gridjs/gridjs.min.js') !!}
{!! library('assets/plugin/ckeditor/ckeditor.min.js') !!}
@if (LaravelLocalization::getCurrentLocale() != 'en')
    {!! library('assets/plugin/ckeditor/translations/'. LaravelLocalization::getCurrentLocale() . '.js') !!}
@endif
{!! library('assets/plugin/apexcharts/apexcharts.min.js') !!}
{!! library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') !!}
{!! library('assets/js/admin/admin.min.js') !!}