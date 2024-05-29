/// <reference types="cypress" />

describe('KOMOJU for WooCommerce: Checkout', () => {
  beforeEach(() => {
    cy.installWordpress();
    cy.signinToWordpress();
    cy.installWooCommerce();
    cy.installKomoju();
  })

  it('lets me make a payment using the specialized konbini gateway', () => {
    cy.setupKomoju(['konbini', 'credit_card']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_konbini');
    cy.goToStore();
    cy.addItemAndProceedToCheckout();
    cy.fillInAddress();

    cy.get('label[for="radio-control-wc-payment-method-options-komoju_konbini"]').click();
    cy.get('komoju-fields[payment-type="konbini"]').should('be.visible');
    cy.get('komoju-fields[payment-type="konbini"] iframe').iframe().find('komoju-host').should('exist');
    cy.get('komoju-fields[payment-type="konbini"] iframe').iframe().find('#kb-name').should('exist');

    cy.get('komoju-fields[payment-type="konbini"] iframe').iframe().find('#kb-name').type('Test Test');
    cy.get('komoju-fields[payment-type="konbini"] iframe').iframe().find('#kb-email').type('test@example.com');
    cy.get('komoju-fields[payment-type="konbini"] iframe').iframe().find('[value="family-mart"]').click();

    cy.contains('Place Order').click();
    cy.get('.blockUI,.blockOverlay').should('not.exist');
    cy.wait(2000);

    // It can take a crazy long time to reach KOMOJU from here...
    cy.contains('How to make a payment at Family Mart', { matchCase: false, timeout: 20000 }).should('be.visible');
    cy.location('pathname').should('include', '/sessions/');
    cy.contains('Return to').click();
    cy.contains('Thank you. Your order has been received.').should('be.visible');
  })

  it('lets me make a payment using the specialized credit card gateway', () => {
    cy.setupKomoju(['credit_card']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_credit_card');
    cy.contains('Save changes').click();
    cy.goToStore();

    cy.addItemAndProceedToCheckout();
    cy.fillInAddress();

    cy.get('komoju-fields[payment-type="credit_card"]').should('be.visible');
    cy.get('komoju-fields[payment-type="credit_card"] iframe').iframe().find('komoju-host').should('exist');
    cy.wait(2000);
    cy.get('komoju-fields[payment-type="credit_card"] iframe').iframe().find('#cc-name').should('exist');
    cy.get('komoju-fields[payment-type="credit_card"] iframe').iframe().find('#cc-name').type('Test Test');
    cy.get('komoju-fields[payment-type="credit_card"] iframe').iframe().find('#cc-number').type('4111111111111111');
    cy.get('komoju-fields[payment-type="credit_card"] iframe').iframe().find('#cc-exp').type('1299');
    cy.get('komoju-fields[payment-type="credit_card"] iframe').iframe().find('#cc-cvc').type('111');

    cy.contains('Place Order').click();
    cy.wait(2000);
    cy.contains('Thank you. Your order has been received.').should('be.visible');
  })

  it('lets me use the specialized WebMoney gateway, despite it being unsupported by Fields', () => {
    cy.setupKomoju(['credit_card', 'konbini', 'web_money']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_web_money');
    cy.contains('Save changes').click();
    cy.goToStore();

    cy.addItemAndProceedToCheckout();
    cy.fillInAddress();

    cy.get('label[for="radio-control-wc-payment-method-options-komoju_web_money"]').click();
    // cy.get('komoju-fields[payment-type="web_money"]').should('be.visible');
    cy.get('komoju-fields[payment-type="web_money"] iframe').iframe().find('komoju-host').should('exist');

    cy.contains('Place Order').click();
    cy.get('.blockUI,.blockOverlay').should('not.exist');
    cy.wait(2000);
    cy.contains('WebMoney Details', { matchCase: false }).should('be.visible');
    cy.location('pathname').should('include', '/sessions/');
  });

  it('lets me turn checkout icons on and off', () => {
    cy.setupKomoju(['konbini', 'credit_card']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_credit_card');

    cy.get('[data-gateway_id="komoju_credit_card"] a.button')
      .click()

    cy.get('#woocommerce_komoju_credit_card_showIcon').then(input => {
      cy.log(input.attr('checked'));
      if (input.attr('checked'))  cy.wrap(input).click()
    })
    cy.contains('Save changes').click()
    cy.contains('Your settings have been saved.').should('exist')

    cy.goToStore();
    cy.addItemAndProceedToCheckout();
    cy.get('label[for="radio-control-wc-payment-method-options-komoju_credit_card"] img').should('not.exist')

    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=checkout')
    cy.get('[data-gateway_id="komoju_credit_card"] a.button')
      .click()

    cy.get('#woocommerce_komoju_credit_card_showIcon').click()
    cy.contains('Save changes').click()
    cy.contains('Your settings have been saved.').should('exist')

    cy.goToStore();
    cy.addItemAndProceedToCheckout();
    cy.get('label[for="radio-control-wc-payment-method-options-komoju_credit_card"] img').should('exist')
  })
});
