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
use Presta_Shop\Presta_Shop\Core\Domain\Address\Command\Edit_Cart_Address_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Exception\Address_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Address\Query\Get_Customer_Address_For_Editing;
use Presta_Shop\Presta_Shop\Core\Domain\Cart\Exception\Cart_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Cart\Exception\Invalid_Address_Type_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Country\Exception\Country_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\State\Exception\State_Constraint_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Partial_Update(uriTemplate: '/addresses/carts/{cartId}', CQRSCommand: Edit_Cart_Address_Command::class, CQRSQuery: Get_Customer_Address_For_Editing::class, scopes: ['address_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING, validationContext: ['groups' => ['Default', 'Update']])], exceptionToStatus: [Address_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Country_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, State_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Invalid_Address_Type_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Cart_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Cart_Address
{
    // Identifiers from URI
    #[Api_Property(identifier: true)]
    public int $cart_id = 0;
    public int $address_id;
    public int $customer_id;
    public string $address_type;
    // Optional address fields for update
    public ?string $address_alias = null;
    public ?string $first_name = null;
    public ?string $last_name = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_ADDRESS])]
    public ?string $address = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_ADDRESS])]
    public ?string $address2 = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_CITY_NAME])]
    public ?string $city = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_POST_CODE])]
    public ?string $post_code = null;
    public ?int $country_id = null;
    public ?int $state_id = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_PHONE_NUMBER])]
    public ?string $home_phone = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_PHONE_NUMBER])]
    public ?string $mobile_phone = null;
    public ?string $company = null;
    public ?string $vat_number = null;
    public ?string $other = null;
    #[Typed_Regex(['type' => Typed_Regex::TYPE_DNI_LITE])]
    public ?string $dni = null;
    public const QUERY_MAPPING = [
        // This is to handle NoStateId that is not normalized properly, it was fixed in 9.1 with
        // https://github.com/PrestaShop/PrestaShop/pull/40912
        '[stateId][value]' => '[stateId]',
    ];
    public const COMMAND_MAPPING = ['[postCode]' => '[postcode]', '[homePhone]' => '[phone]', '[mobilePhone]' => '[phone_mobile]', '[vatNumber]' => '[vat_number]', '[stateId]' => '[id_state]'];
}