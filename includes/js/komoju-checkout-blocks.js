function registerPaymentMethod(paymentMethod) {
    let name = `${paymentMethod.id}`

    const settings = window.wc.wcSettings.getSetting(`${name}_data`, {});
    const label = window.wp.htmlEntities.decodeEntities(settings.title) || window.wp.i18n.__('NULL GATEWAY', 'test_komoju_gateway');
    const Content = () => {
        return window.wp.htmlEntities.decodeEntities(settings.description || '');
    };

    const Block_Gateway = {
        name: name,
        label: label,
        content: Object(window.wp.element.createElement)(Content, null),
        edit: Object(window.wp.element.createElement)(Content, null),
        canMakePayment: () => true,
        ariaLabel: label,
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