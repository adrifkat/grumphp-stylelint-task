<?php

namespace Adrifkat\GrumPHPStylelint;

use GrumPHP\Collection\ProcessArgumentsCollection;
use GrumPHP\Fixer\Provider\FixableProcessResultProvider;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\AbstractExternalTask;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

class StylelintTask extends AbstractExternalTask
{
    public static function getConfigurableOptions(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            // Task config options
            'triggered_by' => ['less', 'sass', 'scss', 'css'],
            'whitelist_patterns' => null,

            // Stylelint native config options
            'config' => null,
            'max-warnings' => null,
            'quiet' => false,
        ]);

        // Task config options
        $resolver->addAllowedTypes('whitelist_patterns', ['null', 'array']);
        $resolver->addAllowedTypes('triggered_by', ['array']);

        // Stylelint native config options
        $resolver->addAllowedTypes('config', ['null', 'string']);
        $resolver->addAllowedTypes('max-warnings', ['null', 'integer']);
        $resolver->addAllowedTypes('quiet', ['bool']);

        return $resolver;
    }

    public function canRunInContext(ContextInterface $context): bool
    {
        return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
    }

    public function run(ContextInterface $context): TaskResultInterface
    {
        $config = $this->getConfig()->getOptions();

        $files = $context
            ->getFiles()
            ->paths($config['whitelist_patterns'] ?? [])
            ->extensions($config['triggered_by']);

        if (0 === \count($files)) {
            return TaskResult::createSkipped($this, $context);
        }

        $arguments = $this->processBuilder->createArgumentsForCommand('npx');

        $arguments->add('stylelint');

        $arguments->addOptionalArgument('--config=%s', $config['config']);
        $arguments->addOptionalArgument('--quiet', $config['quiet']);
        $arguments->addOptionalIntegerArgument('--max-warnings=%d', $config['max-warnings']);
        $arguments->addFiles($files);

        $process = $this->processBuilder->buildProcess($arguments);
        $process->run();

        if (!$process->isSuccessful()) {
            return FixableProcessResultProvider::provide(
                TaskResult::createFailed($this, $context, $this->formatter->format($process)),
                function () use ($arguments): Process {
                    $arguments->add('--fix');
                    return $this->processBuilder->buildProcess($arguments);
                }
            );
        }

        return TaskResult::createPassed($this, $context);
    }
}
