/*
    Copyright AneonTech
    aneontech.help@gmail.com
*/

"use strict";

var totalFiles = 0;
var currentFile = 0;
var queuedFiles = [];
var uploadedFile = 0;

// Listen for clicks
document.addEventListener("click", function (event) {

    // Start dropzone
    if (event.target.matches("#upload-files")) {
        queuedFiles = myDropzone.getQueuedFiles();
        if (queuedFiles.length > 0) {
            totalFiles = queuedFiles.length;
            currentFile = 0;

            if (document.querySelectorAll('.dz-remove') !== null) {
                document.querySelectorAll('.dz-remove').forEach(dzRemove => {
                    dzRemove.remove();
                });
            }

            setTimeout(function () {
                myDropzone.processFile(queuedFiles[currentFile]);
            }, 250);
        } else {
            toaster(langVars.no_file_selected, 0); 
        }
    }

    // Open uploader settings modals      
    if (event.target.matches("[data-modal]")) {
    
        var target = event.target.getAttribute("data-modal");
        const myModal = new bootstrap.Modal(`#${target}`, {
            keyboard: false,
            backdrop: "static"
        });
        myModal.toggle();
    }

    // Close and reset uploader settings modals    
    if (event.target.matches(".modal-cancel")) {
        if (event.target.getAttribute('data-modal-id') !== null) {
            var modalId = event.target.getAttribute('data-modal-id');
            var modal = document.querySelector(`#${modalId}`);
            modal.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            modal.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            var save = document.querySelector('.save-settings');
            var fileId = save.getAttribute('data-id');
            var currentForm = save.getAttribute('data-current-form');
            var modalOpener = document
                .querySelector(`.dropzone-settings[data-id="${fileId}"]`);
            
            var formdata = new FormData(document.querySelector(currentForm));
            formdata = serialize(formdata);

            if (modalOpener) {
                modalOpener.setAttribute('data-form', JSON.stringify(formdata));
            }
                
        }
    }
    
    // Copy text to clipboard
    if (event.target.matches(".copy-this > span")) {
        event.preventDefault();
        copyThis(event.target.parentElement.getAttribute('data-copy'));
        setTimeout(() => {
            tooltipHide();
        }, 250);
    }

    // Download all files
    if (event.target.matches(".file-dropdown-download")) {
        event.preventDefault();
        event.target.querySelector('.spinner-border')
            .classList.remove('d-none');
        event.target.disabled = true;
        downloadAll(
            event.target.getAttribute('data-action-zip'),
            event.target.getAttribute('data-keys')
        )
    }

    // Download single file
    if (event.target.matches(".download-file-single")) {
        event.preventDefault();
        event.target.querySelector('i')
            .classList.add('d-none');
        event.target.querySelector('.spinner-border')
            .classList.remove('d-none');
        event.target.disabled = true;
        var action = document.querySelector('.file-dropdown-download')
            .getAttribute('data-action');
        downloadSingle(
            event.target,
            action,
            event.target.getAttribute('data-key')
        );
        setTimeout(() => {
            tooltipHide();
        }, 250);
    }

    // Download single file
    if (event.target.matches(".download-file-single > span")) {
        event.preventDefault();
        event.target.querySelector('i')
            .classList.add('d-none');
        event.target.parentElement.querySelector('.spinner-border')
            .classList.remove('d-none');
        event.target.parentElement.disabled = true;
        var action = document.querySelector('.file-dropdown-download')
            .getAttribute('data-action');
        downloadSingle(
            event.target.parentElement,
            action,
            event.target.parentElement.getAttribute('data-key')
        );
        setTimeout(() => {
            tooltipHide();
        }, 250);
    }

    // Clear (delete from dropzone) single file
    if (event.target.matches(".clear-file")) {
        event.preventDefault();
        var files = myDropzone.files;
        var target = event.target.getAttribute('data-file-id');
        files.forEach(file => {
            if (file.upload.uuid == target) {
                myDropzone.removeFile(file);
            }
        });
        setTimeout(() => {
            tooltipHide();
        }, 250);
    }

    // Clear (delete from dropzone) single file
    if (event.target.matches(".clear-file > span")) {
        event.preventDefault();
        var files = myDropzone.files;
        var target = event.target.parentElement.getAttribute('data-file-id');
        files.forEach(file => {
            if (file.upload.uuid == target) {
                myDropzone.removeFile(file);
            }
        });
        setTimeout(() => {
            tooltipHide();
        }, 250);
    }

    // Change output (format) value
    if (event.target.matches(".output-dropdown-item")) {
        event.preventDefault();
        var value = event.target.getAttribute('data-format');
        var dropdown = event.target
            .closest(".dropzone-output-selector")
            .querySelector('.output-button');
        if (dropdown && value.length > 0) {
            dropdown.setAttribute('data-format', value);
            dropdown.innerHTML = value;
        }
    }
    
    // Pass file id to settings modal
    if (event.target.matches(".dropzone-settings > span")) {
        var modalId = event.target.parentElement.getAttribute('data-bs-target');
        var modal = document.querySelector(modalId);
        var saveButton = modal.querySelector('.save-settings');
        saveButton.setAttribute(
            'data-id',
            event.target.parentElement.getAttribute('data-id')
        );

        var formData = event.target.parentElement.getAttribute('data-form');
        if (formData !== null) {
            var formArray = JSON.parse(formData);
            for (const [key, value] of Object.entries(formArray)) {
                if (modal.querySelector(`input[name="${key}"]`) !== null) {
                    modal.querySelector(`input[name="${key}"]`).value = value;
                }
                if (modal.querySelector(`select[name="${key}"]`) !== null) {
                    modal.querySelector(`select[name="${key}"]`).value = value;
                }
            }
        }
    }

    // Save settings (modal) formdata
    if (event.target.matches(".save-settings")) {

        var fileId = event.target.getAttribute('data-id');

        var currentForm = event.target.getAttribute('data-current-form');
        var modalOpener = document
            .querySelector(`.dropzone-settings[data-id="${fileId}"]`);
        
        var formdata = new FormData(document.querySelector(currentForm));
        formdata = serialize(formdata);

        if (modalOpener) {
            modalOpener.setAttribute('data-form', JSON.stringify(formdata));
        }
    }

});

document.addEventListener("submit", function (event) {

    // Prevent default submit func. for dropzone
    if (event.target.matches("#file-upload-form")) {
        event.preventDefault();
    }

    // Prevent default submit func. for dropzone
    if (event.target.matches("#add-file-url-form")) {
        event.preventDefault();
        addFileUrl();
    }
    
});

document.addEventListener("paste", function (event) {
    // Copy-paste a file to dropzone
    const items = (event.clipboardData || event.originalEvent.clipboardData).items;
    for (const item of Object.values(items)) {
        if (item.kind === 'file') {
            // adds the file to your dropzone instance
            myDropzone.addFile(item.getAsFile())
        }
    }
});

document.addEventListener("show.bs.modal", function (event) {

    // Hide tooltips when settings modal opens
    if (event.target.matches("#settings-modal")) {
        setTimeout(() => {
            tooltipHide();
        }, 250);
    }

});

document.addEventListener("hidden.bs.modal", function (event) {

    // Reset form
    if (event.target.matches("#from-link-modal")) {
        var input = document.querySelector("#add-file-url-form input");
        var button = document.querySelector("#add-file-url-form button");
        var error = document.querySelector("#add-file-url-form .url-input-error");
        input.classList.remove('missing');
        input.readOnly = false;
        input.value = '';
        button.disabled = false;
        button.querySelector(".spinner-border").classList.add('d-none');
        error.classList.add('d-none');
        error.innerHTML = '';
    }

    // Reset form
    if (event.target.matches("#settings-modal")) {
        var form = document.querySelector("#settings-form");
        form.reset();
    }

});

function acceptedFormats() {
    if (sysVars.accepted_files !== null) {
        var types = '';
        for (let [index, type] of sysVars.accepted_files.entries()) {
            if (index === sysVars.accepted_files.length - 1) {
                types += `.${type}`;
            } else {
                types += `.${type},`;
            }
        }
    }
    return types ?? '';
}

function dropzoneItemTemplate() {
    var previewNode = document.querySelector('.dropzone .dropzone-previews-inner');
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
    return previewTemplate;
}

Dropzone.autoDiscover = false;
var myDropzone;
function dropzoneInit() {
    var dropzoneEl = document.querySelector('.dropzone-uploader');
    var formats = acceptedFormats();
    var previewTemplate = dropzoneItemTemplate();
    myDropzone = new Dropzone(".dropzone-uploader", {
        url: sysVars.upload_url,
        paramName: "file",
        previewTemplate: previewTemplate,
        addRemoveLinks: false,
        autoProcessQueue: false,
        uploadMultiple: false,
        parallelUploads: 1,
        acceptedFiles: formats !== null && formats
            ? formats
            : null,
        maxFiles: sysVars.max_file_count,
        maxFilesize: sysVars.max_file_size,
        withCredentials: true,
        previewsContainer: '.dropzone-previews',
        clickable: '.dropzone-select',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="token"]').content
        },
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                var submitButton = document.querySelector('#upload-files');
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
                submitButton.querySelector('.spinner-border').classList.remove('d-none');
                submitButton.querySelector('.upload-button-text').classList.add('d-none');
                var fileSelector = document.querySelector('.file-dropdown-add > button');
                fileSelector.classList.add('disabled');
                fileSelector.disabled = true;
                if (file.previewElement.querySelector('.output-button') !== null) {
                    formData.append(
                        'type', 
                        file.previewElement.querySelector('.output-button').getAttribute('data-format')
                    );
                }
                var fileSettings = file.previewElement
                    .querySelector('.dropzone-settings')
                    .getAttribute('data-form');
                if (fileSettings) {
                    jsonObjectToFormaData(
                        file.previewElement.querySelector('.dropzone-settings').getAttribute('data-form'),
                        formData
                    );
                }

            });
            this.on("addedfile", function(file) {

                var count = myDropzone.files.length;
                filesScroll(count);

                if (count > 0 ) {
                    document.querySelector('.file-dropdown-add')
                        .classList.remove('d-none');
                    document.querySelector('.upload-files')
                        .classList.remove('d-none');
                } else {
                    document.querySelector('.file-dropdown-add')
                        .classList.add('d-none');
                    document.querySelector('.upload-files')
                        .classList.add('d-none');
                }

                document.querySelector('.dropzone .dropzone-intro')
                    .classList.add('d-none');
                document.querySelector('.dropzone .dropzone-scroll')
                    .classList.remove('d-none');
                document.querySelector('.dropzone .dropzone-previews')
                    .classList.remove('d-none');

                file.previewElement.querySelector('.dropzone-file-info')
                    .setAttribute('data-id',generateId());
                file.previewElement.querySelector('.dropzone-settings')
                    .setAttribute('data-id',generateId());

                if (count > this.options.maxFiles) {
                    this.removeFile(file);
                    toaster(`${langVars.file_max}`,0);
                    return false;
                }

                var fileNameArr = fileNameAndExt(file.name);
                var newName = `${fileNameArr[0]}.${fileNameArr[1]}`;
                file.upload.filename = newName;
                file.previewElement.querySelector('[data-dz-name]').innerHTML = `${fileNameShort(fileNameArr[0])}.${fileNameArr[1]}`;
                file.previewElement.querySelector('.file-extension > span').innerHTML = fileNameArr[1].toUpperCase();

                if (dropzoneEl.getAttribute('data-modal-bs') !== null) {
                    file.previewElement
                        .querySelector('.dropzone-settings')
                        .setAttribute(
                            'data-bs-target',
                            dropzoneEl.getAttribute('data-modal-bs')
                        );
                } else {
                    file.previewElement
                        .querySelector('.dropzone-settings')
                        .setAttribute('data-bs-target','#settings-modal');
                }

                setTimeout(() => {
                    tooltipHide();
                }, 250);
            });
            this.on("removedfile", function(file) {
                if (file.previewElement.querySelector('.copy-this') !== null) {
                    uploadedFile = uploadedFile - 1;
                    var fileUrl = file.previewElement
                        .querySelector('.copy-this')
                        .getAttribute('data-copy');
                    copyButton(fileUrl, 'remove');
                }
                if (file.previewElement.querySelector('.download-file-single') !== null) {
                    var fileKey = file.previewElement
                        .querySelector('.download-file-single')
                        .getAttribute('data-key');
                    downloadButton(fileKey, 'remove');
                }
                
                file.previewElement.remove();
                var count = myDropzone.files.length;
                filesScroll(count);
                
                if (count > 0 ) {
                    document.querySelector('.file-dropdown-add')
                        .classList.remove('d-none');
                    document.querySelector('.upload-files')
                        .classList.remove('d-none');
                    if (count == 1) {
                        document.querySelectorAll('.file-dropdown-button')
                            .forEach(el => {
                                el.classList.add('d-none');
                            });
                    }
                } else {
                    document.querySelector('.file-dropdown-add')
                        .classList.add('d-none');
                    document.querySelector('.upload-files')
                        .classList.add('d-none');
                    document.querySelector('.dropzone .dropzone-scroll')
                        .classList.add('d-none');
                    document.querySelector('.dropzone .dropzone-previews')
                        .classList.add('d-none');

                    document.querySelector('.dropzone .dropzone-intro')
                        .classList.remove('d-none');
                    document.querySelectorAll('.file-dropdown-button')
                        .forEach(el => {
                            el.classList.add('d-none');
                        });
                }
                if (file.previewElement.querySelector('.delete-tooltip') !== null) {
                    const tooltip = bootstrap.Tooltip.getOrCreateInstance(
                        file.previewElement.querySelector('.delete-tooltip')
                    );
                    tooltip.hide();
                }
                setTimeout(() => {
                    tooltipHide();
                }, 250);
            });
            this.on("uploadprogress", function(file, progress) {
                dropzoneEl.querySelectorAll('.output-button').forEach(el => {
                    el.classList.add('disabled');
                    el.disabled = true;
                });
                dropzoneEl.querySelectorAll('.dropzone-settings').forEach(el => {
                    el.classList.add('disabled');
                    el.setAttribute('aria-disabled','true');
                });
                dropzoneEl.querySelectorAll('.dropzone-delete').forEach(el => {
                    el.classList.add('disabled');
                    el.setAttribute('aria-disabled','true');
                });
                var progressBar = file.previewElement
                    .querySelector('.dropzone-progress-bar');
                progressBar.classList.remove('d-none');
                progressBar.querySelector('.progress > .progress-bar')
                    .style.width = progress + "%";
                if (progress > 99) {
                    progressBar.classList.add('d-none');
                    file.previewElement.querySelector('.dropzone-processing')
                        .classList.remove('d-none');
                }
            });
            this.on("complete", function(file) {
                file.previewElement.querySelector('.dropzone-processing')
                    .classList.add('d-none');
                currentFile = currentFile + 1;

                if (
                    queuedFiles[currentFile] != null 
                    && queuedFiles[currentFile] != undefined 
                    && totalFiles >= currentFile + 1
                ) {
                    myDropzone.processFile(queuedFiles[currentFile]);
                }
            });
            this.on("queuecomplete", function(file, result) {
                var fileSelector = document.querySelector('.file-dropdown-add > button');
                fileSelector.classList.remove('disabled');
                fileSelector.disabled = false;
                var submitButton = document.querySelector('#upload-files');
                submitButton.classList.remove('disabled');
                submitButton.disabled = false;
                submitButton.querySelector('.spinner-border').classList.add('d-none');
                submitButton.querySelector('.upload-button-text').classList.remove('d-none');
                if (uploadedFile > 1) {
                    document.querySelectorAll('.file-dropdown-button')
                        .forEach(el => {
                            el.classList.remove('d-none');
                        });
                }
            });
            this.on("success", function(file, result) {
                try {
                    if (result.result) {
                        dropzoneButtons(file,result.url,result.key);
                        uploadedFile = uploadedFile + 1;
                        copyButton(result.url, 'add');
                        downloadButton(result.key, 'add');
                    } else {
                        file.previewElement.querySelector('.dropzone-delete')
                            .classList.remove('disabled');
                        file.previewElement.querySelector('.dropzone-delete')
                            .setAttribute('aria-disabled','false');
                        if (result.errors) {
                            var errors = Object.values(result.errors);
                            let errorsHtml = document.createElement("div");
                            let errs = '';
                            errors.forEach(error => {
                                errs += `<div>
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    <span data-dz-errormessage>${error}</span>
                                </div>`;
                            });
                            errorsHtml.innerHTML = errs;

                            var errorsContainer = file.previewElement
                                .querySelector('.dropzone-error');
                            errorsContainer.innerHTML = '';
                            errorsContainer.append(errorsHtml);
                            errorsContainer.classList.remove('d-none');

                        } else {
                            var errorsContainer = file.previewElement
                                .querySelector('.dropzone-error');
                            errorsContainer.innerHTML = result.data;
                            errorsContainer.classList.remove('d-none');
                        }
                    }
                } catch (e) {
                    console.log(e);
                    var errorsContainer = file.previewElement
                        .querySelector('.dropzone-error');
                    errorsContainer.innerHTML = langVars.error;
                    errorsContainer.classList.remove('d-none');
                }
            });
            this.on("error", function(file, message) {
                var fileContainer = file.previewElement;
                var error = fileContainer.querySelector('.dropzone-error');
                error.innerHTML = message.message !== undefined ? message.message : message;
                error.classList.remove('d-none');
                if (fileContainer.querySelector('.dropzone-output') !== null) {
                    fileContainer.querySelector('.dropzone-output').remove();
                }
                if (fileContainer.querySelector('.dropzone-settings') !== null) {
                    fileContainer.querySelector('.dropzone-settings').remove();
                }
                if (fileContainer.querySelector('.dropzone-delete') !== null) {
                    fileContainer.querySelector('.dropzone-delete')
                        .classList.remove('disabled');
                    fileContainer.querySelector('.dropzone-delete')
                        .setAttribute('aria-disabled','false');
                }
            });
        }
    });
}

// Add file with url
function addFileUrl() {
    var input = document.querySelector("#add-file-url-form input");
    input.classList.remove("missing");
    input.readOnly = true;
    var button = document.querySelector("#add-file-url-form button");
    button.disabled = true;
    button.querySelector(".spinner-border").classList.remove('d-none');
    var error = input.parentElement.parentElement.querySelector('.url-input-error');
    error.classList.add('d-none');
    error.innerHTML = '';
    error.classList.remove('dropzone-error');

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        document.querySelector("#add-file-url-form").action, 
        true
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(langVars.error, 0);
    });
    xhr.addEventListener("abort", () => {
        toaster(langVars.cancelled, 0);
    });
    xhr.addEventListener("load", () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                error.classList.remove('dropzone-error');
                dropzoneAddFile(input,result.fileName,result.fileUrl);
            } else {
                input.readOnly = false;
                button.disabled = false;
                button.querySelector(".spinner-border").classList.add('d-none');
                input.classList.add("missing");
                if (result.errors) {
                    var entries = Object.entries(result.errors);
                    for (const [key, value] of entries) {
                        error.innerHTML = value;
                        error.classList.remove('dropzone-success');
                        error.classList.add('dropzone-error');
                        error.classList.remove('d-none');
                    }
                } else {
                    error.innerHTML = result.data;
                    error.classList.remove('dropzone-success');
                    error.classList.add('dropzone-error');
                    error.classList.remove('d-none');
                }
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData();
    params.append(input.name,input.value);
    xhr.send(params);
}

// Download all files
function downloadAll(action, keys) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", action, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(langVars.error, 0);
    });
    xhr.addEventListener("abort", () => {
        toaster(langVars.cancelled, 0);
    });
    xhr.addEventListener("load", () => {
        document.querySelector('.file-dropdown-download .spinner-border')
            .classList.add('d-none');
        document.querySelector('.file-dropdown-download').disabled = false;

        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                const url = result.data;
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', '');
                document.body.appendChild(link);
                link.click();
            } else {
                toaster(result.data, 0);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    const params = new FormData();
    params.append('keys', keys);
    xhr.send(params);
}

// Download single file
function downloadSingle(button, action, keys) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", action, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(langVars.error, 0);
    });
    xhr.addEventListener("abort", () => {
        toaster(langVars.cancelled, 0);
    });
    xhr.addEventListener("load", () => {
        button.querySelector('.spinner-border')
            .classList.add('d-none');
        button.querySelector('i')
            .classList.remove('d-none');
        button.disabled = false;

        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                const url = result.data;
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', '');
                document.body.appendChild(link);
                link.click();
            } else {
                toaster(result.data, 0);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    const params = new FormData();
    params.append('keys', keys);
    xhr.send(params);
}

// Add buttons to dropzone elements
function dropzoneButtons(file,url,key) {
    var div = document.createElement("div");
    div.classList.add(
        'd-flex',
        'gap-1',
        'gap-md-2',
        'align-items-center',
        'justify-content-sm-end',
        'flex-wrap',
        'flex-sm-nowrap'
    );
    div.innerHTML = `<a 
        href="${url}" 
        class="open-file result-button btn px-3" 
        target="_blank">
        Open
    </a>
    <a href="#" class="copy-this" data-copy="${url}" aria-label="Copy">
        <span 
            class="result-button"
            data-bs-toggle="tooltip" data-bs-placement="top" 
            data-bs-custom-class="custom-tooltip" data-bs-title="Copy" 
            data-bs-trigger="hover">
            <i class="fa-regular fa-copy fa-lg pe-none"></i>
        </span>
    </a>
    <a href="#" class="download-file-single" data-key="${key}">
        <span 
            class="result-button"
            data-bs-toggle="tooltip" data-bs-placement="top" 
            data-bs-custom-class="custom-tooltip" data-bs-title="Download" 
            data-bs-trigger="hover">
            <i class="fa-regular fa-circle-down fa-lg pe-none"></i>
            <span class="spinner-border spinner-border-sm pe-none d-none" 
                role="status"></span>
        </span>
    </a>
    <a href="#" class="clear-file" data-file-id="${file.upload.uuid}">
            <span 
                class="result-button delete-tooltip"
                data-bs-toggle="tooltip" data-bs-placement="top" 
                data-bs-custom-class="custom-tooltip" data-bs-title="Clear" 
                data-bs-trigger="hover">
            <i class="fa-regular fa-circle-xmark fa-lg pe-none"></i>
        </span>
    </a>`;

    var panel = file.previewElement.querySelector('.dropzone-toolbar');
    panel.innerHTML = '';
    panel.append(div);
    tooltipInit();
}

// Add files to dropzone manually
function dropzoneAddFile(input,filename,fileurl) {
    var input = document.querySelector("#add-file-url-form input");
    var button = document.querySelector("#add-file-url-form button");
    const xhr = new XMLHttpRequest();
    xhr.open("POST", '/fetch-file', true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(langVars.error, 0);
    });
    xhr.addEventListener("abort", () => {
        toaster(langVars.cancelled, 0);
    });
    xhr.addEventListener("load", () => {
        input.readOnly = false;
        button.disabled = false;
        button.querySelector(".spinner-border").classList.add('d-none');
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {

                var blob = base64ToBlob(result.file);
                var file = new File(blob, filename, {type: result.mime});
                myDropzone.addFile(file);

                var error = input.parentElement.parentElement.querySelector('.url-input-error');
                error.innerHTML = result.data;
                error.classList.add('dropzone-success');
                error.classList.remove('d-none');
                input.value = '';

                setTimeout(() => {
                    closeUrlModel();
                }, 750);
                setTimeout(() => {
                    error.innerHTML = '';
                }, 1250);
            } else {
                var error = input.parentElement.parentElement.querySelector('.url-input-error');
                error.innerHTML = `${result.data} (Error:${result.error})`;
                error.classList.add('dropzone-error');
                error.classList.remove('d-none');
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    const params = new FormData();
    params.append('url', fileurl);
    xhr.send(params);
}

// Close and reset add file with url modal
function closeUrlModel() {
    const modal = bootstrap.Modal.getOrCreateInstance(
        document.querySelector('#from-link-modal')
    );
    modal.hide();
}

// Generate random id for dropzone items
function generateId() {
    let result = '';
    const length = 16;
    const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    const cLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * cLength));
    }

    return result;
}

// Get user screen resolution as bootstrap 5 type
function getViewport() {
    const width = Math.max(
      document.documentElement.clientWidth,
      window.innerWidth || 0
    )

    // xs - size less than or 575.98
    if (width <= 575.98) return 'xs'
    // sm - size between 576 - 767.98
    if (width >= 576 && width <= 767.98) return 'sm'
    // md - size between 768 - 991.98
    if (width >= 768 && width <= 991.98) return 'md'
    // lg - size between 992 - 1199.98
    if (width >= 992 && width <= 1199.98) return 'lg'
    // xl - size between 1200 - 1399.98
    if (width >= 1200 && width <= 1399.98) return 'xl'

    // xxl- size greater than 1399.98
    return 'xxl'
}

// Update links on the copy button
function copyButton(filelink, action) {
    if (action == 'add') {
        var button = document.querySelector('.file-dropdown-copy');
        var current = button.getAttribute('data-copy');
        if (current) {
            var currentArr = current.split(",");
            currentArr.push(filelink);
            var currentStr = currentArr.toString();
        } else {
            var currentStr = filelink;
        }
        button.setAttribute('data-copy', currentStr);
    } else if (action == 'remove') {
        var button = document.querySelector('.file-dropdown-copy');
        var current = button.getAttribute('data-copy');
        var currentArr = current.split(",");
        removeFromArr(currentArr,filelink);
        for (let i = currentArr.length - 1; i >= 0; i--) {
            if (!currentArr[i]) {
                currentArr.splice(i, 1);
            }
        }
        var currentStr = currentArr.toString();

        button.setAttribute('data-copy', currentStr);
    }

    return true;
}

// Update links on the download button
function downloadButton(key,action) {
    if (action == 'add') {
        var button = document.querySelector('.file-dropdown-download');
        var current = button.getAttribute('data-keys');
        if (current) {
            var currentArr = current.split(",");
            currentArr.push(key);
            var currentStr = currentArr.toString();
        } else {
            var currentStr = key;
        }
        button.setAttribute('data-keys', currentStr);
    } else if (action == 'remove') {
        var button = document.querySelector('.file-dropdown-download');
        var current = button.getAttribute('data-keys');
        var currentArr = current.split(",");
        removeFromArr(currentArr,key);
        for (let i = currentArr.length - 1; i >= 0; i--) {
            if (!currentArr[i]) {
                currentArr.splice(i, 1);
            }
        }
        var currentStr = currentArr.toString();

        button.setAttribute('data-keys', currentStr);
    }

    return true;
}

// JS array remove element
function removeFromArr(a, ele) {
    a.forEach((item, index) => {
        if (item === ele) {
            a.splice(index, 1);
        }
    });
    return a;
}

function dropdownScroll(dropdown) {
    var list = dropdown.querySelector('.output-dropdown-list');
    var height = (37 * 3) + 16;
    list.style.height = `${height}px`;

    OverlayScrollbars(
        list, 
        {
            overflow: {
                x: 'hidden',
                y: 'scroll',
            },
            scrollbars: {
                autoHide: 'never',
            },
        }
    );
}

function filesScroll(fileCount) {
    var list = document.querySelector('.dropzone-scroll');
    if (fileCount > 3) {
        var height = 132;
        var totalHeight = (fileCount > 3 ? 3 : fileCount) * height;
        list.style.height = `${totalHeight}px`;

        var filesScroller = OverlayScrollbars(
            list, 
            {
                overflow: {
                    x: 'hidden',
                    y: 'scroll',
                },
                scrollbars: {
                    autoHide: 'never',
                },
            }
        );
        list.classList.add('pe-3');
    } else {
        if (filesScroller !== undefined) {
            filesScroller.update();
        }
        list.style.height = `auto`;
        list.classList.remove('pe-3');
    }
}

function base64ToBlob(
    base64, 
    contentType = "",
    sliceSize = 512
) {
    const byteCharacters = atob(base64.split(",")[1]);
    const byteArrays = [];

    for (let offset = 0; offset < byteCharacters.length;
        offset += sliceSize) {
        const slice = byteCharacters.slice(
            offset, offset + sliceSize);

        const byteNumbers = new Array(slice.length);
        for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    return byteArrays;
}

// Serialize formdata to object
function serialize(data) {
	let obj = {};
	for (let [key, value] of data) {
		if (obj[key] !== undefined) {
			if (!Array.isArray(obj[key])) {
				obj[key] = [obj[key]];
			}
			obj[key].push(value);
		} else {
			obj[key] = value;
		}
	}
	return obj;
}

// Convert object to formdata
const jsonObjectToFormaData = (data, formData) => {
    convertRecursively(JSON.parse(data), formData);
    return formData;
};
const convertRecursively = (data, formData, parentKey = '') => {
    // array
    if (Array.isArray(data)) {
        data.forEach((item) => convertRecursively(item, formData, parentKey));
        return;
    }
    // file
    if (data instanceof File) {
        formData.append(parentKey, data);
        return;
    }
    // date
    if (data instanceof Date) {
        formData.append(parentKey, data.toISOString());
        return;
    }
    // object
    if ((data instanceof Object) && !(data instanceof File) && !(data instanceof Blob) && !(data instanceof Date)) {
        Object.keys(data).forEach(key => convertRecursively(data[key], formData, parentKey ? `${parentKey}[${key}]` : key));
        return;
    }
    // default
    const value = data == null ? '' : data.toString();
    formData.append(parentKey, value);
};

// Get file name and ext
function fileNameAndExt(filename) {
    const lastDot = filename.lastIndexOf('.');
    const name = filename.slice(0, lastDot);
    const extension = filename.slice(lastDot + 1);

    return [name,extension.toLowerCase()];
}

// Shorten file name
function fileNameShort(filename) {
    let length = filename.length;
    return length > 6
        ? `${filename.substring(0, 3)}...${filename.substring(length-3)}`
        : filename;
}

// Hide all tooltips
function tooltipHide() {
    if (document.querySelector('[data-bs-toggle="tooltip"]') !== null) {
        const tooltipTriggerList = document
            .querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(element => {
            const tooltip = bootstrap.Tooltip.getOrCreateInstance(element)
            tooltip.hide();
        });
    }
}

