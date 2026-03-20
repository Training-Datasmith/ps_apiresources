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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Search_Alias;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/search-aliases', provider: Query_List_Provider::class, scopes: ['search_alias_read'], gridDataFactory: 'prestashop.core.grid.data_provider.alias_decorator', experimentalOperation: true)])]
class Search_Alias_List
{
    #[Api_Property(identifier: true)]
    public string $search = '';
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'object', 'properties' => ['id_alias' => ['type' => 'integer'], 'alias' => ['type' => 'string'], 'enabled' => ['type' => 'boolean']]]])]
    public array $aliases = [];
}