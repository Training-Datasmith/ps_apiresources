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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Address;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Exception\Address_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Address_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/addresses', scopes: ['address_read'], ApiResourceMapping: self::MAPPING, gridDataFactory: 'prestashop.core.grid.data.factory.address', filtersClass: Address_Filters::class, filtersMapping: ['[addressId]' => '[id_address]'])], exceptionToStatus: [Address_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Address_List
{
    #[Api_Property(identifier: true)]
    public int $address_id;
    public string $firstname;
    public string $lastname;
    public string $address1;
    public string $postcode;
    public string $city;
    public string $country_name;
    public const MAPPING = ['[id_address]' => '[addressId]'];
}