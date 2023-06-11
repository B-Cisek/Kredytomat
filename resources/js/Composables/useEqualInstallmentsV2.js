import {useHelpers} from "@/Composables/useHelpers";

/**
 *
 * @param credit Credit data
 * @param overpayment overpayments
 * @param newInterestRate Interest Rate Changes
 * @param fixedFees Fixed Fees
 * @param changingFees Changing Fees
 * @returns {{getSchedule: (function(): (*|number|number)[][])}}
 */
export function useEqualInstallmentsV2(
    credit,
    overpayment = [],
    newInterestRate = [],
    fixedFees = [],
    changingFees = []
) {
    const {toDecimal} = useHelpers();

    /**
     * @param margin Credit margin
     * @param wibor Wibor
     * @returns {number} Credit Interest
     */
    function getCreditInterest(margin, wibor) {
        let creditInterest = 0;
        let tab = [margin, wibor].map(x => Number(x));
        tab.forEach(elm => creditInterest += elm);
        return creditInterest;
    }

    /**
     *
     * @param wibor Wibor
     * @param margin Margin
     * @returns {*} Credit Interest Per Year
     */
    function getCreditInterestPerYear(wibor, margin) {
        return toDecimal(parseFloat(getCreditInterest(margin, wibor)) / 12);
    }

    /**
     *
     * @param wibor Wibor
     * @param margin Margin
     * @returns {number} Installment
     */
    function getInstallment(wibor, margin) {
        let mn = credit.period * 12;
        let creditInterest = getCreditInterestPerYear(wibor, margin);
        let installment =
            credit.amountOfCredit * creditInterest * (creditInterest + 1) ** mn / (((creditInterest + 1) ** mn) - 1);
        return parseFloat(installment);
    }

    /**
     * @param interest Interest
     * @param capitalToPay Capital To Pay
     * @returns Interest Part
     */
    function getInterestPart(interest, capitalToPay) {
        let result = ((toDecimal(interest) / 12) * capitalToPay);
        return parseFloat(result);
    }

    /**
     * @param installmentTotal Total Installment
     * @param partInterest Interest Part
     * @returns Capital Part
     */
    function getCapitalPart(installmentTotal, partInterest) {
        let result = installmentTotal - partInterest;
        return parseFloat(result);
    }

    /**
     * @param capitalToPay Repayment Capital
     * @param capitalPart Capital Part
     * @returns Capital After Repayment
     */
    function getCapitalAfterPay(capitalToPay, capitalPart) {
        let result = capitalToPay - capitalPart;
        return parseFloat(result);
    }

    function getNextMonth(current) {
        return current.getMonth() === 11
            ? new Date(current.getFullYear() + 1, 0)
            : new Date(current.getFullYear(), current.getMonth() + 1);
    }

    function isDateInRange(date, start, end) {
        return date >= start && date <= end;
    }

    function getNewInstallment(capitalToPay, index, currentWibor) {
        let mn = credit.period * 12;
        let interest  = getCreditInterestPerYear(currentWibor);
        let newInstallment =
            capitalToPay * interest * (interest + 1) ** (mn - index) / (((interest + 1) ** (mn - index)) - 1);

        return parseFloat(newInstallment);
    }

    /**
     *
     * @param installmentNumber Number of capital part to subtract
     * @param creditInterest Interest
     * @param schedule Credit Schedule
     * @returns {number} Total Installment
     */
    function getNewInstallmentAfterInterestChange(installmentNumber, creditInterest, schedule) {
        let mn = credit.period * 12;
        mn -= installmentNumber;
        let sumOfPaidCapital = getSumOfPaidCapital(installmentNumber, schedule);
        creditInterest = getCreditInterestPerYear(creditInterest, 0)
        let installment =
            ((credit.amountOfCredit - sumOfPaidCapital) * creditInterest * (creditInterest + 1) ** mn) /
            (((creditInterest + 1) ** mn) - 1);

        return parseFloat(installment);
    }

    function getSumOfPaidCapital(installmentNumber, schedule) {
        let sumOfPaidCapital = 0.00;
        for (let i = 0; i < installmentNumber; i++) {
            sumOfPaidCapital += schedule[i][3];
        }

        return parseFloat(sumOfPaidCapital);
    }

    function calculateChangingFee(fee, capitalToPay) {
        return (capitalToPay * fee) / 100;
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



    /**
     *
     * @returns {[]} Credit Schedule
     */
    function getSchedule() {
        let currentInterest = getCreditInterest(credit.margin, credit.wibor);
        let amountOfCredit = parseFloat(credit.amountOfCredit);
        let installmentTotal = getInstallment(credit.wibor, credit.margin);
        let interestPart = getInterestPart(currentInterest, amountOfCredit);
        let capitalPart = getCapitalPart(installmentTotal, interestPart);
        let capitalAfterPay = getCapitalAfterPay(amountOfCredit, capitalPart);

        let creditSchedule = [
            [
                credit.date,
                amountOfCredit,
                interestPart,
                capitalPart,
                installmentTotal,
                capitalAfterPay,
                0, // overpayment
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
            let fixedFee = 0;
            let changingFee = 0;
            let isInterestRateChanged = false;

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

            let current = [];

            if (isInterestRateChanged) {
                current = [
                    currentDate,
                    capitalToPay,
                    getInterestPart(currentInterest, capitalToPay),
                    getCapitalPart(getNewInstallmentAfterInterestChange(index, currentInterest, creditSchedule), getInterestPart(currentInterest, capitalToPay)),
                    getNewInstallmentAfterInterestChange(index, currentInterest, creditSchedule),
                    getCapitalAfterPay(capitalToPay, getCapitalPart(getNewInstallmentAfterInterestChange(index, currentInterest, creditSchedule), getInterestPart(currentInterest, capitalToPay))),
                    0,
                    currentInterest,
                    fixedFee,
                    changingFee,
                ];
            } else {
                current = [
                    currentDate,
                    capitalToPay,
                    getInterestPart(currentInterest, capitalToPay),
                    getCapitalPart(lastRow[4], getInterestPart(currentInterest, capitalToPay)),
                    lastRow[4],
                    getCapitalAfterPay(capitalToPay, getCapitalPart(lastRow[4], getInterestPart(currentInterest, capitalToPay))),
                    0,
                    currentInterest,
                    fixedFee,
                    changingFee,
                ];
            }

            // jezeli rata calkowita jest mniejsza od kapitalu do splaty
            if (current[4] > current[1]) {
                current[4] = current[1] + current[2]; // rata calkowita
                current[3] = current[4] - current[2]; // czesc kapitalowa
                current[5] = current[1] - current[3]; // kapital po splacie
            }

            creditSchedule.push(current);
            if (current[5] <= 0) break;
        }

        return creditSchedule;
    }

    return {
        getSchedule
    }
}
