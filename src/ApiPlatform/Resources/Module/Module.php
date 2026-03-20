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
use Presta_Shop\Presta_Shop\Core\Domain\Module\Command\Install_Module_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Command\Update_Module_Status_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Exception\Already_Installed_Module_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Exception\Module_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Exception\Module_Not_Installed_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Query\Get_Module_Infos;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/modules/{technicalName}', CQRSQuery: Get_Module_Infos::class, scopes: ['module_read']), new Cqrs_Update(uriTemplate: '/modules/{technicalName}/status', CQRSCommand: Update_Module_Status_Command::class, CQRSQuery: Get_Module_Infos::class, scopes: ['module_write']), new Cqrs_Update(uriTemplate: '/modules/{technicalName}/install', CQRSCommand: Install_Module_Command::class, CQRSQuery: Get_Module_Infos::class, scopes: ['module_write'], allowEmptyBody: true), new Paginated_List(uriTemplate: '/modules', scopes: ['module_read'], gridDataFactory: 'prestashop.core.grid.data_factory.module')], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Module_Not_Found_Exception::class => 404, Module_Not_Installed_Exception::class => 403, Already_Installed_Module_Exception::class => 403])]
class Module
{
    public ?int $module_id = null;
    public string $technical_name;
    public string $module_version;
    public ?string $installed_version = null;
    public bool $enabled;
    public bool $installed;
}