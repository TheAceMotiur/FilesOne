@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div>
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-2 alert-dismissible fade show" role="alert">
                        <p class="m-0">{{ session('success') }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-1 alert-dismissible fade show" role="alert">
                        <p class="m-0">{{ session('error') }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="#" id="reports-table-search" method="post">
                    <div class="input-group bg-container justify-content-between p-3 mb-4">
                        <input 
                            type="text" 
                            name="filename" 
                            aria-label="File name" 
                            class="form-control mb-2 mb-xxl-0" 
                            placeholder="{{ __('lang.file_name') }}">
                        <input 
                            type="text" 
                            name="short-key" 
                            aria-label="Short Key" 
                            class="form-control mb-2 mb-xxl-0" 
                            placeholder="{{ __('lang.short_key') }}">
                        <input 
                            type="text" 
                            name="uploader" 
                            aria-label="Uploader" 
                            class="form-control mb-2 mb-xxl-0" 
                            placeholder="{{ __('lang.uploader') }}">
                        <select class="form-select mb-2 mb-xxl-0" name="disk" aria-label="Disk">
                            <option value="" selected>{{ __('lang.disk') }}</option>
                            @foreach ($storages as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option> 
                            @endforeach
                        </select>
                        <select class="form-select mb-2 mb-xxl-0" name="sort" aria-label="Sort">
                            <option value="" selected>{{ __('lang.sort') }}</option>
                            <optgroup label="{{ __('lang.upload_date') }}">
                                <option value="date_asc">{{ __('lang.asc') }}</option>
                                <option value="date_desc">{{ __('lang.desc') }}</option>
                            </optgroup>
                            <optgroup label="{{ __('lang.file_size') }}">
                                <option value="size_asc">{{ __('lang.asc') }}</option>
                                <option value="size_desc">{{ __('lang.desc') }}</option>
                            </optgroup>
                        </select>
                        <button class="btn btn-color-1 mb-2 mb-md-0" type="submit">{{ __('lang.filter') }}</button>
                        <button class="btn btn-color-2" id="reset-reports" type="button">{{ __('lang.reset') }}</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="table-custom" id="reports-table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('lang.report_date') }}</th>
                                    <th scope="col">{{ __('lang.file_name') }}</th>
                                    <th scope="col">{{ __('lang.short_key') }}</th>
                                    <th scope="col">{{ __('lang.size') }}</th>
                                    <th scope="col">{{ __('lang.uploader') }}</th>
                                    <th scope="col">{{ __('lang.storage') }}</th>
                                    <th scope="col">{{ __('lang.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="reports-table-body"></tbody>
                            <tfoot class="text-center">
                                <tr>
                                    <td colspan="7">
                                        <nav id="reports-pagination">
                                            <ul class="pagination justify-content-center"></ul>
                                        </nav>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/files/reports/delete') }}" class="delete-modal-form">
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-4x mb-3"></i>
                    <h4>{{ __('lang.modal_question') }}</h4>
                    <p class="modal-text m-0">{{ __('lang.modal_text') }}</p>
                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-color-4" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
                    <button type="submit" class="btn btn-color-1 delete-row-modal" data-url="">{{ __('lang.delete') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="offcanvas offcanvas-content offcanvas-end" tabindex="-1" id="report-details">
    <div class="offcanvas-header">
        <button type="button" class="action-button btn btn-sm" data-bg="white" data-bs-dismiss="offcanvas"
            aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="detail-card p-3 mt-3">
            <p class="title">{{ __('lang.report_info') }}</p>
            <p class="content d-flex">
                {{ __('lang.reported_by') }}:<span class="ms-2" id="reported-by"></span>
            </p>
            <p class="content d-flex m-0">
                {{ __('lang.reason') }}:<span class="ms-2" id="reason"></span>
            </p>
        </div>
    </div>
</div>
@stop