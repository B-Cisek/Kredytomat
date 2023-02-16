import {useHelpers} from "@/Composables/useHelpers";

/**
 * RATY MALEJĄCE
 *
 * @param credit Kredyt dane
 * @param overpayment Lista nadpłat
 * @param wiborList Lasta zmian wiboru
 */
export function useDecreasinginstallments(credit, overpayment, wiborList) {
    const {toDecimal} = useHelpers();
    let firstDateFromOverpayment = new Date(1999,0);

    if (overpayment[0]) {
        firstDateFromOverpayment = new Date(overpayment[0].start.year, overpayment[0].start.month);
    }

    /**
     * @param margin Marża
     * @param wibor WIBOR
     * @param commission Prowizja
     * @returns Oprocentowanie kredytu
     */
    function getCreditInterest(margin, wibor, commission) {
        let creditInterest = 0;
        let tab = [margin, wibor, commission].map(x => Number(x));
        tab.forEach(elm => creditInterest += elm);
        return creditInterest;
    }

    /**
     *
     * @param capitalToPay Kapitał do spłaty
     * @param interest Oprocentowanie
     * @returns Część odsetkowa
     */
    function getInterestPart(capitalToPay, interest) {
        interest = toDecimal(parseFloat(interest) / 12);
        return capitalToPay * interest;
    }

    /**
     *
     * @param capitalTotal Kapitał
     * @param n Okres
     * @returns Część kapitałowa
     */
    function getCapitalPart(capitalTotal, n) {
        let mn = n * 12;
        return capitalTotal / mn;
    }

    /**
     *
     * @param capitalPart Część kapitałowa
     * @param interestPart Część odsetkowa
     * @returns Rata całkowita
     */
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

    function getScheduleShorterPeriod() {
        let creditInterest = getCreditInterest(credit.margin, credit.wibor, credit.commission);
        let amount = parseFloat(credit.amountOfCredit);
        let interestPart = getInterestPart(amount, creditInterest);
        let capitalPart = getCapitalPart(amount, credit.period);
        let installmentTotal = getInstallment(capitalPart, interestPart);
        let capitalAfterPay = getCapitalAfterPay(amount, capitalPart);

        let creditSchedule = [
            [
                credit.date,
                amount,
                interestPart,
                capitalPart,
                installmentTotal,
                capitalAfterPay,
                firstDateFromOverpayment.getTime() === credit.date.getTime()
                    ? overpayment[0].overpayment
                    : 0,
                credit.wibor
            ],
        ];

        for (let index = 1; index < credit.period * 12; index++) {
            let lastRow = creditSchedule.at(-1);
            let capitalToPay = lastRow[5] - lastRow[6];
            let currentDate = getNextMonth(lastRow[0]);
            let currentWibor = lastRow[7];
            let lastCreditInterest = getCreditInterest(credit.margin, lastRow[7], credit.commission);
            let onceOverpayment = 0;

            overpayment.forEach(value => {
                let startDate = new Date(value.start.year, value.start.month);
                let endDate = new Date(value.end.year, value.end.month);
                let currentDate = getNextMonth(lastRow[0]);

                if (isDateInRange(currentDate, startDate, endDate)) {
                    onceOverpayment = value.overpayment;
                }
            });

            let current = [
                currentDate,
                capitalToPay,
                getInterestPart(capitalToPay, lastCreditInterest),
                getCapitalPart(amount, credit.period),
                getInstallment(getCapitalPart(amount, credit.period), getInterestPart(capitalToPay, lastCreditInterest)),
                getCapitalAfterPay(capitalToPay, getCapitalPart(amount, credit.period)),
                onceOverpayment,
                currentWibor
            ];

            creditSchedule.push(current);
            if (current[5] <= 0) break;
            onceOverpayment = 0;
        }

        return creditSchedule;
    }

    function getScheduleSmallerInstallment() {
        let creditInterest = getCreditInterest(credit.margin, credit.wibor, credit.commission);
        let amount = parseFloat(credit.amountOfCredit);
        let interestPart = getInterestPart(amount, creditInterest);
        let capitalPart = getCapitalPart(amount, credit.period);
        let installmentTotal = getInstallment(capitalPart, interestPart);
        let capitalAfterPay = getCapitalAfterPay(amount, capitalPart);

        let creditSchedule = [
            [
                credit.date,
                amount,
                interestPart,
                capitalPart,
                installmentTotal,
                capitalAfterPay,
                firstDateFromOverpayment.getTime() === credit.date.getTime()
                    ? overpayment[0].overpayment
                    : 0,
                credit.wibor
            ],
        ];

        for (let index = 1; index < credit.period * 12; index++) {
            let lastRow = creditSchedule.at(-1);
            let capitalToPay = lastRow[5] - lastRow[6];
            let currentDate = getNextMonth(lastRow[0]);
            let currentWibor = lastRow[7];
            let lastCreditInterest = getCreditInterest(credit.margin, lastRow[7], credit.commission);
            let lastCapitalPart = (lastRow[6] == 0)
                ? lastRow[3]
                : getNewCapitalPart(capitalToPay, credit.period * 12, index);
            let onceOverpayment = 0;

            overpayment.forEach(value => {
                let startDate = new Date(value.start.year, value.start.month);
                let endDate = new Date(value.end.year, value.end.month);
                let currentDate = getNextMonth(lastRow[0]);

                if (isDateInRange(currentDate, startDate, endDate)) {
                    onceOverpayment = value.overpayment;
                }
            });

            let current = [
                currentDate,
                capitalToPay,
                getInterestPart(capitalToPay, lastCreditInterest),
                lastCapitalPart,
                getInstallment(lastCapitalPart, getInterestPart(capitalToPay, lastCreditInterest)),
                getCapitalAfterPay(capitalToPay, lastCapitalPart),
                onceOverpayment,
                currentWibor
            ];

            creditSchedule.push(current);
            onceOverpayment = 0;
        }

        return creditSchedule;
    }

    return {getScheduleShorterPeriod, getScheduleSmallerInstallment}
}
