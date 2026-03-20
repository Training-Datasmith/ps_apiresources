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
use Presta_Shop\Presta_Shop\Core\Domain\Product\Combination\Exception\Combination_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Combination\Query\Get_Combination_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/products/combinations/{combinationId}', CQRSQuery: Get_Combination_For_Editing::class, scopes: ['product_read'], CQRSQueryMapping: self::QUERY_MAPPING)], exceptionToStatus: [Combination_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Combination
{
    public int $product_id;
    #[Api_Property(identifier: true)]
    public int $combination_id;
    public string $name;
    public bool $default;
    public string $gtin;
    public string $isbn;
    public string $mpn;
    public string $reference;
    public string $upc;
    public string $cover_thumbnail_url;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'List of image IDs', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    public array $image_ids;
    public Decimal_Number $impact_on_price_tax_excluded;
    public Decimal_Number $impact_on_price_tax_included;
    public Decimal_Number $impact_on_unit_price;
    public Decimal_Number $impact_on_unit_price_tax_included;
    public Decimal_Number $ecotax_tax_excluded;
    public Decimal_Number $ecotax_tax_included;
    public Decimal_Number $impact_on_weight;
    public Decimal_Number $wholesale_price;
    public Decimal_Number $product_tax_rate;
    public Decimal_Number $product_price_tax_excluded;
    public Decimal_Number $product_ecotax_tax_excluded;
    public int $quantity;
    public const QUERY_MAPPING = ['[_context][shopConstraint]' => '[shopConstraint]', '[details][gtin]' => '[gtin]', '[details][isbn]' => '[isbn]', '[details][mpn]' => '[mpn]', '[details][reference]' => '[reference]', '[details][upc]' => '[upc]', '[details][impactOnWeight]' => '[impactOnWeight]', '[prices][impactOnPrice]' => '[impactOnPriceTaxExcluded]', '[prices][impactOnPriceTaxIncluded]' => '[impactOnPriceTaxIncluded]', '[prices][impactOnUnitPrice]' => '[impactOnUnitPriceTaxExcluded]', '[prices][impactOnUnitPriceTaxIncluded]' => '[impactOnUnitPriceTaxIncluded]', '[prices][ecotax]' => '[ecotaxTaxExcluded]', '[prices][ecotaxTaxIncluded]' => '[ecotaxTaxIncluded]', '[prices][wholesalePrice]' => '[wholesalePrice]', '[prices][productTaxRate]' => '[productTaxRate]', '[prices][productPrice]' => '[productPriceTaxExcluded]', '[prices][productEcotax]' => '[productEcotaxTaxExcluded]', '[stock][quantity]' => '[quantity]'];
}