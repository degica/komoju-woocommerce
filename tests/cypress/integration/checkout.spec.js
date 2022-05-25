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

    // Not sure why this click is flaky. I guess because of the JS animation?
    cy.wait(600);
    cy.get('#komoju-cc-form').contains('Konbini').click();
    cy.wait(200);

    cy.get('#place_order').click();
    cy.location('host').should('equal', 'komoju.com');
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
    cy.wait(400);
    cy.get('#place_order').click();

    cy.location('host').should('equal', 'komoju.com');
    cy.location('pathname').should('include', '/sessions/');
  })
});
