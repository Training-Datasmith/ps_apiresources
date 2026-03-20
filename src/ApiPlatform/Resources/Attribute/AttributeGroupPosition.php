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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Attribute;

use Api_Platform\Metadata\Api_Resource;
use Presta_Shop_Bundle\Api_Platform\Metadata\Position_Collection;
use Presta_Shop_Bundle\Api_Platform\Metadata\Update_Position;
#[Api_Resource(operations: [new Update_Position(uriTemplate: '/attributes/groups/positions', scopes: ['attribute_group_write'], positionDefinition: 'prestashop.core.grid.attribute_group.position_definition')])]
class Attribute_Group_Position
{
    #[Position_Collection(rowIdField: 'attributeGroupId')]
    public array $positions;
}