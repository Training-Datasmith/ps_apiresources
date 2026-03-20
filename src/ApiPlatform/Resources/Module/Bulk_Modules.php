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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Module;

use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Command\Bulk_Toggle_Module_Status_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Command\Bulk_Uninstall_Module_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Exception\Module_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
#[Api_Resource(operations: [new Cqrs_Update(uriTemplate: '/modules/bulk-update-status', output: false, CQRSCommand: Bulk_Toggle_Module_Status_Command::class, scopes: ['module_write'], CQRSCommandMapping: ['[enabled]' => '[expectedStatus]']), new Cqrs_Update(uriTemplate: '/modules/bulk-uninstall', output: false, CQRSCommand: Bulk_Uninstall_Module_Command::class, scopes: ['module_write'])], exceptionToStatus: [Module_Not_Found_Exception::class => 404])]
class Bulk_Modules
{
    /**
     * @var string[]
     */
    public array $modules;
    public bool $enabled;
    public bool $delete_files;
}