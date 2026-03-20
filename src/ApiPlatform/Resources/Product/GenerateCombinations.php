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
use Presta_Shop\Presta_Shop\Core\Domain\Product\Combination\Command\Generate_Product_Combinations_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Product\Exception\Product_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Create(uriTemplate: '/products/{productId}/generate-combinations', CQRSCommand: Generate_Product_Combinations_Command::class, scopes: ['product_write'], ApiResourceMapping: [
    // Used to denormalize the command result
    '[@index][combinationId]' => '[newCombinationIds][@index]',
])], exceptionToStatus: [Product_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Generate_Combinations
{
    public int $product_id;
    #[Api_Property(openapiContext: ['type' => 'array', 'description' => 'List of new generated combination IDs', 'items' => ['type' => 'integer', 'description' => 'Combination ID']])]
    public array $new_combination_ids = [];
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'object', 'description' => 'List of attributes grouped by their attribute group', 'properties' => ['attributeGroupId' => ['type' => 'number', 'description' => 'Attribute group ID'], 'attributeIds' => ['type' => 'array', 'items' => ['type' => 'integer', 'description' => 'Attribute ID']]]]])]
    public array $grouped_attributes;
}