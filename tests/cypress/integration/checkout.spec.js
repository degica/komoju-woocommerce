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

    // This waits for the "expanding box" animation to finish
    cy.get('.payment_method_komoju[style]').should('not.exist');

    cy.get('#komoju-cc-form').contains('Pay Easy').click();
    cy.get('.blockUI,.blockOverlay').should('not.exist');

    cy.get('#place_order').click();
    cy.contains('Enter Account Holder Details').should('exist');
    cy.location('pathname').should('include', '/sessions/');
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

    cy.get('#payment_method_komoju_konbini').click();
    cy.get('.payment_method_komoju[style="display: none;"]').should('exist');
    cy.get('.blockUI,.blockOverlay').should('not.exist');
    cy.get('#place_order').click();

    cy.contains('Choose a Convenience Store').should('exist');
    cy.location('pathname').should('include', '/sessions/');
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
