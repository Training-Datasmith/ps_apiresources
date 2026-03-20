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
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Typed_Regex;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Command\Add_Customer_Address_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Command\Edit_Customer_Address_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Exception\Address_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Exception\Address_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Query\Get_Customer_Address_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/addresses/customers/{addressId}', CQRSQuery: Get_Customer_Address_For_Editing::class, scopes: ['address_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/addresses/customers', CQRSCommand: Add_Customer_Address_Command::class, CQRSQuery: Get_Customer_Address_For_Editing::class, scopes: ['address_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING, validationContext: ['groups' => ['Default', 'Create']]), new Cqrs_Partial_Update(uriTemplate: '/addresses/customers/{addressId}', CQRSCommand: Edit_Customer_Address_Command::class, CQRSQuery: Get_Customer_Address_For_Editing::class, scopes: ['address_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING, validationContext: ['groups' => ['Default', 'Update']])], exceptionToStatus: [Address_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Address_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Customer_Address
{
    #[Api_Property(identifier: true)]
    public int $address_id;
    public int $customer_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $address_alias;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $first_name;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $last_name;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Typed_Regex(['type' => Typed_Regex::TYPE_ADDRESS])]
    public string $address;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_ADDRESS])]
    public ?string $address2 = null;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Typed_Regex(['type' => Typed_Regex::TYPE_CITY_NAME])]
    public string $city;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_POST_CODE])]
    public string $post_code;
    #[Assert\Not_Blank(groups: ['Create'])]
    public int $country_id;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_DNI_LITE])]
    public ?string $dni = null;
    public ?string $company = null;
    public ?string $vat_number = null;
    public int $state_id;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_PHONE_NUMBER])]
    public ?string $home_phone = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_PHONE_NUMBER])]
    public ?string $mobile_phone = null;
    public ?string $other = null;
    public const QUERY_MAPPING = [
        '[id]' => '[addressId]',
        // This is to handle NoStateId that is not normalized properly, it was fixed in 9.1 with
        // https://github.com/PrestaShop/PrestaShop/pull/40912
        '[stateId][value]' => '[stateId]',
    ];
    public const COMMAND_MAPPING = ['[postCode]' => '[postcode]', '[homePhone]' => '[phone]', '[mobilePhone]' => '[phone_mobile]', '[vatNumber]' => '[vat_number]', '[stateId]' => '[id_state]'];
}