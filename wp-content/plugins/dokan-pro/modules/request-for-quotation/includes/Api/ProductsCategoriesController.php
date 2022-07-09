<?php
namespace WeDevs\DokanPro\Modules\RequestForQuotation\Api;

use WC_REST_Product_Categories_V2_Controller;

/**
 * Request A Quote Controller Class
 *
 * @since 3.6.0
 */
class ProductsCategoriesController extends WC_REST_Product_Categories_V2_Controller {

    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'dokan/v1';

    /**
     * Route name
     *
     * @var string
     */
    protected $base = 'products/categories';
}
