describe('KOMOJU for WooCommerce', () => {
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
    cy.contains('Pay Easy').click({ force: true });

    cy.contains('Place order').click();
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

    cy.contains('Konbini').click();
    cy.contains('Place order').click();

    cy.location('host').should('equal', 'komoju.com');
    cy.location('pathname').should('include', '/sessions/');
  })

  it('lets me add and remove specialized payment gateways', () => {
    cy.setupKomoju(['konbini', 'credit_card']);
    cy.contains('Payments').click();

    cy.get('.form-table').should('include.text', 'Komoju - Konbini');
    cy.get('.form-table').should('include.text', 'Komoju - Credit Card');

    cy.setupKomoju(['docomo', 'softbank']);
    cy.contains('Payments').click();

    cy.get('.form-table').should('not.include.text', 'Komoju - Konbini');
    cy.get('.form-table').should('not.include.text', 'Komoju - Credit Card');
    cy.get('.form-table').should('include.text',     'Komoju - SoftBank');
    cy.get('.form-table').should('include.text',     'Komoju - docomo');
  })
});
