<?php
namespace SonnyBlaine\Integrator;

/**
 * Class ScriptHandler
 * @package SonnyBlaine\Integrator
 */
class ScriptHandler
{
    public static function registerProviders()
    {
        $filename = APP_PATH . '/composer.local.json';

        $data = json_decode(file_get_contents($filename), true);

        $providers = [];

        foreach ($data['require'] as $bridge => $version) {
            $providers = array_merge($providers, require __DIR__ . '/../vendor/' . $bridge . '/config.php');
        }

        $template = <<<'PHP'
$app->register(new \%s);
PHP;

        $providers = array_map(function ($provider) use ($template) {
            return sprintf($template, $provider);
        }, $providers);

        $providersContent = <<<PHP
<?php
%s
PHP;

        file_put_contents(APP_PATH . '/providers_bridges.php', sprintf($providersContent, implode(PHP_EOL, $providers)));
    }
}