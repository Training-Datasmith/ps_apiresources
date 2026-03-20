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
namespace Presta_Shop\Module\Api_Resources\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Array_Input;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Input\Input_Option;
use Symfony\Component\Console\Output\Buffered_Output;
use Symfony\Component\Console\Output\Output_Interface;
use Symfony\Component\Console\Style\Symfony_Style;
use Symfony\Component\Finder\Finder;
class Generate_Api_Tracking_Table_Command extends Command
{
    private array $cqrs_endpoints = [];
    private array $cqrs_lookup = [];
    protected function configure(): void
    {
        $this->set_name('prestashop:api:generate-tracking-table')->set_aliases(['prestashop:generate-api-tracking'])->set_description('Generate API tracking table for Admin API endpoints')->add_option('output', 'o', Input_Option::VALUE_OPTIONAL, 'Output file', 'api-endpoints-tracking.md')->add_option('github-token', 'g', Input_Option::VALUE_OPTIONAL, 'GitHub API token for PR status detection')->add_option('skip-github', null, Input_Option::VALUE_NONE, 'Skip GitHub PR analysis for faster execution');
    }
    protected function execute(Input_Interface $input, Output_Interface $output): int
    {
        $io = new Symfony_Style($input, $output);
        $output_file = $input->get_option('output');
        try {
            $io->title('🔍 PrestaShop API Tracking Table Generator');
            // Step 1: Get all CQRS endpoints from the core
            $io->section('🔍 Scanning CQRS endpoints from the core...');
            $cqrs_endpoints = $this->get_cqrs_endpoints_from_core_command();
            $io->info(sprintf('Found %d CQRS endpoints', count($cqrs_endpoints)));
            // Step 2: Scan API Platform resources directly
            $io->section('📄 Scanning API Platform resources...');
            $api_endpoints = $this->scan_api_platform_resources();
            $io->info(sprintf('Found %d API endpoints with CQRS mappings', count($api_endpoints)));
            // Step 3: Analyze GitHub PRs for status detection (if enabled)
            $pr_status_map = [];
            if (!$input->get_option('skip-github')) {
                $io->section('🐙 Analyzing GitHub PRs for status detection...');
                $github_token = $input->get_option('github-token');
                $pr_status_map = $this->analyze_git_hub_pull_requests($github_token, $io);
                $io->info(sprintf('Found status information for %d endpoints from GitHub PRs', count($pr_status_map)));
            }
            // Step 4: Compare and match
            $io->section('🔍 Comparing CQRS endpoints with API implementations...');
            $matched_endpoints = $this->compare_cqrs_with_api($cqrs_endpoints, $api_endpoints, $pr_status_map);
            $api_count = count(array_filter($matched_endpoints, fn(array $e) => $e['has_api']));
            $io->info(sprintf('Matched %d CQRS endpoints with API implementations', $api_count));
            // Step 4: Generate markdown table
            $io->section('📝 Generating markdown table...');
            $domain_groups = $this->process_domain_groups($matched_endpoints);
            $this->generate_markdown_table($domain_groups, $output_file);
            $total_endpoints = count($matched_endpoints);
            $implemented_count = $this->count_implemented_endpoints($domain_groups);
            $in_progress_count = $this->count_in_progress_endpoints($domain_groups);
            $percentage = $total_endpoints > 0 ? round($implemented_count / $total_endpoints * 100, 1) : 0;
            $io->success(['API tracking table generated successfully!', sprintf('📁 Saved to: %s', $output_file), sprintf('📊 Summary: %d implemented, %d in progress, %d missing (%s%% complete)', $implemented_count, $in_progress_count, $total_endpoints - $implemented_count - $in_progress_count, $percentage)]);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Failed to generate API tracking table: ' . $e->get_message());
            return Command::FAILURE;
        }
    }
    private function get_cqrs_endpoints_from_core_command(): array
    {
        $application = $this->get_application();
        $command = $application->find('prestashop:list:commands-and-queries');
        $input = new Array_Input([]);
        $output = new Buffered_Output();
        $command->run($input, $output);
        return $this->parse_cqrs_command_output($output->fetch());
    }
    private function parse_cqrs_command_output(string $output): array
    {
        $endpoints = [];
        $lines = explode("\n", trim($output));
        $current_endpoint = null;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            if (preg_match('/^\d+\.$/', $line)) {
                if ($current_endpoint) {
                    $endpoints[] = $current_endpoint;
                }
                $current_endpoint = ['class' => '', 'type' => '', 'domain' => '', 'action' => ''];
                continue;
            }
            if (str_starts_with($line, 'Class: ')) {
                $class = substr($line, 7);
                $current_endpoint['class'] = $class;
                if (preg_match('/PrestaShop\\\\PrestaShop\\\\Core\\\\Domain\\\\([^\\\\]+)\\\\(?:.*\\\\)?(Command|Query)\\\\(.+)/', $class, $matches)) {
                    $current_endpoint['domain'] = $matches[1];
                    $current_endpoint['action'] = $matches[3];
                }
                continue;
            }
            if (str_starts_with($line, 'Type: ')) {
                $current_endpoint['type'] = substr($line, 6);
                continue;
            }
        }
        if ($current_endpoint) {
            $endpoints[] = $current_endpoint;
        }
        return $endpoints;
    }
    private function scan_api_platform_resources(): array
    {
        $endpoints = [];
        $resources_path = _PS_MODULE_DIR_ . 'ps_apiresources/src/ApiPlatform/Resources';
        if (!is_dir($resources_path)) {
            return $endpoints;
        }
        $finder = new Finder();
        $finder->files()->name('*.php')->in($resources_path);
        foreach ($finder as $file) {
            $content = file_get_contents($file->get_real_path());
            $mappings = $this->extract_cqrs_mappings_from_resource_file($content);
            $endpoints = array_merge($endpoints, $mappings);
        }
        return $endpoints;
    }
    private function extract_cqrs_mappings_from_resource_file(string $content): array
    {
        $mappings = [];
        // Map CQRS operation types to HTTP methods
        $operation_patterns = ['CQRSCreate' => 'POST', 'CQRSUpdate' => 'PUT', 'CQRSPartialUpdate' => 'PATCH', 'CQRSDelete' => 'DELETE', 'CQRSGet' => 'GET'];
        foreach ($operation_patterns as $operation_type => $http_method) {
            preg_match_all('/new\s+' . $operation_type . '\s*\((.*?)\)/s', $content, $matches);
            foreach ($matches[1] as $operation_content) {
                $mapping = $this->parse_cqrs_operation_content($operation_content, $http_method);
                if ($mapping) {
                    $mappings[$mapping['cqrs_class']] = $mapping;
                }
            }
        }
        return $mappings;
    }
    private function parse_cqrs_operation_content(string $operation_content, string $http_method): ?array
    {
        // Extract uriTemplate
        if (!preg_match('/uriTemplate:\s*[\'"]([^\'"]+)[\'"]/', $operation_content, $uri_match)) {
            return null;
        }
        $uri_template = $uri_match[1];
        // Extract CQRSCommand or CQRSQuery class
        $cqrs_class = null;
        if (preg_match('/CQRSCommand:\s*([^:,\s]+)::class/', $operation_content, $command_match)) {
            $cqrs_class = trim($command_match[1]);
        } elseif (preg_match('/CQRSQuery:\s*([^:,\s]+)::class/', $operation_content, $query_match)) {
            $cqrs_class = trim($query_match[1]);
        }
        if (!$cqrs_class) {
            return null;
        }
        // Convert short class name to full class name
        $full_cqrs_class = $this->find_full_cqrs_class_name($cqrs_class);
        if (!$full_cqrs_class) {
            return null;
        }
        return ['uri' => $uri_template, 'method' => $http_method, 'cqrs_class' => $full_cqrs_class, 'operation' => strtolower($http_method) . '_' . str_replace(['/', '{', '}'], ['_', '', ''], $uri_template), 'summary' => ''];
    }
    private function find_full_cqrs_class_name(string $short_class_name): ?string
    {
        if (empty($this->cqrs_lookup)) {
            foreach ($this->get_all_cqrs_endpoints() as $endpoint) {
                $short_name = basename(str_replace('\\', '/', $endpoint['class']));
                $this->cqrs_lookup[$short_name] = $endpoint['class'];
            }
        }
        return $this->cqrs_lookup[$short_class_name] ?? null;
    }
    private function get_all_cqrs_endpoints(): array
    {
        if (empty($this->cqrs_endpoints)) {
            $this->cqrs_endpoints = $this->get_cqrs_endpoints_from_core_command();
        }
        return $this->cqrs_endpoints;
    }
    private function compare_cqrs_with_api(array $cqrs_endpoints, array $api_endpoints, array $pr_status_map = []): array
    {
        $matched = [];
        foreach ($cqrs_endpoints as $cqrs) {
            $has_api = isset($api_endpoints[$cqrs['class']]);
            $api_info = $has_api ? $api_endpoints[$cqrs['class']] : null;
            // Determine status from PR analysis
            $pr_status = $pr_status_map[$cqrs['class']] ?? null;
            $matched[] = ['class' => $cqrs['class'], 'type' => $cqrs['type'], 'domain' => $cqrs['domain'], 'action' => $cqrs['action'], 'has_api' => $has_api, 'api' => $has_api ? $api_info['method'] . ' ' . $api_info['uri'] : '', 'api_info' => $api_info, 'pr_status' => $pr_status];
        }
        return $matched;
    }
    private function analyze_git_hub_pull_requests(?string $github_token, Symfony_Style $io): array
    {
        $status_map = [];
        $repo_owner = 'PrestaShop';
        $repo_name = 'ps_apiresources';
        try {
            // Fetch open PRs only
            $open_p_rs = $this->fetch_git_hub_p_rs($repo_owner, $repo_name, 'open', $github_token);
            $io->text(sprintf('  Found %d open PRs', count($open_p_rs)));
            // Analyze open PRs for "In Progress" status
            foreach ($open_p_rs as $pr) {
                $changed_endpoints = $this->analyze_pr_changes($pr, $github_token);
                foreach ($changed_endpoints as $endpoint) {
                    $status_map[$endpoint] = ['status' => '🚧 In Progress', 'pr_url' => $pr['html_url'], 'pr_title' => $pr['title'], 'assignee' => $pr['assignee']['login'] ?? $pr['user']['login'] ?? 'Unknown'];
                }
            }
        } catch (\Exception $e) {
            $io->warning('GitHub API analysis failed: ' . $e->get_message());
            $io->text('Continuing without PR status detection...');
        }
        return $status_map;
    }
    private function process_domain_groups(array $all_endpoints): array
    {
        $domain_groups = [];
        foreach ($all_endpoints as $endpoint) {
            $domain = $endpoint['domain'] ?: 'Unknown';
            if (!isset($domain_groups[$domain])) {
                $domain_groups[$domain] = [];
            }
            $has_api = $endpoint['has_api'];
            $pr_status = $endpoint['pr_status'] ?? null;
            // Determine final status based on API implementation and PR status
            $final_status = '❌ Missing';
            $assignee_info = '';
            if ($has_api) {
                $final_status = '✅ Implemented';
            } elseif ($pr_status) {
                $final_status = $pr_status['status'];
                $assignee_info = $pr_status['assignee'];
            }
            $domain_groups[$domain][] = ['action' => $endpoint['action'] ?: basename(str_replace('\\', '/', $endpoint['class'])), 'type' => $endpoint['type'], 'hasApi' => $has_api, 'api' => $endpoint['api'], 'status' => $final_status, 'assignee' => $assignee_info, 'pr_info' => $pr_status];
        }
        ksort($domain_groups);
        foreach ($domain_groups as &$endpoints) {
            usort($endpoints, function (array $a, array $b): int {
                if ($a['type'] !== $b['type']) {
                    return 'Command' === $a['type'] ? -1 : 1;
                }
                return strcasecmp((string) $a['action'], (string) $b['action']);
            });
        }
        return $domain_groups;
    }
    private function count_implemented_endpoints(array $domain_groups): int
    {
        $count = 0;
        foreach ($domain_groups as $endpoints) {
            foreach ($endpoints as $endpoint) {
                if ($endpoint['hasApi']) {
                    ++$count;
                }
            }
        }
        return $count;
    }
    private function count_in_progress_endpoints(array $domain_groups): int
    {
        $count = 0;
        foreach ($domain_groups as $endpoints) {
            foreach ($endpoints as $endpoint) {
                if (str_contains((string) $endpoint['status'], '🚧 In Progress')) {
                    ++$count;
                }
            }
        }
        return $count;
    }
    private function generate_markdown_table(array $domain_groups, string $output_file): void
    {
        $total_endpoints = array_sum(array_map(count(...), $domain_groups));
        $implemented_count = $this->count_implemented_endpoints($domain_groups);
        $in_progress_count = $this->count_in_progress_endpoints($domain_groups);
        $missing_count = $total_endpoints - $implemented_count - $in_progress_count;
        $percentage = $total_endpoints > 0 ? round($implemented_count / $total_endpoints * 100, 1) : 0;
        $markdown = "# PrestaShop API Endpoints - Tracking\n\n";
        $markdown .= "This table tracks the progress of API endpoint implementations for PrestaShop CQRS commands and queries.\n\n";
        $markdown .= "## 📊 Overall Progress\n\n";
        $markdown .= "- **Total Endpoints**: {$total_endpoints}\n";
        $markdown .= "- **Implemented**: {$implemented_count} ✅\n";
        $markdown .= "- **In Progress**: {$in_progress_count} 🚧\n";
        $markdown .= "- **Missing**: {$missing_count} ❌\n";
        $markdown .= "- **Progress**: {$percentage}%\n\n";
        $markdown .= "---\n\n";
        foreach ($domain_groups as $domain => $endpoints) {
            $domain_implemented = count(array_filter($endpoints, fn(array $e) => $e['hasApi']));
            $domain_total = count($endpoints);
            $domain_percentage = $domain_total > 0 ? round($domain_implemented / $domain_total * 100, 1) : 0;
            $markdown .= "## 🏷️ Domain: {$domain}\n\n";
            $markdown .= "**Progress**: {$domain_implemented}/{$domain_total} ({$domain_percentage}%)\n\n";
            $markdown .= "| Action | Type | Status | API Endpoint | Assignee / PR |\n";
            $markdown .= "|--------|------|--------|--------------|---------------|\n";
            foreach ($endpoints as $endpoint) {
                $action = '`' . $endpoint['action'] . '`';
                $type = $endpoint['type'];
                $status = $endpoint['status'];
                $api = $endpoint['api'];
                // Build assignee/PR info
                $assignee_info = '';
                if (!empty($endpoint['assignee'])) {
                    $assignee_info = $endpoint['assignee'];
                    if ($endpoint['pr_info'] && !empty($endpoint['pr_info']['pr_url'])) {
                        $assignee_info = "[{$endpoint['assignee']}](https://github.com/{$endpoint['assignee']}) / [PR]({$endpoint['pr_info']['pr_url']})";
                    }
                }
                $markdown .= "| {$action} | {$type} | {$status} | {$api} | {$assignee_info} |\n";
            }
            $markdown .= "\n";
        }
        $markdown .= "## 📋 Status Legend\n\n";
        $markdown .= "- ✅ **Implemented**: API endpoint is available and working\n";
        $markdown .= "- 🚧 **In Progress**: Someone is actively working on this endpoint (PR open)\n";
        $markdown .= "- ❌ **Missing**: API endpoint needs to be implemented\n\n";
        $markdown .= '*Last updated: ' . date('Y-m-d H:i:s') . "*\n";
        file_put_contents($output_file, $markdown);
    }
    private function fetch_git_hub_p_rs(string $owner, string $repo, string $state, ?string $token, int $limit = 50): array
    {
        $url = "https://api.github.com/repos/{$owner}/{$repo}/pulls?state={$state}&per_page={$limit}";
        $headers = ['User-Agent: PrestaShop-API-Tracker', 'Accept: application/vnd.github.v3+json'];
        if ($token) {
            $headers[] = "Authorization: token {$token}";
        }
        $context = stream_context_create(['http' => ['header' => implode("\r\n", $headers), 'method' => 'GET']]);
        $response = file_get_contents($url, false, $context);
        if (false === $response) {
            throw new \RuntimeException('Failed to fetch PRs from GitHub API');
        }
        return json_decode($response, true) ?: [];
    }
    private function analyze_pr_changes(array $pr, ?string $token): array
    {
        $endpoints = [];
        try {
            // Fetch PR files
            $url = $pr['url'] . '/files';
            $headers = ['User-Agent: PrestaShop-API-Tracker', 'Accept: application/vnd.github.v3+json'];
            if ($token) {
                $headers[] = "Authorization: token {$token}";
            }
            $context = stream_context_create(['http' => ['header' => implode("\r\n", $headers), 'method' => 'GET']]);
            $response = file_get_contents($url, false, $context);
            if (false === $response) {
                return $endpoints;
            }
            $files = json_decode($response, true);
            foreach ($files as $file) {
                $filename = $file['filename'];
                // Check if it's an API resource file
                if (str_contains((string) $filename, 'src/ApiPlatform/Resources/') && str_ends_with((string) $filename, '.php')) {
                    // Extract CQRS references from the patch
                    $patch = $file['patch'] ?? '';
                    $found_endpoints = $this->extract_cqrs_from_patch($patch);
                    $endpoints = array_merge($endpoints, $found_endpoints);
                }
            }
        } catch (\Exception) {
            // Silently continue if we can't analyze PR changes
        }
        return array_unique($endpoints);
    }
    /**
     * Extract CQRS command/query references from a git patch.
     */
    private function extract_cqrs_from_patch(string $patch): array
    {
        $endpoints = [];
        // Look for CQRS command/query references in added lines
        preg_match_all('/\+.*CQRSCommand:\s*([A-Za-z]+)::class/', $patch, $command_matches);
        preg_match_all('/\+.*CQRSQuery:\s*([A-Za-z]+)::class/', $patch, $query_matches);
        $all_matches = array_merge($command_matches[1], $query_matches[1]);
        foreach ($all_matches as $short_class_name) {
            $full_class_name = $this->find_full_cqrs_class_name($short_class_name);
            if ($full_class_name) {
                $endpoints[] = $full_class_name;
            }
        }
        return $endpoints;
    }
}