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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Feature;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Default_Language;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Typed_Regex;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Command\Add_Feature_Value_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Command\Delete_Feature_Value_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Command\Edit_Feature_Value_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Exception\Feature_Value_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Exception\Feature_Value_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Query\Get_Feature_Value_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/features/values/{featureValueId}', CQRSQuery: Get_Feature_Value_For_Editing::class, scopes: ['feature_value_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/features/values', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Feature_Value_Command::class, CQRSQuery: Get_Feature_Value_For_Editing::class, scopes: ['feature_value_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/features/values/{featureValueId}', validationContext: ['groups' => ['Default', 'Update']], CQRSCommand: Edit_Feature_Value_Command::class, CQRSQuery: Get_Feature_Value_For_Editing::class, scopes: ['feature_value_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/features/values/{featureValueId}', CQRSCommand: Delete_Feature_Value_Command::class, scopes: ['feature_value_write'])], exceptionToStatus: [Feature_Value_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Feature_Value_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Feature_Value
{
    #[Api_Property(identifier: true)]
    public int $feature_value_id;
    #[Api_Property(openapiContext: ['type' => 'integer', 'example' => 1])]
    public int $feature_id;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'values')]
    #[Default_Language(groups: ['Update'], fieldName: 'values', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $values;
    public int $position;
    public const QUERY_MAPPING = ['[value]' => '[values]', '[localizedValues]' => '[values]'];
    public const COMMAND_MAPPING = ['[values]' => '[localizedValues]'];
}