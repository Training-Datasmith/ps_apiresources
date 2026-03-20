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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Customer;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Command\Add_Customer_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Command\Delete_Customer_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Command\Edit_Customer_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Duplicate_Customer_Email_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Query\Get_Customer_For_Editing;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Value_Object\Customer_Delete_Method;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/customers', CQRSCommand: Add_Customer_Command::class, CQRSQuery: Get_Customer_For_Editing::class, scopes: ['customer_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING, validationContext: ['groups' => ['Default', 'Create']]), new Cqrs_Get(uriTemplate: '/customers/{customerId}', requirements: ['customerId' => '\d+'], CQRSQuery: Get_Customer_For_Editing::class, scopes: ['customer_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/customers/{customerId}', requirements: ['customerId' => '\d+'], read: false, CQRSCommand: Edit_Customer_Command::class, CQRSCommandMapping: self::COMMAND_MAPPING, CQRSQuery: Get_Customer_For_Editing::class, CQRSQueryMapping: self::QUERY_MAPPING, scopes: ['customer_write']), new Cqrs_Delete(uriTemplate: '/customers/{customerId}', CQRSCommand: Delete_Customer_Command::class, scopes: ['customer_write'], CQRSCommandMapping: self::DELETE_COMMAND_MAPPING, openapiContext: ['requestBody' => ['required' => true, 'content' => ['application/json' => ['schema' => ['type' => 'object', 'required' => ['deleteMethod'], 'properties' => ['deleteMethod' => ['type' => 'string', 'enum' => [Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION, Customer_Delete_Method::DENY_CUSTOMER_REGISTRATION], 'description' => 'Method to use for customer deletion', 'example' => Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION]]], 'example' => ['deleteMethod' => 'allow_registration_after']]], 'description' => 'Request body specifying the deletion method']])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Customer_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Customer_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Duplicate_Customer_Email_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Customer_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY])]
class Customer
{
    #[Api_Property(identifier: true, openapiContext: ['type' => 'integer', 'example' => 1])]
    public int $customer_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $first_name;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $last_name;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Email(mode: Assert\Email::VALIDATION_MODE_STRICT)]
    public string $email;
    #[Assert\Not_Blank(groups: ['Create'])]
    public string $password;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 3])]
    public int $default_group_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    public array $group_ids;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 1])]
    public ?int $gender_id = null;
    #[Api_Property(openapiContext: ['type' => 'boolean', 'example' => true])]
    public bool $enabled;
    #[Api_Property(openapiContext: ['type' => 'boolean', 'example' => false])]
    public bool $newsletter_subscribed;
    #[Api_Property(openapiContext: ['type' => 'boolean', 'example' => false])]
    public bool $partner_offers_subscribed;
    public ?string $birthday = null;
    public ?string $company_name = null;
    public ?string $siret_code = null;
    public ?string $ape_code = null;
    public ?string $website = null;
    #[Api_Property(openapiContext: ['type' => 'string', 'example' => '1000.50'])]
    public ?Decimal_Number $allowed_outstanding_amount = null;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 30])]
    public ?int $max_payment_days = null;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 1])]
    public ?int $risk_id = null;
    #[Api_Property(openapiContext: ['type' => 'boolean', 'example' => false])]
    public bool $guest;
    public ?string $delete_method = null;
    public const QUERY_MAPPING = ['[id]' => '[customerId]'];
    public const COMMAND_MAPPING = ['[_context][shopId]' => '[shopId]', '[partnerOffersSubscribed]' => '[isPartnerOffersSubscribed]', '[guest]' => '[isGuest]', '[enabled]' => '[isEnabled]'];
    public const DELETE_COMMAND_MAPPING = ['[deleteMethod]' => '[deleteMethod]'];
}