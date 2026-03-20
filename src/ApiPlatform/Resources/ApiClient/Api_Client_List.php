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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Api_Client;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Api_Client_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/api-clients', scopes: ['api_client_read'], ApiResourceMapping: ['[id_api_client]' => '[apiClientId]', '[client_id]' => '[clientId]', '[client_name]' => '[clientName]', '[external_issuer]' => '[externalIssuer]'], gridDataFactory: 'prestashop.core.grid.data_factory.api_client', filtersClass: Api_Client_Filters::class, filtersMapping: ['[apiClientId]' => '[id_api_client]', '[clientId]' => '[client_id]', '[clientName]' => '[client_name]', '[externalIssuer]' => '[external_issuer]'])], normalizationContext: ['skip_null_values' => false])]
class Api_Client_List
{
    #[Api_Property(identifier: true)]
    public int $api_client_id;
    public string $client_id;
    public string $client_name;
    public string $description;
    public ?string $external_issuer = null;
    public bool $enabled;
    public int $lifetime;
}