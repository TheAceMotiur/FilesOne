/*
    Copyright AneonTech
    aneontech.help@gmail.com
*/

"use strict";

// Listen for clicks
document.addEventListener("click", function (event) {

    /* Change tab */
    if (event.target.matches("[data-tab]")) {
        event.preventDefault();
        var target = event.target.getAttribute('data-tab');
        const triggerEl = document
            .querySelector('#installation-tabs')
            .querySelector(`button[data-bs-target="#${target}"]`);
        bootstrap.Tab.getOrCreateInstance(triggerEl).show();
    }

    /* Start Installation */
    if (event.target.matches(".start-installation")) {
        event.preventDefault();
        const triggerEl = document
            .querySelector('#installation-tabs')
            .querySelector('button[data-bs-target="#pills-database"]');
        bootstrap.Tab.getOrCreateInstance(triggerEl).show();
    }

    /* Database connection */
    if (event.target.matches(".connect-database")) {
        event.preventDefault();
        connectDatabase();
    }

    /* Database OK, continue */
    if (event.target.matches(".continue-installation")) {
        event.preventDefault();
        const triggerEl = document
            .querySelector('#installation-tabs')
            .querySelector('button[data-bs-target="#pills-installation"]');
        bootstrap.Tab.getOrCreateInstance(triggerEl).show();
    }

});

// Listen for form submit
document.addEventListener("submit", function (event) {

    /* Step 1 - Database check */
    if (event.target.matches("#database-check")) {
        event.preventDefault();
        connectDatabase();
    }

    /* Step 2 - Settings */
    if (event.target.matches("#settings")) {
        event.preventDefault();
        settings();
    }

    /* Step 3 - Final */
    if (event.target.matches("#finish")) {
        event.preventDefault();
        install('requirements');
    }
});

// Show toast message
function toaster(text, type) {
    if (type === 1) {
        var bgColor = `${cssVars.color6}`;
    } else {
        var bgColor = `${cssVars.color5}`;
    }
    Toastify({
        text: text,
        duration: 3000,
        gravity: "bottom",
        position: "center",
        stopOnFocus: true,
        style: {
            background: bgColor,
            borderRadius: '1rem',
        },
    }).showToast();
}

function connectDatabase() {
    var formEl = document.querySelector('#database-check');
    var formAction = installVars.database;

    var stepCard = formEl.querySelector('.checker-card');
    var stepTitle = formEl.querySelector('.step-title');
    var stepOk = formEl.querySelector('.step-ok');

    stepCard.removeAttribute("style");
    stepTitle.removeAttribute("style");
    stepOk.removeAttribute("style");

    var submitButton = formEl.querySelector('button[type=submit]');
    submitButton.classList.add('d-none');

    var nextButton = formEl.querySelector('button[type=button]');
    nextButton.classList.add('d-none');

    var errors = formEl.querySelector('.errors');
    errors.innerHTML = '';

	var loader = formEl.querySelector('.loader');
	loader.classList.remove('d-none');

    formEl.querySelectorAll('input').forEach(input => {
        input.disabled = true;
        input.classList.add('disabled');
        input.classList.remove('missing');
    });
    formEl.querySelectorAll('select').forEach(select => {
        select.disabled = true;
        select.classList.add('disabled');
        select.classList.remove('missing');
    });

    const xhr = new XMLHttpRequest();
    xhr.open("POST", formAction, true);
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
        if (
            xhr.readyState === XMLHttpRequest.DONE 
            && xhr.status === 200
        ) {
            var result = JSON.parse(xhr.responseText);
            
            if (result.result) {

                loader.classList.add('d-none');

                errors.innerHTML = '';

                stepOk.classList.remove('d-none');
                stepTitle.style.color = "var(--color-6)";
                stepCard.style.borderColor = "var(--color-6)";
                stepOk.style.color = "var(--color-6)";

                submitButton.classList.add('d-none');
                nextButton.classList.remove('d-none');

            } else {

                loader.classList.add('d-none');

                stepTitle.style.color = "var(--color-5)";
                stepCard.style.borderColor = "var(--color-5)";
            
                formEl.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                    input.classList.remove('disabled');
                }); 
                formEl.querySelectorAll('select').forEach(select => {
                    select.disabled = false;
                    select.classList.remove('disabled');
                }); 

                submitButton.classList.remove('d-none');
                nextButton.classList.add('d-none');

                var html = '';
                if (result.text) {
                    html += `<p class="install-error mb-2" style="color: var(--color-5)">${result.text}</p>`;
                }
                if (result.errors) {
                    for (const [key, value] of Object.entries(result.errors)) {
                        html += `<p class="install-error mb-2" style="color: var(--color-5)">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <span>${value}</span>
                        </p>`;
                        if (formEl.querySelector(`[name=${key}]`) !== null) {
                            formEl.querySelector(`[name=${key}]`).classList.add('missing');
                        }
                    }
                }
            
                errors.classList.remove('d-none');
                errors.innerHTML = html;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData();
	params.append("type", document.querySelector('select[name=type]').value);
    params.append("hostname", document.querySelector('input[name=hostname]').value);
    params.append("database", document.querySelector('input[name=database]').value);
    params.append("username", document.querySelector('input[name=username]').value);
    params.append("password", document.querySelector('input[name=password]').value);
    xhr.send(params);
}

function settings() {
    var formEl = document.querySelector('#settings');
    var formAction = installVars.settings;

    var stepCard = formEl.querySelector('.checker-card');
    var stepTitle = formEl.querySelector('.step-title');
    var stepOk = formEl.querySelector('.step-ok');

    stepCard.removeAttribute("style");
    stepTitle.removeAttribute("style");
    stepOk.removeAttribute("style");

    var submitButton = formEl.querySelector('button[type=submit]');
    submitButton.classList.add('d-none');

    var nextButton = formEl.querySelector('button[type=button]');
    nextButton.classList.add('d-none');

    var errors = formEl.querySelector('.errors');
    errors.innerHTML = '';

	var loader = formEl.querySelector('.loader');
	loader.classList.remove('d-none');

    formEl.querySelectorAll('input').forEach(input => {
        input.disabled = true;
        input.classList.add('disabled');
        input.classList.remove('missing');
    });
    formEl.querySelectorAll('select').forEach(select => {
        select.disabled = true;
        select.classList.add('disabled');
        select.classList.remove('missing');
    });

    const xhr = new XMLHttpRequest();
    xhr.open("POST", formAction, true);
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
        if (
            xhr.readyState === XMLHttpRequest.DONE 
            && xhr.status === 200
        ) {
            var result = JSON.parse(xhr.responseText);
            
            if (result.result) {

                loader.classList.add('d-none');

                errors.innerHTML = '';

                stepOk.classList.remove('d-none');
                stepTitle.style.color = "var(--color-6)";
                stepCard.style.borderColor = "var(--color-6)";
                stepOk.style.color = "var(--color-6)";

                submitButton.classList.add('d-none');
                nextButton.classList.remove('d-none');

            } else {

                loader.classList.add('d-none');

                stepTitle.style.color = "var(--color-5)";
                stepCard.style.borderColor = "var(--color-5)";
            
                formEl.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                    input.classList.remove('d-none');
                }); 
                formEl.querySelectorAll('select').forEach(select => {
                    select.disabled = false;
                    select.classList.remove('d-none');
                }); 

                submitButton.classList.remove('d-none');
                nextButton.classList.add('d-none');

                var html = '';
                if (result.text) {
                    html += `<p class="install-error mb-2" style="color: var(--color-5)">${result.text}</p>`;
                }
                if (result.errors) {
                    for (const [key, value] of Object.entries(result.errors)) {
                        html += `<p class="install-error mb-2" style="color: var(--color-5)">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <span>${value}</span>
                        </p>`;
                        if (formEl.querySelector(`[name=${key}]`) !== null) {
                            formEl.querySelector(`[name=${key}]`).classList.add('missing');
                        }
                    }
                }
            
                errors.classList.remove('d-none');
                errors.innerHTML = html;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData();
    params.append("website-url", document.querySelector('input[name=website-url]').value);
	params.append("admin-email", document.querySelector('input[name=admin-email]').value);
    params.append("admin-password", document.querySelector('input[name=admin-password]').value);
    xhr.send(params);
}

function install(stepSelector) {

    var formEl = document.querySelector('#finish');
    var formAction = installVars.finish;
    var step = formEl.querySelector(`[data-step=${stepSelector}]`);

    var submitButton = formEl.querySelector('button[type=submit]');
    submitButton.classList.add('d-none');

    var errors = step.querySelector('.errors');
    errors.innerHTML = '';

	var loader = step.querySelector('.loader');
	loader.classList.remove('d-none');

    step.style.borderColor = "var(--color-3)";
    step.style.borderStyle = "dashed";

    const xhr = new XMLHttpRequest();
    xhr.open("POST", formAction, true);
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
        if (
            xhr.readyState === XMLHttpRequest.DONE 
            && xhr.status === 200
        ) {
            var result = JSON.parse(xhr.responseText);

            if (result.result) {

                loader.classList.add('d-none');
                step.style.borderColor = "var(--color-6)";

                if (result.finish) {

                    document.querySelector('[data-step=requirements]').classList.add('d-none');
                    document.querySelector('[data-step=database]').classList.add('d-none');
                    document.querySelector('[data-step=finish]').classList.add('d-none');

                    document.querySelector('.finish-text').classList.remove('d-none');
                    document.querySelector('.finish-text').innerHTML = result.text;
                    
                    submitButton.classList.add('d-none');

                } else {
                    install(result.step);
                }

            } else {
                loader.classList.add('d-none');
                step.style.borderColor = "var(--color-5)";
            
                submitButton.classList.remove('d-none');

                var html = '';
                if (result.text) {
                    html += `<p class="install-error mb-2" style="color: var(--color-5)">${result.text}</p>`;
                }
                if (result.errors) {
                    result.errors.forEach(error => {
                        html += `<p class="install-error mb-2" style="color: var(--color-5)">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <span>${error}</span>
                        </p>`;
                    });
                }
            
                errors.classList.remove('d-none');
                errors.innerHTML = html;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData();
    params.append("step", stepSelector);
    xhr.send(params);
}

// Check user device is mobile or not
function isMobile() {
    if (
        Math.min(window.screen.width, window.screen.height) < 768 
        || navigator.userAgent.indexOf("Mobi") > -1
    ) {
        return true;
    } else {
        return false;
    }
}

// Cursor animation
function handleCustomCursor() {
    const cursorInnerEl = document.querySelector('.cursor-follower-inner');
    const cursorOuterEl = document.querySelector('.cursor-follower-outer');
    let lastY, lastX;
    let magneticFlag = false;
    const links = document.querySelectorAll('a');
    document.addEventListener("mousemove", (event) => {
        if (!magneticFlag) {
            cursorOuterEl.style.transform = 
                'translate(' 
                    + event.clientX 
                    + 'px, ' 
                    + event.clientY 
                    + 'px' 
                    + ')';
        }
        if (cursorInnerEl.style.opacity = '0') {
            cursorInnerEl.style.opacity = '1';
        }
        cursorInnerEl.style.transform = 
            'translate(' 
                + event.clientX 
                + 'px, ' 
                + event.clientY 
                + 'px' 
                + ')';
        lastY = event.clientY;
        lastX = event.clientX;
    });
    links.forEach(link => {
        link.addEventListener("mouseenter", (e) => {
            //
        });
        link.addEventListener("mouseleave", (e) => {
            //
        });
    });
}
if (!isMobile()) {
    handleCustomCursor();
}