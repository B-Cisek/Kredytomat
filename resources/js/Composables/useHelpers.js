export function useHelpers() {

    function formatHarmonogram(harmonogram) {
        return harmonogram.map((subarray) => subarray.map((x) => x.toFixed(2)));
    }

    function toDecimal(percent) {
        percent = percent + '%';
        return parseFloat(percent) / 100;
    }

    const formattedToPLN = new Intl.NumberFormat("pl-PL", {
        style: "currency",
        currency: "PLN",
        maximumFractionDigits: 2,
    });

    function kosztKredytu(harmonogram) {
        let szumaKoszt = 0.00;
        for (let index = 0; index < harmonogram.length; index++) {
            szumaKoszt +=harmonogram[index][1];
        }
        return szumaKoszt;
    }

    return {
        formatHarmonogram,
        toDecimal,
        formattedToPLN,
        kosztKredytu
    }
}
