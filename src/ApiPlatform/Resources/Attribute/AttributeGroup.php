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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Attribute;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Default_Language;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Typed_Regex;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Command\Add_Attribute_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Command\Delete_Attribute_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Command\Edit_Attribute_Group_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Exception\Attribute_Group_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Exception\Attribute_Group_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Query\Get_Attribute_Group_For_Editing;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Value_Object\Attribute_Group_Type;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/attributes/groups/{attributeGroupId}', CQRSQuery: Get_Attribute_Group_For_Editing::class, scopes: ['attribute_group_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/attributes/groups', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Attribute_Group_Command::class, CQRSQuery: Get_Attribute_Group_For_Editing::class, scopes: ['attribute_group_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/attributes/groups/{attributeGroupId}', requirements: ['attributeGroupId' => '\d+'], validationContext: ['groups' => ['Default', 'Update']], CQRSCommand: Edit_Attribute_Group_Command::class, CQRSQuery: Get_Attribute_Group_For_Editing::class, scopes: ['attribute_group_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/attributes/groups/{attributeGroupId}', requirements: ['attributeGroupId' => '\d+'], CQRSCommand: Delete_Attribute_Group_Command::class, scopes: ['attribute_group_write'])], exceptionToStatus: [Attribute_Group_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Attribute_Group_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Attribute_Group
{
    #[Api_Property(identifier: true)]
    public int $attribute_group_id;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'names')]
    #[Default_Language(groups: ['Update'], fieldName: 'names', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $names;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'publicNames')]
    #[Default_Language(groups: ['Update'], fieldName: 'publicNames', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $public_names;
    #[Assert\Choice(choices: [Attribute_Group_Type::ATTRIBUTE_GROUP_TYPE_COLOR, Attribute_Group_Type::ATTRIBUTE_GROUP_TYPE_SELECT, Attribute_Group_Type::ATTRIBUTE_GROUP_TYPE_RADIO])]
    public string $type;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public int $position;
    public const QUERY_MAPPING = ['[name]' => '[names]', '[publicName]' => '[publicNames]', '[associatedShopIds]' => '[shopIds]'];
    public const COMMAND_MAPPING = ['[names]' => '[localizedNames]', '[publicNames]' => '[localizedPublicNames]', '[shopIds]' => '[associatedShopIds]'];
}