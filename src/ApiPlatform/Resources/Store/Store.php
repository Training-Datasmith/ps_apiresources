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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Store;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Store\Command\Delete_Store_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Store\Command\Toggle_Store_Status_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Store\Exception\Store_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Store\Query\Get_Store_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Delete(uriTemplate: '/stores/{storeId}', requirements: ['storeId' => '\d+'], output: false, CQRSCommand: Delete_Store_Command::class, scopes: ['store_write']), new Cqrs_Get(uriTemplate: '/stores/{storeId}', requirements: ['storeId' => '\d+'], CQRSQuery: Get_Store_For_Editing::class, scopes: ['store_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Update(uriTemplate: '/stores/{storeId}/toggle-status', requirements: ['storeId' => '\d+'], output: false, allowEmptyBody: true, CQRSCommand: Toggle_Store_Status_Command::class, scopes: ['store_write'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Store_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Store
{
    #[Api_Property(identifier: true)]
    public int $store_id;
    #[Assert\Not_Null(groups: ['Create'])]
    public bool $enabled;
    public const COMMAND_MAPPING = ['[enabled]' => '[active]'];
    public const QUERY_MAPPING = ['[active]' => '[enabled]'];
}