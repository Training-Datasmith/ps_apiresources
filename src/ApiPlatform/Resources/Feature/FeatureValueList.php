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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Feature;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Api_Platform\Metadata\Link;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Feature_Value_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/features/{featureId}/values', scopes: ['feature_value_read'], uriVariables: ['featureId' => new Link(identifiers: ['featureId'])], ApiResourceMapping: self::MAPPING, gridDataFactory: 'prestashop.core.grid.data.factory.feature_value', filtersClass: Feature_Value_Filters::class, filtersMapping: ['[featureValueId]' => '[id_feature_value]'])])]
class Feature_Value_List
{
    #[Api_Property(identifier: true)]
    public int $feature_value_id;
    #[Api_Property(readable: false, writable: false)]
    public int $feature_id;
    public string $value;
    public int $position;
    public const MAPPING = ['[id_feature_value]' => '[featureValueId]', '[value]' => '[value]', '[position]' => '[position]'];
}