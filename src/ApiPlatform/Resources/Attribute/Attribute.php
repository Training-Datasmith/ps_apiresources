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
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Attribute\Command\Add_Attribute_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Attribute\Command\Delete_Attribute_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Attribute\Command\Edit_Attribute_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Attribute\Exception\Attribute_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Attribute\Exception\Attribute_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Attribute_Group\Attribute\Query\Get_Attribute_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/attributes/attributes/{attributeId}', CQRSQuery: Get_Attribute_For_Editing::class, scopes: ['attribute_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/attributes/attributes', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Attribute_Command::class, CQRSQuery: Get_Attribute_For_Editing::class, scopes: ['attribute_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::CREATE_COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/attributes/attributes/{attributeId}', validationContext: ['groups' => ['Default', 'Update']], CQRSCommand: Edit_Attribute_Command::class, CQRSQuery: Get_Attribute_For_Editing::class, scopes: ['attribute_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::UPDATE_COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/attributes/attributes/{attributeId}', requirements: ['attributeId' => '\d+'], CQRSCommand: Delete_Attribute_Command::class, scopes: ['attribute_write'])], exceptionToStatus: [Attribute_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Attribute_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Attribute
{
    #[Api_Property(identifier: true)]
    public int $attribute_id;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 1])]
    public int $attribute_group_id;
    #[Localized_Value]
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Default_Language(groups: ['Create'], fieldName: 'names')]
    #[Default_Language(groups: ['Update'], fieldName: 'names', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $names;
    public string $color;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public const QUERY_MAPPING = ['[localizedNames]' => '[names]', '[name]' => '[names]', '[associatedShopIds]' => '[shopIds]'];
    public const CREATE_COMMAND_MAPPING = ['[names]' => '[localizedNames]', '[shopIds]' => '[associatedShopIds]'];
    public const UPDATE_COMMAND_MAPPING = ['[names]' => '[localizedNames]', '[shopIds]' => '[associatedShopIds]'];
}