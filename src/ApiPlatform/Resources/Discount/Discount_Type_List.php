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
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Query\Get_Discount_Types;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get_Collection;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
#[Api_Resource(operations: [new Cqrs_Get_Collection(uriTemplate: '/discounts/types', CQRSQuery: Get_Discount_Types::class, scopes: ['discount_read'], CQRSQueryMapping: [], ApiResourceMapping: ['[type]' => '[type]', '[localizedNames]' => '[names]', '[localizedDescriptions]' => '[descriptions]', '[core]' => '[core]', '[enabled]' => '[enabled]'])], normalizationContext: ['skip_null_values' => false])]
class Discount_Type_List
{
    #[Api_Property(identifier: true)]
    public int $discount_type_id;
    public string $type;
    #[Localized_Value]
    public array $names;
    #[Localized_Value]
    public array $descriptions;
    public bool $core;
    public bool $enabled;
}