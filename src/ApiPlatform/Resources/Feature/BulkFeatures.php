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
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Command\Bulk_Delete_Feature_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Feature\Exception\Feature_Not_Found_Exception;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Delete(uriTemplate: '/features/bulk-delete', CQRSCommand: Bulk_Delete_Feature_Command::class, scopes: ['feature_write'], allowEmptyBody: false)], exceptionToStatus: [Feature_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Bulk_Features
{
    /**
     * @var int[]
     */
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank]
    public array $feature_ids;
}