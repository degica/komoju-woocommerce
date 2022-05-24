# Testing KOMOJU for WooCommerce

This plugin is tested using [https://cypress.io](cypress).

These tests should work on a fresh `docker-compose up`. If your database is not fresh, you can clear it with the following commands:

```bash
# delete dev containers and database
docker-compose down
docker volume rm komoju-woocommerce_db_data
```


To run tests,

```bash
npx cypress run
```


To visually debug and inspect the in-progress tests,

```bash
npx cypress open
```
