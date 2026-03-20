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
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Exception\Customer_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Customer\Query\Get_Customer_For_Viewing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/customers/{customerId}/details', requirements: ['customerId' => '\d+'], CQRSQuery: Get_Customer_For_Viewing::class, scopes: ['customer_read'], CQRSQueryMapping: self::QUERY_MAPPING)], exceptionToStatus: [Customer_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Customer_Details
{
    #[Api_Property(identifier: true, openapiContext: ['type' => 'integer', 'example' => 1])]
    public int $customer_id;
    public array $personal_information;
    public array $orders_information;
    public array $carts_information;
    public array $products_information;
    public array $messages_information;
    public array $discounts_information;
    public array $sent_emails_information;
    public array $last_connections_information;
    public array $groups_information;
    public array $addresses_information;
    public array $general_information;
    public const QUERY_MAPPING = ['[customerId]' => '[customerId]'];
}