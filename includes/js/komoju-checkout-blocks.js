const KomojuPaymentModule = (() => {
    const { useEffect, useCallback, useRef, createElement } = window.wp.element;

    function registerPaymentMethod(paymentMethod) {
        const name = `${paymentMethod.id}`
        const settings = window.wc.wcSettings.getSetting(`${name}_data`, {});
        const description = window.wp.htmlEntities.decodeEntities(settings.description || window.wp.i18n.__('title', 'komoju_woocommerce'));
        const descriptionDiv = createElement('div',
            {
                id: `${name}_description`,
                style: { display: 'none', alignItems: 'center', justifyContent: 'center', width: '100%' }
            },
            description
        );

        const label = createElement('div', {
            style: { display: 'flex', alignItems: 'center', justifyContent: 'space-between', width: '95%', flexWrap: 'wrap' }
        },
            createElement('span', { style: { width: 'auto' } }, window.wp.htmlEntities.decodeEntities(settings.title || window.wp.i18n.__('title', 'komoju_woocommerce'))),
            settings.icon ?
                createElement('img', {
                    src: settings.icon,
                    alt: settings.title || 'Payment Method Icon',
                    style: { display: 'flex', alignItems: 'center', justifyContent: 'center', marginLeft: '10px' }
                }) : null,
            descriptionDiv
        );

        const KomojuComponent = ({ activePaymentMethod, emitResponse, eventRegistration }) => {
            const { onPaymentSetup } = eventRegistration;
            const komojuFieldEnabledMethods = ['komoju_credit_card', 'komoju_konbini', 'komoju_bank_transfer']

            useEffect(() => {
                if (paymentMethod.id != activePaymentMethod) return;

                const komojuField = document.querySelector(`komoju-fields[payment-type='${paymentMethod.paymentType}']`);
                if (komojuFieldEnabledMethods.includes(paymentMethod.id)) komojuField.style.display = 'block';
                const descriptionElement = document.getElementById(`${name}_description`);
                descriptionElement.style.display = 'block';

                const unsubscribe = onPaymentSetup(async () => {
                    if (paymentMethod.id != activePaymentMethod) return;
                    if (!komojuFieldEnabledMethods.includes(paymentMethod.id)) return;

                    if (!(komojuField || typeof komojuField.submit === 'function')) {
                        return {
                            type: emitResponse.responseTypes.ERROR,
                            message: 'There was an error',
                        };
                    }

                    async function submitFields(fields) {
                        return new Promise(async (resolve, reject) => {
                            fields.addEventListener("komoju-invalid", reject);
                            const token = await fields.submit();
                            fields.removeEventListener("komoju-invalid", reject);
                            if (token) resolve(token);
                        });
                    }

                    try {
                        const token = await submitFields(komojuField);
                        return {
                            type: emitResponse.responseTypes.SUCCESS,
                            meta: {
                                paymentMethodData: {
                                    komoju_payment_token: token.id
                                },
                            },
                        };
                    } catch (e) {
                        return {
                            type: emitResponse.responseTypes.ERROR,
                            message: e.detail.errors[0].message,
                        };
                    }
                });

                return () => {
                    komojuField.style.display = 'none';
                    descriptionElement.style.display = 'none';
                    unsubscribe();
                };
            }, [
                activePaymentMethod,
                emitResponse.responseTypes.ERROR,
                emitResponse.responseTypes.SUCCESS
            ]);

            const komojuFields = createElement('komoju-fields', {
                'token': '',
                'name': 'komoju_payment_token',
                'komoju-api': settings.komojuApi,
                'publishable-key': settings.publishableKey,
                'session': settings.session,
                'payment-type': settings.paymentType,
                'locale': settings.locale,
                style: { display: 'none' },
            });

            return komojuFields;
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

    return {
        init: () => {
            const paymentMethodData = window.wc.wcSettings.getSetting('paymentMethodData', {});
            Object.values(paymentMethodData).forEach(registerPaymentMethod);
        }
    };
})();

KomojuPaymentModule.init();