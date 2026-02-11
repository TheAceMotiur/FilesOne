/*
    Copyright AneonTech
    aneontech.help@gmail.com
*/

"use strict";
var {
    OverlayScrollbars, 
    ScrollbarsHidingPlugin, 
    SizeObserverPlugin, 
    ClickScrollPlugin  
} = OverlayScrollbarsGlobal;

// Define variables
var menuDiv;

// Listen for clicks
document.addEventListener("click", function (event) {

    // Make website fullscreen
    if (event.target.matches(".fullscreen")) {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            event.target.innerHTML =
                '<i class="fa-solid fa-compress fa-fw"></i>';
        } else if (document.exitFullscreen) {
            document.exitFullscreen();
            event.target.innerHTML 
                = '<i class="fa-solid fa-expand fa-fw"></i>';
        }
    }
    
    // Sidebar menu init
    if (event.target.matches(".dashboard-sidebar .nav-link")) {
        if (
            !document.querySelector('.dashboard-sidebar')
                .classList.contains('sm')
        ) {
            if (event.target.nextElementSibling) {
                event.preventDefault();
                if (
                    event.target.nextElementSibling
                        .classList.contains('show')
                ) {
                    event.target
                        .querySelector('.fa-angle-down')
                        .classList.remove('rotated-arrow');
                    var collapse = new bootstrap
                        .Collapse(event.target.nextElementSibling);
                    collapse.hide();
                } else {
                    var collapse = new bootstrap
                        .Collapse(event.target.nextElementSibling);
                    collapse.show();
                    event.target
                        .querySelector('.fa-angle-down')
                        .classList.add('rotated-arrow');
                    if (
                        event.target.parentElement.parentElement
                            .querySelector('.submenu.show')
                    ) {
                        new bootstrap.Collapse(
                            event.target.parentElement.parentElement
                                .querySelector('.submenu.show')
                        );
                    }
                }
            }
        } else {
            event.preventDefault();
            layoutChanger();
        }
    }

    // Change sidebar menu width
    if (event.target.matches(".layout-changer")) {
        event.preventDefault();
        layoutChanger();
    }

    // Copy text to clipboard
    if (event.target.matches(".copy-this")) {
        event.preventDefault();
        copyThis(event.target.getAttribute('data-copy'));
    }

    // Close alert on tab changes
    if (event.target.matches(".setting-tabs > a")) {
        if (event.isTrusted) {
            alertCloser();
        }
    }

    // Print data to delete modal from tables
    if (event.target.matches(".delete-row")) {
        document.querySelector(".delete-modal-form").action 
            = event.target.getAttribute("data-url");
    }

    // Change status of the language
    if (event.target.matches(".switch-row")) {
        var id = event.target.getAttribute("data-id");
        var url = event.target.getAttribute("data-url");
        var status = event.target.checked ? "1" : "0";
        var formData = new FormData();
        formData.append("id", id);
        formData.append("status", status);
        xhr(url, formData);
    }

    // View contact form emails
    if (event.target.matches(".view-email-log")) {
        var subject = event.target.getAttribute("data-subject");
        var message = event.target.getAttribute("data-message");
        if (subject) {
            document.querySelector("#subject").value = subject;
        }
        if (message) {
            document.querySelector("#message").innerHTML = message;
        }
    }

    // View email contents
    if (event.target.matches(".contents-row")) {
        var variables = event.target.getAttribute("data-variables");
        var content = event.target.getAttribute("data-content");

        if (variables) {
            var str = "";
            var vArr = variables.split(",");
            vArr.forEach((v) => {
                str += '<span class="badge-primary user-select-all me-2">'
                    + v
                    + '</span>';
            });
            document.querySelector("#variables").innerHTML = str;
        }
        if (content) {
            document.querySelector("#content").value = content;
            document.querySelector("#content").innerHTML = content;
        }

        document.querySelector(".edit-modal-form").action =
            event.target.getAttribute("data-url") +
            event.target.getAttribute("data-id");
    }

    // View payment log details (plan payments)
    if (event.target.matches(".view-payment-log")) {
        plogView(event.target.getAttribute("data-id"));
    }

    // View contact email log details
    if (event.target.matches(".view-contact-email")) {
        elogView(event.target.getAttribute("data-id"));
    }

    // View withdrawal log details
    if (event.target.matches(".view-withdrawal-log")) {
        withdrawalView(event.target.getAttribute("data-id"));
    }

    // Verify a payment
    if (event.target.matches(".verify-withdrawal")) {
        if (document.querySelector(".verify-modal-form") !== null) {
            document.querySelector(".verify-modal-form").action =
                event.target.getAttribute("data-url");
        }
    }

    // Verify a payment
    if (event.target.matches(".verify-payment")) {
        if (document.querySelector(".verify-payment-modal-form") !== null) {
            document.querySelector(".verify-payment-modal-form").action =
                event.target.getAttribute("data-url");
        }
    }
    
    // Verify a comment
    if (event.target.matches(".verify-comment")) {
        if (document.querySelector(".verify-modal-form") !== null) {
            document.querySelector(".verify-modal-form").action =
                event.target.getAttribute("data-url");
        }
    }

    // Pagination for files table
    if (event.target.matches("#files-pagination .page-link")) {
        event.preventDefault();
        setTimeout(() => {
            filesTable(event.target.href); 
        }, 100);
    }

    // Pagination for file reports table
    if (event.target.matches("#reports-pagination .page-link")) {
        event.preventDefault();
        setTimeout(() => {
            reportsTable(event.target.href); 
        }, 100);
    }

    // Reset filters in files table
    if (event.target.matches("#reset-files")) {
        event.preventDefault();
        document.querySelector('input[name=filename]').value = '';
        document.querySelector('input[name=short-key]').value = '';
        document.querySelector('input[name=uploader]').value = '';
        document.querySelector('select[name=disk]').value = '';
        document.querySelector('select[name=sort]').value = '';
        setTimeout(() => {
            filesTable(urlMake(`/${langVars.locale}/admin/files/all`));
        }, 100);
    }

    // Reset filters in file reports table
    if (event.target.matches("#reset-reports")) {
        event.preventDefault();
        document.querySelector('input[name=filename]').value = '';
        document.querySelector('input[name=short-key]').value = '';
        document.querySelector('input[name=uploader]').value = '';
        document.querySelector('select[name=disk]').value = '';
        document.querySelector('select[name=sort]').value = '';
        setTimeout(() => {
            reportsTable(urlMake(`/${langVars.locale}/admin/files/reports`));
        }, 100);
    }
    
    // View image in a modal in file reports page
    if (event.target.matches(".view-report-log")) {
        reportsGet(event.target.getAttribute("data-id"));
    }

});

// Listen for changes
document.addEventListener("change", function (event) {

    // Change website color (dark or light)
    if (event.target.matches("input[name=color-mode]")) {
        if (event.isTrusted) {
            colorMode(event.target.value, true);
        } else {
            colorMode(event.target.value);
        }
    }

});

// Listen for form submits
document.addEventListener("submit", function (event) {

    // Show spinner icon inside submit button
    if (event.target.matches("form")) {
        if (event.target.querySelector(".spinner") !== null) {
            event.preventDefault();
            var spinner = event.target.querySelector(".spinner");
            spinner.removeAttribute("class");
            spinner.classList.add(
                "spinner",
                "fa-solid",
                "fa-circle-notch",
                "fa-spin",
                "me-1"
            );
            setTimeout(function () {
                event.target.submit();
            }, 1000);
        }
    }

    // Fixes for page edit function
    if (event.target.matches(".page-edit-form")) {
        event.preventDefault();
        var form = new FormData(event.target);
        for (let entry of form) {
            if (document.querySelector(`[name="${entry[0]}"]`) !== null) {
                var input = document.querySelector(`[name="${entry[0]}"]`);
                if (input.type == "file") {
                    if (input.files.length == 0) {
                        input.type = "text";
                        input.value = null;
                    }
                } else {
                    input.value = entry[1] ? entry[1] : null;
                }
            }
        }
        setTimeout(function () {
            event.target.submit();
        }, 100);
    }

    // Search in files table
    if (event.target.matches("#files-table-search")) {
        event.preventDefault();
        var query = '?';
        var filename = event.target.querySelector('input[name=filename]');
        if (filename.value.length > 0) {
            query += `filename=${filename.value}&`;
        }
        var shortkey = event.target.querySelector('input[name=short-key]');
        if (shortkey.value.length > 0) {
            query += `shortkey=${shortkey.value}&`;
        }
        var uploader = event.target.querySelector('input[name=uploader]');
        if (uploader.value.length > 0) {
            query += `uploader=${uploader.value}&`;
        }
        var disk = event.target.querySelector('select[name=disk]');
        if (disk.value.length > 0) {
            query += `disk=${disk.value}&`;
        }
        var sort = event.target.querySelector('select[name=sort]');
        if (sort.value.length > 0) {
            query += `sort=${sort.value}&`;
        }
        setTimeout(() => {
            filesTable(urlMake(`/admin/files/all${query}`)); 
        }, 100);
    }

    // Search in file reporst table
    if (event.target.matches("#reports-table-search")) {
        event.preventDefault();
        var query = '?';
        var filename = event.target.querySelector('input[name=filename]');
        if (filename.value.length > 0) {
            query += `filename=${filename.value}&`;
        }
        var shortkey = event.target.querySelector('input[name=short-key]');
        if (shortkey.value.length > 0) {
            query += `shortkey=${shortkey.value}&`;
        }
        var uploader = event.target.querySelector('input[name=uploader]');
        if (uploader.value.length > 0) {
            query += `uploader=${uploader.value}&`;
        }
        var disk = event.target.querySelector('select[name=disk]');
        if (disk.value.length > 0) {
            query += `disk=${disk.value}&`;
        }
        var sort = event.target.querySelector('select[name=sort]');
        if (sort.value.length > 0) {
            query += `sort=${sort.value}&`;
        }
        setTimeout(() => {
            reportsTable(urlMake(`/admin/files/reports${query}`)); 
        }, 100);
    }

});

// Listen for Bootstrap tab changes
document.addEventListener("show.bs.tab", function (event) {

    // Close alerts on tab change
    if (event.target.matches(".setting-tabs > .nav-link")) {
        if (event.isTrusted) {
            if (document.querySelectorAll(".alert") !== null) {
                document.querySelectorAll(".alert").forEach(alert => {
                    var alert = bootstrap.Alert
                        .getOrCreateInstance(alert);
                    alert.close();  
                });
            }
        }
    }

});

// Listen for Bootstrap tab changes
document.addEventListener("shown.bs.tab", function (event) {

    // Contact table init
    if (event.target.matches("#v-pills-emails-tab")) {
        if (document.querySelector('.contact-table') !== null) {
            var target = document.querySelector('.contact-table');
            if (target.querySelector('.gridjs') === null) {
                setTimeout(() => {
                    table(target); 
                }, 500);
            }
        }
    }

    // Subscribers table init
    if (event.target.matches("#v-pills-subscribers-tab")) {
        if (document.querySelector('.subscribers-table') !== null) {
            var target = document.querySelector('.subscribers-table');
            if (target.querySelector('.gridjs') === null) {
                setTimeout(() => {
                    table(target); 
                }, 500);
            }
        }
    }

    // Language table init
    if (event.target.matches("#v-pills-languages-tab")) {
        if (document.querySelector('.language-table') !== null) {
            var target = document.querySelector('.language-table');
            if (target.querySelector('.gridjs') === null) {
                setTimeout(() => {
                    table(target); 
                    if (target.getAttribute('data-buttons') !== null) {
                        tableButtons(target.getAttribute('data-buttons'));
                    }
                }, 500);
            }
        }
    }
    
});

// Listen for Bootstrap modal changes
document.addEventListener("hide.bs.modal", function (event) {

    // Contact table init
    if (event.target.matches("#view-modal")) {
        var videoEl = document.querySelector('#video-player');
        var audioEl = document.querySelector('#audio-player');
        var videoPlayer = videojs(videoEl);
        var audioPlayer = videojs(audioEl);
        videoPlayer.pause();
        audioPlayer.pause();
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

function xhr(url, formData = null, refresh = null) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
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
            if (result.type == 1) {
                toaster(result.text, 1);
            } else {
                toaster(result.text, 0);
            }
            if (refresh) {
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    xhr.send(formData ?? "");
}

var grid;
function table(selector) {
    var url = selector.getAttribute("data-url");
    var columns = selector.getAttribute("data-columns");
    var columnsArr = columns.split(",");
    var columnsData = [];
    columnsArr.forEach((element) => {
        var item = {};
        item["name"] = element;
        item["formatter"] = (cell) => gridjs.html(`${cell}`);
        columnsData.push(item);
    });
    var columnsSource = [];
    columnsArr.forEach((element) => {
        columnsSource.push(element.toLowerCase().replace(" ", "_"));
    });
    var searchInput = selector.getAttribute("data-search");
    var buttonLimit =
        Math.max(document.clientWidth || 0, window.innerWidth || 0) >= 768
            ? 3
            : 0;
    grid = new gridjs.Grid({
        className: {
            table: "table-responsive",
            th: "text-center",
            td: "text-center",
            search: "m-0 ms-md-auto mt-3 mt-md-0",
            header: "d-flex flex-column flex-md-row",
        },
        fixedHeader: true,
        style: {
            table: {
                "white-space": "nowrap",
            },
        },
        sort: true,
        pagination: {
            limit: 10,
            summary: false,
            buttonsCount: buttonLimit,
            resetPageOnUpdate: true,
        },
        search: searchInput ? true : false,
        columns: columnsData,
        server: {
            method: "POST",
            url: url,
            then: (data) =>
                Array.isArray(data.data) && data.data.length > 0
                    ? data.data.map((colData) =>
                        columnsSource.map((val) => colData[val])
                    )
                    : "",
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="token"]').content,
            },
        },
        language: {
            search: {
                placeholder: `${langVars.search}`,
              },
            pagination: {
                previous: "<",
                next: ">",
            },
            noRecordsFound: `${langVars.data_not_found}`,
            error: `${langVars.error}`,
        }
    }).render(selector);
}
if (document.querySelector('.table-init') !== null) {
    document.querySelectorAll('.table-init').forEach(tabl => {
        table(tabl);
        if (tabl.getAttribute('data-buttons') !== null) {
            tableButtons(tabl.getAttribute('data-buttons'));
        }
    });
}

function tableButtons(selector) {
    grid.plugin.add({
        id: selector,
        component: window[`${selector}Buttons`],
        position: gridjs.PluginPosition.Header,
        order: 1,
    });
}

function logButtons() {
    return gridjs.h(
        "div",
        {
            class: "table-buttons my-auto me-auto",
        },
        [
            gridjs.h("button", {
                type: "button",
                class: "btn btn-color-1",
                "data-bs-toggle": "modal",
                "data-bs-target": "#delete-modal",
                innerHTML:
                    "<i class='fa-regular fa-trash-can pe-none me-2'></i>" 
                        + `${langVars.clear_all}`,
            }),
        ]
    );
}

function categoryButtons() {
    return gridjs.h(
        "div",
        {
            class: "table-buttons my-auto me-auto",
        },
        [
            gridjs.h("a", {
                href: urlMake(`/${langVars.locale}/admin/blog/categories/add`),
                class: "btn btn-color-1",
                innerHTML:
                    "<i class='fa-solid fa-plus pe-none me-2'></i>" 
                        + `${langVars.add_category}`,
            }),
        ]
    );
}

function payoutRatesButtons() {
    return gridjs.h(
        "div",
        {
            class: "table-buttons my-auto me-auto",
        },
        [
            gridjs.h("a", {
                href: urlMake(`/${langVars.locale}/admin/affiliate/payout-rates/add`),
                class: "btn btn-color-1",
                innerHTML:
                    "<i class='fa-solid fa-plus pe-none me-2'></i>" 
                        + `${langVars.add_country}`,
            }),
        ]
    );
}

function withdrawalMethodsButtons() {
    return gridjs.h(
        "div",
        {
            class: "table-buttons my-auto me-auto",
        },
        [
            gridjs.h("a", {
                href: urlMake(`/${langVars.locale}/admin/affiliate/withdrawal-methods/add`),
                class: "btn btn-color-1",
                innerHTML:
                    "<i class='fa-solid fa-plus pe-none me-2'></i>" 
                        + `${langVars.add_method}`,
            }),
        ]
    );
}

function postButtons() {
    return gridjs.h(
        "div",
        {
            class: "table-buttons my-auto me-auto",
        },
        [
            gridjs.h("a", {
                href: urlMake(`/${langVars.locale}/admin/blog/posts/add`),
                class: "btn btn-color-1",
                innerHTML:
                    "<i class='fa-solid fa-plus pe-none me-2'></i>" 
                        + `${langVars.add_post}`,
            }),
        ]
    );
}

function pageButtons() {
    return gridjs.h("div", { class: "table-buttons d-flex my-auto me-auto" }, [
        gridjs.h("a", {
            href: urlMake(`/${langVars.locale}/admin/pages/custom/add`),
            class: "btn btn-color-1 text-nowrap my-auto",
            innerHTML:
                "<i class='fa-solid fa-plus pe-none me-2'></i>" 
                    + `${langVars.add_page}`,
        }),
    ]);
}

function planButtons() {
    return gridjs.h(
        "div",
        { class: "table-buttons d-flex gap-3 my-auto me-auto" },
        [
            gridjs.h("a", {
                href: urlMake(`/${langVars.locale}/admin/payments/plans/add`),
                class: "btn btn-color-1",
                innerHTML:
                    "<i class='fa-solid fa-plus pe-none me-2'></i>" 
                        + `${langVars.add_plan}`,
            }),
        ]
    );
}

function tabHash() {
    if (document.querySelector('.setting-tabs') !== null) {
        let url = location.href.replace(/\/$/, "");
        if (url.includes("?tab")) {
            const hash = url.split("?");
            const currentTab = document.querySelector(
                '.setting-tabs > a[href="?' + hash[1] + '"]'
            );
            const curTab = new bootstrap.Tab(currentTab);
            curTab.show();
        }
        const selectableTabList = [].slice.call(
            document.querySelectorAll(".setting-tabs > a")
        );
        selectableTabList.forEach((selectableTab) => {
            const selTab = new bootstrap.Tab(selectableTab);
            selectableTab.addEventListener("click", function () {
                var newUrl;
                const hash = selectableTab.getAttribute("href");
                newUrl = url.split("?")[0] + hash;
                history.replaceState(null, null, newUrl);
            });
        });
    }
}
tabHash();

function alertCloser() {
    if (document.querySelector(".alert") !== null) {
        document.querySelectorAll(".alert").forEach(alrt => {
            var alert = bootstrap.Alert
                .getOrCreateInstance(alrt);
            alert.close();
        });
    }
}

function plogView(logId) {
    document.querySelector("#price").innerHTML = "";
    document.querySelector("#period").innerHTML = "";
    document.querySelector("#plan-name").innerHTML = "";
    document.querySelector("#gateway").innerHTML = "";
    document.querySelector("#transaction").innerHTML = "";
    document.querySelector("#info").innerHTML = "";
    document.querySelector("#user-ip").innerHTML = "";
    document.querySelector("#countdown").innerHTML = "";
    document.querySelector("#api").innerHTML = "";

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${langVars.locale}/admin/payments/logs/get/${logId}`), 
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
                document.querySelector("#price").innerHTML =
                    result.data.planPrice;
                document.querySelector("#period").innerHTML =
                    result.data.planDuration === 1 
                        ? `${langVars.monthly}` 
                        : `${langVars.yearly}`;
                document.querySelector("#plan-name").innerHTML =
                    result.data.planName;
                document.querySelector("#gateway").innerHTML =
                    result.data.gatewayName;
                document.querySelector("#transaction").innerHTML =
                    result.data.transaction;
                document.querySelector("#info").innerHTML =
                    result.data.paymentInfo;
                document.querySelector("#user-ip").innerHTML =
                    result.data.userIp;

                document.querySelector("#disk").innerHTML = 
                    `${result.data.planFeatures['disk']} MB`;

                result.data.planFeatures['formats'] = result.data.planFeatures['formats'].map(function(x){ 
                    return x.toUpperCase(); 
                })
                document.querySelector("#formats").innerHTML = 
                    result.data.planFeatures['formats'].join(', ');
                var autoDeletion = result.data.planFeatures['auto_deletion'];
                document.querySelector("#auto-deletion").innerHTML = autoDeletion > 0
                    ? `${result.data.planFeatures['auto_deletion']} ${langVars.days}`
                    : `${langVars.never}`;
                document.querySelector("#countdown").innerHTML = 
                    `${result.data.planFeatures['countdown']}`;
                document.querySelector("#api").innerHTML = 
                    `${result.data.planFeatures['api']}`;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    xhr.send();
}

function elogView(emailId) {
    document.querySelector("#subject").innerHTML = "";
    document.querySelector("#message").innerHTML = "";

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${langVars.locale}/admin/emails/contact/get/${emailId}`), 
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
                document.querySelector("#subject").innerHTML =
                    result.data.subject;
                document.querySelector("#message").innerHTML =
                    result.data.message;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    xhr.send();
}

function withdrawalView(withdrawalId) {
    document.querySelector("#gateway").innerHTML = "";
    document.querySelector("#amount").innerHTML = "";
    document.querySelector("#info").innerHTML = "";
    document.querySelector("#user-ip").innerHTML = "";

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${langVars.locale}/admin/withdrawals/get/${withdrawalId}`), 
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
                document.querySelector("#gateway").innerHTML =
                    result.data.gateway;
                document.querySelector("#amount").innerHTML =
                    result.data.amount;
                document.querySelector("#info").innerHTML =
                    result.data.info;
                document.querySelector("#user-ip").innerHTML =
                    result.data.userIp;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    xhr.send();
}

// Change website color mode (Dark or Light)
function colorMode(colorMode, selected) {
    if (colorMode === "dark") {
        document.querySelector("html").classList.add("dark-mode");
        document.querySelector('#sidebar-logo-img').src 
            = sysVars.logo;
    } else {
        document.querySelector("html").classList.remove("dark-mode");
        document.querySelector('#sidebar-logo-img').src 
            = sysVars.logo_dark;
    }
    const xhr = new XMLHttpRequest();
    xhr.open("POST", urlMake(`/${langVars.locale}/color-mode`), true);
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
    const params = new FormData();
    params.append("mode", colorMode);
    if (selected) {
        params.append("selected", selected);
    }
    xhr.send(params);
}

function convertAlerts() {
    window.alert = function(message) {
        toaster(message, 0);
    };
}
convertAlerts();

/* Text editor (ckeditor) init */
function textEditor(selector, upUrl, delUrl) {
    if (selector !== null) {
        ClassicEditor
            .create(selector, {
                simpleUpload: {
                    uploadUrl: upUrl,
                    withCredentials: true,
                    headers: {
                        "X-CSRF-TOKEN":document
                            .querySelector('meta[name="token"]').content,
                        "X-Requested-With":"XMLHttpRequest",
                    },
                },
                language: {
                    // The UI will be English.
                    ui: `${sysVars.lang}`,
                    // But the content will be edited in Arabic.
                    content: `${sysVars.lang}`
                },
            })
            .then(editor => {
                editor.model.document.on( 'change:data', () => {
                    for (const change of editor.model.document.differ.getChanges()) {
                        if (change.type === 'remove' && change.name === 'imageBlock') {
                            for (const [key, value] of change.attributes) {
                                if (key === 'src') {
                                    deleteEditorImage(value, delUrl);
                                }
                                
                            }
                        }
                    }
                } ); 
            })
            .catch(error => {
                console.log(err.stack);
            });
    }
}
function deleteEditorImage(imageUrl, delUrl) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        delUrl, 
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
        if (xhr.readyState === XMLHttpRequest.DONE &&
            xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                console.log(result.data);
            } else {
                console.log(result.data);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const formData = new FormData();
    formData.append("file", imageUrl);
    xhr.send(formData);
}
if (document.querySelector('.editor-init') !== null) {
    document.querySelectorAll('.editor-init').forEach(editor => {
        var upUrl = editor.getAttribute('data-upload') ?? '';
        var delUrl = editor.getAttribute('data-delete') ?? '';
        textEditor(editor, upUrl, delUrl);
    });
}

// Init all tooltips
function tooltipInit() {
    if (document.querySelector('[data-bs-toggle="tooltip"]') !== null) {
        const tooltipTriggerList = document
            .querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(
            tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl)
        );
    }
}
tooltipInit();

// Init all popovers
function popoverInit() {
    if (document.querySelector('[data-bs-toggle="popover"]') !== null) {
        document.querySelectorAll('[data-bs-toggle="popover"]')
        .forEach((el) => {
            new bootstrap.Popover(el);
        });
    }
}
setTimeout(() => {
    popoverInit();
}, 750);

// Collapse sidebar
function collapseSidebar(collapse) {
    if (collapse == 1) {
        document.querySelectorAll('.dashboard-sidebar .submenu')
            .forEach(function (el) {
            el.classList.remove('show');
            el.classList.add('dropdown-menu');
            el.setAttribute('style','');
        });
        document.querySelectorAll('.has-submenu').forEach(function (el) {
            el.classList.add('dropend');
        });
        document.querySelectorAll('.has-submenu > .nav-link')
            .forEach(function (el) {
            el.classList.add('dropdown-toggle');
            el.classList.remove('show');
            el.setAttribute('role', 'button');
            el.setAttribute('data-bs-toggle', 'dropdown');
            el.setAttribute('aria-expanded', 'false');
        });
    } else {
        document.querySelectorAll('.dashboard-sidebar .submenu')
            .forEach(function (el) {
            el.classList.remove('show');
            el.classList.remove('dropdown-menu');
            el.setAttribute('style','');
        });
        document.querySelectorAll('.has-submenu').forEach(function (el) {
            el.classList.remove('dropend');
        });
        document.querySelectorAll('.has-submenu > .nav-link')
            .forEach(function (el) {
            el.classList.remove('dropdown-toggle');
            el.classList.remove('show');
            el.removeAttribute('role');
            el.removeAttribute('data-bs-toggle');
            el.removeAttribute('aria-expanded');
        });
    }
}

// Make given url to full path
function urlMake(slug) {
    if (slug.startsWith("/")) {
        return `${sysVars.base_url}${slug}`;
    }
 return `${sysVars.base_url}/${slug}`;
}

// Add custom scroll to dashboard sidebars
function dashboardSidebars() {
    if (document.querySelector(".dashboard-sidebar") !== null) {
        document.querySelectorAll('.dashboard-sidebar > .nav')
            .forEach(scroll => {
            OverlayScrollbars(
                scroll, 
                {
                    overflow: {
                        x: 'hidden',
                        y: 'scroll',
                    },
                    scrollbars: {
                        autoHide: 'leave',
                    },
                }
            );
        });
    }
}
dashboardSidebars();

// Change sidebar menu width
function layoutChanger() {
    document.querySelector('.dashboard-sidebar')
        .classList.add('animate');
    document.querySelector('.dashboard-content')
        .classList.add('animate');
    if (
        !document.querySelector('.dashboard-sidebar')
            .classList.contains('sm')
    ) {
        document.querySelector('.dashboard-sidebar')
            .classList.add('sm');
        document.querySelector('.dashboard-content')
            .classList.add('lg');
        document.querySelector('.layout-changer')
            .setAttribute('data-collapsed','1');
        collapseSidebar(1);
    } else {
        document.querySelector('.dashboard-sidebar')
            .classList.remove('sm');
        document.querySelector('.dashboard-content')
            .classList.remove('lg');
        document.querySelector('.layout-changer')
            .setAttribute('data-collapsed','0');
        collapseSidebar();
    }
    var formData = new FormData();
    formData.append(
        'collapsed', 
        document.querySelector('.layout-changer')
            .getAttribute('data-collapsed')
    );
    const xhr = new XMLHttpRequest();
    xhr.open("POST", urlMake(`/${langVars.locale}/admin/collapse-sidebar`), true);
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.send(formData);
}

// Copy text to clipboard
function copyThis(val) {
    if (window.isSecureContext && navigator.clipboard) {
        navigator.clipboard.writeText(val);
        toaster(`${langVars.copied}`, 1);
    } else {
        unsecuredCopyToClipboard(val);
    }
}

function unsecuredCopyToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        toaster(`${langVars.copied}`, 1);
    } catch (err) {
        toaster(`${langVars.copied_error}. (${err})`, 0);
    }
    document.body.removeChild(textArea);
}

// User registration by months chart
function registrationAnalytics() {
    if (document.querySelector("#chart-register") !== null) {
        var url = document.querySelector("#chart-register")
            .getAttribute('data-url');
        const xhr = new XMLHttpRequest();
        xhr.open("POST", `${url}`, true);
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
            if (xhr.readyState === XMLHttpRequest.DONE &&
                xhr.status === 200) {
                var result = JSON.parse(xhr.responseText);
                var months = result.months;
                var users = result.users;
                var options = {
                    series: [{
                        name: `${langVars.users}`,
                        data: users
                    }],
                    chart: {
                        type: 'bar',
                        height: 320,
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        },
                        animations: {
                            enabled: true,
                            easing: 'linear'
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '35%',
                            borderRadius: 2,
                            borderRadiusApplication: 'last'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        type: 'category',
                        categories: months,
                        axisBorder: {
                            show: false
                        },
                        crosshairs: {
                            show: false
                        },
                    },
                    yaxis: {
                        labels: {
                            formatter: (value) => {
                                return value.toFixed(0)
                            },
                        }
                    },
                    legend: {
                        show: false
                    },
                    tooltip: {
                        x: {
                            show: false
                        },
                        y: {
                            labels: {
                                formatter: (value) => {
                                    return value.toFixed(1)
                                },
                            }
                        },
                    },
                    fill: {
                        opacity: 1
                    },
                    title: {
                        text: `${langVars.register_analytics}`,
                        style: {
                            fontSize: '20px',
                            fontWeight: '700',
                            fontFamily: 'Inter, sans-serif',
                            color: `${cssVars.color2}`
                        },
                    },
                    colors: `${cssVars.color1}`,
                    grid: {
                        strokeDashArray: 3,
                        padding: {
                            right: 25
                        },
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    states: {
                        hover: {
                            filter: {
                                type: 'darken',
                                value: 0.75,
                            },
                        },
                    },
                    noData: {
                        text: `${langVars.data_not_found}`,
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                            color: `${cssVars.color2}`,
                            fontSize: '20px',
                            fontFamily: 'Inter, sans-serif',
                        },
                    },
                };
                var chart = new ApexCharts(document
                    .querySelector("#chart-register"), options);
                chart.render();
            } else {
                toaster(langVars.error, 0);
            }
        });
        xhr.send();
    }
}
registrationAnalytics();

// Uploaded file types chart
function fileTypesAnalytics() {
    if (document.querySelector("#chart-types") !== null) {
        var url = document.querySelector("#chart-types")
            .getAttribute('data-url');
        const xhr = new XMLHttpRequest();
        xhr.open("POST", `${url}`, true);
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
            if (xhr.readyState === XMLHttpRequest.DONE &&
                xhr.status === 200) {
                var result = JSON.parse(xhr.responseText);
                var types = result.types;

                var options = {
                    series: [
                        {
                            data: types
                        }
                    ],
                    chart: {
                        height: 320,
                        type: 'treemap',
                        toolbar: {
                            show: false
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                height: 300
                            }
                        }
                    }],
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        style: {
                            fontSize: '12px'
                        },
                    },
                    title: {
                        text: `${langVars.file_types}`,
                        style: {
                            fontSize: '20px',
                            fontWeight: '700',
                            fontFamily: 'Inter, sans-serif',
                            color: `${cssVars.color2}`
                        },
                    },
                    stroke: {
                        colors: [`${cssVars.color3}`]
                    },
                    noData: {
                        text: `${langVars.no_data}`,
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                            fontSize: '20px',
                            fontWeight: '700',
                            fontFamily: 'Inter, sans-serif',
                            color: `${cssVars.color2}`
                        }
                    },
                    colors: [
                        `${cssVars.color1}`
                    ],
                };
         
                var chart = new ApexCharts(document
                    .querySelector("#chart-types"), options);
                chart.render();
            } else {
                toaster(langVars.error, 0);
            }
        });
        xhr.send();
    }
}
fileTypesAnalytics();

// Quota chart
function quotaAnalytics() {
    if (document.querySelector("#chart-quota") !== null) {
        var url = document.querySelector("#chart-quota")
            .getAttribute('data-url');
        const xhr = new XMLHttpRequest();
        xhr.open("POST", `${url}`, true);
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
            if (xhr.readyState === XMLHttpRequest.DONE &&
                xhr.status === 200) {
                var result = JSON.parse(xhr.responseText);
                var options = {
                    series: [result.empty,result.non_empty],
                    labels: [langVars.free_space,langVars.files],
                    chart: {
                        width: '100%',
                        height: 320,
                        type: 'donut',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                height: 300
                            }
                        }
                    }],
                    yaxis: {
                        labels: {
                            formatter: function(val, index) {
                                return index.seriesIndex == 0
                                    ? result.empty_mb
                                    : result.non_empty_mb;
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                    title: {
                        text: `${langVars.file_quota}`,
                        style: {
                            fontSize: '20px',
                            fontWeight: '700',
                            fontFamily: 'Inter, sans-serif',
                            color: `${cssVars.color2}`
                        },
                    },
                    colors: [`${cssVars.color2}`, `${cssVars.color1}`],
                    states: {
                        hover: {
                            filter: {
                                type: 'none',
                            },
                        },
                        active: {
                            filter: {
                                type: 'darken',
                                value: 0.8,
                            },
                        },
                    },
                    stroke: {
                        colors: [`${cssVars.color3}`]
                    }
                };
                var chart = new ApexCharts(document
                    .querySelector("#chart-quota"), options);
                chart.render();
            } else {
                toaster(langVars.error, 0);
            }
        });
        xhr.send();
    }
}
quotaAnalytics();

// Top cards stats
function topCards(url) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", `${url}`, true);
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

            document.querySelector('#top-card-users').innerHTML = result.users ?? 0;
            document.querySelector('#top-card-files').innerHTML = result.files ?? 0;
            document.querySelector('#top-card-file-types').innerHTML = result.file_types ?? 0;
            document.querySelector('#top-card-file-size').innerHTML = result.file_size ?? 0;
            document.querySelector('#top-card-posts').innerHTML = result.posts ?? 0;
            document.querySelector('#top-card-comments').innerHTML = result.comments ?? 0;
            document.querySelector('#top-card-sales').innerHTML = result.sales ?? 0;
            document.querySelector('#top-card-revenue').innerHTML = result.revenue ?? 0;

        } else {
            toaster(langVars.error, 0);
        }
    });
    xhr.send();
}

// Convert bytes to other size units
function convertSize(bytes, separator = '', postFix = '') {
    if (bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.min(parseInt(Math.floor(Math.log(bytes) / Math.log(1024)).toString(), 10), sizes.length - 1);
        return `${(bytes / (1024 ** i)).toFixed(i ? 1 : 0)}${separator}${sizes[i]}${postFix}`;
    }
    return 'n/a';
}

// Files table init
function filesTable(targetUrl) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        targetUrl, 
        true
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(`${langVars.error}`, 0)
    });
    xhr.addEventListener("abort", () => {
        toaster(`${langVars.cancelled}`, 0);
    });
    xhr.addEventListener("load", () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                document.querySelector('#files-table-body').innerHTML = '';
                var html = '';
            
                if (result.data.data != undefined) {
                    var files = Object.values(result.data.data);
                    files.forEach(val => {
                        html += `<tr>
                                    <td>${val.uploaddate}</td>
                                    <td>${val.filedetails}</td>
                                    <td>${val.filekey}</td>
                                    <td>${val.filesize}</td>
                                    <td>${val.uploader}</td>
                                    <td>${val.storage}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            ${val.action}
                                        </div>
                                    </td>
                                </tr>`;
                    });
                } else {
                    html += `<tr>
                        <td colspan="7">${langVars.data_not_found}</td>
                    </tr>`;
                }
                document.querySelector('#files-table-body').innerHTML = html;
                var pagination = '';
                if (result.data.links != undefined) {
                    result.data.links.forEach(link => {
                        if (result.data.total > 1) {
                            var active = link.active ? ' active' : '';
                            var disabled = (link.url == null) ? ' disabled' : '';
                            pagination += `<li class="page-item${active}">
                                            <a class="page-link${disabled}" href="${link.url}"${disabled}>
                                                ${link.label}
                                            </a>
                                        </li>`;
                        }
                    });
                }
                document.querySelector('#files-pagination > ul').innerHTML = pagination ?? '';
                setTimeout(() => {
                    popoverInit();
                }, 750);
            }
        } else {
            toaster(`${langVars.error}`, 0)
        }
    });
    xhr.send();
}
if (document.getElementById("files-table") !== null) {
    filesTable(urlMake(`/${langVars.locale}/admin/files/all`));
}

// File reports table init
function reportsTable(targetUrl) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        targetUrl, 
        true
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(`${langVars.error}`, 0)
    });
    xhr.addEventListener("abort", () => {
        toaster(`${langVars.cancelled}`, 0);
    });
    xhr.addEventListener("load", () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                document.querySelector('#reports-table-body').innerHTML = '';
                var html = '';
            
                if (result.data.data != undefined) {
                    var files = Object.values(result.data.data);
                    files.forEach(val => {
                        html += `<tr>
                                    <td>${val.reportDate}</td>
                                    <td>${val.fileDetails}</td>
                                    <td>${val.filekey}</td>
                                    <td>${val.fileSize}</td>
                                    <td>${val.uploader}</td>
                                    <td>${val.storage}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            ${val.action}
                                        </div>
                                    </td>
                                </tr>`;
                    });
                } else {
                    html += `<tr>
                        <td colspan="7">${langVars.data_not_found}</td>
                    </tr>`;
                }
                document.querySelector('#reports-table-body').innerHTML = html;
                var pagination = '';
                if (result.data.links != undefined) {
                    result.data.links.forEach(link => {
                        if (result.data.total > 1) {
                            var active = link.active ? ' active' : '';
                            var disabled = (link.url == null) ? ' disabled' : '';
                            pagination += `<li class="page-item${active}">
                                            <a class="page-link${disabled}" href="${link.url}"${disabled}>
                                                ${link.label}
                                            </a>
                                        </li>`;
                        }
                    });
                }
                document.querySelector('#reports-pagination > ul').innerHTML = pagination ?? '';
                setTimeout(() => {
                    popoverInit();
                }, 750);
            }
        } else {
            toaster(`${langVars.error}`, 0)
        }
    });
    xhr.send();
}
if (document.getElementById("reports-table") !== null) {
    reportsTable(urlMake(`/${langVars.locale}/admin/files/reports`));
}

// Get report details and print to offcanvas
function reportsGet(reportId) {
    document.querySelector("#reported-by").innerHTML = "";
    document.querySelector("#reason").innerHTML = "";
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${langVars.locale}/admin/files/reports/get/${reportId}`),
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
                document.querySelector("#reported-by").innerHTML =
                    result.data.reporter;
                document.querySelector("#reason").innerHTML =
                    result.data.reason;
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    xhr.send();
}

// Get uploaded file stats in files page
function fileStats() {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${langVars.locale}/admin/files/all/stats`), 
        true
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(`${langVars.error}`, 0)
    });
    xhr.addEventListener("abort", () => {
        toaster(`${langVars.cancelled}`, 0);
    });
    xhr.addEventListener("load", () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                document.querySelector('#file-count-stat').innerHTML = result.count;
                document.querySelector('#file-size-stat').innerHTML = result.size;
                document.querySelector('#file-types-stat').innerHTML = result.types[0];
                document.querySelector('#file-uploaders-stat').innerHTML = result.uploaders;
            }
        } else {
            toaster(`${langVars.error}`, 0)
        }
    });
    xhr.send();
}
if (document.getElementById("file-count-stat") !== null) {
    fileStats();
}