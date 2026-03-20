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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Showcase_Card;

use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Showcase_Card\Command\Close_Showcase_Card_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Showcase_Card\Query\Get_Showcase_Card_Is_Closed;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/showcase-cards/{showcaseCardName}/{employeeId}', requirements: ['showcaseCardName' => '[a-z_-]+', 'employeeId' => '\d+'], CQRSQuery: Get_Showcase_Card_Is_Closed::class, CQRSQueryMapping: ['[_queryResult]' => '[closed]'], scopes: ['showcase_card_read']), new Cqrs_Update(uriTemplate: '/showcase-cards/{showcaseCardName}/{employeeId}/close', requirements: ['showcaseCardName' => '[a-z_-]+', 'employeeId' => '\d+'], allowEmptyBody: true, CQRSCommand: Close_Showcase_Card_Command::class, CQRSQuery: Get_Showcase_Card_Is_Closed::class, CQRSQueryMapping: ['[_queryResult]' => '[closed]'], scopes: ['showcase_card_write'])])]
class Showcase_Card
{
    public string $showcase_card_name;
    public int $employee_id;
    public bool $closed;
}