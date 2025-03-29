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
