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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Discount;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Api_Platform\Open_Api\Model\Operation as OpenApiOperation;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Command\Add_Discount_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Command\Delete_Discount_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Command\Duplicate_Discount_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Command\Update_Discount_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Exception\Discount_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Exception\Discount_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Query\Get_Discount_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/discounts/{discountId}', requirements: ['discountId' => '\d+'], CQRSQuery: Get_Discount_For_Editing::class, scopes: ['discount_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/discounts', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Discount_Command::class, CQRSQuery: Get_Discount_For_Editing::class, scopes: ['discount_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/discounts/{discountId}', requirements: ['discountId' => '\d+'], CQRSCommand: Update_Discount_Command::class, CQRSQuery: Get_Discount_For_Editing::class, scopes: ['discount_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/discounts/{discountId}', CQRSCommand: Delete_Discount_Command::class, scopes: ['discount_write']), new Cqrs_Create(uriTemplate: '/discounts/{discountId}/duplicate', requirements: ['discountId' => '\d+'], CQRSCommand: Duplicate_Discount_Command::class, CQRSQuery: Get_Discount_For_Editing::class, scopes: ['discount_write'], CQRSQueryMapping: self::QUERY_MAPPING, allowEmptyBody: true, openapi: new Open_Api_Operation(summary: 'Duplicate a Discount resource.', description: 'Creates a copy of an existing Discount resource.'))], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Discount_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Discount_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY])]
class Discount
{
    #[Api_Property(identifier: true)]
    public int $discount_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $type;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Localized_Value]
    public array $names;
    public string $description;
    public string $code;
    public bool $enabled;
    public ?int $total_quantity = null;
    public ?int $quantity_per_user = null;
    public ?Decimal_Number $reduction_percent = null;
    #[Api_Property(openapiContext: ['type' => 'object', 'description' => 'Fixed reduction amount', 'properties' => ['amount' => ['type' => 'number', 'description' => 'Fixed reduction amount value'], 'currencyId' => ['type' => 'integer', 'description' => 'Currency ID for reduction amount'], 'taxIncluded' => ['type' => 'boolean', 'Whether reduction amount is tax included']]])]
    public ?array $reduction_amount = null;
    public ?int $gift_product_id = null;
    public ?int $gift_combination_id = null;
    // Conditions/compatibility values
    public bool $cheapest_product;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'Product conditions (rule groups)', 'items' => ['type' => 'object', 'properties' => ['quantity' => ['type' => 'integer'], 'rules' => ['type' => 'array', 'items' => ['type' => 'object', 'properties' => ['type' => ['type' => 'string', 'enum' => [
        // We use hard-coded values because the ProductRuleType class is only available
        // in 9.1 and using it breaks the parsing of API resources on 9.0
        'categories',
        'products',
        'combinations',
        'manufacturers',
        'suppliers',
        'attributes',
        'features',
    ]], 'itemIds' => ['type' => 'array', 'items' => ['type' => 'integer']], 'required' => ['type', 'itemIds']]]], 'type' => ['type' => 'string', 'enum' => [
        // We use hard-coded values because the ProductRuleGroupType class is only available
        // in 9.1 and using it breaks the parsing of API resources on 9.0
        'all_product_rules',
        'at_least_one_product_rule',
    ]], 'required' => ['quantity', 'rules']]]])]
    public ?array $product_conditions = null;
    #[Api_Property(openapiContext: ['type' => 'integer', 'description' => 'Minimum quantity of products required', 'minimum' => 0])]
    public ?int $minimum_product_quantity = null;
    #[Api_Property(openapiContext: ['type' => 'object', 'description' => 'Minimum amount required', 'properties' => ['amount' => ['type' => 'number', 'description' => 'Minimum amount value'], 'currencyId' => ['type' => 'integer', 'description' => 'Currency ID for minimum amount'], 'taxIncluded' => ['type' => 'boolean', 'Whether minimum amount is tax included'], 'shippingIncluded' => ['type' => 'boolean', 'description' => 'Whether minimum amount includes shipping']]])]
    public ?array $minimum_amount = null;
    public ?int $customer_id = null;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'Customer group IDs for which the discount is valid', 'items' => ['type' => 'integer']])]
    public ?array $customer_group_ids = null;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'Carrier IDs for which the discount is valid', 'items' => ['type' => 'integer']])]
    public ?array $carrier_ids = null;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'Country IDs for which the discount is valid', 'items' => ['type' => 'integer']])]
    public ?array $country_ids = null;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'Discount Type IDs compatible with the discount', 'items' => ['type' => 'integer']])]
    public ?array $compatible_discount_type_ids = null;
    // End of conditions/compatibility values
    public bool $highlight_in_cart;
    public bool $allow_partial_use;
    public int $priority;
    public \DateTimeImmutable $valid_from;
    public ?\DateTimeImmutable $valid_to = null;
    protected const QUERY_MAPPING = ['[localizedNames]' => '[names]', '[active]' => '[enabled]'];
    protected const COMMAND_MAPPING = ['[names]' => '[localizedNames]', '[enabled]' => '[active]'];
}