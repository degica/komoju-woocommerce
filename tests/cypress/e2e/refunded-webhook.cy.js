/// <reference types="cypress" />

describe('KOMOJU for WooCommerce: Refunded webhook', () => {
  beforeEach(() => {
    cy.installWordpress();
    cy.signinToWordpress();
    cy.installWooCommerce();
    cy.installKomoju();

    cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');
    cy.get('.komoju-endpoint-field').contains('Reset').click();
    cy.contains('Save changes').click();
  })

  it('sets an order as a refunded based on incoming refunded webhook', () => {

    //
    // setup order
    const orderId = "....";

    cy.request({
      method: 'POST',
      url: 'http://localhost:8000/?wc-api=WC_Gateway_Komoju',
      headers: {
        'X-Komoju-ID': 'f57pcxs3lw5o47rroo9uwlxiy',
        'X-Komoju-Signature': 'cfb6ae2ee58ecef4265f324b057e1db5ee4ba2a5a1db861e512ef10da2b14e35', // <--- replaced for test
        'X-Komoju-Event': 'payment.refunded',
        'User-Agent': 'Komoju-Webhook',
        'Content-Type': 'application/json',
      },
      body: {
        id: '3rzbqxbx4iucmrj7iuhzk9lpp',
        type: 'payment.refunded',
        resource: 'event',
        data: {
          id: 'e2gd49o427t0epiav2cisg6k5',
          resource: 'payment',
          status: 'refunded',
          amount: 333800,
          tax: 0,
          customer: null,
          payment_deadline: '2023-02-19T14:59:59Z',
          payment_details: {
            type: 'credit_card',
            email: 'khdhd2155@gmail.com',
            brand: 'master',
            last_four_digits: '3795',
            month: 9,
            year: 2024,
          },
          payment_method_fee: 0,
          total: 333800,
          currency: 'HKD',
          description: null,
          captured_at: '2023-02-17T11:57:05Z',
          external_order_num: `${orderId}-A49R0D`, // <---- the order id
          metadata: {
            woocommerce_order_id: '255095',
          },
          created_at: '2023-02-17T11:57:01Z',
          amount_refunded: 333800,
          locale: 'ja',
          session: '5xd6vvo4py7zog87s56q7q3eh',
          customer_family_name: null,
          customer_given_name: null,
          mcc: null,
          statement_descriptor: null,
          refunds: [
            {
              id: '3fbcth4baiw8hvdwqi3piw4j5',
              resource: 'refund',
              amount: 333800,
              currency: 'HKD',
              payment: 'e2gd49o427t0epiav2cisg6k5',
              description: 'Bulk Chargeback 2023-08-28',
              created_at: '2023-08-28T02:13:46Z',
              chargeback: true,
            },
          ],
          refund_requests: [],
        },
        created_at: '2023-08-28T02:13:48Z',
        reason: null,
      },
    })
      .then((responseBody) => {
        //
        // log in to admin and check order status
      });
  });

  //   cy.setupKomoju(['konbini', 'credit_card']);
  //   cy.contains('Payments').click();

  //   cy.get('.form-table').should('include.text', 'Komoju - Konbini');
  //   cy.get('.form-table').should('include.text', 'Komoju - Credit Card');

  //   cy.setupKomoju(['paypay', 'linepay']);
  //   cy.contains('Payments').click();

  //   cy.get('.form-table').should('not.include.text', 'Komoju - Konbini');
  //   cy.get('.form-table').should('not.include.text', 'Komoju - Credit Card');
  //   cy.get('.form-table').should('include.text',     'Komoju - PayPay');
  //   cy.get('.form-table').should('include.text',     'Komoju - LINE Pay');
  // })

  // it('lets me change the KOMOJU endpoint', () => {
  //   cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');

  //   cy.get('.komoju-endpoint-komoju_woocommerce_api_endpoint').contains('Edit').click();
  //   cy.get('#komoju_woocommerce_api_endpoint').type('{selectAll}https://requestbin.labs.degica.com');
  //   cy.contains('Save changes').click();

  //   cy.contains('Payment methods').click();
  //   cy.get('#mainform').should('include.text', 'Unable to reach KOMOJU. Is your secret key correct?');
  //   cy.contains('API settings').click();

  //   cy.get('.komoju-endpoint-komoju_woocommerce_api_endpoint').contains('Reset').click();
  //   cy.contains('Save changes').click();

  //   cy.contains('Payment methods').click();
  //   cy.get('#mainform').should('not.include.text', 'Unable to reach KOMOJU. Is your secret key correct?');
  // })

  // it('updates secret key with one-click setup', () => {
  //   cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');

  //   cy.get('#komoju_woocommerce_secret_key').type('{selectAll}{backspace}');
  //   cy.get('#komoju_woocommerce_webhook_secret').type('{selectAll}{backspace}');
  //   cy.contains('Save changes').click();

  //   cy.contains('Payment methods').click();

  //   cy.get('#mainform').should('include.text', 'Once signed into KOMOJU, you can select payment methods to use as WooCommerce gateways.');

  //   let nonce;
  //   cy.contains('Sign into KOMOJU').then((connectButton) => {
  //     const href = connectButton.attr('href');
  //     nonce = href.split('&nonce=')[1];
  //   })

  //   const options = {
  //     method: 'POST',
  //     url: '/?wc-api=WC_Gateway_Komoju',
  //     body: {
  //       secret_key: 'abc123',
  //       nonce: 'wrong',
  //       webhook_secret: 'webhooks123'
  //     },
  //     failOnStatusCode: false,
  //     form: true
  //   }

  //   cy.request(options)
  //     .should(response => {
  //       expect(response.status).to.eq(422)
  //       expect(response.body).to.include('Invalid nonce. Please try again.')
  //     })
  //     .then(() => {
  //       options.body.nonce = nonce;
  //       options.failOnStatusCode = true;

  //       cy.request(options)
  //         .should(response => {
  //           expect(response.status).to.eq(200)
  //         })
  //     })

  //   cy.reload()
  //   cy.get('.komoju-setup').should('include.text', 'Reconnect with KOMOJU')
  //   cy.contains('API settings').click()
  //   cy.get('#komoju_woocommerce_secret_key').should('have.value', 'abc123')
  //   cy.get('#komoju_woocommerce_webhook_secret').should('have.value', 'webhooks123')
  // })
});
