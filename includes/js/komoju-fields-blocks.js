function registerPaymentMethod(paymentMethod) {
    const settings = window.wc.wcSettings.getSetting(`${paymentMethod.title}_data`, {});
    const label = window.wp.htmlEntities.decodeEntities(settings.title) || window.wp.i18n.__(paymentMethod.title, paymentMethod.title);

    // Need to rework WC_Gateway_Komoju_Single_Slug::payment_fields to get the komoju-fields element to show up
    const createContent = () => {
        return '';
    };
    
    // Need to rework WC_Gateway_Komoju_Single_Slug::payment_fields to get the komoju-fields element to show up
    const createEdit = () => {
        return '';
    };
    
    const Block_Gateway = {
        name: paymentMethod.title,
        label: label,
        content: createContent(),
        edit: createEdit(),
        canMakePayment: () => true,
        ariaLabel: label,
        supports: {
            features: settings.supports,
        },
    };
    window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);
}

window.addEventListener('load', () => {
    paymentMethodData = window.wc.wcSettings.getSetting('paymentMethodData', {});
    Object.values(paymentMethodData).forEach((value) => {
        registerPaymentMethod(value);
    });
});