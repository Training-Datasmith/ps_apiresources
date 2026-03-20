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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Webservice_Key;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Webservice\Command\Add_Webservice_Key_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Webservice\Command\Edit_Webservice_Key_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Webservice\Exception\Webservice_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Webservice\Exception\Webservice_Key_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Webservice\Query\Get_Webservice_Key_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Form\Admin\Type\Formatted_Textarea_Type;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/webservice-keys', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Webservice_Key_Command::class, scopes: ['webservice_key_write'], CQRSCommandMapping: ['[shopIds]' => '[associatedShops]', '[enabled]' => '[status]']), new Cqrs_Get(uriTemplate: '/webservice-keys/{webserviceKeyId}', requirements: ['webserviceKeyId' => '\d+'], CQRSQuery: Get_Webservice_Key_For_Editing::class, scopes: ['webservice_key_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/webservice-keys/{webserviceKeyId}', read: false, CQRSCommand: Edit_Webservice_Key_Command::class, CQRSQuery: Get_Webservice_Key_For_Editing::class, scopes: ['webservice_key_write'], CQRSCommandMapping: ['[shopIds]' => '[shopAssociation]', '[enabled]' => '[status]'], CQRSQueryMapping: self::QUERY_MAPPING)], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Webservice_Key_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Webservice_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY])]
class Webservice_Key
{
    #[Api_Property(identifier: true)]
    public int $webservice_key_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Length(min: 32, max: 32)]
    public string $key;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: Formatted_Textarea_Type::LIMIT_MEDIUMTEXT_UTF8_MB4)]
    public string $description;
    #[Assert\Not_Null(groups: ['Create'])]
    public bool $enabled;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'array']])]
    #[Assert\Collection(fields: ['DELETE' => new Assert\All(constraints: [new Assert\Type(type: 'string')], groups: ['Create']), 'GET' => new Assert\All(constraints: [new Assert\Type(type: 'string')], groups: ['Create']), 'HEAD' => new Assert\All(constraints: [new Assert\Type(type: 'string')], groups: ['Create']), 'PATCH' => new Assert\All(constraints: [new Assert\Type(type: 'string')], groups: ['Create']), 'PUT' => new Assert\All(constraints: [new Assert\Type(type: 'string')], groups: ['Create']), 'POST' => new Assert\All(constraints: [new Assert\Type(type: 'string')], groups: ['Create'])], allowMissingFields: true, groups: ['Create'])]
    public array $permissions;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public const QUERY_MAPPING = ['[status]' => '[enabled]', '[resourcePermissions]' => '[permissions]', '[associatedShops]' => '[shopIds]'];
    public function set_enabled(string|bool $enabled): self
    {
        $this->enabled = (bool) $enabled;
        return $this;
    }
}