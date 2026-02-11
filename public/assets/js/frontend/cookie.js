/*
    Copyright AneonTech
    aneontech.help@gmail.com
*/

"use strict";

var cookies = document.querySelector('#cookies-policy');
var reset = document.querySelector('.cookiereset');

if(reset) {
    reset.addEventListener('submit', (event) => resetCookies(event))
}

if(cookies) {
    var essentials = cookies.querySelector('#cookies-essentials');
    var all = cookies.querySelector('#cookies-all');
    var preferences = cookies.querySelector('#customize-cookies-form');

    essentials.addEventListener(
        'submit', (event) => acceptEssentialsCookies(event)
    );
    all.addEventListener(
        'submit', (event) => acceptAllCookies(event)
    );
    preferences.addEventListener(
        'submit', (event) => configureCookies(event)
    );
}

function configureCookies(event)  {
    event.preventDefault();
    window.LaravelCookieConsent.configure(new FormData(event.target));
    closeCookieBox();
}

function acceptAllCookies(event) {
    event.preventDefault();
    window.LaravelCookieConsent.acceptAll();
    closeCookieBox();
}

function acceptEssentialsCookies(event) {
    event.preventDefault();
    window.LaravelCookieConsent.acceptEssentials();
    closeCookieBox();
}

function resetCookies(event) {
    event.preventDefault();
    if(document.querySelector('#cookies-policy')) return;
    window.LaravelCookieConsent.reset();
}

function closeCookieBox() {
    if (document.querySelector('#cookies-policy')) {
        document.querySelector('#cookies-policy')
            .classList.remove('cookies-policy-opened');
    }
    if (document.querySelector('.cookie-opener')) {
        document.querySelector('.cookie-opener').remove();
    }
    setTimeout(() => {
        if (document.querySelector('.go-to-top')) {
            document.querySelector('.go-to-top')
                .setAttribute('data-cookies','accepted')
        }    
    }, 500);
}