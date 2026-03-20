<?php

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */
declare (strict_types=1);
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Product;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Api_Platform\Metadata\Parameters;
use Api_Platform\Metadata\Query_Parameter;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Query\Search_Products;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get_Collection;
#[Api_Resource(operations: [new Cqrs_Get_Collection(uriTemplate: '/products/search', scopes: ['product_read'], CQRSQuery: Search_Products::class, parameters: new Parameters([new Query_Parameter(key: 'phrase', required: true, description: 'Search phrase to find products'), new Query_Parameter(key: 'resultsLimit', schema: ['type' => 'integer', 'default' => '20'], required: true, description: 'Maximum number of results to return'), new Query_Parameter(key: 'isoCode', required: true, description: 'Currency ISO code (e.g., EUR, USD)'), new Query_Parameter(key: 'orderId', schema: ['type' => 'integer'], required: false, description: 'Optional order ID for context-specific pricing')]), openapiContext: ['parameters' => [['name' => 'phrase', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'string'], 'description' => 'Search phrase to find products'], ['name' => 'resultsLimit', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'integer', 'default' => 20], 'description' => 'Maximum number of results to return'], ['name' => 'isoCode', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'string'], 'description' => 'Currency ISO code (e.g., EUR, USD)'], ['name' => 'orderId', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'integer'], 'description' => 'Optional order ID for context-specific pricing']]])])]
class Found_Product
{
    #[Api_Property(identifier: true)]
    public int $product_id;
    public bool $available_out_of_stock;
    public string $name;
    public Decimal_Number $tax_rate;
    public string $formatted_price;
    public Decimal_Number $price_tax_incl;
    public Decimal_Number $price_tax_excl;
    public int $stock;
    public string $location;
    public array $combinations;
    public array $customization_fields;
}