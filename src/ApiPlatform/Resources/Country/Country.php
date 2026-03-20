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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Country;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Country\Exception\Country_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Country\Query\Get_Country_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/countries/{countryId}', requirements: ['countryId' => '\d+'], CQRSQuery: Get_Country_For_Editing::class, scopes: ['country_read'], CQRSQueryMapping: self::QUERY_MAPPING)], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Country_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Country
{
    #[Api_Property(identifier: true)]
    public int $country_id;
    #[Localized_Value]
    public array $names;
    public string $iso_code;
    public int $call_prefix;
    public int $default_currency_id;
    public int $zone_id;
    public bool $need_zip_code;
    public ?string $zip_code_format = null;
    public string $address_format;
    public bool $enabled;
    public bool $contains_states;
    public bool $need_id_number;
    public bool $display_tax_label;
    public array $shop_ids;
    public const QUERY_MAPPING = ['[localizedNames]' => '[names]', '[defaultCurrency]' => '[defaultCurrencyId]', '[zone]' => '[zoneId]', '[shopAssociation]' => '[shopIds]'];
}