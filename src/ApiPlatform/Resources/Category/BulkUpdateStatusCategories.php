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
use Presta_Shop\Presta_Shop\Core\Domain\Category\Command\Bulk_Update_Categories_Status_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Category\Exception\Category_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Update(uriTemplate: '/categories/bulk-update-status', output: false, CQRSCommand: Bulk_Update_Categories_Status_Command::class, CQRSCommandMapping: ['[enabled]' => '[newStatus]'], scopes: ['category_write'])], exceptionToStatus: [Category_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Bulk_Update_Status_Categories
{
    /**
     * @var int[]
     */
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank]
    public array $category_ids;
    public bool $enabled;
}