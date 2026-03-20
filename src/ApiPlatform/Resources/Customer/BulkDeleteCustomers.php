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
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Command\Bulk_Delete_Customer_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Value_Object\Customer_Delete_Method;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Delete(uriTemplate: '/customers/bulk-delete', CQRSCommand: Bulk_Delete_Customer_Command::class, scopes: ['customer_write'], allowEmptyBody: false, openapiContext: ['requestBody' => ['required' => true, 'content' => ['application/json' => ['schema' => ['type' => 'object', 'required' => ['customerIds'], 'properties' => ['customerIds' => ['type' => 'array', 'items' => ['type' => 'integer']], 'deleteMethod' => ['type' => 'string', 'enum' => [Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION, Customer_Delete_Method::DENY_CUSTOMER_REGISTRATION], 'description' => 'Method to use for customer deletion. Default: allow_registration_after']]], 'example' => ['customerIds' => [1, 2, 3], 'deleteMethod' => 'allow_registration_after']]]]])], exceptionToStatus: [Customer_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Customer_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Bulk_Delete_Customers
{
    /**
     * @var int[]
     */
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 2, 3]])]
    #[Assert\Not_Blank]
    public array $customer_ids;
    #[Api_Property(openapiContext: ['type' => 'string', 'enum' => [Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION, Customer_Delete_Method::DENY_CUSTOMER_REGISTRATION], 'example' => Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION])]
    #[Assert\Choice(choices: [Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION, Customer_Delete_Method::DENY_CUSTOMER_REGISTRATION], message: 'The delete method must be either "allow_registration_after" or "deny_registration_after".')]
    public string $delete_method = Customer_Delete_Method::ALLOW_CUSTOMER_REGISTRATION;
}