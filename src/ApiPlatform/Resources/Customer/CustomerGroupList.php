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

use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Group\Exception\Group_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/customers/groups', gridDataFactory: 'prestashop.core.grid.data.factory.customer_groups', ApiResourceMapping: ['[id_group]' => '[customerGroupId]', '[reduction]' => '[reductionPercent]', '[show_prices]' => '[showPrice]', '[members]' => '[customers]'])], exceptionToStatus: [Group_Not_Found_Exception::class => 404])]
class Customer_Group_List
{
    public int $customer_group_id;
    public string $name;
    public Decimal_Number $reduction_percent;
    public int $customers;
    public bool $show_price;
}