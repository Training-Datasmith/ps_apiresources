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
namespace Presta_Shop\Module\Api_Resources\Validation;

use Presta_Shop\Presta_Shop\Adapter\Configuration;
final class Iframe_Validation_Groups_Resolver
{
    public function __construct(private readonly Configuration $config)
    {
    }
    public function create(): array
    {
        return ['groups' => array_filter(['Default', 'Create', $this->flag_group()])];
    }
    public function update(): array
    {
        return ['groups' => array_filter(['Default', 'Update', $this->flag_group()])];
    }
    private function flag_group(): string
    {
        $allow_iframe = (bool) ($this->config->get('PS_ALLOW_HTML_IFRAME') ?: false);
        return $allow_iframe ? 'AllowIframe' : 'NoIframe';
    }
}