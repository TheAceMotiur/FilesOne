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
var viewport = getViewport();
var locale = langVars.locale ? `${langVars.locale}/` : '';
var countdownTime = parseInt(sysVars.download_countdown);
var timePassed = 0;
var timeLeft = countdownTime;
var timerInterval;

if (document.querySelector('.file-preview-inner [name="g-recaptcha-response"]') !== null) {
    grecaptcha.ready(function() {
        setTimeout(() => {
            document
                .querySelector('.file-preview-inner .download-file')
                .classList
                .remove('disabled');  
        }, 1000);
    }); 
}

// Listen for clicks
document.addEventListener("click", function (event) {

    // Copy text to clipboard
    if (event.target.matches(".copy-this")) {
        event.preventDefault();
        copyThis(event.target.getAttribute('data-copy'));
    }

    // Open cookie consent box
    if (event.target.matches(".cookie-opener")) {
        var cookiePanel = document.querySelector('#cookies-policy');
        if (cookiePanel.classList.contains('cookies-policy-opened')) {
            event.target.querySelector('i').classList.toggle('d-none');
            event.target.querySelector('svg').classList.toggle('d-none');
        } else {
            event.target.querySelector('svg').classList.toggle('d-none');
            event.target.querySelector('i').classList.toggle('d-none');
        }
        cookiePanel.classList.toggle('cookies-policy-opened');
    }

    // Proceed to next step on pay page (Tap switcher)
    if (event.target.matches(".payment-tabs")) {
        if (event.target.getAttribute('data-tab')) {
            var target = event.target.getAttribute('data-tab');
            const triggerEl = document
                .querySelector(`#payment-tabs #${target}`);
            bootstrap.Tab.getOrCreateInstance(triggerEl).show();
        }
    }

    // Load more blog posts
    if (event.target.matches(".load-more-posts")) {
        var slug = event.target.getAttribute('data-slug');
        var limit = event.target.getAttribute('data-limit');
        var offset = event.target.getAttribute('data-offset');
        loadPosts(slug,limit,offset);
    }

    // Get download link   
    if (event.target.matches("#get-link")) {
        if (event.target.getAttribute('href') == '#') {
            event.preventDefault();
            if (event.target.getAttribute('data-id') !== null
                && event.target.getAttribute('data-key') !== null
            ) {
                sendId(
                    event.target.getAttribute('data-id'),
                    event.target.getAttribute('data-key'),
                    event.target.getAttribute('data-slug')
                );
            } else {
                toaster(langVars.error, 0);
            }
        } else {
            setTimeout(() => {
                event.target.remove();
            }, 1000);
        }
    }
});

// Listen for changes
document.addEventListener("change", function (event) {

    // Change website color (dark or light)
    if (event.target.matches(".color-mode")) {
        var activeId = event.target.id;
        document.querySelectorAll(".color-mode").forEach(element => {
            if (element.id != activeId) {
                if (element.checked == true) {
                    element.checked = false;
                } else {
                    element.checked = true;
                }
            }
        });
        const mode = event.target.checked ? "dark" : "light";
        if (event.isTrusted) {
            colorMode(mode, true);
        } else {
            colorMode(mode);
        }
    }

    // Change plan periods on switch change
    if (event.target.matches("#pay-terms")) {
        if (
            document.querySelector(".payment-tabs[data-tab=pay-payment]")
        ) {
            if (event.target.checked) {
                if (document.querySelector("#pay-custom-terms")) {
                    if (document.querySelector("#pay-custom-terms").checked) {
                        document
                            .querySelector("[data-tab=pay-payment]")
                            .disabled = false;
                    } else {
                        document
                            .querySelector("[data-tab=pay-payment]")
                            .disabled = true;
                    }
                } else {
                    document
                        .querySelector("[data-tab=pay-payment]")
                        .disabled = false; 
                }
            } else {
                document
                    .querySelector("[data-tab=pay-payment]")
                    .disabled = true;
            }
        }
    }

    // Change plan data on plan period change (Plan page)
    if (event.target.matches("#plans-inner-card-period")) {

        if (document.querySelector('.plan-card') !== null) {
            document.querySelectorAll('.plan-card').forEach(el => {
                el.classList
                    .remove('animate__animated','animate__zoomIn');
                setTimeout(function() {
                    el.classList
                        .add('animate__animated','animate__zoomIn');
                }, 250);

                var priceData = el.querySelector('.plan-price') ;

                if (event.target.checked) {
                    var price = priceData.getAttribute('data-yearly');
                    var currency = priceData.getAttribute('data-currency');
                    var text = priceData.getAttribute('data-yearly-string');
                    var url = priceData.getAttribute('data-yearly-url');
                } else {
                    var price = priceData.getAttribute('data-monthly');
                    var currency = priceData.getAttribute('data-currency');
                    var text = priceData.getAttribute('data-monthly-string');
                    var url = priceData.getAttribute('data-monthly-url');
                }

                setTimeout(function() {
                    priceData.querySelector('.plan-price .price').innerHTML 
                        = currency + price;
                    priceData.querySelector('.plan-price .period').innerHTML 
                        = text;
                    el.querySelector('a').href = url; 
                }, 350);
            });
        }

    }

});

// Listen for form submits
document.addEventListener("submit", function (event) {

    // Send new feedback XHR function
    if (
        event.target.matches("form") 
        && event.target.id !== 'stripe-payment' 
        && event.target.name !== 'bank-payment'
    ) {
        if (
            document.querySelector('button[type=submit] > span') !== null 
            && document.querySelector('button[type=submit] > i') !== null
        ) {
            document.querySelector('button[type=submit] > span')
                .classList.add('d-none');
            document.querySelector('button[type=submit] > i')
                .classList.remove('d-none');
        }
    }

    // Send file report XHR function
    if (event.target.matches("#file-report-form")) {
        event.preventDefault();
        fileReport(event.target.getAttribute("data-id"));
    }

    // Subscription form in footer XHR function
    if (event.target.matches("#subscription-form")) {
        event.preventDefault();
        event.target
            .querySelector("button[type=submit] > .fa-check")
            .classList.add("d-none");
        event.target
            .querySelector("button[type=submit] > .fa-spin")
            .classList.remove("d-none");
        subscriberForm(event.target);
    }

    // Contact form XHR function
    if (event.target.matches("#contact-form")) {
        event.preventDefault();
        contactForm();
    }

});

// Listen for keyups
document.addEventListener("keyup", function (event) {

    // Count characters and print to element
    if (event.target.matches("[data-limit]")) {
        var limit = event.target.getAttribute("data-limit");
        document.querySelector(`[data-counter=${event.target.id}]`).innerHTML 
            = limit - event.target.value.length >= 0
                ? limit - event.target.value.length
                : 0;
        if (event.target.value.length > limit) {
            event.target.value = event.target.value.substr(0, limit);
        }
    }

});

// Listen for Bootstrap collapse changes
document.addEventListener("show.bs.collapse", function (event) {

    // Pay page step tabs
    if (event.target.matches(".payment-method-collapse")) {
        event.target.previousElementSibling.classList
            .add('pe-none');
        event.target.previousElementSibling
            .querySelector('input[type=checkbox]').checked = true;
        document.querySelectorAll('.payment-method-collapse')
            .forEach(collapse => {
            if (collapse.classList.contains('show')) {
                var mycollapse = new bootstrap.Collapse(collapse);
                mycollapse.hide();
            }
        })
    }

});

// Listen for Bootstrap collapse changes
document.addEventListener("hide.bs.collapse", function (event) {

    // Pay page step tabs
    if (event.target.matches(".payment-method-collapse")) {
        event.target.previousElementSibling.classList
            .remove('pe-none');
        event.target.previousElementSibling
            .querySelector('input[type=checkbox]').checked = false;
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

// Change website color mode (Dark or Light)
function colorMode(colorMode, selected) {
    if (colorMode === "dark") {
        document.querySelector("html").classList.add("dark-mode");
        if (document.querySelector('#header-logo') !== null) {
            document.querySelector('#header-logo').src = sysVars.logo;
        }
        if (document.querySelector('#footer-logo') !== null) {
            document.querySelector('#footer-logo').src = sysVars.logo;
        }
    } else {
        document.querySelector("html").classList.remove("dark-mode");
        if (document.querySelector('#header-logo') !== null) {
            document.querySelector('#header-logo').src = sysVars.logo_dark;
        }
        if (document.querySelector('#footer-logo') !== null) {
            document.querySelector('#footer-logo').src = sysVars.logo_dark;
        }
    }
    const xhr = new XMLHttpRequest();
    xhr.open("POST", urlMake(`/${locale}color-mode`), true);
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

// Scroll to a element general function
function scroller(el) {
    if (el && document.querySelector(el) !== null) {
        setTimeout(function () {
            var target = document.querySelector(el);
            target.scrollIntoView();
        }, 500);
    }
}

// File report
function fileReport() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", document.querySelector("#file-report-form").action, true);
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
                const reportModal = bootstrap.Modal.getInstance(
                    document.querySelector('#report-file')
                );
                reportModal.hide();
                document.querySelector("#file-report-form textarea").value = '';
                toaster(result.data, 1);
            } else {
                if (result.errors !== undefined) {
                    var errors = Object.values(result.errors);
                    errors.forEach((err) => {
                        toaster(err, 0);
                    });
                } else {
                    toaster(result.data, 0);
                }
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData(
        document.querySelector("#file-report-form")
    );
    xhr.send(params);
}

// Subscriber form XHR function
function subscriberForm(form) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", urlMake(`/${locale}subscribe`), true);
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
        form.querySelector("button[type=submit] > .fa-check")
            .classList.remove("d-none");
        form.querySelector("button[type=submit] > .fa-spin")
            .classList.add("d-none");
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                toaster(result.data, 1);
            } else {
                if (typeof result.data === "object") {
                    var errors = Object.values(result.data);
                    errors.forEach((err) => {
                        toaster(err, 0);
                    });
                } else {
                    toaster(result.data, 0);
                }
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData(form);
    xhr.send(params);
}

// Init lazyload library
function lazy() {
    if (typeof LazyLoad != "undefined") {
        var lazy_loader = new LazyLoad();
        lazy_loader.update();
    }
}

// Change plan periods on switch change
function planPeriod(switcher) {
    if (document.querySelectorAll(".plan-price") !== null) {
        document.querySelectorAll(".plan-price").forEach((el) => {
            var price = switcher.checked
                ? el.getAttribute("data-yearly")
                : el.getAttribute("data-monthly");
            var currency = el.getAttribute("data-currency");
            var period = switcher.checked
                ? el.getAttribute("data-yearly-string")
                : el.getAttribute("data-monthly-string");
            el.querySelector(".price").innerHTML = currency + price;
            el.querySelector(".period").innerHTML = ` / ${period}`;
            document.querySelectorAll(".plan-footer").forEach((el2) => {
                el2.querySelector("a").href = switcher.checked
                    ? el2.querySelector("a").getAttribute("data-yearly")
                    : el2.querySelector("a").getAttribute("data-monthly");
            });
        });
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
if (
    viewport == 'md' 
    || viewport == 'lg' 
    || viewport == 'xl' 
    || viewport == 'xxl'
) {
    handleCustomCursor();
}

// SmoothScroll plugin init
function smoothScroll() {
    window.SmoothScrollOptions = {
        // Scrolling Core
        animationTime    : 800, // [ms]
        stepSize         : 200, // [px]
        // Acceleration
        accelerationDelta : 50,  // 50
        accelerationMax   : 3,   // 3
        // Keyboard Settings
        keyboardSupport   : true,  // option
        arrowScroll       : 50,    // [px]
        // Pulse (less tweakable)
        // ratio of "tail" to "acceleration"
        pulseAlgorithm   : true,
        pulseScale       : 4,
        pulseNormalize   : 1,
        // Other
        touchpadSupport   : false, // ignore touchpad by default
        fixedBackground   : true, 
        excluded          : ''    
    };
}
if (viewport == 'xl' || viewport == 'xxl') {
    smoothScroll();
}

// WOW plugin init
function wowInit() {
    var wow = new WOW({
        boxClass: 'animate',
        animateClass: 'animate__animated',
        offset: -10,
        mobile: true,
        live: true,
        scrollContainer: null,
        resetAnimation: false,
        callback: afterReveal
    });
    wow.init();
}
wowInit();

// WOW plugin callback function
function afterReveal (el) {
    if (el.classList.contains('animate')) {
        el.addEventListener('animationend', function () {
            if (el.getAttribute('data-after-anm') !== null) {
                if (el.getAttribute('data-after-anm') === 'x') {
                    el.style.animationTimingFunction = "linear";
                    el.style.transform = "translateX(0)";
                    el.animate(
                        [ 
                            { transform: 'translateX(0)' },
                            { transform: 'translateX(5px)' },
                            { transform: 'translateX(0)' },
                     
                        ],
                        { duration: 2000, iterations: Infinity }
                    );
                }
                if (el.getAttribute('data-after-anm') === 'y') {
                    el.style.animationTimingFunction = "linear";
                    el.style.transform = "translateY(0)";
                    el.animate(
                        [ 
                            { transform: 'translateY(0)' },
                            { transform: 'translateY(5px)' },
                            { transform: 'translateY(0)' },
                        ],
                        { duration: 2000, iterations: Infinity }
                    );
                }
            }
        });
    }
}

// Website loader
function loader() {
    document.onreadystatechange = function () {
        if (document.readyState === "complete") {
            document.querySelector("body").classList
                .remove('overflow-hidden','pe-none');
            document.querySelector(".page-loader").classList
                .add('d-none');
        }
    };
}

// Add animation to custom element
function animateCss(node, animation, old = null) {
    if (node !== null) {
        node.classList.add('animate__animated', `${animation}`);
        node.addEventListener('animationend', (event) => {
            event.stopPropagation();
            node.classList.remove('animate__animated', `${animation}`);
        }, {once: true});

    }
}

// Contact page form XHR
function contactForm() {
    document
        .querySelector('#contact-form')
        .querySelectorAll('input').forEach(inp => {
            inp.classList.remove('missing');
        });
    document
        .querySelector('#contact-form')
        .querySelector('textarea')
        .classList.remove('missing');

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${locale}contact`), 
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
            if (result.result == true) {
                document.querySelector('#contact-form').reset();
                toaster(result.data, 1);
            } else {
                if (result.input) {
                    var errors = Object.keys(result.input);
                    errors.forEach((err) => {
                        document
                            .querySelector(`[name=${err}]`)
                            .classList.add("missing");
                    });
                    toaster(result.data, 0);
                } else {
                    toaster(result.data, 0);
                }
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    const params = new FormData(
        document.querySelector("#contact-form")
    );
    xhr.send(params);
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
        const popoverTriggerList = document
            .querySelectorAll('[data-bs-toggle="popover"]');
        [...popoverTriggerList].map(
            popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl)
        );
    }
}
popoverInit();

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

// Go to top button
if (
    viewport == 'md' 
    || viewport == 'lg' 
    || viewport == 'xl' 
    || viewport == 'xxl'
) {
    let calcScrollValue = () => {
        let scrollProgress = document.querySelector(".go-to-top");
        let pos = document.documentElement.scrollTop;
        let calcHeight =
            document.documentElement.scrollHeight -
            document.documentElement.clientHeight;
        let scrollValue = Math.round((pos * 100) / calcHeight);
        if (pos == 0) {
            scrollProgress.classList.remove('scrolling');
        } else {
            scrollProgress.classList.add('scrolling');
            if (pos > 100) {
                scrollProgress.style.display = "grid";
            } else {
                scrollProgress.style.display = "none";
            }
        }
        scrollProgress.addEventListener("click", () => {
            document.documentElement.scrollTop = 0;
        });
        scrollProgress.style.background = 'conic-gradient(var(--color-1)'
            +  `${scrollValue}%, var(--color-3) ${scrollValue}%)`;
    };
    if (document.querySelector(".go-to-top") !== null) {
        window.onscroll = calcScrollValue;
        window.onload = calcScrollValue;
    }
}

// Load more blog posts
function loadPosts(slug,limit,offset) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${locale}${slug}/${limit}/${offset}`), 
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
                document.querySelector('#blog-posts').innerHTML += 
                    result.data;
                document.querySelector('.load-more-posts')
                    .setAttribute(
                        'data-offset', 
                        (parseInt(result.count) + parseInt(offset))
                    );
                if (result.more) {
                    document.querySelector('.load-more-posts')
                        .classList.remove('d-none');
                } else {
                    document.querySelector('.load-more-posts')
                        .classList.add('d-none');
                }
                if (typeof LazyLoad != "undefined") {
                    var lazy_loader = new LazyLoad();
                    lazy_loader.update();
                }
            } else {
                toaster(result.data, 0);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    xhr.send();
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

// Make given url to full path
function urlMake(slug) {
    if (slug.startsWith("/")) {
        return `${sysVars.base_url}${slug}`;
    }
    return `${sysVars.base_url}/${slug}`;
}

// Get download link   
function sendId(id,key,slug) {
    var button = document.querySelector('#get-link');
    
    button.classList.add('disabled');
    button.querySelector('span:nth-child(1)')
        .classList.add('d-none');
    if (countdownTime > 0) {
        document.querySelector("#download-counter")
            .classList.remove('d-none');
    }
    button.querySelector('span:nth-child(2)')
        .classList.remove('d-none');

    if (countdownTime > 0) {
        countdown();
    }

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${locale}${slug}/${key}/get-source`), 
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

        button.classList.remove('disabled');

        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {

                button.querySelector('span:nth-child(2)')
                    .classList.add('d-none');
                button.querySelector('span:nth-child(3)')
                    .classList.remove('d-none');
                getLink(key,result.data,slug);

            } else {
                resetCoundown();
                document.getElementById("download-counter").innerHTML = '';
                document.getElementById("download-counter")
                    .classList.add('d-none');
                button.querySelector('span:nth-child(1)')
                    .classList.remove('d-none');
                button.querySelector('span:nth-child(2)')
                    .classList.add('d-none');
                button.querySelector('span:nth-child(3)')
                    .classList.add('d-none');
                toaster(result.data, 0);
            }
            if (document.querySelector('.file-preview-inner [name="g-recaptcha-response"]') !== null) {
                grecaptcha.ready(function() {
                    grecaptcha.execute(`${sysVars.recaptcha_site_key}`, {
                        action: 'shortener'
                    }).then(function(token) {
                        document
                            .querySelector('.file-preview-inner [name="g-recaptcha-response"]')
                            .value = token;
                    });
                });
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    const params = new FormData();
    params.append('id', id);
    if (document.querySelector('.file-preview-inner [name="g-recaptcha-response"]') !== null) {
        var recaptchaEl = document
            .querySelector('.file-preview-inner [name="g-recaptcha-response"]');
        params.append(
            'g-recaptcha-response',
            recaptchaEl.value
        );
    }
    xhr.send(params);
}

// Get redirection link   
function getLink(key,name,slug) {
    var button = document.querySelector('#get-link');

    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        urlMake(`/${locale}${slug}/${key}/get-link`), 
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

        button.classList.remove('disabled');

        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result == true) {

                button.querySelector('span:nth-child(2)')
                    .classList.add('d-none');
                button.querySelector('span:nth-child(3)')
                    .classList.remove('d-none');
                button.setAttribute('href', result.data);

                document.getElementById("download-counter")
                    .classList.add('d-none');

                button.setAttribute('download','');

            } else {
                toaster(result.data, 0);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });

    const params = new FormData();
    params.append('name', name);
    xhr.send(params);
}

// Countdown for the file download page
function countdown() {
    document.getElementById("download-counter").innerHTML = `
    <div class="base-timer position-relative">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <g>
            <circle cx="50" cy="50" r="45"></circle>
            <path
                id="base-timer-remaining"
                stroke-dasharray="283"
                class="base-timer-remaining"
                d="
                M 50, 50
                m -45, 0
                a 45,45 0 1,0 90,0
                a 45,45 0 1,0 -90,0
                "
            ></path>
            </g>
        </svg>
        <span 
            id="base-timer-label" 
            class="base-timer-label position-absolute d-flex align-items-center justify-content-center">
            ${timeLeft}
        </span>
    </div>`;
    
    timerInterval = setInterval(() => {
        timePassed = timePassed += 1;
        timeLeft = countdownTime - timePassed;
        document.getElementById("base-timer-label").innerHTML = timeLeft;
        setCircleDasharray();
    
        if (timeLeft === 0) {
            clearInterval(timerInterval);
        }
    }, 1000);

}
function calculateTimeFraction() {
    const rawTimeFraction = timeLeft / countdownTime;
    return rawTimeFraction - (1 / countdownTime) * (1 - rawTimeFraction);
}
function setCircleDasharray() {
    const circleDasharray = `${(
      calculateTimeFraction() * 283
    ).toFixed(0)} 283`;
    document
      .getElementById("base-timer-remaining")
      .setAttribute("stroke-dasharray", circleDasharray);
}
function resetCoundown() {
    countdownTime = parseInt(sysVars.redirection_countdown);
    timePassed = 0;
    timeLeft = countdownTime;
    clearInterval(timerInterval);
}