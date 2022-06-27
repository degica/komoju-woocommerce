describe('KOMOJU for WooCommerce: Admin', () => {
  beforeEach(() => {
    cy.installWordpress();
    cy.signinToWordpress();
    cy.installWooCommerce();
    cy.installKomoju();

    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');
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
    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');

    cy.get('.komoju-endpoint-field').contains('Edit').click();
    cy.get('#komoju_woocommerce_api_endpoint').type('{selectAll}https://requestbin.labs.degica.com');
    cy.contains('Save changes').click();

    cy.contains('Payment methods').click();
    cy.get('#mainform').should('include.text', 'Unable to reach KOMOJU. Is your secret key correct?');
    cy.contains('API settings').click();

    cy.get('.komoju-endpoint-field').contains('Reset').click();
    cy.contains('Save changes').click();

    cy.contains('Payment methods').click();
    cy.get('#mainform').should('not.include.text', 'Unable to reach KOMOJU. Is your secret key correct?');
  })

  it('updates secret key with one-click setup', () => {
    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');

    cy.get('#komoju_woocommerce_secret_key').type('{selectAll}{backspace}');
    cy.get('#komoju_woocommerce_webhook_secret').type('{selectAll}{backspace}');
    cy.contains('Save changes').click();

    cy.contains('Payment methods').click();

    cy.get('#mainform').should('include.text', 'Once signed into KOMOJU, you can select payment methods to use as WooCommerce gateways.');

    let nonce;
    cy.contains('Sign into KOMOJU').then((connectButton) => {
      const href = connectButton.attr('href');
      nonce = href.split('&nonce=')[1];
    })

    const options = {
      method: 'POST',
      url: '/?wc-api=WC_Gateway_Komoju',
      body: {
        secret_key: 'abc123',
        nonce: 'wrong',
        webhook_secret: 'webhooks123'
      },
      failOnStatusCode: false,
      form: true
    }

    cy.request(options)
      .should(response => {
        expect(response.status).to.eq(422)
        expect(response.body).to.include('Invalid nonce. Please try again.')
      })
      .then(() => {
        options.body.nonce = nonce;
        options.failOnStatusCode = true;

        cy.request(options)
          .should(response => {
            expect(response.status).to.eq(200)
          })
      })

    cy.reload()
    cy.get('.komoju-setup').should('include.text', 'Reconnect with KOMOJU')
    cy.contains('API settings').click()
    cy.get('#komoju_woocommerce_secret_key').should('have.value', 'abc123')
    cy.get('#komoju_woocommerce_webhook_secret').should('have.value', 'webhooks123')
  })
});
