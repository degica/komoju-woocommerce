const { useEffect, useCallback, useRef, createElement } = window.wp.element;

function registerPaymentMethod(paymentMethod) {
    let name = `${paymentMethod.id}`

    const settings = window.wc.wcSettings.getSetting(`${name}_data`, {});

    const komojuField = createElement('komoju-fields', {
        'name': 'komoju_payment_token',
        'komoju-api': settings.komojuApi,
        'publishable-key': settings.publishableKey,
        'session': settings.session,
        'payment-type': settings.paymentType,
        style: { display: 'none' },
    });

    const label = createElement('div', {
        style: { display: 'block', alignItems: 'center', justifyContent: 'center' }
    },
        window.wp.htmlEntities.decodeEntities(settings.title || window.wp.i18n.__('NULL GATEWAY', 'test_komoju_gateway')),
        createElement('img', {
            src: settings.icon,
            alt: settings.title || 'Payment Method Icon',
            style: { display: 'flex', alignItems: 'center', justifyContent: 'center', marginLeft: '10px' }
        }),
        komojuField,
    );
    const Content = () => {
        return window.wp.htmlEntities.decodeEntities(settings.description || '');
    };

    const Block_Gateway = {
        name: name,
        label: label,
        content: createElement(Content, null),
        edit: createElement(Content, null),
        canMakePayment: () => true,
        ariaLabel: settings.title || 'Payment Method',
        supports: {
            features: settings.supports || ['products'],
        },
    };
    window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);
}

paymentMethodData = window.wc.wcSettings.getSetting('paymentMethodData', {});
Object.values(paymentMethodData).forEach((value) => {
    registerPaymentMethod(value);
});
