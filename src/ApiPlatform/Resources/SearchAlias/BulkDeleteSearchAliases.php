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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Search_Alias;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Alias\Command\Bulk_Delete_Search_Terms_Aliases_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Alias\Exception\Alias_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Delete(uriTemplate: '/search-aliases/bulk-delete', CQRSCommand: Bulk_Delete_Search_Terms_Aliases_Command::class, scopes: ['search_alias_write'], CQRSCommandMapping: ['[searchTerms]' => '[searchTerms]'], allowEmptyBody: false, experimentalOperation: true)], exceptionToStatus: [Alias_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Bulk_Delete_Search_Aliases
{
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'string']])]
    public array $search_terms = [];
}