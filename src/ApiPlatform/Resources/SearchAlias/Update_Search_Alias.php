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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Search_Alias;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Alias\Command\Update_Search_Term_Aliases_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Alias\Exception\Alias_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Alias\Exception\Alias_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Alias\Query\Get_Aliases_By_Search_Term_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Update(uriTemplate: '/search-aliases/{searchTerm}', CQRSCommand: Update_Search_Term_Aliases_Command::class, CQRSQuery: Get_Aliases_By_Search_Term_For_Editing::class, scopes: ['search_alias_write'], CQRSCommandMapping: self::UPDATE_COMMAND_MAPPING, output: false, experimentalOperation: true)], exceptionToStatus: [Alias_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Alias_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY])]
class Update_Search_Alias
{
    #[Api_Property(identifier: true)]
    #[Assert\Not_Blank]
    #[Assert\Length(min: 1, max: 255)]
    public string $search_term;
    #[Assert\All(constraints: [new Assert\Collection(fields: [
        'alias' => new Assert\Not_Blank(),
        'enabled' => new Assert\Type(type: 'bool'),
        // Add active because after normalization both active and enabled are present
        'active' => new Assert\Type(type: 'bool'),
    ])])]
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'object', 'properties' => ['alias' => ['type' => 'string'], 'enabled' => ['type' => 'boolean']]]])]
    public array $aliases = [];
    #[Assert\Length(min: 1, max: 255)]
    public ?string $new_search_term = null;
    protected const UPDATE_COMMAND_MAPPING = ['[searchTerm]' => '[oldSearchTerm]', '[aliases][@index][alias]' => '[aliases][@index][alias]', '[aliases][@index][enabled]' => '[aliases][@index][active]'];
}