<?php
namespace App\WPOONonces;

class WPOONonces
{

    /**
     * Retrieve URL with nonce added to URL query.
     * @param string $url       :: this is a required parameter which represents the URL to add nonce action.
     * @param string $action    :: this is an optional parameter which represents the action name of the nonce. Default is -1.
     * @param string $name      :: this is an optional parameter which represents the name of the nonce. Default is _wpnonce.
     * @return string in a form of the nonce token
     */

    public function get_nonce_url($url, $action = -1, $name = '_wpnonce')
    {
        return wp_nonce_url($url, $action, $name);
    }

    /**
     * Retrieves or displays the nonce hidden form field.
     * @param string $action            :: this is an optional but recommended parameter which represents the action name
     * @param string $name              :: this is an optional parameter which represents the nonce hidden form field. Default is _wpnonce.
     * @param boolean $referer          :: this is an optional parameter which determines whether the referal hidden form field should be created. Default is true.
     * @param boolean $echo             :: this is an optional parameter which determines whether to display or return the hidden nonce field. Default is true.
     * @return string is a nonce hidden form field, optionally followed by the referer hidden form field if the $referer argument is set to true.
     */

    public function get_nonce_field($action = -1, $name = '_wpnonce', $referer = true, $echo = true)
    {

        $html = wp_nonce_field($action, $name, $referer, $echo);

        if (!$echo) {
            return $html;
        }

        echo $html;

        return true;

    }

    /**
     * Create a nonce
     * @param string/int $action :: this is an optional but recommended parameter which represents the name of the nonce. Default is -1.
     * @return string is a one use form token
     */

    public function create_nonce($action = -1)
    {
        return wp_create_nonce($action);
    }

    /**
     * A way to verify nonce.
     * @param string $action    :: it determines the context to what is taking place. Default is -1.
     * @param string $query_arg :: it determines where to look for nonce in $_REQUEST PHP variable. Default is _wpnonce.
     * @return boolean is a boolean true/false value.
     */

    public function wp_check_admin_referer($action = -1, $query_arg = '_wpnonce')
    {
        return check_admin_referer($action, $query_arg);
    }

    /**
     * Verify nonces in AJAX requests
     * @param string $action    :: this is an optional parameter which represents the action name. Default is -1.
     * @param string $query_arg :: this is an optional parameter which determines where to look for nonce in $_REQUEST PHP variable. Default is false.
     * @param  boolean $die     :: this is an optional boolean parameter which determines whether to die if nonce is invalid. Default is true.
     * @return boolean is a true/false value.
     */

    public function wp_check_ajax_referer($action = -1, $query_arg = false, $die = true)
    {
        return check_ajax_referer($action, $query_arg, $die);
    }

    /**
     * Verify nonces
     * @param string $nonce     :: this is a required parameter which determines the Nonce to verify.
     * @param string $action    :: this is an optional parameter which determines the action specified when nonce was created.
     * @return boolean/integer  :: returns false if the nonce is invalid or
     * returns '1' if nonce <= 12hours or
     * returns '2' if 12hours < nonce < 24hours
     */

    public function wp_verify_nonce_field($nonce, $action = -1)
    {
        return wp_verify_nonce($nonce, $action);
    }

    /**
     * Retrieves or displays the referer hidden form field.
     * @param string $echo   :: this is an optional parameter which determines whether to display or return the hidden form field. Default is true.
     * @return string is a referral field.
     */

    public function get_referral_field($echo = true)
    {
        return wp_referer_field($echo);
    }

    /**
     * Display 'Are you sure you want to do this?' message to confirm the action being taken
     * @param string $action    :: this is an required parameter which determines the nonce action.
     * @return it does not return any value.
     */

    public function wpNonceAys($action)
    {
        wp_nonce_ays($action);
    }

    /**
     * Checks the current $_REQUEST if a nonce exists and if its valid. If is is not valid, `wp_die()` will be executed or a defined callback function.
     * @param string $name      :: this is an optional parameter which determines the nonce name.
     * @param string $action    :: this is an optional parameter which determines the nonce action.
     * @param string $callback  :: this is an optional parameter which determines the callback function.
     * @return (boolean) `false` indicates the nonce was not valid. `true` indicates, the nonce was valid or no nonce was found in the $_REQUEST.
     */

    public function check_nonce_request($name = '_wpnonce', $action = '', $callback = 'Your are not allowed to do this.')
    {
        // Check if the $REQUEST contains a nonce
        if (!isset($_REQUEST[$name])) {
            return true;
        }

        // Check the nonce
        $is_valid = $this->wp_verify_nonce_field($_REQUEST[$name]);

        // Since `check_ajax_referer()` and `check_admin_referer()` rely on `wp_verify_nonce()`
        // no additional layer of security is given. But we could run into `wp_die()` although a callback
        // function has been defined. So instead of using these functions, we just call the internally
        // used actions.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            // In case we have an ajax request, we reproduce a bit of the check_ajax_referer()
            do_action('check_ajax_referer', $action, $is_valid);
        } elseif (is_admin()) {
            // In case we are in the admin, we reproduce a bit of the check_admin_referer()
            do_action('check_admin_referer', $action, $is_valid);
        }

        if ($is_valid) {
            return true;
        }

        if (!is_callable($callback)) {
            // Check for ajax
            if (defined('DOING_AJAX') && DOING_AJAX) {
                // In case we have an ajax request, we reproduce a bit of the check_ajax_referer()
                wp_die(-1);
            }

            // If $callback contains the error message, we exit with `wp_die()`
            wp_die($callback);
        } else {

            // If $callback contains a callable function, we exeute the function.
            // The current object will be given as parameter.
            call_user_func_array($callback, array($this));
        }

        return false;
    }

}
