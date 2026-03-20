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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Api_Client;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Api_Client_Settings;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Command\Add_Api_Client_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Command\Delete_Api_Client_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Command\Edit_Api_Client_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Exception\Api_Client_Constraint_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Exception\Api_Client_Not_Found_Exception;
use Presta_Shop\Presta_Shop\Core\Domain\Api_Client\Query\Get_Api_Client_For_Editing;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Create;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Delete;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Get;
use Presta_Shop_Bundle\Api_Platform\Metadata\Cqrs_Partial_Update;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
#[Api_Resource(operations: [new Cqrs_Get(uriTemplate: '/api-clients/{apiClientId}', requirements: ['apiClientId' => '\d+'], CQRSQuery: Get_Api_Client_For_Editing::class, scopes: ['api_client_read']), new Cqrs_Delete(uriTemplate: '/api-clients/{apiClientId}', requirements: ['apiClientId' => '\d+'], output: false, CQRSCommand: Delete_Api_Client_Command::class, scopes: ['api_client_write']), new Cqrs_Create(uriTemplate: '/api-clients', validationContext: ['groups' => ['Default', 'Create']], CQRSCommand: Add_Api_Client_Command::class, scopes: ['api_client_write']), new Cqrs_Partial_Update(uriTemplate: '/api-clients/{apiClientId}', read: false, CQRSCommand: Edit_Api_Client_Command::class, CQRSQuery: Get_Api_Client_For_Editing::class, scopes: ['api_client_write'])], normalizationContext: ['skip_null_values' => false], exceptionToStatus: [Api_Client_Not_Found_Exception::class => Response::HTTP_NOT_FOUND, Api_Client_Constraint_Exception::class => Response::HTTP_UNPROCESSABLE_ENTITY])]
class Api_Client
{
    #[Api_Property(identifier: true)]
    public int $api_client_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: Api_Client_Settings::MAX_CLIENT_ID_LENGTH)]
    public string $client_id;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Length(min: 1, max: Api_Client_Settings::MAX_CLIENT_NAME_LENGTH)]
    public string $client_name;
    #[Assert\Length(max: Api_Client_Settings::MAX_DESCRIPTION_LENGTH)]
    public string $description;
    public ?string $external_issuer = null;
    #[Assert\Not_Null(groups: ['Create'])]
    public bool $enabled;
    #[Assert\Not_Blank(groups: ['Create'])]
    #[Assert\Positive]
    public int $lifetime;
    public array $scopes;
    /**
     * Only used for the return of created API Client, it is the only endpoint where the secret is returned.
     */
    public string $secret;
}