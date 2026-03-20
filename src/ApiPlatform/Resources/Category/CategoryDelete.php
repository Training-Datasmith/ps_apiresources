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
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Delete_Category_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Exception\Category_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Exception\Category_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Symfony\Component\Http_Foundation\Response;
#[Api_Resource(operations: [new Cqrs_Delete(uriTemplate: '/categories/{categoryId}/{mode}', CQRSCommand: Delete_Category_Command::class, scopes: ['category_write'], openapiContext: ['summary' => 'Delete a category using a specific mode', 'parameters' => [['name' => 'categoryId', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer'], 'description' => 'Category ID to delete'], ['name' => 'mode', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string', 'enum' => ['associate_and_disable', 'associate_only', 'remove_associated']], 'description' => 'Delete mode: "associate_and_disable" associate products with parent category and disable them, "associate_only" associate products with parent and do not change their status, "remove_associated" remove products that are associated only with category that is being deleted']]], CQRSCommandMapping: ['[deleteMode]' => '[mode]'])], exceptionToStatus: [Category_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Category_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Category_Delete
{
    #[Api_Property(identifier: true)]
    public int $category_id;
    public string $delete_mode;
}