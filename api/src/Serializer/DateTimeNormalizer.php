<?php

/**
 * This file is part of the jobtime-backend package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Serializer;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer as BaseDateTimeNormalizer;

class DateTimeNormalizer extends BaseDateTimeNormalizer
{
    private static array $supportedTypes = [
        DateTimeInterface::class => true,
        DateTimeImmutable::class => true,
        DateTime::class => true,
        CarbonInterface::class => true,
        CarbonImmutable::class => true,
        Carbon::class => true,
    ];

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return isset(self::$supportedTypes[$type]);
    }

    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): DateTimeInterface {
        $datetime = parent::denormalize($data, $type, $format, $context);

        if (CarbonInterface::class === $type || CarbonImmutable::class === $type) {
            return CarbonImmutable::instance($datetime);
        }

        if (Carbon::class === $type) {
            return Carbon::instance($datetime);
        }

        return $datetime;
    }
}
