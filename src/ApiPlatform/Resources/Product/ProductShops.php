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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Product;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Exception\Product_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Query\Get_Product_For_Editing;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Shop\Command\Set_Product_Shops_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Shop\Exception\Shop_Association_Not_Found;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Partial_Update(uriTemplate: '/products/{productId}/shops', CQRSCommand: Set_Product_Shops_Command::class, CQRSQuery: Get_Product_For_Editing::class, scopes: ['product_write'], CQRSQueryMapping: Product::QUERY_MAPPING, CQRSCommandMapping: ['[associatedShopIds]' => '[shopIds]'])], exceptionToStatus: [Product_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Shop_Association_Not_Found::class => Response::HTTP_NOT_FOUND])]
class Product_Shops extends Product
{
    public int $source_shop_id;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer']])]
    public array $associated_shop_ids;
}