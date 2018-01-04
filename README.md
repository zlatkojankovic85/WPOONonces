# WPOONonces

This is a Composer package for WordPress with wp_nonce* () implemented in object oriented way.

## Installation

Clone repository via git command:
``` ~$ git clone https://github.com/zlatkojankovic85/WPOONonces.git  ```
Go to the WPOONonces directory and run composer:
```
~$ cd WPOONonces
WPOONonces$ composer install
```

## Nonce functions

### `get_nonce_url( $url )`
Adds a valid nonce to the given URL.

### `create_nonce( $action )`
Returns a valid nonce for the given action. This can be used in ajax requests.

### `check_nonce_request( $name, $action, $callback )`
Is used for the automatic check on the `$_REQUEST` and is hooked into the `init` action, if automatic check is enabled. `check_request()` calls at least the actions `check_ajax_referer` (in case of `DOING_AJAX`) or `check_admin_referer` (in case of `is_admin()`), so functions hooked into these actions are still executed.

### `get_nonce_field( $action, $name, $referer, $echo )`
Creates an `<input type="hidden">` with a valid nonce for the given action. The name of the field corresponds with the given name. `$referer` is a boolean. Set to `true` the referer input field will be printed as well (Default: `false`). `$echo` defines, if the field will be immediatly echoed (`true`) or just returned (`false`, default).

### `wp_verify_nonce_field( $nonce, $action )`
Verifies if the given nonce is valid for the current action.

### `get_referral_field( $echo )`
Retrieves or displays the referer hidden form field.


## Testing
How to run UnitTests:

1. Go to the WPOONonces directory
2. Run `composer install`,
3. Create file with name `phpunit.xml` and enter the database credentials to your database.There is example file `phpunit.xml.dist`
3. Run `phpunit`

Tests use:
https://github.com/inpsyde/wordpress-dev,
https://github.com/inpsyde/WP-Tests-Starter repositories.
