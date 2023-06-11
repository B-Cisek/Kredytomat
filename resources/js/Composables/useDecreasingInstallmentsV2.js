import {useHelpers} from "@/Composables/useHelpers";

export function useDecreasingInstallmentsV2(
    credit,
    overpayment = [],
    newInterestRate = [],
    fixedFees = [],
    changingFees = []
) {
    const {toDecimal} = useHelpers();

    function getCreditInterest(margin, wibor) {
        let creditInterest = 0;
        let tab = [margin, wibor].map(x => Number(x));
        tab.forEach(elm => creditInterest += elm);
        return creditInterest;
    }

    function getInterestPart(capitalToPay, interest) {
        interest = toDecimal(parseFloat(interest) / 12);
        return capitalToPay * interest;
    }

    function getCapitalPart(capitalTotal, n) {
        let mn = n * 12;
        return capitalTotal / mn;
    }

    function getInstallment(capitalPart, interestPart) {
        return capitalPart + interestPart;
    }

    function getCapitalAfterPay(capital, capitalPart) {
        return capital - capitalPart;
    }

    function getNextMonth(current) {
        return current.getMonth() === 11
            ? new Date(current.getFullYear() + 1, 0)
            : new Date(current.getFullYear(), current.getMonth() + 1);
    }

    function isDateInRange(date, start, end) {
        return date >= start && date <= end;
    }

    function getNewCapitalPart(capitalToPay, mn, n) {
        return capitalToPay / (mn - n);
    }

    function getFirstDateFromFixedFees(fixedFees) {
        if (Array.isArray(fixedFees) && fixedFees.length > 0) {
            return new Date(fixedFees[0].start.year, fixedFees[0].start.month);
        }
        return  new Date(1999,0);
    }

    function getFirstDateFromChangingFees(changingFees) {
        if (Array.isArray(changingFees) && changingFees.length > 0) {
            return new Date(changingFees[0].start.year, changingFees[0].start.month);
        }
        return  new Date(1999,0);
    }

    function calculateChangingFee(fee, capitalToPay) {
        return (capitalToPay * fee) / 100;
    }


    function getSchedule() {
        let currentInterest = getCreditInterest(credit.margin, credit.wibor);
        let amountOfCredit = parseFloat(credit.amountOfCredit);
        let interestPart = getInterestPart(amountOfCredit, currentInterest);
        let capitalPart = getCapitalPart(amountOfCredit, credit.period);
        let installmentTotal = getInstallment(capitalPart, interestPart);
        let capitalAfterPay = getCapitalAfterPay(amountOfCredit, capitalPart);

        let creditSchedule = [
            [
                credit.date,
                amountOfCredit,
                interestPart,
                capitalPart,
                installmentTotal,
                capitalAfterPay,
                0,
                currentInterest,
                getFirstDateFromFixedFees(fixedFees).getTime() === credit.date.getTime()
                    ? fixedFees[0].fee
                    : 0,
                getFirstDateFromChangingFees(changingFees).getTime() === credit.date.getTime()
                    ? calculateChangingFee(changingFees[0].fee, amountOfCredit)
                    : 0,
            ]
        ];

        for (let index = 1; index < credit.period * 12; index++) {
            let lastRow = creditSchedule.at(-1);
            let capitalToPay = lastRow[5] - lastRow[6];
            let currentDate = getNextMonth(lastRow[0]);
            let isInterestRateChanged = false;
            let fixedFee = 0;
            let changingFee = 0;

            newInterestRate.forEach(value => {
                if (value.date.month === currentDate.getMonth()
                    && value.date.year === currentDate.getFullYear()) {
                    currentInterest = value.value
                    isInterestRateChanged = true;
                }
            });

            fixedFees.forEach(value => {
                let startDate = new Date(value.start.year, value.start.month);
                let endDate = new Date(value.end.year, value.end.month);
                let currentDate = getNextMonth(lastRow[0]);

                if (isDateInRange(currentDate, startDate, endDate)) {
                    fixedFee = value.fee;
                }
            });

            changingFees.forEach(value => {
                let startDate = new Date(value.start.year, value.start.month);
                let endDate = new Date(value.end.year, value.end.month);
                let currentDate = getNextMonth(lastRow[0]);

                if (isDateInRange(currentDate, startDate, endDate)) {
                    changingFee = calculateChangingFee(value.fee, capitalToPay);
                }
            });

            let current = [
                currentDate,
                capitalToPay,
                getInterestPart(capitalToPay, currentInterest),
                getCapitalPart(amountOfCredit, credit.period),
                getInstallment(getCapitalPart(amountOfCredit, credit.period), getInterestPart(capitalToPay, currentInterest)),
                getCapitalAfterPay(capitalToPay, getCapitalPart(amountOfCredit, credit.period)),
                0,
                currentInterest,
                fixedFee,
                changingFee
            ];

            creditSchedule.push(current);
            if (current[5] <= 0) break;

        }

        return creditSchedule;
    }

    return {
        getSchedule
    }
}
