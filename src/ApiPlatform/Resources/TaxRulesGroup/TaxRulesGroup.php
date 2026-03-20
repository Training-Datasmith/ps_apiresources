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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Tax_Rules_Group;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Tax_Rules_Group\Command\Add_Tax_Rules_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Tax_Rules_Group\Command\Delete_Tax_Rules_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Tax_Rules_Group\Command\Edit_Tax_Rules_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Tax_Rules_Group\Exception\Cannot_Add_Tax_Rules_Group_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Tax_Rules_Group\Exception\Tax_Rules_Group_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Tax_Rules_Group\Query\Get_Tax_Rules_Group_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/tax-rules-groups', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Tax_Rules_Group_Command::class, scopes: ['tax_rules_group_write'], CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/tax-rules-groups/{taxRulesGroupId}', requirements: ['taxRulesGroupId' => '\d+'], output: false, CQRSCommand: Delete_Tax_Rules_Group_Command::class, scopes: ['tax_rules_group_write']), new Cqrs_Get(uriTemplate: '/tax-rules-groups/{taxRulesGroupId}', requirements: ['taxRulesGroupId' => '\d+'], CQRSQuery: Get_Tax_Rules_Group_For_Editing::class, scopes: ['tax_rules_group_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/tax-rules-groups/{taxRulesGroupId}', requirements: ['taxRulesGroupId' => '\d+'], read: false, CQRSCommand: Edit_Tax_Rules_Group_Command::class, CQRSCommandMapping: self::COMMAND_MAPPING, CQRSQuery: Get_Tax_Rules_Group_For_Editing::class, CQRSQueryMapping: self::QUERY_MAPPING, scopes: ['tax_rules_group_write'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Cannot_Add_Tax_Rules_Group_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Tax_Rules_Group_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Tax_Rules_Group
{
    #[Api_Property(identifier: true)]
    public int $tax_rules_group_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: 64)]
    public string $name;
    #[Assert\Not_Null(groups: ['Create'])]
    public bool $enabled;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public const COMMAND_MAPPING = ['[shopIds]' => '[shopAssociation]'];
    public const QUERY_MAPPING = ['[active]' => '[enabled]', '[shopAssociationIds]' => '[shopIds]'];
}