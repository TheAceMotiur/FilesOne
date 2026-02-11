@extends("user.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="api-card card">
            <div class="card-body">
                <p class="mb-2">{{ __('lang.your_api_token') }}</p>
                <p class="api-card-data user-select-all bg-container p-3 m-0">{{ userData('api_token') }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="api-card card">
            <div class="card-body">
                <p class="mb-2">
                    {{ __('lang.api_status') }}
                    <i 
                        class="form-help fa-solid fa-circle-question ms-1" 
                        data-bs-container="body"
                        data-bs-toggle="popover" 
                        data-bs-placement="bottom" 
                        data-bs-content="{{ __('lang.api_info') }}"></i>
                </p>
                <p class="api-card-data bg-container p-3 m-0">{{ $apiStatus ? __('lang.enable') : __('lang.disable') }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="api-card card">
            <div class="card-body">
                <p class="card-heading pb-3">{{ __('lang.upload_file') }}</p>
                <p class="mb-2">Endpoint</p>
                <p class="api-card-data user-select-all bg-container p-3 mb-3">{{ url('api/upload') }}</p>
                <p class="mb-2">Header</p>
                <p class="api-card-data user-select-all bg-container p-3 mb-3">token: {{ userData('api_token') }}</p>
                <p class="mb-2">Post Data</p>
<pre class="user-select-all bg-container p-3">{
    "file" : "XXX",
    "password" : "XXX",
    "auto-remove" : "XXX"
}
</pre>
                <p class="mb-2">Response (Successful)</p>
<pre class="user-select-all bg-container p-3">{
    "result" : true,
    "url" : "XXX"
}
</pre>
                <p class="mb-2">Response  (Failed)</p>
<pre class="user-select-all bg-container p-3">{
    "result" : false,
    "data" : "XXX"
}
</pre>
                <p class="mb-2">Response (Form Validation)</p>
<pre class="user-select-all bg-container p-3 mb-0">{
    "result": false,
    "errors": {
        "XXX": [
            "XXX"
        ],
        "YYY": [
            "YYY"
        ]
    }
}
</pre>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="api-card card">
            <div class="card-body">
                <p class="card-heading pb-3">{{ __('lang.upload_file_link') }}</p>
                <p class="mb-2">Endpoint</p>
                <p class="api-card-data user-select-all bg-container p-3 mb-3">{{ url('api/upload-link') }}</p>
                <p class="mb-2">Header</p>
                <p class="api-card-data user-select-all bg-container p-3 mb-3">token: {{ userData('api_token') }}</p>
                <p class="mb-2">Post Data</p>
<pre class="user-select-all bg-container p-3">{
    "file-link" : "XXX",
    "password" : "XXX",
    "auto-remove" : "XXX"
}
</pre>
                <p class="mb-2">Response (Successful)</p>
<pre class="user-select-all bg-container p-3">{
    "result" : true,
    "url" : "XXX"
}
</pre>
                <p class="mb-2">Response  (Failed)</p>
<pre class="user-select-all bg-container p-3">{
    "result" : false,
    "data" : "XXX"
}
</pre>
                <p class="mb-2">Response (Form Validation)</p>
<pre class="user-select-all bg-container p-3 mb-0">{
    "result": false,
    "errors": {
        "XXX": [
            "XXX"
        ],
        "YYY": [
            "YYY"
        ]
    }
}
</pre>
            </div>
        </div>
    </div>
</div>
@stop