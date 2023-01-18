/// <reference types="cypress" />

// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

Cypress.Commands.add('installWordpress', () => {
  cy.visit('/wp-admin');

  cy.window().then(win => {
    if (!win.document.querySelector('#setup')) {
      cy.log('Wordpress already installed');
      return;
    }
    cy.log('Wordpress not yet installed. Installing now.');

    cy.contains('English (United States)').click();
    cy.get('#language-continue').click();

    cy.get('#weblog_title').type('Degica Mart').type('{selectAll}Degica Mart');
    cy.get('#user_login').type('degica');
    cy.get('#pass1').type('{selectAll}deg1kaX7reme!');
    cy.get('#admin_email').type('dev@degica.com');
    cy.get('#submit').click();
  });
});

Cypress.Commands.add('signinToWordpress', () => {
  cy.visit('/wp-admin');

  cy.window().then(win => {
    if (!win.document.querySelector('#loginform')) {
      cy.log('Already logged in');
      return;
    }
    cy.log('Logging in');

    cy.get('#user_login').type('degica').type('{selectAll}degica');
    cy.get('#user_pass').type('deg1kaX7reme!');
    cy.get('#wp-submit').click();
  });
});

Cypress.Commands.add('installWooCommerce', () => {
  cy.visit('/wp-admin/plugins.php');

  cy.window().then(win => {
    if (!win.document.querySelector('tr[data-slug="woocommerce"].inactive')) {
      cy.log('WooCommerce already activated');
      return;
    }

    cy.get('tr[data-slug="woocommerce"]').contains('Activate').click();
    cy.wait(1000);

    cy.visit('/wp-admin/edit.php?post_type=product&page=product_importer');
    cy.get('#upload').selectFile({
      contents: Cypress.Buffer.from('ID,Type,SKU,Name,Published,"Is featured?","Visibility in catalog","Short description",Description,"Date sale price starts","Date sale price ends","Tax status","Tax class","In stock?",Stock,"Low stock amount","Backorders allowed?","Sold individually?","Weight (kg)","Length (cm)","Width (cm)","Height (cm)","Allow customer reviews?","Purchase note","Sale price","Regular price",Categories,Tags,"Shipping class",Images,"Download limit","Download expiry days",Parent,"Grouped products",Upsells,Cross-sells,"External URL","Button text",Position\n10,simple,komoju-sticker,"KOMOJU Sticker",1,0,visible,,"The best sticker around",,,taxable,,1,,,0,0,,,,,1,,,12,Uncategorized,,,,,,,,,,,,0'
      ),
      fileName: 'example-product.csv',
      mimeType: 'text/csv'
    });
    cy.contains('Continue').click();
    cy.contains('Run the importer').click();
    cy.contains('Import complete! 1 product imported').should('exist');
  });
});

Cypress.Commands.add('installKomoju', () => {
  cy.visit('/wp-admin/plugins.php');

  cy.window().then(win => {
    if (!win.document.querySelector('tr[data-slug="komoju-japanese-payments"].inactive')) {
      cy.log('KOMOJU already activated');
      return;
    }

    cy.get('tr[data-slug="komoju-japanese-payments"]').contains('Activate').click();
  });
});

Cypress.Commands.add('setupKomoju', (
  paymentTypes = [],
  secretKey = 'degica-mart-test',
  publishableKey = 'pk_d6acce1f17e4468c30833b666d9006f100e9fa8c'
) => {
  cy.visit('/wp-admin/admin.php?page=wc-settings&tab=komoju_settings&section=api_settings');

  cy.get('#komoju_woocommerce_secret_key').type('{selectAll}').type(secretKey);
  cy.get('#komoju_woocommerce_publishable_key').type('{selectAll}').type(publishableKey);
  cy.get('.komoju-endpoint-komoju_woocommerce_fields_url').then($element => {
    const $edit = $element.find('.komoju-endpoint-edit');
    if ($edit.length > 0) { return cy.wrap($edit).click(); }
  });
  cy.get('#komoju_woocommerce_fields_url').type('{selectAll}').type('https://multipay-staging.test.komoju.com/fields.js');
  cy.contains('Save changes').click();

  cy.contains('Payment methods').click();

  cy.get('input[type="checkbox"]').each($match => {
    $match.each(function() {
      if (this.checked) cy.wrap($match).click();
    });
  });

  for (const slug of paymentTypes) {
    cy.get(`input[type="checkbox"][value="${slug}"]`).click();
  }

  cy.contains('Save changes').click();
});

Cypress.Commands.add('enablePaymentGateway', (slug) => {
  cy.get(`tr[data-gateway_id="${slug}"]`)
    .find('.woocommerce-input-toggle')
    .each($match => {
      if ($match[0].textContent === 'No') cy.wrap($match).click();
    });
});

Cypress.Commands.add('goToStore', () => {
  cy.get('#wp-admin-bar-view-store a').click({ force: true });
});

Cypress.Commands.add('fillInAddress', () => {
  cy.get('#billing_last_name').type('{selectAll}Johnson');
  cy.get('#billing_first_name').type('{selectAll}Test');
  cy.get('#billing_postcode').type('{selectAll}48103');
  cy.get('#billing_city').type('{selectAll}Musashino');
  cy.get('#billing_address_1').type('{selectAll}a');
  cy.get('#billing_phone').type('{selectAll}123123213213213');

  // I cannot for the life of me figure out how to safely wait for WC to finish
  // re-generating the payment fields. If I do anything at all here, Cypress complains
  // about elements not being mounted, not existing, etc.
  cy.wait(2000);
  cy.get('.blockOverlay').should('not.exist');
});

Cypress.Commands.add('iframe', { prevSubject: 'element' }, ($iframe) => {
  // get the iframe > document > body
  // and retry until the body element is not empty
  Cypress.log({
    name: 'iframe',
    consoleProps() {
      return {
        iframe: $iframe,
      };
    },
  });

  return cy.wrap($iframe)
  .its('0.contentDocument.body').should('not.be.empty')
  // wraps "body" DOM element to allow
  // chaining more Cypress commands, like ".find(...)"
  // https://on.cypress.io/wrap
  .then(cy.wrap)
})
