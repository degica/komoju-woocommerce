var x = Object.defineProperty;
var K = (i,s)=>{
    for (var e in s)
        x(i, e, {
            get: s[e],
            enumerable: !0
        })
}
;
var c = {
    CDN: "https://multipay.komoju.com",
    ENV: "production",
    HONEYBADGER_API_KEY: "hbp_aKD3g1hCztStGSvVXmhzrbEtOrtyKm3CqBxX",
    GIT_REV: "af25e949e91d20f2e2276824995c8f8fd5c433ed"
};
function y(i) {
    window.komojuTranslations || (window.komojuTranslations = {
        en: {},
        ja: {}
    });
    for (let s of Object.keys(window.komojuTranslations))
        window.komojuTranslations[s] = {
            ...window.komojuTranslations[s],
            ...i[s]
        }
}
var E = {};
K(E, {
    en: ()=>C,
    ja: ()=>P
});
var C = {
    "customer-fee-will-be-charged": "A fee of %{fee} will be included.",
    "dynamic-currency-notice": "Payment will be made in %{currency}: %{original} \u2192 %{converted}.",
    "dynamic-currency-notice-with-fee": "Payment will be made in %{currency}: %{original} \u2192 %{converted}. (total: %{total})",
    "payment-method-unavailable": "This payment method is currently unavailable.",
    "verification-failed": "Verification failed.",
    close: "Close"
}
  , P = {
    "customer-fee-will-be-charged": "%{fee}\u306E\u624B\u6570\u6599\u304C\u8FFD\u52A0\u3055\u308C\u307E\u3059\u3002",
    "dynamic-currency-notice": "\u652F\u6255\u3044\u306F%{currency}\u3067\u6C7A\u6E08\u3055\u308C\u307E\u3059: %{original} \u2192 %{converted}\u3002",
    "dynamic-currency-notice-with-fee": "\u652F\u6255\u3044\u306F%{currency}\u3067\u6C7A\u6E08\u3055\u308C\u307E\u3059: %{original} \u2192 %{converted}\u3002(\u5408\u8A08%{total})",
    "payment-method-unavailable": "\u3053\u306E\u652F\u6255\u3044\u65B9\u6CD5\u306F\u73FE\u5728\u3054\u5229\u7528\u3044\u305F\u3060\u3051\u307E\u305B\u3093\u3002",
    "verification-failed": "\u8A8D\u8A3C\u306B\u5931\u6557\u3057\u307E\u3057\u305F\u3002",
    close: "\u9589\u3058\u308B"
};
var w = class {
    constructor(s) {
        this._sendWindow = new Promise(e=>this._setSendWindow = e),
        this._receiveWindow = new Promise(e=>this._setReceiveWindow = e),
        this.messageHandler = e=>{
            this.origin !== "*" && e.origin !== this.origin || this.handleMessage(e)
        }
        ,
        this.id = s ?? crypto.randomUUID(),
        this.origin = "*",
        this.promises = new Map,
        this.listeners = new Map
    }
    setup(s) {
        this._setSendWindow ? (this._setSendWindow(s.send),
        this._setSendWindow = void 0) : this._sendWindow = Promise.resolve(s.send),
        this._setReceiveWindow ? (this._setReceiveWindow(s.receive),
        this._setReceiveWindow = void 0) : this._receiveWindow = Promise.resolve(s.receive),
        s.receive.addEventListener("message", this.messageHandler)
    }
    send(s) {
        let e = {
            ...s,
            brokerId: this.id,
            id: crypto.randomUUID()
        }
          , t = null
          , o = new Promise((n,r)=>{
            t = n
        }
        );
        if (!t)
            throw new Error("Broker is busted");
        return this.promises.set(e.id, {
            promise: o,
            resolve: t
        }),
        this._sendWindow.then(n=>n.postMessage(e, this.origin)).then(()=>o)
    }
    receive(s, e) {
        this.listeners.set(s, e)
    }
    async handleMessage(s) {
        let e = s.data;
        if (e.brokerId === this.id)
            if (e.type === "ack") {
                let t = e
                  , o = this.promises.get(t.id);
                if (!o)
                    return;
                o.resolve(t.response),
                this.promises.delete(t.id)
            } else {
                let t = this.listeners.get(e.type)
                  , o = {
                    type: "ack",
                    brokerId: this.id,
                    id: e.id
                };
                t && (o.response = await t(e) ?? void 0),
                await this._sendWindow.then(n=>n.postMessage(o, this.origin))
            }
    }
    destroy() {
        return this._receiveWindow.then(s=>s.removeEventListener("message", this.messageHandler))
    }
}
;
function k(i, s, e, t) {
    if (!i.komojuApi)
        throw new Error("KOMOJU API URL is null");
    if (!i.publishableKey)
        throw new Error("KOMOJU publishable-key not set");
    return fetch(`${i.komojuApi}${e}`, {
        method: s,
        headers: {
            accept: "application/json",
            "content-type": "application/json",
            authorization: `Basic ${btoa(`${i.publishableKey}:`)}`,
            "komoju-via": "fields"
        },
        body: t ? JSON.stringify(t) : void 0
    })
}
y(E);
var m = class extends HTMLElement {
    constructor() {
        super();
        this._submitting = !1;
        this.session = null;
        this.broker = new w,
        this.dialog = document.createElement("dialog"),
        this.listenToMessagesFromIframe(this.broker),
        this.dialog.style.width = "80%",
        this.dialog.style.height = "80%",
        this.dialog.style.padding = "0"
    }
    static get observedAttributes() {
        return ["komoju-api", "session", "session-id", "publishable-key", "payment-type", "locale", "theme", "token", "name"]
    }
    get theme() {
        return this.getAttribute("theme")
    }
    set theme(e) {
        this.setAttribute("theme", e ?? "")
    }
    get komojuApi() {
        return this.getAttribute("komoju-api") ?? "https://komoju.com"
    }
    set komojuApi(e) {
        this.setAttribute("komoju-api", e)
    }
    connectedCallback() {
        let e = new URLSearchParams;
        e.append("broker", this.broker.id),
        this.hasAttribute("komoju-api") && e.append("api", this.getAttribute("komoju-api"));
        let t = document.createElement("iframe");
        t.setAttribute("sandbox", "allow-scripts allow-same-origin"),
        t.setAttribute("allow", "payment *"),
        t.title = "KOMOJU secure payment fields",
        t.src = `${c.CDN}/fields-iframe.html#${e.toString()}`,
        t.style.border = "none",
        t.style.width = "100%",
        t.style.overflow = "hidden",
        t.height = "50",
        t.addEventListener("load", ()=>{
            if (!t.contentWindow)
                throw new Error("KOMOJU Fields: iframe had no contentWindow");
            this.broker.setup({
                send: t.contentWindow,
                receive: window
            })
        }
        ),
        this.replaceChildren(t, this.dialog);
        let o = this.parentElement;
        for (; o && o.tagName !== "FORM"; )
            o = o.parentElement;
        if (!o)
            return;
        let n = o
          , r = n.parentElement;
        if (!r)
            return;
        let a = l=>{
            this._submitting || this.offsetParent !== null && l.target === n && (l.preventDefault(),
            l.stopImmediatePropagation(),
            this.submit(l))
        }
        ;
        r.addEventListener("submit", a, !0),
        this.formSubmitHandler = {
            form: n,
            target: r,
            handler: a
        }
    }
    disconnectedCallback() {
        this.formSubmitHandler && (this.formSubmitHandler.target.removeEventListener("submit", this.formSubmitHandler.handler, !0),
        this.formSubmitHandler = void 0),
        this.broker.destroy()
    }
    listenToMessagesFromIframe(e) {
        e.receive("dispatch-event", t=>{
            let o = new CustomEvent(t.name,{
                detail: t.detail,
                bubbles: !0,
                composed: !0,
                cancelable: !0
            });
            return t.name === "komoju-session-change" && (this.session = t.detail.session),
            {
                type: "dispatch-result",
                cancel: !this.dispatchEvent(o)
            }
        }
        ),
        e.receive("resize", t=>{
            let o = this.querySelector("iframe");
            o.height = t.height
        }
        ),
        e.receive("dialog-start", async t=>({
            type: "dialog-result",
            result: await this.show3DSDialog(t.url)
        }))
    }
    async attributeChangedCallback(e, t, o) {
        this.broker.send({
            type: "attr",
            attr: e,
            value: o
        })
    }
    async submit(e) {
        if (this.token)
            return this.token;
        let t = await this.broker.send({
            type: "submit"
        });
        if (t?.type !== "submit-result")
            throw new Error(`Unexpected submit response from komoju-fields iframe ${JSON.stringify(t)}`);
        let o = t;
        if (o.errors) {
            this.dispatchEvent(new CustomEvent("komoju-invalid",{
                detail: {
                    errors: o.errors
                },
                bubbles: !0,
                composed: !0
            }));
            return
        }
        if (o.pay) {
            o.pay.error || await this.handlePayResult(o.pay);
            return
        }
        if (o.token && e && this.formSubmitHandler) {
            let n = this.formSubmitHandler.form
              , r = this.getAttribute("name") ?? "komoju_token"
              , a = document.querySelector(`input[name="${r}"]`);
            a || (a = document.createElement("input"),
            a.type = "hidden",
            a.name = r,
            n.append(a)),
            a.value = o.token.id,
            this.submitParentForm();
            return
        }
        if (o.token)
            return this.token = o.token,
            o.token;
        throw new Error("KOMOJU Fields bug: submit result was not handled")
    }
    async handlePayResult(e) {
        let t = this.session;
        if (!t)
            throw new Error("handlePayResult called without a session");
        let o = e.payment?.payment_details?.instructions_url;
        if (o) {
            let n = new URL(t.return_url ?? t.session_url);
            n.searchParams.append("session_id", t.id),
            this.showInstructionsDialog(o, n.toString())
        } else if (e.redirect_url)
            window.location.href = e.redirect_url;
        else
            throw new Error(`payResult should have a redirect_url but doesnt ${JSON.stringify(e)}`)
    }
    async submitParentForm() {
        if (!this.formSubmitHandler)
            throw new Error("KOMOJU Fields: tried to submit nonexistent parent form");
        let e = this.formSubmitHandler.form;
        try {
            this._submitting = !0;
            let t = new Event("submit",{
                bubbles: !0,
                cancelable: !0
            });
            e.dispatchEvent(t) ? e.submit() : this.broker.send({
                type: "end-fade"
            })
        } finally {
            this._submitting = !1
        }
    }
    showInstructionsDialog(e, t) {
        let o = this.dialog
          , n = j(e);
        n.style.height = "90%";
        let r = document.createElement("a")
          , a = document.createElement("komoju-i18n");
        a.key = "close",
        r.append(a),
        r.classList.add("komoju-fields-close-dialog"),
        r.href = t,
        r.style.display = "block",
        r.style.padding = "10px",
        o.replaceChildren(r, n),
        o.showModal()
    }
    show3DSDialog(e) {
        return new Promise((t,o)=>{
            let n = this.dialog
              , r = c.CDN
              , a = j(e);
            window.addEventListener("message", async l=>{
                if (l.origin === r)
                    try {
                        if (!l.data?._komojuFields)
                            return;
                        let {secureTokenId: d} = l.data;
                        if (!d)
                            throw new Error("No secureTokenId in message");
                        let b = {
                            komojuApi: this.komojuApi,
                            publishableKey: this.getAttribute("publishable-key") ?? ""
                        }
                          , u = await k(b, "GET", `/api/v1/secure_tokens/${d}`);
                        if (u.status >= 400) {
                            let _ = await u.json();
                            t({
                                error: _
                            });
                            return
                        }
                        let L = await u.json();
                        n.close(),
                        t({
                            secureToken: L
                        })
                    } catch (d) {
                        o(d)
                    }
            }
            ),
            n.replaceChildren(a),
            n.showModal()
        }
        )
    }
}
;
for (let i of m.observedAttributes)
    i === "session" || i === "theme" || i === "komoju-api" || Object.defineProperty(m.prototype, H(i), {
        get() {
            return this.getAttribute(i)
        },
        set(s) {
            s === null ? this.removeAttribute(i) : this.setAttribute(i, s)
        }
    });
function H(i) {
    return i.split("-").reduce((s,e)=>s + e.charAt(0).toUpperCase() + e.slice(1))
}
function j(i) {
    let s = document.createElement("iframe");
    return s.setAttribute("sandbox", "allow-scripts allow-forms allow-same-origin"),
    s.src = i,
    s.style.border = "none",
    s.style.width = "100%",
    s.style.height = "100%",
    s
}
var v = `<template id="radio-template">
  <label class="radio">
    <input type="radio" name="payment-type">
    <img class="picker-icon">
    <komoju-i18n></komoju-i18n>
  </label>
</template>

<div id="picker">
</div>

<style>
  #picker {
    box-sizing: border-box;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 4px;
    justify-content: space-between;
  }

  .radio {
    display: flex;
    flex-direction: row;
    width: 100%;
    gap: 12px;
  }

  img {
    width: 42px;
    background: white;
    border-radius: 6px;
    border: 1px solid white;
  }
</style>
`;
function M(i, s) {
    if (!i.parentElement)
        throw new Error("KOMOJU Fields bug: radio input has no parent");
    if (!i.parentElement.classList.contains("radio"))
        throw new Error("KOMOJU Fields bug: radio input parent has no .radio class");
    i.checked && i.parentElement.classList.add("checked"),
    i.addEventListener("change", ()=>{
        s.querySelectorAll(".radio.checked").forEach(e=>e.classList.remove("checked")),
        i.parentElement.classList.add("checked")
    }
    )
}
var h = new Set;
h.add("bank_transfer");
h.add("credit_card");
h.add("konbini");
h.add("offsite");
var T = h;
var A = "en";
function S(i, s) {
    i.querySelectorAll("komoju-i18n").forEach(e=>{
        e.render(s)
    }
    )
}
var p = class extends HTMLElement {
    static get observedAttributes() {
        return ["key"]
    }
    get key() {
        return this.getAttribute("key")
    }
    set key(s) {
        this.setAttribute("key", s ?? "")
    }
    connectedCallback() {
        this.render()
    }
    attributeChangedCallback(s, e, t) {
        s === "key" && this.render()
    }
    findLocale() {
        let s = this.parentElement;
        for (; s && !s.getAttribute("locale"); )
            s = s.parentElement;
        return s?.getAttribute("locale") ?? A
    }
    render(s) {
        if (!this.key)
            return;
        s || (s = this.findLocale()),
        Object.keys(window.komojuTranslations).includes(s) || (s = A);
        let e = s.substring(0, 2)
          , t = window.komojuTranslations[e][this.key];
        if (!t) {
            console.error(`KOMOJU bug: missing translation for key: ${this.key}`);
            return
        }
        let o = t.match(/%\{[\w-]+\}/g);
        if (o) {
            let n = t;
            o.forEach(r=>{
                let a = r.replace(/%{|}/g, "")
                  , l = this.dataset[a];
                l && (n = n.replace(r, l))
            }
            ),
            this.textContent = n;
            return
        }
        this.textContent = t
    }
}
;
var f = class extends HTMLElement {
    get theme() {
        return this.getAttribute("theme")
    }
    set theme(s) {
        this.setAttribute("theme", s ?? "")
    }
    applyTheme() {
        let s = this.shadowRoot ?? document;
        this.theme === null ? s.querySelectorAll("#theme,#inline-theme").forEach(e=>e.remove()) : this.theme.startsWith("http") || this.theme.startsWith("/") || this.theme.startsWith("data:") ? this.applyExternalTheme(s, this.theme) : this.applyInlineTheme(s, this.theme)
    }
    applyInlineTheme(s, e) {
        s.querySelectorAll("#theme,#inline-theme").forEach(o=>o.remove());
        let t = document.createElement("style");
        t.id = "inline-theme",
        t.textContent = e,
        this.appendStyleTag(t)
    }
    applyExternalTheme(s, e) {
        s.querySelectorAll("#inline-theme").forEach(o=>o.remove());
        let t = s.querySelector("#theme");
        t ? t.href !== this.theme && (t.href = e) : (t = document.createElement("link"),
        t.id = "theme",
        t.rel = "stylesheet",
        t.href = e,
        this.appendStyleTag(t))
    }
    appendStyleTag(s) {
        this.shadowRoot ? this.shadowRoot.append(s) : document.head.append(s)
    }
}
;
var g = class extends f {
    constructor() {
        super();
        this.sessionChangedHandler = null;
        let e = this.attachShadow({
            mode: "open"
        });
        e.innerHTML = v;
        let t = document.createElement("link");
        t.rel = "stylesheet",
        t.href = `${c.CDN}/static/shared.css`,
        e.append(t)
    }
    static get observedAttributes() {
        return ["locale", "theme"]
    }
    get fields() {
        return this.getAttribute("fields")
    }
    set fields(e) {
        this.setAttribute("fields", e ?? "")
    }
    get locale() {
        return this.getAttribute("locale")
    }
    set locale(e) {
        this.setAttribute("locale", e ?? "")
    }
    async connectedCallback() {
        let e = this.komojuFieldsElement()
          , t = {
            element: e,
            handler: o=>{
                this.render(e)
            }
        };
        await this.setupPaymentTypesI18n(),
        this.render(e),
        e.addEventListener("komoju-session-change", t.handler),
        this.sessionChangedHandler = t
    }
    disconnectedCallback() {
        this.sessionChangedHandler && this.sessionChangedHandler.element.removeEventListener("komoju-session-change", this.sessionChangedHandler.handler)
    }
    async attributeChangedCallback(e, t, o) {
        !this.shadowRoot || (e === "locale" && o && t !== o ? (S(this.shadowRoot, o),
        this.updatePickerLocale(o)) : e === "theme" && this.applyTheme())
    }
    komojuFieldsElement() {
        return this.fields ? document.querySelector(`#${this.fields}`) : document.querySelector("komoju-fields")
    }
    render(e) {
        if (!e.session || !this.shadowRoot)
            return;
        let t = this.shadowRoot.getElementById("picker")
          , o = this.shadowRoot.getElementById("radio-template");
        if (!t)
            throw new Error("KOMOJU Fields bug: <komoju-picker> wrong shadow DOM (no picker)");
        if (!o)
            throw new Error("KOMOJU Fields bug: <komoju-picker> wrong shadow DOM (no template)");
        this.locale || (this.locale = e.session.default_locale.substring(0, 2)),
        this.updatePickerLocale(this.locale),
        t.replaceChildren();
        let n = 0;
        for (let r of e.session.payment_methods) {
            let a = r.offsite ? "offsite" : r.type;
            if (e.hasAttribute("token") && !T.has(a))
                continue;
            let l = o.content.cloneNode(!0)
              , d = l.querySelector("input")
              , b = l.querySelector("img")
              , u = l.querySelector("komoju-i18n");
            (n === 0 || e.paymentType === r.type) && (d.checked = !0),
            d.addEventListener("change", ()=>{
                e.paymentType = r.type
            }
            ),
            M(d, this.shadowRoot),
            b.src = `${e.komojuApi}/payment_methods/${r.type}.svg`,
            u.key = r.type,
            t.append(l),
            n += 1
        }
        !this.theme && e.theme && (this.theme = e.theme,
        this.applyTheme())
    }
    async setupPaymentTypesI18n() {
        let e = this.komojuFieldsElement()
          , t = await k(e, "GET", "/api/v1/payment_methods");
        this.komojuPaymentMethods = await t.json();
        for (let o of this.komojuPaymentMethods) {
            let n = {
                en: {
                    [o.type_slug]: o.name_en
                },
                ja: {
                    [o.type_slug]: o.name_ja
                },
                ko: {
                    [o.type_slug]: o.name_ko
                }
            };
            y(n)
        }
    }
    updatePickerLocale(e) {
        if (!this.shadowRoot)
            return;
        let t = this.shadowRoot.getElementById("picker");
        t && t.setAttribute("locale", e)
    }
}
;
window.customElements.define("komoju-fields", m);
window.customElements.define("komoju-picker", g);
window.customElements.define("komoju-i18n", p);
window.komojuReportError = (i,s)=>{
    console.error(i, s)
}
;
if (window.komojuErrorReporting !== !1) {
    (async()=>{
        let e = await import("/extras/error-reporting/module.js");
        window.komojuReportError = e.reportError
    }
    )();
    let i = s=>{
        let e = [/\/fields\.js:\d+:\d+\n/, /\/fields\/[\w-]+\/module\.js\n:\d+:\d+/, /\/extras\/[\w-]+\/module\.js\n:\d+:\d+/]
          , t = s instanceof ErrorEvent ? s.error : s.reason;
        t instanceof Error && (!t.stack || !e.find(o=>o.test(t.stack)) || window.komojuReportError(t))
    }
    ;
    window.addEventListener("error", i),
    window.addEventListener("unhandledrejection", i)
}

// function registerPaymentMethod(paymentMethod) {
//     const settings = window.wc.wcSettings.getSetting(`${paymentMethod.title}_data`, {});
//     const label = window.wp.htmlEntities.decodeEntities(settings.title) || window.wp.i18n.__(paymentMethod.title, paymentMethod.title);
//     const Content = () => {
//         return window.wp.htmlEntities.decodeEntities(settings.description || '');
//     };
//     const Block_Gateway = {
//         name: paymentMethod.title,
//         label: label,
//         content: Object(window.wp.element.createElement)(Content, null),
//         edit: Object(window.wp.element.createElement)(Content, null),
//         canMakePayment: () => true,
//         ariaLabel: label,
//         supports: {
//             features: settings.supports,
//         },
//     };
//     window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);
// }

// window.addEventListener('load', () => {
//     paymentMethodData = window.wc.wcSettings.getSetting('paymentMethodData', {});
//     Object.values(paymentMethodData).forEach((value) => {
//         registerPaymentMethod(value);
//     });
// });
