<?php

use App\Helpers\DurationConverter;

uses()->group('duration');

describe('convertDuration', function () {
    it('should convert every part', function () {
        expect(DurationConverter::convertDuration('P5Y'))->toBe([
            'year' => 5,
            'month' => null,
            'day' => null,
            'hour' => null,
            'minute' => null,
            'second' => null,
        ]);
        expect(DurationConverter::convertDuration('P11M'))->toBe([
            'year' => null,
            'month' => 11,
            'day' => null,
            'hour' => null,
            'minute' => null,
            'second' => null,
        ]);
        expect(DurationConverter::convertDuration('P238D'))->toBe([
            'year' => null,
            'month' => null,
            'day' => 238,
            'hour' => null,
            'minute' => null,
            'second' => null,
        ]);
        expect(DurationConverter::convertDuration('PT15H'))->toBe([
            'year' => null,
            'month' => null,
            'day' => null,
            'hour' => 15,
            'minute' => null,
            'second' => null,
        ]);
        expect(DurationConverter::convertDuration('PT56M'))->toBe([
            'year' => null,
            'month' => null,
            'day' => null,
            'hour' => null,
            'minute' => 56,
            'second' => null,
        ]);
    });

    it('should handle HMS missing T', function () {
        expect(DurationConverter::convertDuration('P23H'))->toBe(null);
        expect(DurationConverter::convertDuration('P8S'))->toBe(null);
    });
});

describe('convertToSecond', function () {
    it('should convert every part', function () {
        expect(DurationConverter::convertToSecond('P5Y'))->toBe(157680000);
        expect(DurationConverter::convertToSecond('P11M'))->toBe(28512000);
        expect(DurationConverter::convertToSecond('P238D'))->toBe(20563200);
    });

    it('should handle invalid input', function () {
        expect(DurationConverter::convertToSecond('ieurht834'))->toBe(null);
    });
});

describe('convertYouTubeDuration', function () {
    it('should convert every part with hour', function () {
        expect(DurationConverter::convertYouTubeDuration('P5Y'))->toBe('43800:00:00');
        expect(DurationConverter::convertYouTubeDuration('P11M'))->toBe('7920:00:00');
        expect(DurationConverter::convertYouTubeDuration('PT15H'))->toBe('15:00:00');
    });

    it('should convert minute-second format when hour is zero', function () {
        expect(DurationConverter::convertYouTubeDuration('PT0M30S'))->toBe('0:30');
        expect(DurationConverter::convertYouTubeDuration('PT28M59S'))->toBe('28:59');
    });

    it('should convert correct hh syntax', function () {
        expect(DurationConverter::convertYouTubeDuration('PT3H'))->toBe('3:00:00');
    });

    it('should handle invalid input', function () {
        expect(DurationConverter::convertYouTubeDuration('P8S'))->toBe(null);
    });
});

describe('convertSecondsToYouTubeDuration', function () {
    it('should convert seconds to minute:second format when less than an hour', function () {
        expect(DurationConverter::convertSecondsToYouTubeDuration(30))->toBe('0:30');
        expect(DurationConverter::convertSecondsToYouTubeDuration(3599))->toBe('59:59');
    });

    it('should convert seconds to hour:minute:second format when one hour or more', function () {
        expect(DurationConverter::convertSecondsToYouTubeDuration(3600))->toBe('1:00:00');
        expect(DurationConverter::convertSecondsToYouTubeDuration(3661))->toBe('1:01:01');
    });

    it('should handle invalid input', function () {
        expect(DurationConverter::convertSecondsToYouTubeDuration('invalid'))->toBe(null);
        expect(DurationConverter::convertSecondsToYouTubeDuration(-10))->toBe(null);
    });
});

describe('convertSecondsToDuration', function () {
    it('should return PT0S for 0 seconds', function () {
        expect(DurationConverter::convertSecondsToDuration(0))->toBe('PT0S');
    });

    it('should convert seconds to duration with only seconds', function () {
        expect(DurationConverter::convertSecondsToDuration(30))->toBe('PT30S');
    });

    it('should convert seconds to duration with minutes and seconds', function () {
        expect(DurationConverter::convertSecondsToDuration(90))->toBe('PT1M30S');
    });

    it('should convert seconds to duration with hours, minutes, and seconds', function () {
        expect(DurationConverter::convertSecondsToDuration(3661))->toBe('PT1H1M1S');
    });

    it('should convert seconds to duration with days and hours', function () {
        // 90000 seconds = 1 day (86400s) + 3600 seconds = 1 day and 1 hour
        expect(DurationConverter::convertSecondsToDuration(90000))->toBe('P1DT1H');
    });

    it('should convert seconds to duration with years, months, days, hours, minutes, and seconds', function () {
        $yearSeconds  = 365 * 24 * 60 * 60;
        $monthSeconds = 30 * 24 * 60 * 60;
        $daySeconds   = 24 * 60 * 60;
        $input = 2 * $yearSeconds + 3 * $monthSeconds + 4 * $daySeconds + 5 * 3600 + 6 * 60 + 7;
        expect(DurationConverter::convertSecondsToDuration($input))->toBe('P2Y3M4DT5H6M7S');
    });

    it('should handle invalid input', function () {
        expect(DurationConverter::convertSecondsToDuration('invalid'))->toBe(null);
        expect(DurationConverter::convertSecondsToDuration(-10))->toBe(null);
    });
});
