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
use Presta_Shop\Presta_Shop\Core\Domain\Discount\Exception\Discount_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Discount_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/discounts', provider: Query_List_Provider::class, scopes: ['discount_read'], ApiResourceMapping: ['[id_discount]' => '[discountId]', '[active]' => '[enabled]', '[discount_type]' => '[type]'], gridDataFactory: 'prestashop.core.grid.data.factory.discount', filtersClass: Discount_Filters::class)], exceptionToStatus: [Discount_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Discount_List
{
    #[Api_Property(identifier: true)]
    public int $discount_id;
    public string $type;
    public string $name;
    public bool $enabled;
    public string $code;
}