describe('KOMOJU for WooCommerce: Admin', () => {
  beforeEach(() => {
    cy.installWordpress();
    cy.signinToWordpress();
    cy.installWooCommerce();
    cy.installKomoju();

    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings');
    cy.get('.komoju-endpoint-field').contains('Reset').click();
    cy.contains('Save changes').click();
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

  it('lets me change the KOMOJU endpoint', () => {
    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings');

    cy.get('.komoju-endpoint-field').contains('Edit').click();
    cy.get('#komoju_woocommerce_api_endpoint').type('{selectAll}https://requestbin.labs.degica.com');
    cy.contains('Save changes').click();

    cy.get('#mainform').should('include.text', 'Unable to reach KOMOJU. Is your secret key correct?');

    cy.get('.komoju-endpoint-field').contains('Reset').click();
    cy.contains('Save changes').click();

    cy.get('#mainform').should('not.include.text', 'Unable to reach KOMOJU. Is your secret key correct?');
  })

  // Unfortunately this spec relies rather heavily on production KOMOJU state
  it('lets me sign into KOMOJU to automatically set my secret key', () => {
    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings');

    cy.get('#komoju_woocommerce_secret_key').type('{selectAll}{backspace}');
    cy.contains('Save changes').click();

    cy.get('#mainform').should('include.text', 'Once signed into KOMOJU, you can select payment methods to use as WooCommerce gateways.');

    cy.contains('Sign into KOMOJU').click();

    // TODO this will fail from here on right now because the feature is not yet deployed to KOMOJU.

    cy.get('#user_email').type('shopifydemo@example.com');
    cy.get('#user_password').type('ShopifyTest123');
    cy.get('input[type="submit"]').click();

    cy.pause();
  })
});
