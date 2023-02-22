

export function useHelpers() {

    function formatHarmonogram(harmonogram) {
        //return harmonogram.map((subarray) => subarray.map((x) => x.toFixed(2)));

        return harmonogram.map((subarray) => subarray.map((x) => {
            return (typeof x !== "object") ? x.toFixed(2) : x
        }));
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
            szumaKoszt += harmonogram[index][1];
        }
        return szumaKoszt;
    }

    function totalCreditCost(schedule) {
        let cost = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            cost += schedule[i][3];
        }
        return cost + totalCreditInterest(schedule);
    }

    function totalCreditInterest(schedule) {
        let cost = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            cost += schedule[i][2];
        }
        return cost;
    }

    function totalOverpayments(schedule) {
        let overpayments = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            overpayments += schedule[i][6];
        }
        return overpayments;
    }

    return {
        formatHarmonogram,
        totalCreditInterest,
        toDecimal,
        totalCreditCost,
        formattedToPLN,
        totalOverpayments,
        kosztKredytu

    }
}
