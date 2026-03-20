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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Category;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Exception\Category_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Shop\Exception\Shop_Association_Not_Found;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Category_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/categories', provider: Query_List_Provider::class, scopes: ['category_read'], ApiResourceMapping: ['[id_category]' => '[categoryId]', '[active]' => '[enabled]'], gridDataFactory: 'prestashop.core.grid.data.factory.category_decorator', filtersClass: Category_Filters::class, filtersMapping: ['[categoryId]' => '[id_category]', '[enabled]' => '[active]'])], exceptionToStatus: [Category_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Shop_Association_Not_Found::class => Response::HTTP_NOT_FOUND])]
class Category_List
{
    #[Api_Property(identifier: true)]
    public int $category_id;
    public bool $enabled;
    public string $name;
}