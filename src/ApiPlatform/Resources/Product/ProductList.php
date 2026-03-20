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
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Adapter\Product\Grid\Data\Factory\Product_Grid_Data_Factory_Decorator;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Exception\Product_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Shop\Exception\Shop_Association_Not_Found;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Product_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/products', provider: Query_List_Provider::class, scopes: ['product_read'], ApiResourceMapping: ['[id_product]' => '[productId]', '[price_tax_excluded_decimal_value]' => '[priceTaxExcluded]', '[price_tax_included_decimal_value]' => '[priceTaxIncluded]', '[active]' => '[enabled]'], gridDataFactory: Product_Grid_Data_Factory_Decorator::class, filtersClass: Product_Filters::class, filtersMapping: ['[productId]' => '[id_product]', '[priceTaxExcluded]' => '[final_price_tax_excluded]', '[priceTaxIncluded]' => '[final_price_tax_included]', '[enabled]' => '[active]'])], exceptionToStatus: [Product_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Shop_Association_Not_Found::class => Response::HTTP_NOT_FOUND])]
class Product_List
{
    #[Api_Property(identifier: true)]
    public int $product_id;
    public string $type;
    public bool $enabled;
    public string $name;
    public int $quantity;
    public Decimal_Number $price_tax_excluded;
    public Decimal_Number $price_tax_included;
    public string $category;
}