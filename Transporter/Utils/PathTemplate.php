<?php

namespace Transporter\Utils;

use Transporter\TransporterException;
use Transporter\TransporterSyncOptions;

/**
 * Resolves a templated filesystem path against a TransporterSyncOptions.
 *
 * Syntax:
 *   {name}     -> resolved against the TransporterSyncOptions
 *                 (delegates to the magic __get, so any free-form
 *                 attribute — e.g. {filemask}, {flags} — works)
 *   {{expr}}   -> evaluated as PHP, e.g. {{date('YmdHis')}},
 *                 {{mt_rand(0, 9999)}}, {{strtoupper(uniqid())}}
 *
 * Security: {{expr}} uses eval(). Templates MUST come from trusted
 * configuration — never from inbound EDIFACT, filenames, or any
 * other user-controlled input.
 */
final class PathTemplate
{
    private const FN_PATTERN   = '/\{\{\s*(.+?)\s*\}\}/';
    private const PROP_PATTERN = '/\{(\w+)\}/';

    /**
     * @throws TransporterException when a property is missing or when
     *         a function expression returns null/false.
     */
    public static function resolve(string $template, TransporterSyncOptions $fs): string
    {
        $functionsResolved = preg_replace_callback(
            self::FN_PATTERN,
            static function (array $matches): string {
                $expr = trim($matches[1]);

                $value = eval(sprintf('return %s;', $expr));

                if (is_null($value) || $value === false) {
                    throw new TransporterException(sprintf(
                        "PathTemplate: expression '%s' did not return a usable value",
                        $expr
                    ));
                }

                return (string) $value;
            },
            $template
        );

        if (is_null($functionsResolved)) {
            throw new TransporterException('PathTemplate: function regex failed');
        }

        $propertiesResolved = preg_replace_callback(
            self::PROP_PATTERN,
            static function (array $matches) use ($fs): string {
                $name  = $matches[1];
                $value = $fs->{$name};

                if (is_null($value)) {
                    throw new TransporterException(sprintf(
                        "PathTemplate: unknown property '%s'",
                        $name
                    ));
                }

                return (string) $value;
            },
            $functionsResolved
        );

        if (is_null($propertiesResolved)) {
            throw new TransporterException('PathTemplate: property regex failed');
        }

        return $propertiesResolved;
    }
}
