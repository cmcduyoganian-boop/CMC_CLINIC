<?php

namespace App\Support;

/**
 * VitalSigns — Centralized classification for adult/college-age vital sign ranges.
 *
 * Status levels (ascending severity):
 *   NORMAL        – all within normal range
 *   BELOW_NORMAL  – below-normal but not critical
 *   ABOVE_NORMAL  – above-normal but not critical
 *   ABNORMAL      – critical / out-of-range → needs immediate attention
 */
class VitalSigns
{
    // Status constants
    const NORMAL       = 'normal';
    const BELOW_NORMAL = 'below_normal';
    const ABOVE_NORMAL = 'above_normal';
    const ABNORMAL     = 'abnormal';

    // ── Temperature (°C) ────────────────────────────────────────────────
    public static function classifyTemperature(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value < 35.0 || $value >= 38.0) return self::ABNORMAL;
        if ($value < 36.0) return self::BELOW_NORMAL;
        if ($value > 37.5) return self::ABOVE_NORMAL;
        return self::NORMAL;
    }

    // ── Pulse Rate (bpm) ─────────────────────────────────────────────────
    public static function classifyPulseRate(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value < 50 || $value > 120) return self::ABNORMAL;
        if ($value < 60) return self::BELOW_NORMAL;
        if ($value > 100) return self::ABOVE_NORMAL;
        return self::NORMAL;
    }

    // ── Respiratory Rate (breaths/min) ───────────────────────────────────
    public static function classifyRespiratoryRate(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value < 8 || $value > 30) return self::ABNORMAL;
        if ($value < 10) return self::BELOW_NORMAL;
        if ($value > 20) return self::ABOVE_NORMAL;
        return self::NORMAL;
    }

    // ── Systolic BP (mmHg) ───────────────────────────────────────────────
    public static function classifySystolic(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value >= 180) return self::ABNORMAL;
        if ($value < 80)   return self::ABNORMAL;
        if ($value < 90)   return self::BELOW_NORMAL;
        if ($value >= 140) return self::ABOVE_NORMAL;
        return self::NORMAL;
    }

    // ── Diastolic BP (mmHg) ──────────────────────────────────────────────
    public static function classifyDiastolic(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value >= 120) return self::ABNORMAL;
        if ($value < 50)   return self::ABNORMAL;
        if ($value < 60)   return self::BELOW_NORMAL;
        if ($value >= 90)  return self::ABOVE_NORMAL;
        return self::NORMAL;
    }

    // ── SpO₂ (%) ─────────────────────────────────────────────────────────
    public static function classifySpO2(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value <= 90) return self::ABNORMAL;
        if ($value <= 92) return self::BELOW_NORMAL;
        return self::NORMAL;                         // 93–100 = Normal (no "above" for SpO2)
    }

    // ── BMI ───────────────────────────────────────────────────────────────
    public static function classifyBMI(?float $value): ?string
    {
        if ($value === null) return null;
        if ($value < 16 || $value >= 30) return self::ABNORMAL;
        if ($value < 18.5)  return self::BELOW_NORMAL;
        if ($value >= 25.0) return self::ABOVE_NORMAL;
        return self::NORMAL;
    }

    // ── Overall Assessment ────────────────────────────────────────────────
    /**
     * Returns the worst classification across all given vital sign values.
     * Returns null if ALL values are null (nothing recorded).
     */
    public static function overallStatus(array $statuses): ?string
    {
        $statuses = array_filter($statuses); // remove nulls
        if (empty($statuses)) return null;

        $priority = [
            self::ABNORMAL     => 4,
            self::ABOVE_NORMAL => 3,
            self::BELOW_NORMAL => 2,
            self::NORMAL       => 1,
        ];

        $worst = self::NORMAL;
        foreach ($statuses as $s) {
            if (($priority[$s] ?? 0) > ($priority[$worst] ?? 0)) {
                $worst = $s;
            }
        }
        return $worst;
    }

    // ── Display Helpers ───────────────────────────────────────────────────

    /** Human-readable label */
    public static function label(string $status): string
    {
        return match ($status) {
            self::ABNORMAL     => 'ABNORMAL / CRITICAL',
            self::ABOVE_NORMAL => 'ABOVE NORMAL',
            self::BELOW_NORMAL => 'BELOW NORMAL',
            self::NORMAL       => 'NORMAL',
            default            => strtoupper($status),
        };
    }

    /** CSS class suffix for styling */
    public static function cssClass(string $status): string
    {
        return match ($status) {
            self::ABNORMAL     => 'vs-abnormal',
            self::ABOVE_NORMAL => 'vs-above',
            self::BELOW_NORMAL => 'vs-below',
            self::NORMAL       => 'vs-normal',
            default            => '',
        };
    }

    /** Emoji icon for quick visual scan */
    public static function icon(string $status): string
    {
        return match ($status) {
            self::ABNORMAL     => '🚨',
            self::ABOVE_NORMAL => '⬆️',
            self::BELOW_NORMAL => '⬇️',
            self::NORMAL       => '✅',
            default            => '—',
        };
    }
}
