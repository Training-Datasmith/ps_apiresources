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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Customer;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Group\Command\Add_Customer_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Group\Command\Delete_Customer_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Group\Command\Edit_Customer_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Group\Exception\Group_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Group\Query\Get_Customer_Group_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
#[Api_Resource(operations: [new Cqrs_Get(
    uriTemplate: '/customers/groups/{customerGroupId}',
    CQRSQuery: Get_Customer_Group_For_Editing::class,
    scopes: ['customer_group_read'],
    // QueryResult format doesn't match with ApiResource, so we can specify a mapping so that it is normalized with extra fields adapted for the ApiResource DTO
    CQRSQueryMapping: [
        // EditableCustomerGroup::$id is normalized as [customerGroupId]
        '[id]' => '[customerGroupId]',
        // EditableCustomerGroup::$reduction is normalized as [reductionPercent]
        '[reduction]' => '[reductionPercent]',
    ]
), new Cqrs_Create(
    uriTemplate: '/customers/groups',
    CQRSCommand: Add_Customer_Group_Command::class,
    CQRSQuery: Get_Customer_Group_For_Editing::class,
    scopes: ['customer_group_write'],
    // Here, we use query mapping to adapt normalized query result for the ApiPlatform DTO
    CQRSQueryMapping: ['[id]' => '[customerGroupId]', '[reduction]' => '[reductionPercent]'],
    // Here, we use command mapping to adapt the normalized command result for the CQRS query
    CQRSCommandMapping: ['[_context][shopIds]' => '[shopIds]', '[groupId]' => '[customerGroupId]']
), new Cqrs_Update(
    uriTemplate: '/customers/groups/{customerGroupId}',
    CQRSCommand: Edit_Customer_Group_Command::class,
    CQRSQuery: Get_Customer_Group_For_Editing::class,
    scopes: ['customer_group_write'],
    // Here we use the ApiResource DTO mapping to transform the normalized query result
    ApiResourceMapping: ['[id]' => '[customerGroupId]', '[reduction]' => '[reductionPercent]']
), new Cqrs_Delete(
    uriTemplate: '/customers/groups/{customerGroupId}',
    CQRSCommand: Delete_Customer_Group_Command::class,
    scopes: ['customer_group_write'],
    // Here, we use query mapping to adapt URI parameters to the expected constructor parameter name
    CQRSCommandMapping: ['[customerGroupId]' => '[groupId]']
)], exceptionToStatus: [Group_Not_Found_Exception::class => 404])]
class Customer_Group
{
    #[Api_Property(identifier: true)]
    public int $customer_group_id;
    #[Localized_Value]
    public array $localized_names;
    public Decimal_Number $reduction_percent;
    public bool $display_price_tax_excluded;
    public bool $show_price;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer']])]
    public array $shop_ids;
}