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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Product;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Image\Command\Delete_Product_Image_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Image\Command\Update_Product_Image_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Image\Exception\Product_Image_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Image\Query\Get_Product_Image;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Serializer\Normalizer\Object_Normalizer;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/products/images/{imageId}', CQRSQuery: Get_Product_Image::class, scopes: ['product_read'], CQRSQueryMapping: Product_Image::QUERY_MAPPING), new Cqrs_Update(
    // We have to force POST request, because we cannot use PUT with files AND data
    method: Cqrs_Update::METHOD_POST,
    uriTemplate: '/products/images/{imageId}',
    inputFormats: ['multipart' => ['multipart/form-data']],
    status: Response::HTTP_OK,
    // Form data value are all string so we disable type enforcement
    denormalizationContext: [Object_Normalizer::DISABLE_TYPE_ENFORCEMENT => true],
    CQRSCommand: Update_Product_Image_Command::class,
    CQRSQuery: Get_Product_Image::class,
    scopes: ['product_write'],
    CQRSQueryMapping: Product_Image::QUERY_MAPPING,
    CQRSCommandMapping: ['[_context][shopConstraint]' => '[shopConstraint]', '[image].pathName' => '[filePath]', '[legends]' => '[localizedLegends]', '[cover]' => '[isCover]']
), new Cqrs_Delete(uriTemplate: '/products/images/{imageId}', CQRSCommand: Delete_Product_Image_Command::class)], exceptionToStatus: [Product_Image_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Product_Image
{
    #[Api_Property(identifier: true)]
    public int $image_id;
    public string $image_url;
    public string $thumbnail_url;
    #[Localized_Value]
    public array $legends;
    public bool $cover;
    public int $position;
    public array $shop_ids;
    public const QUERY_MAPPING = ['[_context][shopConstraint]' => '[shopConstraint]', '[localizedLegends]' => '[legends]'];
}