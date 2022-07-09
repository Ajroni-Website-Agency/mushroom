<?php

use WeDevs\Dokan\Cache;

/**
 * Get default withdraw methods for vendor
 *
 * @since 1.0.0
 *
 * @return array
 */
function dokan_withdraw_register_methods() {
    $methods = [
        'paypal' => [
            'title'    => __( 'PayPal', 'dokan-lite' ),
            'callback' => 'dokan_withdraw_method_paypal',
        ],
        'bank' => [
            'title'    => __( 'Bank Transfer', 'dokan-lite' ),
            'callback' => 'dokan_withdraw_method_bank',
        ],
    ];

    return apply_filters( 'dokan_withdraw_methods', $methods );
}

/**
 * Get registered withdraw methods suitable for Settings Api
 *
 * @return array
 */
function dokan_withdraw_get_methods() {
    $methods    = [];
    $registered = dokan_withdraw_register_methods();

    foreach ( $registered as $key => $value ) {
        $methods[ $key ] = $value['title'];
    }

    return $methods;
}

/**
 * Get active withdraw methods.( Default is paypal )
 *
 * @return array
 */
function dokan_withdraw_get_active_methods() {
    $methods = dokan_get_option( 'withdraw_methods', 'dokan_withdraw', [ 'paypal' ] );

    return apply_filters( 'dokan_get_active_withdraw_methods', $methods );
}

/**
 * Get active withdraw methods for seller.
 *
 * @since 3.0.0 add $vendor_id param
 *
 * @param int $vendor_id Seller vendor id
 *
 * @return array
 */
function dokan_get_seller_active_withdraw_methods( $vendor_id = 0 ) {
    $vendor_id = $vendor_id ? $vendor_id : dokan_get_current_user_id();

    $payment_methods = get_user_meta( $vendor_id, 'dokan_profile_settings' );
    $paypal          = isset( $payment_methods[0]['payment']['paypal']['email'] ) && $payment_methods[0]['payment']['paypal']['email'] !== false ? 'paypal' : '';
    $bank            = isset( $payment_methods[0]['payment']['bank']['ac_number'] ) && $payment_methods[0]['payment']['bank']['ac_number'] !== '' ? 'bank' : '';
    $skrill          = isset( $payment_methods[0]['payment']['skrill']['email'] ) && $payment_methods[0]['payment']['skrill']['email'] !== false ? 'skrill' : '';

    $payment_methods        = [ $paypal, $bank, $skrill ];
    $active_payment_methods = [];

    foreach ( $payment_methods as $payment_method ) {
        if ( ! empty( $payment_method ) ) {
            array_push( $active_payment_methods, $payment_method );
        }
    }

    return apply_filters( 'dokan_get_seller_active_withdraw_methods', $active_payment_methods, $vendor_id );
}

/**
 * Get a single withdraw method based on key
 *
 * @param string $method_key
 *
 * @return bool|array
 */
function dokan_withdraw_get_method( $method_key ) {
    $methods = dokan_withdraw_register_methods();

    if ( isset( $methods[ $method_key ] ) ) {
        return $methods[ $method_key ];
    }

    return false;
}

/**
 * Get title from a withdraw method
 *
 * @param string $method_key
 * @param object|null $request //@since 3.3.7
 *
 * @return string
 */
function dokan_withdraw_get_method_title( $method_key, $request = null ) {
    $registered = dokan_withdraw_register_methods();
    if ( isset( $registered[ $method_key ] ) ) {
        /**
         * @since 3.3.7 added filter dokan_get_withdraw_method_title
         */
        return apply_filters( 'dokan_get_withdraw_method_title', $registered[ $method_key ]['title'], $method_key, $request );
    }
}

/**
 * Callback for PayPal in store settings
 *
 * @global WP_User $current_user
 *
 * @param array $store_settings
 */
function dokan_withdraw_method_paypal( $store_settings ) {
    global $current_user;

    $email = isset( $store_settings['payment']['paypal']['email'] ) ? esc_attr( $store_settings['payment']['paypal']['email'] ) : $current_user->user_email; ?>
    <div class="dokan-form-group">
        <div class="dokan-w8">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon"><?php esc_html_e( 'E-mail', 'dokan-lite' ); ?></span>
                <input value="<?php echo esc_attr( $email ); ?>" name="settings[paypal][email]" class="dokan-form-control email" placeholder="you@domain.com" type="text">
            </div>
        </div>
    </div>
    <?php
}

/**
 * Callback for Skrill in store settings
 *
 * @global WP_User $current_user
 *
 * @param array $store_settings
 */
function dokan_withdraw_method_skrill( $store_settings ) {
    global $current_user;

    $email = isset( $store_settings['payment']['skrill']['email'] ) ? esc_attr( $store_settings['payment']['skrill']['email'] ) : $current_user->user_email;
    ?>
    <div class="dokan-form-group">
        <div class="dokan-w8">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon"><?php esc_html_e( 'E-mail', 'dokan-lite' ); ?></span>
                <input value="<?php echo esc_attr( $email ); ?>" name="settings[skrill][email]" class="dokan-form-control email" placeholder="you@domain.com" type="text">
            </div>
        </div>
    </div>
    <?php
}

/**
 * Callback for Bank in store settings
 *
 * @global WP_User $current_user
 *
 * @param array $store_settings
 */
function dokan_withdraw_method_bank( $store_settings ) {
    $args = [
        'account_name'         => isset( $store_settings['payment']['bank']['ac_name'] ) ? $store_settings['payment']['bank']['ac_name'] : '',
        'account_number'       => isset( $store_settings['payment']['bank']['ac_number'] ) ? $store_settings['payment']['bank']['ac_number'] : '',
        'bank_name'            => isset( $store_settings['payment']['bank']['bank_name'] ) ? $store_settings['payment']['bank']['bank_name'] : '',
        'bank_addr'            => isset( $store_settings['payment']['bank']['bank_addr'] ) ? $store_settings['payment']['bank']['bank_addr'] : '',
        'routing_number'       => isset( $store_settings['payment']['bank']['routing_number'] ) ? $store_settings['payment']['bank']['routing_number'] : '',
        'iban'                 => isset( $store_settings['payment']['bank']['iban'] ) ? $store_settings['payment']['bank']['iban'] : '',
        'swift_code'           => isset( $store_settings['payment']['bank']['swift'] ) ? $store_settings['payment']['bank']['swift'] : '',
        'account_type'         => isset( $store_settings['payment']['bank']['ac_type'] ) ? $store_settings['payment']['bank']['ac_type'] : '',
        'save_or_add_btn_text' => isset( $store_settings['is_edit_method'] ) && $store_settings['is_edit_method'] ? __( 'Save', 'dokan-lite' ) : __( 'Add Account', 'dokan-lite' ),
    ];

    dokan_get_template_part( 'settings/bank-payment-method-settings', '', $args );
}

/**
 * Get withdraw counts, used in admin area
 *
 * @global WPDB $wpdb
 *
 * @return array
 */
function dokan_get_withdraw_count( $user_id = null ) {
    global $wpdb;

    $cache_group = empty( $user_id ) ? 'withdraws' : "withdraws_seller_$user_id";
    $cache_key   = "get_withdraw_count_{$user_id}";
    $counts      = Cache::get( $cache_key, $cache_group );

    if ( false === $counts ) {
        $counts = [
            'pending'   => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];

        if ( ! empty( $user_id ) ) {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(id) as count, status FROM {$wpdb->dokan_withdraw} WHERE user_id=%d GROUP BY status", $user_id ) );
        } else {
            $result = $wpdb->get_results( "SELECT COUNT(id) as count, status FROM {$wpdb->dokan_withdraw} WHERE 1=1 GROUP BY status" );
        }

        if ( $result ) {
            foreach ( $result as $row ) {
                if ( $row->status === '0' ) {
                    $counts['pending'] = (int) $row->count;
                } elseif ( $row->status === '1' ) {
                    $counts['completed'] = (int) $row->count;
                } elseif ( $row->status === '2' ) {
                    $counts['cancelled'] = (int) $row->count;
                }
            }
        }

        Cache::set( $cache_key, $counts, $cache_group );
    }

    return $counts;
}

/**
 * Get active withdraw order status.
 *
 * Default is 'completed', 'processing', 'on-hold'
 *
 * @return array
 */
function dokan_withdraw_get_active_order_status() {
    $order_status  = dokan_get_option( 'withdraw_order_status', 'dokan_withdraw', [ 'wc-completed' ] );
    $saving_status = [ 'wc-refunded' ];

    foreach ( $order_status as $key => $status ) {
        if ( ! empty( $status ) ) {
            $saving_status[] = $status;
        }
    }

    return apply_filters( 'dokan_withdraw_active_status', $saving_status );
}

/**
 * Get comma seperated value from "dokan_withdraw_get_active_order_status()" return array
 *
 * @param array array
 */
function dokan_withdraw_get_active_order_status_in_comma() {
    $order_status = dokan_withdraw_get_active_order_status();
    $status       = "'" . implode( "', '", $order_status ) . "'";

    return $status;
}

/**
 * Get withdraw method formatted icon.
 *
 * @since 3.4.3
 *
 * @param string $method_key Withdraw Method key
 *
 * @return string
 */
function dokan_withdraw_get_method_icon( $method_key ) {
    $asset_path = DOKAN_PLUGIN_ASSEST . '/images/withdraw-methods/';

    switch ( $method_key ) {
        case 'paypal':
            $method_icon = $asset_path . 'paypal.svg';
            break;
        default:
            $method_icon = $asset_path . 'bank.svg';
    }

    return apply_filters( 'dokan_withdraw_method_icon', $method_icon, $method_key );
}

/**
 * Get withdraw method additional info.
 *
 * @since 3.3.7
 *
 * @param string $method_key Withdraw Method key
 *
 * @return string
 */
function dokan_withdraw_get_method_additional_info( $method_key ) {
    $profile_settings = get_user_meta( dokan_get_current_user_id(), 'dokan_profile_settings' );
    $payment_methods  = ! empty( $profile_settings[0]['payment'] ) ? $profile_settings[0]['payment'] : [];
    $no_information   = __( 'No information found.', 'dokan-lite' );

    switch ( $method_key ) {
        case 'paypal':
        case 'skrill':
            // translators: 1: Email address for withdraw method.
            $method_info = empty( $payment_methods[ $method_key ]['email'] ) ? $no_information : sprintf( __( '( %1$s )', 'dokan-lite' ), dokan_mask_email_address( $payment_methods[ $method_key ]['email'] ) );
            break;
        case 'bank':
            // translators: 1: Bank account holder name. 2: Bank name. 1: Bank account number
            $method_info = empty( $payment_methods[ $method_key ]['ac_number'] ) ? $no_information : sprintf( __( '- %1$s - %2$s - ****%3$s', 'dokan-lite' ), $payment_methods[ $method_key ]['ac_name'], $payment_methods[ $method_key ]['bank_name'], substr( $payment_methods[ $method_key ]['ac_number'], -4 ) );
            break;
        default:
            $method_info = '';
    }
    return apply_filters( 'dokan_withdraw_method_additional_info', $method_info, $method_key );
}

/**
 * Get the default withdrawal method.
 *
 * @since 3.3.7
 *
 * @param int $vendor_id
 *
 * @return string
 */
function dokan_withdraw_get_default_method( $vendor_id = 0 ) {
    $vendor_id      = $vendor_id ? $vendor_id : dokan_get_current_user_id();
    $active_methods = dokan_get_seller_active_withdraw_methods( $vendor_id );
    $method         = get_user_meta( $vendor_id, 'dokan_withdraw_default_method', true );

    if ( ! empty( $method ) ) {
        return $method;
    }

    if ( ! empty( $active_methods ) ) {
        return $active_methods[0];
    }

    return 'paypal';
}

/**
 * Check if manual withdraw request sending enabled.
 *
 * @since 3.3.7
 *
 * @return bool
 */
function dokan_withdraw_is_manual_request_enabled() {
    return apply_filters( 'dokan_withdraw_manual_request_enable', true );
}

/**
 * Check if `Hide Withdraw Option` is enabled and hide withdraw dashboard.
 *
 * @since 3.3.7
 *
 * @return bool
 */
function dokan_withdraw_is_disabled() {
    return apply_filters( 'dokan_withdraw_disable', false );
}

/**
 * Get the payment methods that are eligable for manual/schedule withdraw.
 *
 * @since 3.3.7
 *
 * @return array
 */
function dokan_withdraw_get_withdrawable_active_methods() {
    return array_intersect(
        array_filter( dokan_withdraw_get_active_methods() ),
        apply_filters(
            'dokan_withdraw_withdrawable_payment_methods',
            [
                'paypal',
                'dokan_custom',
                'bank',
                'skrill',
            ]
        )
    );
}