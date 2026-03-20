<?php

declare (strict_types=1);
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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Product;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Combination\Query\Get_Editable_Combinations_List;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Exception\Product_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Product_Combination_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Paginate;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Paginate(uriTemplate: '/products/{productId}/combinations', CQRSQuery: Get_Editable_Combinations_List::class, scopes: ['product_read'], CQRSQueryMapping: ['[_context][langId]' => '[languageId]', '[_context][shopConstraint]' => '[shopConstraint]'], ApiResourceMapping: ['[combinationName]' => '[name]', '[attributesInformation]' => '[attributes]', '[impactOnPrice]' => '[impactOnPriceTaxExcluded]'], filtersClass: Product_Combination_Filters::class, filtersMapping: ['[_context][shopId]' => '[shopId]'], itemsField: 'combinations', countField: 'totalCombinationsCount')], exceptionToStatus: [Product_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Combination_List
{
    public int $product_id;
    public int $combination_id;
    public string $name;
    public bool $default;
    public string $reference;
    public Decimal_Number $impact_on_price_tax_excluded;
    public Decimal_Number $eco_tax;
    public int $quantity;
    public string $image_url;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'Combination attributes', 'items' => ['type' => 'object', 'properties' => ['attributeGroupId' => ['type' => 'integer'], 'attributeGroupName' => ['type' => 'string'], 'attributeId' => ['type' => 'integer'], 'attributeName' => ['type' => 'string']]]])]
    public array $attributes;
}