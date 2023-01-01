export function useHelpers() {

    function formatHarmonogram(harmonogram) {
        return harmonogram.map((subarray) => subarray.map((x) => x.toFixed(2)));
    }

    function toDecimal(percent) {
        percent = percent + '%';
        return parseFloat(percent) / 100;
    }

    return {formatHarmonogram, toDecimal}
}
