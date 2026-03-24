export const durationMultipliers = {
    minutes: 1,
    hours: 60,
    days: 60 * 24,
    weeks: 60 * 24 * 7,
    months: 60 * 24 * 30,
};

export function convertToMinutes(
    value: number,
    type: keyof typeof durationMultipliers,
) {
    return value * durationMultipliers[type];
}

export function convertFromMinutes(
    minutes: number,
    type: keyof typeof durationMultipliers,
) {
    return minutes / durationMultipliers[type];
}
