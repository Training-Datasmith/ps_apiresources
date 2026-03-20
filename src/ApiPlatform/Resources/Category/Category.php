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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Category;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Module\Api_Resources\Validation\Iframe_Validation_Groups_Resolver;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Default_Language;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Typed_Regex;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Add_Category_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Delete_Category_Cover_Image_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Delete_Category_Thumbnail_Image_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Edit_Category_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Set_Category_Is_Enabled_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Exception\Category_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Exception\Category_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Query\Get_Category_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/categories/{categoryId}', CQRSQuery: Get_Category_For_Editing::class, scopes: ['category_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/categories', validationContext: [Iframe_Validation_Groups_Resolver::class, 'create'], CQRSCommand: Add_Category_Command::class, CQRSQuery: Get_Category_For_Editing::class, scopes: ['category_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/categories/{categoryId}', validationContext: [Iframe_Validation_Groups_Resolver::class, 'update'], CQRSCommand: Edit_Category_Command::class, CQRSQuery: Get_Category_For_Editing::class, scopes: ['category_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/categories/{categoryId}/status', CQRSCommand: Set_Category_Is_Enabled_Command::class, CQRSQuery: Get_Category_For_Editing::class, scopes: ['category_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: ['[enabled]' => '[isEnabled]']), new Cqrs_Delete(uriTemplate: '/categories/{categoryId}/cover', CQRSCommand: Delete_Category_Cover_Image_Command::class, scopes: ['category_write']), new Cqrs_Delete(uriTemplate: '/categories/{categoryId}/thumbnail', CQRSCommand: Delete_Category_Thumbnail_Image_Command::class, scopes: ['category_write'])], exceptionToStatus: [Category_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Category_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Category
{
    #[Api_Property(identifier: true)]
    public int $category_id;
    public bool $enabled;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'names')]
    #[Default_Language(groups: ['Update'], fieldName: 'names', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $names;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'descriptions')]
    #[Default_Language(groups: ['Update'], fieldName: 'descriptions', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::CLEAN_HTML_NO_IFRAME, 'groups' => ['NoIframe']]), new Typed_Regex(['type' => Typed_Regex::CLEAN_HTML_ALLOW_IFRAME, 'groups' => ['AllowIframe']])])]
    public array $descriptions;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'additionalDescriptions')]
    #[Default_Language(groups: ['Update'], fieldName: 'additionalDescriptions', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::CLEAN_HTML_NO_IFRAME, 'groups' => ['NoIframe']]), new Typed_Regex(['type' => Typed_Regex::CLEAN_HTML_NO_IFRAME, 'groups' => ['AllowIframe']])])]
    public array $additional_descriptions;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'linkRewrites')]
    #[Default_Language(groups: ['Update'], fieldName: 'linkRewrites', allowNull: false)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_URL])])]
    public array $link_rewrites;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'metaTitles')]
    #[Default_Language(groups: ['Update'], fieldName: 'metaTitles', allowNull: true)]
    public array $meta_titles;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'metaDescriptions')]
    #[Default_Language(groups: ['Update'], fieldName: 'metaDescriptions', allowNull: true)]
    public array $meta_descriptions;
    public int $position;
    public int $parent_id;
    public string $redirect_type;
    public ?int $redirect_target = null;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public const QUERY_MAPPING = ['[id]' => '[categoryId]', '[active]' => '[enabled]', '[name]' => '[names]', '[description]' => '[descriptions]', '[additionalDescription]' => '[additionalDescriptions]', '[associatedShopIds]' => '[shopIds]', '[metaTitle]' => '[metaTitles]', '[metaDescription]' => '[metaDescriptions]', '[linkRewrite]' => '[linkRewrites]'];
    public const COMMAND_MAPPING = ['[names]' => '[localizedNames]', '[enabled]' => '[isEnabled]', '[descriptions]' => '[localizedDescriptions]', '[additionalDescriptions]' => '[localizedAdditionalDescriptions]', '[shopIds]' => '[associatedShopIds]', '[metaTitles]' => '[localizedMetaTitles]', '[metaDescriptions]' => '[localizedMetaDescriptions]', '[linkRewrites]' => '[localizedLinkRewrites]'];
}