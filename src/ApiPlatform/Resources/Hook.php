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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Hook\Command\Update_Hook_Status_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Hook\Exception\Hook_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Hook\Query\Get_Hook;
use Presta_Shop\Presta_Shop\Core\Domain\Hook\Query\Get_Hook_Status;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
#[Api_Resource(operations: [new Cqrs_Update(uriTemplate: '/hooks/{hookId}/status', CQRSCommand: Update_Hook_Status_Command::class, CQRSQuery: Get_Hook::class, scopes: ['hook_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Get(uriTemplate: '/hooks/{hookId}', requirements: ['hookId' => '\d+'], exceptionToStatus: [Hook_Not_Found_Exception::class => 404], CQRSQuery: Get_Hook::class, scopes: ['hook_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Get(uriTemplate: '/hooks/{hookId}/status', requirements: ['hookId' => '\d+'], exceptionToStatus: [Hook_Not_Found_Exception::class => 404], CQRSQuery: Get_Hook_Status::class, scopes: ['hook_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Paginated_List(uriTemplate: '/hooks', provider: Query_List_Provider::class, scopes: ['hook_read'], ApiResourceMapping: self::LIST_MAPPING, gridDataFactory: 'prestashop.core.grid.data_factory.hook', filtersMapping: ['[hookId]' => '[id_hook]'])])]
class Hook
{
    #[Api_Property(identifier: true)]
    public int $hook_id;
    public bool $enabled;
    public string $name;
    public string $title;
    public string $description;
    protected const QUERY_MAPPING = [
        // Transforms the url hookId parameter into the $id parameter for GetHook
        '[hookId]' => '[id]',
        // Transforms the query result Hook::getId into the Api resource hookId
        '[id]' => '[hookId]',
        '[active]' => '[enabled]',
    ];
    protected const LIST_MAPPING = ['[id_hook]' => '[hookId]', '[active]' => '[enabled]'];
    protected const COMMAND_MAPPING = [
        // Transforms the url hookId parameter into the $id parameter for UpdateHookStatusCommand
        '[hookId]' => '[id]',
        '[enabled]' => '[active]',
    ];
}