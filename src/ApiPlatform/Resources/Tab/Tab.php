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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Tab;

use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Tab\Command\Update_Tab_Status_By_Class_Name_Command;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Update(uriTemplate: '/tabs/{className}', read: false, CQRSCommand: Update_Tab_Status_By_Class_Name_Command::class, scopes: ['tab_write'], CQRSCommandMapping: ['[enabled]' => '[status]'])], normalizationContext: ['skip_null_values' => false])]
class Tab
{
    #[Assert\Not_Blank]
    public string $class_name;
    #[Assert\Not_Null]
    public bool $enabled;
}