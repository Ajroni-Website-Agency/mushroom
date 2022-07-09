<?php

namespace WeDevs\DokanPro\Modules\RequestForQuotation\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Class for Hooks integration.
 *
 * @since 3.6.0
 */
class Hooks {

    /**
     * Class constructor
     *
     * @since 3.6.0
     *
     * @return void
     */
    public function __construct() {
        add_action( 'dokan_admin_menu', [ $this, 'add_admin_menu' ] );
        add_filter( 'dokan-admin-routes', [ $this, 'add_admin_route' ] );
        add_action( 'dokan-vue-admin-scripts', [ $this, 'enqueue_admin_script' ] );
    }

    /**
     * Add Dokan submenu
     *
     * @since 3.6.0
     *
     * @param string $capability
     *
     * @return void
     */
    public function add_admin_menu( $capability ) {
        if ( current_user_can( $capability ) ) {
            global $submenu;

            $title = esc_html__( 'Request for Quotation', 'dokan' );
            $slug  = 'dokan';

            $submenu[$slug][] = [ $title, $capability, 'admin.php?page=' . $slug . '#/dokan-request-quote' ]; // phpcs:ignore
        }
    }

    /**
     * Add admin page Route
     *
     * @since 3.6.0
     *
     * @param array $routes
     *
     * @return array
     */
    public function add_admin_route( $routes ) {
        $routes[] = [
            'path'      => '/dokan-request-quote',
            'name'      => 'RequestAQuote',
            'component' => 'RequestAQuote',
        ];

        $routes[] = [
            'path'      => '/dokan-request-quote/new',
            'name'      => 'NewRequestQuote',
            'component' => 'NewRequestQuote',
        ];

        $routes[] = [
            'path'      => '/dokan-request-quote/:id/edit',
            'name'      => 'NewRequestQuote',
            'component' => 'NewRequestQuote',
        ];

        $routes[] = [
            'path'      => '/dokan-quote-rules',
            'name'      => 'RequestAQuoteRules',
            'component' => 'RequestAQuoteRules',
        ];

        $routes[] = [
            'path'      => '/dokan-quote-rules/new',
            'name'      => 'NewQuoteRules',
            'component' => 'NewQuoteRules',
        ];

        $routes[] = [
            'path'      => '/dokan-quote-rule/:id/edit',
            'name'      => 'NewQuoteRules',
            'component' => 'NewQuoteRules',
        ];

        return $routes;
    }

    /**
     * Enqueue admin script
     *
     * @since 3.6.0
     *
     * @return void
     */
    public function enqueue_admin_script() {
        // Use minified libraries if SCRIPT_DEBUG is turned off
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        $version = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : DOKAN_PRO_PLUGIN_VERSION;
        wp_enqueue_script(
            'dokan-request-a-quote-admin',
            DOKAN_RAQ_ASSETS . '/js/dokan-request-a-quote-admin' . $suffix . '.js',
            [ 'jquery', 'dokan-vue-vendor', 'dokan-vue-bootstrap', 'selectWoo' ],
            $version,
            true
        );

        wp_enqueue_style(
            'dokan-request-a-quote-admin-css',
            DOKAN_RAQ_ASSETS . '/css/dokan-request-a-quote-admin' . $suffix . '.css',
            [],
            $version,
            'all'
        );
    }

}
