<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class QuoteRequestPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';
    protected const ROUTE_QUOTE_REQUEST_CREATE = 'quote-request/create';
    protected const ROUTE_QUOTE_REQUEST_REVISE = 'quote-request/revise';
    protected const ROUTE_QUOTE_REQUEST_SEND_TO_USER = 'quote-request/send-to-user';
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS = 'quote-request/edit-items';
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'quote-request/edit-items-confirm';
    protected const ROUTE_QUOTE_REQUEST_CANCEL = 'quote-request/cancel';
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';
    protected const ROUTE_QUOTE_REQUEST_CONVERT_TO_CART = 'quote-request/convert-to-cart';

    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addQuoteRequestRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestCreateRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestReviseRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditItemsRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditItemsConfirmRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestSendToUserRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestCancelRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestDetailsRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestConvertToCartRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestViewController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request', 'QuoteRequestPage', 'QuoteRequestView', 'indexAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestCreateController::createAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/create', 'QuoteRequestPage', 'QuoteRequestCreate', 'createAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestReviseController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestReviseRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/revise/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestRevise', 'indexAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_REVISE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEdit', 'indexAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_EDIT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditItemsController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditItemsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-items/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditItems', 'indexAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_EDIT_ITEMS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditItemsController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditItemsConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-items-confirm/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditItems', 'confirmAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditController::submitAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestSendToUserRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/send-to-user/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEdit', 'sendToUserAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_SEND_TO_USER, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestDeleteController::cancelAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/cancel/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestDelete', 'cancelAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_CANCEL, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestViewController::detailsAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/details/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestView', 'detailsAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestCheckoutController::convertToCartAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestConvertToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/convert-to-cart/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestCheckout', 'convertToCartAction');
        $route = $route->assert(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_CONVERT_TO_CART, $route);

        return $routeCollection;
    }
}
