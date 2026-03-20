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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Tax;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Default_Language;
use Presta_Shop\Presta_Shop\Core\Domain\Tax\Command\Add_Tax_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Tax\Command\Delete_Tax_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Tax\Command\Edit_Tax_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Tax\Exception\Tax_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Tax\Query\Get_Tax_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/taxes', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Tax_Command::class, scopes: ['tax_write'], CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/taxes/{taxId}', requirements: ['taxId' => '\d+'], output: false, CQRSCommand: Delete_Tax_Command::class, scopes: ['tax_write']), new Cqrs_Get(uriTemplate: '/taxes/{taxId}', requirements: ['taxId' => '\d+'], CQRSQuery: Get_Tax_For_Editing::class, scopes: ['tax_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/taxes/{taxId}', requirements: ['taxId' => '\d+'], read: false, CQRSCommand: Edit_Tax_Command::class, CQRSCommandMapping: self::COMMAND_MAPPING, CQRSQuery: Get_Tax_For_Editing::class, CQRSQueryMapping: self::QUERY_MAPPING, scopes: ['tax_write'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Tax_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Tax
{
    #[Api_Property(identifier: true)]
    public int $tax_id;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'names')]
    #[Default_Language(groups: ['Update'], fieldName: 'names', allowNull: true)]
    public array $names;
    #[Assert\Not_Null(groups: ['Create'])]
    public Decimal_Number $rate;
    #[Assert\Not_Null(groups: ['Create'])]
    public bool $enabled;
    public const COMMAND_MAPPING = ['[enabled]' => '[active]', '[names]' => '[localizedNames]'];
    public const QUERY_MAPPING = ['[active]' => '[enabled]', '[localizedNames]' => '[names]'];
}