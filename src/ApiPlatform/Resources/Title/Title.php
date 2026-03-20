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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Title;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Title\Command\Add_Title_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Title\Command\Delete_Title_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Title\Command\Edit_Title_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Title\Exception\Title_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Title\Exception\Title_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Title\Query\Get_Title_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\File\File;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Serializer\Normalizer\Object_Normalizer;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Create(
    uriTemplate: '/titles',
    CQRSCommand: Add_Title_Command::class,
    CQRSCommandMapping: self::COMMAND_MAPPING,
    scopes: ['title_write'],
    inputFormats: ['multipart' => ['multipart/form-data']],
    // Form data value are all string so we disable type enforcement
    denormalizationContext: [Object_Normalizer::DISABLE_TYPE_ENFORCEMENT => true]
), new Cqrs_Delete(uriTemplate: '/titles/{titleId}', requirements: ['titleId' => '\d+'], output: false, CQRSCommand: Delete_Title_Command::class, scopes: ['title_write']), new Cqrs_Get(uriTemplate: '/titles/{titleId}', requirements: ['titleId' => '\d+'], CQRSQuery: Get_Title_For_Editing::class, CQRSQueryMapping: self::QUERY_MAPPING, scopes: ['title_read']), new Cqrs_Partial_Update(uriTemplate: '/titles/{titleId}', requirements: ['titleId' => '\d+'], read: false, CQRSCommand: Edit_Title_Command::class, CQRSCommandMapping: self::COMMAND_MAPPING, CQRSQuery: Get_Title_For_Editing::class, CQRSQueryMapping: self::QUERY_MAPPING, scopes: ['title_write'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Title_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Title_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY])]
class Title
{
    #[Api_Property(identifier: true)]
    public int $title_id;
    #[Localized_Value]
    #[Assert\Not_Blank(groups: ['Create'])]
    public array $names;
    public int $gender;
    public ?File $img_file = null;
    public ?int $width = null;
    public ?int $height = null;
    public const QUERY_MAPPING = ['[localizedNames]' => '[names]'];
    public const COMMAND_MAPPING = ['[names]' => '[localizedNames]', '[width]' => '[imgWidth]', '[height]' => '[imgHeight]'];
    public function set_gender(string|int $gender): self
    {
        $this->gender = (int) $gender;
        return $this;
    }
}