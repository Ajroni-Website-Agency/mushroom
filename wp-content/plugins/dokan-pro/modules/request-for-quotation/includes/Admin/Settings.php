<?php

namespace WeDevs\DokanPro\Modules\RequestForQuotation\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Class for Settings Hooks integration.
 *
 * @since 3.6.0
 */
class Settings {

    /**
     * Class constructor
     *
     * @since 3.6.0
     *
     * @return void
     */
    public function __construct() {
        add_filter( 'dokan_settings_sections', [ $this, 'load_settings_section' ], 11 );
        add_filter( 'dokan_settings_fields', [ $this, 'load_settings_fields' ], 11 );
    }

    /**
     * Load admin settings section.
     *
     * @since 3.6.0
     *
     * @param $section
     *
     * @return mixed
     */
    public function load_settings_section( $section ) {
        $section[] = [
            'id'    => 'dokan_quote_settings',
            'title' => __( 'Quote Settings', 'dokan' ),
            'icon'  => 'dashicons-money-alt',
        ];

        return $section;
    }

    /**
     * Load all settings fields.
     *
     * @since 3.6.0
     *
     * @param $fields
     *
     * @return mixed
     */
    public function load_settings_fields( $fields ) {
        $fields['dokan_quote_settings'] = [
            'dokan_quote_settings'           => [
                'name'  => 'dokan_quote_settings',
                'label' => __( 'Quote Settings', 'dokan' ),
                'type'  => 'sub_section',
            ],
            'enable_out_of_stock'            => [
                'name'    => 'enable_out_of_stock',
                'label'   => __( 'Enable quote for Out of stock products', 'dokan' ),
                'type'    => 'checkbox',
                'desc'    => __( 'Enable/Disable quote button for out of stock products. (Note: It is compatible with simple and variable products only)', 'dokan' ),
                'default' => 'on',
            ],
            'enable_ajax_add_to_quote'       => [
                'name'    => 'enable_ajax_add_to_quote',
                'label'   => __( 'Enable Ajax add to Quote', 'dokan' ),
                'type'    => 'checkbox',
                'desc'    => esc_html__( 'Enable/Disable Ajax add to quote.', 'dokan' ),
                'default' => 'on',
            ],
            'redirect_to_quote_page'         => [
                'name'    => 'redirect_to_quote_page',
                'label'   => __( 'Redirect to Quote Page', 'dokan' ),
                'type'    => 'checkbox',
                'desc'    => esc_html__( 'Redirect to quote page after a product added to quote successfully.', 'dokan' ),
                'default' => 'off',
            ],
            'quote_attributes_settings'      => [
                'name'  => 'quote_attributes_settings',
                'label' => __( 'Quote Attributes Settings', 'dokan' ),
                'type'  => 'sub_section',
            ],
            'decrease_offered_price'         => [
                'name'    => 'decrease_offered_price',
                'label'   => __( 'Decrease offered price', 'dokan' ),
                'type'    => 'number',
                'default' => 0,
                'desc'    => esc_html__( 'Enter number in percent to decrease the offered price from standard price of product. Leave empty for standard price. Note: offered price will be display according to settings of cart. (eg: including/excluding tax)', 'dokan' ),
            ],
            'enable_convert_to_order'        => [
                'name'    => 'enable_convert_to_order',
                'label'   => __( 'Enable convert to order', 'dokan' ),
                'type'    => 'checkbox',
                'desc'    => esc_html__( 'Customer can convert a quote into order.', 'dokan' ),
                'default' => 'off',
            ],
            'enable_quote_converter_display' => [
                'name'    => 'enable_quote_converter_display',
                'label'   => __( 'Enable quote converter display', 'dokan' ),
                'type'    => 'checkbox',
                'desc'    => __( 'Enable display of "Quote converted by" in customer\'s my-account quote details page.', 'dokan' ),
                'default' => 'off',
            ],
        ];

        return $fields;
    }

}
