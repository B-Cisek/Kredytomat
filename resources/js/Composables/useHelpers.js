

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

    const formattedToCurrency = new Intl.NumberFormat("pl-PL", {
        maximumFractionDigits: 2,
    });

    function totalCreditCost(schedule) {
        let cost = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            cost += schedule[i][5];
        }
        return cost + totalCreditInterest(schedule);
    }

    function totalCreditInterest(schedule) {
        let cost = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            cost += schedule[i][4];
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

    function getCapitalPartArray(schedule) {
        let arr = [];
        schedule.forEach((value) => {
            arr.push(value[4]);
        })
        return arr;
    }

    function getInterestPartArray(schedule) {
        let arr = [];
        schedule.forEach((value) => {
            arr.push(value[3]);
        })
        return arr;
    }

    function getPaidInterestToIndex(schedule, index) {
        let sum = 0;
        for (let i = 0; i < index; i++) {
            sum += schedule[i][3];
        }
        return sum;
    }

    function getPaidCapitalToIndex(schedule, index) {
        let sum = 0;
        for (let i = 0; i < index; i++) {
            sum += schedule[i][4];
        }
        return sum;
    }

    function getCapitalToPayFromIndex(schedule, index) {
        let sum = 0;
        for (let i = index; i < schedule.length; i++) {
            sum += schedule[i][4];
        }
        return sum;
    }

    function getTotalFixedFees(schedule) {
        let fixedFees = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            fixedFees += schedule[i][8];
        }
        return fixedFees;
    }

    function getTotalChangingFees(schedule) {
        let changingFees = 0.00;
        for (let i = 0; i < schedule.length; i++) {
            changingFees += schedule[i][9];
        }
        return changingFees;
    }

    function getTotalInstallmentsYear(schedule) {
        let totalInstallmentsYear = 0;
        for (let i = 0; i < 11; i++) {
            totalInstallmentsYear += schedule[i][4];
        }
        return totalInstallmentsYear;
    }

    function getCommissionValue(commission, amountOfCredit) {
        return toDecimal(commission) * amountOfCredit;
    }

    return {
        getCommissionValue,
        getTotalInstallmentsYear,
        getTotalChangingFees,
        getTotalFixedFees,
        formatHarmonogram,
        totalCreditInterest,
        toDecimal,
        totalCreditCost,
        formattedToPLN,
        formattedToCurrency,
        getCapitalPartArray,
        getInterestPartArray,
        getPaidInterestToIndex,
        getPaidCapitalToIndex,
        getCapitalToPayFromIndex,
    }
}
