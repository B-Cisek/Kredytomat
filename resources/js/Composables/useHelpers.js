export function useHelpers() {

    function formatHarmonogram(harmonogram) {
        let foramtted = harmonogram.map((subarray) => subarray.map((x) => x.toFixed(2)));

        return foramtted;
    }

    return { formatHarmonogram }
}
