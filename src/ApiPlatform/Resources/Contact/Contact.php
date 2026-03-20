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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Contact;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Default_Language;
use Presta_Shop\Presta_Shop\Core\Constraint_Validator\Constraints\Typed_Regex;
use Presta_Shop\Presta_Shop\Core\Domain\Contact\Command\Add_Contact_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Contact\Command\Edit_Contact_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Contact\Exception\Contact_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Contact\Exception\Contact_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Contact\Query\Get_Contact_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Presta_Shop_Bundle\Api_Platform\Metadata\Localized_Value;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/contacts/{contactId}', CQRSQuery: Get_Contact_For_Editing::class, scopes: ['contact_read'], CQRSQueryMapping: self::QUERY_MAPPING), new Cqrs_Create(uriTemplate: '/contacts', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Contact_Command::class, CQRSQuery: Get_Contact_For_Editing::class, scopes: ['contact_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::CREATE_COMMAND_MAPPING), new Cqrs_Partial_Update(uriTemplate: '/contacts/{contactId}', validationContext: ['groups' => ['Default', 'Update']], CQRSCommand: Edit_Contact_Command::class, CQRSQuery: Get_Contact_For_Editing::class, scopes: ['contact_write'], CQRSQueryMapping: self::QUERY_MAPPING, CQRSCommandMapping: self::UPDATE_COMMAND_MAPPING)], exceptionToStatus: [Contact_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY, Contact_Not_Found_Exception::class => Response::HTTP_NOT_FOUND])]
class Contact
{
    #[Api_Property(identifier: true)]
    public int $contact_id;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'names')]
    #[Default_Language(groups: ['Update'], fieldName: 'names', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $names;
    #[Assert\Email(mode: Assert\Email::VALIDATION_MODE_STRICT)]
    public string $email;
    #[Localized_Value]
    #[Default_Language(groups: ['Create'], fieldName: 'descriptions', allowNull: true)]
    #[Default_Language(groups: ['Update'], fieldName: 'descriptions', allowNull: true)]
    #[Assert\All(constraints: [new Typed_Regex(['type' => Typed_Regex::TYPE_CATALOG_NAME])])]
    public array $descriptions;
    public bool $messages_saving_enabled;
    #[Api_Property(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 3]])]
    #[Assert\Not_Blank(allowNull: true)]
    public array $shop_ids;
    public const QUERY_MAPPING = ['[localisedTitles]' => '[names]', '[localisedDescription]' => '[descriptions]', '[messagesSavingEnabled]' => '[isMessagesSavingEnabled]', '[shopAssociation]' => '[shopIds]'];
    public const CREATE_COMMAND_MAPPING = ['[names]' => '[localisedTitles]', '[descriptions]' => '[localisedDescription]', '[messagesSavingEnabled]' => '[isMessageSavingEnabled]'];
    public const UPDATE_COMMAND_MAPPING = ['[names]' => '[localisedTitles]', '[descriptions]' => '[localisedDescription]', '[messagesSavingEnabled]' => '[isMessagesSavingEnabled]'];
}