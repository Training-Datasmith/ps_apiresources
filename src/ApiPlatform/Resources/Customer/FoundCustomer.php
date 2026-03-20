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
use Api_Platform\Metadata\Parameters;
use Api_Platform\Metadata\Query_Parameter;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Query\Search_Customers;
use Presta_Shop\Presta_Shop\Core\Domain\Shop\Exception\Invalid_Shop_Constraint_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get_Collection;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Get_Collection(uriTemplate: '/customers/search', scopes: ['customer_read'], CQRSQuery: Search_Customers::class, CQRSQueryMapping: self::QUERY_MAPPING, ApiResourceMapping: self::API_RESOURCE_MAPPING, parameters: new Parameters([new Query_Parameter(key: 'phrases', required: true, description: 'Array of search phrases to find customers (matches first name, last name, email, company name and id)')]), openapiContext: ['parameters' => [['name' => 'phrases', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'array', 'items' => ['type' => 'string']], 'description' => 'Array of search phrases to find customers (matches first name, last name, email, company name and id)', 'style' => 'form', 'explode' => true]]])], exceptionToStatus: [Customer_Exception::class => Response::HTTP_BAD_REQUEST, Invalid_Shop_Constraint_Exception::class => Response::HTTP_BAD_REQUEST])]
class Found_Customer
{
    #[Api_Property(identifier: true, openapiContext: ['type' => 'integer', 'example' => 1])]
    public int $id_customer;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $fullname_and_email;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 1])]
    public int $active;
    public ?string $company = null;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 3])]
    public int $id_default_group;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    public array $groups;
    public const QUERY_MAPPING = ['[phrases]' => '[phrases]', '[_context][shopConstraint]' => '[shopConstraint]'];
    public const API_RESOURCE_MAPPING = ['[id_customer]' => '[idCustomer]', '[fullname_and_email]' => '[fullnameAndEmail]', '[id_default_group]' => '[idDefaultGroup]'];
}