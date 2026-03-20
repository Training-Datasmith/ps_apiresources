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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Webservice_Key;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Webservice\Exception\Webservice_Key_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Webservice_Key_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/webservice-keys', provider: Query_List_Provider::class, scopes: ['webservice_key_read'], ApiResourceMapping: ['[id_webservice_account]' => '[webserviceKeyId]', '[active]' => '[enabled]'], gridDataFactory: 'prestashop.core.grid.data_factory.webservice_key', filtersClass: Webservice_Key_Filters::class)], exceptionToStatus: [Webservice_Key_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Webservice_Key_List
{
    #[Api_Property(identifier: true)]
    public int $webservice_key_id;
    public string $key;
    public string $description;
    public bool $enabled;
}