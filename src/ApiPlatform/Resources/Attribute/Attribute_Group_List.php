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

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/attributes/groups', scopes: ['attribute_group_read'], ApiResourceMapping: self::MAPPING, gridDataFactory: 'prestashop.core.grid.data.factory.attribute_group_decorator', filtersMapping: ['[attributeGroupId]' => '[id_attribute_group]'])])]
class Attribute_Group_List
{
    #[Api_Property(identifier: true)]
    public int $attribute_group_id;
    public string $name;
    public int $values;
    public int $position;
    public const MAPPING = ['[id_attribute_group]' => '[attributeGroupId]'];
}