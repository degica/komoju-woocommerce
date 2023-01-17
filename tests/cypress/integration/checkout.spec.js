/// <reference types="cypress" />

describe('KOMOJU for WooCommerce: Checkout', () => {
  beforeEach(() => {
    cy.installWordpress();
    cy.signinToWordpress();
    cy.installWooCommerce();
    cy.installKomoju();
  })

  it('lets me make a payment using the KOMOJU gateway', () => {
    cy.setupKomoju();
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju');
    cy.goToStore();
    cy.contains('Add to cart').click();
    cy.contains('Cart').click();
    cy.contains('Proceed to checkout').click();
    cy.fillInAddress();

    cy.contains('Komoju').click();
    cy.get('.blockUI,.blockOverlay').should('not.exist');

    cy.wait(1500);
    cy.get('#place_order').click();
    cy.location('pathname').should('include', '/sessions/');
    cy.contains('Select Payment Method').should('exist');
  })

  it('lets me make a payment using the specialized konbini gateway', () => {
    cy.setupKomoju(['konbini', 'credit_card']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_konbini');
    cy.goToStore();
    cy.contains('Add to cart').click();
    cy.contains('Cart').click();
    cy.contains('Proceed to checkout').click();
    cy.fillInAddress();

    cy.get('label[for="payment_method_komoju_konbini"]').click();
    cy.get('komoju-fields:visible iframe').iframe('#kb-name').type('Test Test');
    cy.get('komoju-fields:visible iframe').iframe('#kb-email').type('test@example.com');
    cy.get('komoju-fields:visible iframe').iframe('[value="family-mart"]').click();

    cy.get('#place_order').click();
    cy.location('pathname').should('include', '/sessions/');
    cy.contains('How to make a payment at Family Mart').should('be.visible');
    cy.contains('Return to').click();
    cy.contains('Thank you. Your order has been received.').should('be.visible');
  })

  it('lets me make a payment using the specialized credit card gateway', () => {
    cy.setupKomoju(['konbini', 'credit_card']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_credit_card');
    cy.goToStore();
    cy.contains('Add to cart').click();
    cy.contains('Cart').click();
    cy.contains('Proceed to checkout').click();
    cy.fillInAddress();

    cy.contains('Credit Card').click();
    cy.get('komoju-fields:visible iframe').iframe('#cc-name').type('Test Test');
    cy.get('komoju-fields:visible iframe').iframe('#cc-number').type('4111111111111111');
    cy.get('komoju-fields:visible iframe').iframe('#cc-exp').type('1299');
    cy.get('komoju-fields:visible iframe').iframe('#cc-cvc').type('111');

    cy.get('#place_order').click();
    cy.contains('Thank you. Your order has been received.').should('be.visible');
  })

  it('lets me turn checkout icons on and off', () => {
    cy.setupKomoju(['konbini', 'credit_card']);
    cy.contains('Payments').click();
    cy.enablePaymentGateway('komoju_credit_card');

    cy.get('[data-gateway_id="komoju_credit_card"] a.button')
      .click()

    cy.get('#woocommerce_komoju_credit_card_showIcon').then(input => {
      cy.log(input.attr('checked'));
      if (input.attr('checked')) input.click()
    })
    cy.contains('Save changes').click()
    cy.contains('Your settings have been saved.').should('exist')

    cy.goToStore();
    cy.contains('Add to cart').click();
    cy.contains('Cart').click();
    cy.contains('Proceed to checkout').click();
    cy.get('label[for="payment_method_komoju_credit_card"] img').should('not.exist')

    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=checkout')
    cy.get('[data-gateway_id="komoju_credit_card"] a.button')
      .click()

    cy.get('#woocommerce_komoju_credit_card_showIcon').click()
    cy.contains('Save changes').click()
    cy.contains('Your settings have been saved.').should('exist')

    cy.goToStore();
    cy.contains('Add to cart').click();
    cy.contains('Cart').click();
    cy.contains('Proceed to checkout').click();
    cy.get('label[for="payment_method_komoju_credit_card"] img').should('exist')
  })
});
