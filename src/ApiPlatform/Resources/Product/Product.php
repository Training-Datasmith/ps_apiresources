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
use Presta_Shop\Presta_Shop\Core\Domain\Product\Command\Add_Product_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Command\Delete_Product_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Command\Update_Product_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Exception\Product_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Query\Get_Product_For_Editing;
use Presta_Shop\Presta_Shop\Core\Domain\Shop\Exception\Shop_Association_Not_Found;
use Presta_Shop\Presta_Shop\Core\Util\DateTime\Date_Immutable;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/products/{productId}', CQRSQuery: Get_Product_For_Editing::class, scopes: ['product_read'], CQRSQueryMapping: Product::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/products', CQRSCommand: Add_Product_Command::class, CQRSQuery: Get_Product_For_Editing::class, scopes: ['product_write'], CQRSQueryMapping: Product::QUERY_MAPPING, CQRSCommandMapping: self::CREATE_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/products/{productId}', CQRSCommand: Update_Product_Command::class, CQRSQuery: Get_Product_For_Editing::class, scopes: ['product_write'], CQRSQueryMapping: Product::QUERY_MAPPING, CQRSCommandMapping: Product::UPDATE_MAPPING), new Cqrs_Delete(uriTemplate: '/products/{productId}', CQRSCommand: Delete_Product_Command::class, scopes: ['product_write'], CQRSCommandMapping: ['[_context][shopConstraint]' => '[shopConstraint]'])], exceptionToStatus: [Product_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Shop_Association_Not_Found::class => Response::HTTP_NOT_FOUND])]
class Product
{
    #[Api_Property(identifier: true)]
    public int $product_id;
    public string $type;
    public bool $enabled;
    #[Localized_Value]
    public array $names;
    #[Localized_Value]
    public array $descriptions;
    #[Localized_Value]
    public array $short_descriptions;
    #[Localized_Value]
    public array $tags;
    public Decimal_Number $price_tax_excluded;
    public Decimal_Number $price_tax_included;
    public Decimal_Number $ecotax_tax_excluded;
    public Decimal_Number $ecotax_tax_included;
    public int $tax_rules_group_id;
    public bool $on_sale;
    public Decimal_Number $wholesale_price;
    public Decimal_Number $unit_price_tax_excluded;
    public Decimal_Number $unit_price_tax_included;
    public string $unity;
    public Decimal_Number $unit_price_ratio;
    public string $visibility;
    public bool $available_for_order;
    public bool $online_only;
    public bool $show_price;
    public string $condition;
    public bool $show_condition;
    public int $manufacturer_id;
    public string $isbn;
    public string $upc;
    public string $gtin;
    public string $mpn;
    public string $reference;
    public Decimal_Number $width;
    public Decimal_Number $height;
    public Decimal_Number $depth;
    public Decimal_Number $weight;
    public Decimal_Number $additional_shipping_cost;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    public array $carrier_reference_ids;
    public int $delivery_time_note_type;
    #[Localized_Value]
    public array $delivery_time_in_stock_notes;
    #[Localized_Value]
    public array $delivery_time_out_of_stock_notes;
    #[Localized_Value]
    public array $meta_titles;
    #[Localized_Value]
    public array $meta_descriptions;
    #[Localized_Value]
    public array $link_rewrites;
    public string $redirect_type;
    public ?int $redirect_target = null;
    public int $pack_stock_type;
    public int $out_of_stock_type;
    public int $quantity;
    public int $minimal_quantity;
    public int $low_stock_threshold;
    public bool $low_stock_alert_enabled;
    #[Localized_Value]
    public array $available_now_labels;
    public string $location;
    #[Localized_Value]
    public array $available_later_labels;
    public ?Date_Immutable $available_date = null;
    public string $cover_thumbnail_url;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    public array $shop_ids;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'object', 'properties' => ['categoryId' => ['type' => 'integer'], 'name' => ['type' => 'string'], 'displayName' => ['type' => 'string']]], 'example' => [['categoryId' => 2, 'name' => 'Home', 'displayName' => 'Home']]])]
    public array $categories;
    public int $default_category_id;
    public const QUERY_MAPPING = [
        '[_context][shopConstraint]' => '[shopConstraint]',
        '[_context][langId]' => '[displayLanguageId]',
        '[active]' => '[enabled]',
        '[basicInformation][localizedNames]' => '[names]',
        '[basicInformation][localizedDescriptions]' => '[descriptions]',
        '[basicInformation][localizedShortDescriptions]' => '[shortDescriptions]',
        '[basicInformation][localizedTags]' => '[tags]',
        '[pricesInformation][price]' => '[priceTaxExcluded]',
        '[pricesInformation][priceTaxIncluded]' => '[priceTaxIncluded]',
        '[pricesInformation][ecotax]' => '[ecotaxTaxExcluded]',
        '[pricesInformation][ecotaxTaxIncluded]' => '[ecotaxTaxIncluded]',
        '[pricesInformation][taxRulesGroupId]' => '[taxRulesGroupId]',
        '[pricesInformation][onSale]' => '[onSale]',
        '[pricesInformation][wholesalePrice]' => '[wholesalePrice]',
        '[pricesInformation][unitPrice]' => '[unitPriceTaxExcluded]',
        '[pricesInformation][unitPriceTaxIncluded]' => '[unitPriceTaxIncluded]',
        '[pricesInformation][unity]' => '[unity]',
        '[pricesInformation][unitPriceRatio]' => '[unitPriceRatio]',
        '[options][visibility]' => '[visibility]',
        '[options][availableForOrder]' => '[availableForOrder]',
        '[options][onlineOnly]' => '[onlineOnly]',
        '[options][showPrice]' => '[showPrice]',
        '[options][condition]' => '[condition]',
        '[options][showCondition]' => '[showCondition]',
        '[options][manufacturerId]' => '[manufacturerId]',
        '[details][isbn]' => '[isbn]',
        '[details][upc]' => '[upc]',
        '[details][gtin]' => '[gtin]',
        '[details][mpn]' => '[mpn]',
        '[details][reference]' => '[reference]',
        '[shippingInformation][width]' => '[width]',
        '[shippingInformation][height]' => '[height]',
        '[shippingInformation][depth]' => '[depth]',
        '[shippingInformation][weight]' => '[weight]',
        '[shippingInformation][additionalShippingCost]' => '[additionalShippingCost]',
        '[shippingInformation][carrierReferences]' => '[carrierReferenceIds]',
        '[shippingInformation][deliveryTimeNoteType]' => '[deliveryTimeNoteType]',
        '[shippingInformation][localizedDeliveryTimeInStockNotes]' => '[deliveryTimeInStockNotes]',
        '[shippingInformation][localizedDeliveryTimeOutOfStockNotes]' => '[deliveryTimeOutOfStockNotes]',
        '[productSeoOptions][localizedMetaTitles]' => '[metaTitles]',
        '[productSeoOptions][localizedMetaDescriptions]' => '[metaDescriptions]',
        '[productSeoOptions][localizedLinkRewrites]' => '[linkRewrites]',
        '[productSeoOptions][redirectType]' => '[redirectType]',
        '[productSeoOptions][redirectTarget][id]' => '[redirectTarget]',
        '[stockInformation][packStockType]' => '[packStockType]',
        '[stockInformation][outOfStockType]' => '[outOfStockType]',
        '[stockInformation][quantity]' => '[quantity]',
        '[stockInformation][minimalQuantity]' => '[minimalQuantity]',
        '[stockInformation][lowStockThreshold]' => '[lowStockThreshold]',
        '[stockInformation][lowStockAlertEnabled]' => '[lowStockAlertEnabled]',
        '[stockInformation][localizedAvailableNowLabels]' => '[availableNowLabels]',
        '[stockInformation][localizedAvailableLaterLabels]' => '[availableLaterLabels]',
        '[stockInformation][location]' => '[location]',
        '[stockInformation][availableDate]' => '[availableDate]',
        // Transform each field one by one (instead of the whole array) to avoid having an extra id field in the target
        '[categoriesInformation][categoriesInformation][@index][id]' => '[categories][@index][categoryId]',
        '[categoriesInformation][categoriesInformation][@index][name]' => '[categories][@index][name]',
        '[categoriesInformation][categoriesInformation][@index][displayName]' => '[categories][@index][displayName]',
        '[categoriesInformation][defaultCategoryId]' => '[defaultCategoryId]',
    ];
    public const CREATE_MAPPING = ['[_context][shopId]' => '[shopId]', '[type]' => '[productType]', '[names]' => '[localizedNames]', '[enabled]' => '[active]'];
    public const UPDATE_MAPPING = ['[_context][shopConstraint]' => '[shopConstraint]', '[type]' => '[productType]', '[enabled]' => '[active]', '[names]' => '[localizedNames]', '[descriptions]' => '[localizedDescriptions]', '[shortDescriptions]' => '[localizedShortDescriptions]', '[metaTitles]' => '[localizedMetaTitles]', '[metaDescriptions]' => '[localizedMetaDescriptions]', '[linkRewrites]' => '[localizedLinkRewrites]', '[deliveryTimeInStockNotes]' => '[localizedDeliveryTimeInStockNotes]', '[deliveryTimeOutOfStockNotes]' => '[localizedDeliveryTimeOutOfStockNotes]', '[availableNowLabels]' => '[localizedAvailableNowLabels]', '[availableLaterLabels]' => '[localizedAvailableLaterLabels]', '[priceTaxExcluded]' => '[price]', '[unitPriceTaxExcluded]' => '[unitPrice]', '[ecotaxTaxExcluded]' => '[ecotax]'];
}