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
use Presta_Shop\Presta_Shop\Core\Domain\Address\Command\Add_Manufacturer_Address_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Command\Edit_Manufacturer_Address_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Exception\Address_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Exception\Address_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Query\Get_Manufacturer_Address_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/addresses/manufacturers/{addressId}', CQRSQuery: Get_Manufacturer_Address_For_Editing::class, scopes: ['address_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/addresses/manufacturers', CQRSCommand: Add_Manufacturer_Address_Command::class, CQRSQuery: Get_Manufacturer_Address_For_Editing::class, scopes: ['address_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING, validationContext: ['groups' => ['Default', 'Create']]), new Cqrs_Partial_Update(uriTemplate: '/addresses/manufacturers/{addressId}', CQRSCommand: Edit_Manufacturer_Address_Command::class, CQRSQuery: Get_Manufacturer_Address_For_Editing::class, scopes: ['address_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING, validationContext: ['groups' => ['Default', 'Update']])], exceptionToStatus: [Address_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Address_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Manufacturer_Address
{
    #[Api_Property(identifier: true)]
    public int $address_id;
    public ?int $manufacturer_id = null;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $last_name;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $first_name;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Typed_Regex(['type' => Typed_Regex::TYPE_ADDRESS])]
    public string $address;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_ADDRESS])]
    public ?string $address2 = null;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Typed_Regex(['type' => Typed_Regex::TYPE_CITY_NAME])]
    public string $city;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_POST_CODE])]
    public ?string $post_code = null;
    #[Assert\Not_Blank(groups: ['Create'])]
    public int $country_id;
    public ?int $state_id = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_PHONE_NUMBER])]
    public ?string $home_phone = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_PHONE_NUMBER])]
    public ?string $mobile_phone = null;
    public ?string $other = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_DNI_LITE])]
    public ?string $dni = null;
    public const QUERY_MAPPING = ['[id]' => '[addressId]'];
    public const COMMAND_MAPPING = [];
}