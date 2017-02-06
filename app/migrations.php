<?php

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Symfony\Component\Console\Output\ConsoleOutput;
use Doctrine\DBAL\Migrations\OutputWriter;

$commands = [
    'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
    'Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand',
    'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
    'Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand',
    'Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand',
];

$app['migrations.output_writer'] = new OutputWriter(
    function ($message) {
        $output = new ConsoleOutput();
        $output->writeln($message);
    }
);

if (true === $console->getHelperSet()->has('em')) {
    $commands[] = 'Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand';
}

$configuration = new Configuration($app['db'], $app['migrations.output_writer']);
$configuration->setMigrationsDirectory(__DIR__ . '/DoctrineMigrations');
$configuration->setName('Integrator');
$configuration->setMigrationsNamespace('SonnyBlaine\\Integrator');
$configuration->setMigrationsTableName('migrations_integrator');
$configuration->registerMigrationsFromDirectory(__DIR__ . '/../src');

foreach ($commands as $name) {
    /** @var AbstractCommand $command */
    $command = new $name();
    $command->setMigrationConfiguration($configuration);
    $console->add($command);
}