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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Supplier;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Command\Add_Supplier_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Command\Delete_Supplier_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Command\Delete_Supplier_Logo_Image_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Command\Edit_Supplier_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Command\Toggle_Supplier_Status_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Exception\Supplier_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Supplier\Query\Get_Supplier_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/suppliers', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Supplier_Command::class, scopes: ['supplier_write'], CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Delete(uriTemplate: '/suppliers/{supplierId}', requirements: ['supplierId' => '\d+'], output: false, CQRSCommand: Delete_Supplier_Command::class, scopes: ['supplier_write']), new Cqrs_Delete(uriTemplate: '/suppliers/{supplierId}/logo', requirements: ['supplierId' => '\d+'], output: false, CQRSCommand: Delete_Supplier_Logo_Image_Command::class, scopes: ['supplier_write']), new Cqrs_Get(uriTemplate: '/suppliers/{supplierId}', requirements: ['supplierId' => '\d+'], CQRSQuery: Get_Supplier_For_Editing::class, scopes: ['supplier_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/suppliers/{supplierId}', requirements: ['supplierId' => '\d+'], read: false, CQRSCommand: Edit_Supplier_Command::class, CQRSCommandMapping: self::COMMAND_MAPPING, CQRSQuery: Get_Supplier_For_Editing::class, CQRSQueryMapping: self::QUERY_MAPPING, scopes: ['supplier_write']), new Cqrs_Update(uriTemplate: '/suppliers/{supplierId}/toggle-status', requirements: ['supplierId' => '\d+'], output: false, allowEmptyBody: true, CQRSCommand: Toggle_Supplier_Status_Command::class, scopes: ['supplier_write'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Supplier_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Supplier
{
    #[Api_Property(identifier: true)]
    public int $supplier_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: 64)]
    public string $name;
    public string $address;
    public ?string $address2 = null;
    public ?string $post_code = null;
    public string $city;
    public ?int $state_id = null;
    public int $country_id;
    public ?string $phone = null;
    public ?string $mobile_phone = null;
    public ?string $dni = null;
    #[Assert\Not_Null(groups: ['Create'])]
    public bool $enabled;
    #[Localized_Value]
    public array $descriptions;
    #[Localized_Value]
    public array $meta_titles;
    #[Localized_Value]
    public array $meta_descriptions;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public ?array $logo_image = null;
    public const COMMAND_MAPPING = ['[descriptions]' => '[localizedDescriptions]', '[metaTitles]' => '[localizedMetaTitles]', '[metaDescriptions]' => '[localizedMetaDescriptions]', '[shopIds]' => '[shopAssociation]'];
    public const QUERY_MAPPING = ['[localizedDescriptions]' => '[descriptions]', '[localizedMetaTitles]' => '[metaTitles]', '[localizedMetaDescriptions]' => '[metaDescriptions]', '[associatedShops]' => '[shopIds]'];
}