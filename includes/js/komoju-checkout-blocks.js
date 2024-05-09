const { useEffect, useCallback, useRef, createElement } = window.wp.element;

function registerPaymentMethod(paymentMethod) {
    let name = `${paymentMethod.id}`
    const settings = window.wc.wcSettings.getSetting(`${name}_data`, {});

    const komojuFields = createElement('komoju-fields', {
        'token': '',
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
        komojuFields
    );

    const KomojuComponent = ({ activePaymentMethod, emitResponse, eventRegistration }) => {
        const { onPaymentSetup } = eventRegistration;

        useEffect(() => {
            const komojuField = document.querySelector(`komoju-fields[payment-type='${paymentMethod.paymentType}']`);
            komojuField.style.display = 'block';

            const unsubscribe = onPaymentSetup(async () => {
                console.log('onPaymentSetup', paymentMethod.id, activePaymentMethod)
                if (paymentMethod.id != activePaymentMethod) return;
                // Exceptions
                if (paymentMethod.id === 'komoju_paidy') return;
                if (paymentMethod.id === 'komoju_net_cash') return;
                if (paymentMethod.id === 'komoju_bit_cash') return;
                if (paymentMethod.id === 'komoju_web_money') return;
                if (paymentMethod.id === 'komoju_pay_easy') return;

                if (komojuField && typeof komojuField.submit === 'function') {
                    var submitResult = await komojuField.broker.send({ type: 'submit' });

                    if (submitResult.errors && submitResult.errors.length > 0) {
                        return {
                            type: emitResponse.responseTypes.ERROR,
                            message: submitResult.errors[0].message,
                        };
                    }

                    const komoju_payment_token = submitResult.token.id;

                    return {
                        type: emitResponse.responseTypes.SUCCESS,
                        meta: {
                            paymentMethodData: {
                                komoju_payment_token
                            },
                        },
                    };
                }

                return {
                    type: emitResponse.responseTypes.ERROR,
                    message: 'There was an error',
                };
            });

            return () => {
                komojuField.style.display = 'none';
                unsubscribe();
            };
        }, [
            activePaymentMethod,
            emitResponse.responseTypes.ERROR,
            emitResponse.responseTypes.SUCCESS
        ]);
    };

    const Block_Gateway = {
        name: name,
        label: label,
        content: createElement(KomojuComponent, null),
        edit: createElement(KomojuComponent, null),
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
