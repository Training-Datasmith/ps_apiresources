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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Module;

use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Command\Upload_Module_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Exception\Module_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Module\Query\Get_Module_Infos;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Symfony\Component\Http_Foundation\File\File;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/modules/upload-source', CQRSCommand: Upload_Module_Command::class, CQRSQuery: Get_Module_Infos::class, scopes: ['module_write']), new Cqrs_Create(uriTemplate: '/modules/upload-archive', inputFormats: ['multipart' => ['multipart/form-data']], read: false, CQRSCommand: Upload_Module_Command::class, CQRSQuery: Get_Module_Infos::class, scopes: ['module_write'], CQRSCommandMapping: ['[archive].pathName' => '[source]'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Module_Not_Found_Exception::class => 404])]
class Upload_Module extends Module
{
    public string $source;
    public File $archive;
}