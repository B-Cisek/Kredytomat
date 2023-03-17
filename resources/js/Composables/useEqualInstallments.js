import {useHelpers} from "@/Composables/useHelpers";

/**
 * RATY STAŁE
 *
 * @param credit Kredyt dane
 * @param overpayment Lista nadpłat
 * @param wiborList Lasta zmian wiboru
 * @param fixedFees Opłaty stałe
 * @param changingFees Opłaty zmienne
 */
export function useEqualInstallments(
    credit,
    overpayment = [],
    wiborList = [],
    fixedFees = [],
    changingFees = []
) {
    const {toDecimal} = useHelpers();

    let firstDateFromOverpayment = new Date(1999,0);
    if (overpayment[0]) {
        firstDateFromOverpayment = new Date(overpayment[0].start.year, overpayment[0].start.month);
    }

    let firstDateFromFixedFees = new Date(1999,0);
    if (fixedFees[0]) {
        firstDateFromFixedFees = new Date(fixedFees[0].start.year, fixedFees[0].start.month);
    }

    let firstDateFromChangingFees = new Date(1999,0);
    if (changingFees[0]) {
        firstDateFromChangingFees = new Date(changingFees[0].start.year, changingFees[0].start.month);
    }

    /**
     * @param margin Marża
     * @param wibor WIBOR
     * @returns Oprocentowanie kredytu
     */
    function getCreditInterest(margin, wibor) {
        let creditInterest = 0;
        let tab = [margin, wibor].map(x => Number(x));
        tab.forEach(elm => creditInterest += elm);
        return creditInterest;
    }

    /**
     *
     * @param wibor WIBOR
     * @returns Pierwszą rate kredytu
     */
    function getInstallment(wibor) {
        let mn = credit.period * 12;
        let creditInterest =
            toDecimal(parseFloat(getCreditInterest(credit.margin, wibor)) / 12);
        let installment =
            credit.amountOfCredit * creditInterest * (creditInterest + 1) ** mn / (((creditInterest + 1) ** mn) - 1);
        return parseFloat(installment);
    }

    /**
     * @param interest Oprocentowanie
     * @param capitalToPay Kapitał do spłaty
     * @returns Część odsetkowa
     */
    function getInterestPart(interest, capitalToPay ) {
        let result = ((toDecimal(interest) / 12) * capitalToPay);
        return parseFloat(result);
    }

    /**
     * @param installmentTotal Rata całkowita
     * @param partInterest Częśc odsetkowa
     * @returns Część kapitałowa
     */
    function getCapitalPart(installmentTotal, partInterest) {
        let result = installmentTotal - partInterest;
        return parseFloat(result);
    }

    /**
     * @param capitalToPay Kapitał do spłaty
     * @param capitalPart Część kapitałowa
     * @returns Kapitał po spłacie
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
        let interest  =
            toDecimal(parseFloat(getCreditInterest(credit.margin, currentWibor)) / 12);
        let newInstallment =
            capitalToPay * interest * (interest + 1) ** (mn - index) / (((interest + 1) ** (mn - index)) - 1);

        return parseFloat(newInstallment);
    }

    function calculateChangingFee(fee, capitalToPay) {
        return (capitalToPay * fee) / 100;
    }

    function getSchedule() {
        let creditInterest = getCreditInterest(credit.margin, credit.wibor);
        let amount = parseFloat(credit.amountOfCredit);
        let installmentTotal = getInstallment(credit.wibor);
        let interestPart = getInterestPart(creditInterest, amount);
        let capitalPart = getCapitalPart(installmentTotal, interestPart);
        let capitalAfterPay = getCapitalAfterPay(amount, capitalPart);


        let creditSchedule = [
            [
                credit.date,
                amount,
                interestPart,
                capitalPart,
                installmentTotal,
                capitalAfterPay,
                0,
                credit.wibor,
                firstDateFromFixedFees.getTime() === credit.date.getTime()
                    ? fixedFees[0].fee
                    : 0,
                firstDateFromChangingFees.getTime() === credit.date.getTime()
                    ? calculateChangingFee(changingFees[0].fee, amount)
                    : 0,
            ],
        ];

        //creditSchedule[0][4] += creditSchedule[0][8] + creditSchedule[0][9];

        for (let index = 1; index < credit.period * 12; index++) {
            let lastRow = creditSchedule.at(-1);
            let capitalToPay = lastRow[5] - lastRow[6];
            let lastCreditInterest = getCreditInterest(credit.margin, lastRow[7]);
            let currentDate = getNextMonth(lastRow[0]);
            let currentWibor = lastRow[7];
            let fixedFee = 0;
            let changingFee = 0;

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
                getInterestPart(lastCreditInterest, capitalToPay),
                getCapitalPart(getInstallment(currentWibor), getInterestPart(lastCreditInterest, capitalToPay)),
                getInstallment(currentWibor),
                getCapitalAfterPay(capitalToPay, getCapitalPart(getInstallment(currentWibor), getInterestPart(lastCreditInterest, capitalToPay))),
                0,
                currentWibor,
                fixedFee,
                changingFee
            ];

            // jezeli rata calkowita jest mniejsza od kapitalu do splaty
            if (current[4] > current[1]) {
                current[4] = current[1] + current[2]; // rata calkowita
                current[3] = current[4] - current[2]; // czesc kapitalowa
                current[5] = current[1] - current[3]; // kapital po splacie
            }

            // sumowanie oplat
            //current[4] += current[8] + current[9];

            creditSchedule.push(current);
            if (current[5] <= 0) break;
        }

        return creditSchedule;
    }

    function getScheduleShorterPeriod() {
        let creditInterest = getCreditInterest(credit.margin, credit.wibor);
        let amount = parseFloat(credit.amountOfCredit);
        let installmentTotal = getInstallment(credit.wibor);
        let interestPart = getInterestPart(creditInterest, amount);
        let capitalPart = getCapitalPart(installmentTotal, interestPart);
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
            let lastOverpayment = 0;
            let capitalToPay = lastRow[5] - lastRow[6];
            let lastCreditInterest = getCreditInterest(credit.margin, lastRow[7]);
            let currentDate = getNextMonth(lastRow[0]);
            let currentWibor = lastRow[7];
            let onceOverpayment = 0;

            // TODO:
            wiborList.forEach(value => {
                if (value.start.month === lastRow[0].getMonth() &&
                    value.start.year === lastRow[0].getFullYear()) {
                    currentWibor = value.wibor
                }
            });

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
                getInterestPart(lastCreditInterest, capitalToPay),
                getCapitalPart(getInstallment(currentWibor), getInterestPart(lastCreditInterest, capitalToPay)),
                getInstallment(currentWibor),
                getCapitalAfterPay(capitalToPay, getCapitalPart(getInstallment(currentWibor), getInterestPart(lastCreditInterest, capitalToPay))),
                onceOverpayment !== 0 ? onceOverpayment : lastOverpayment,
                currentWibor
            ];

            // jezeli rata calkowita jest mniejsza od kapitalu do splaty
            if (current[4] > current[1]) {
                current[4] = current[1] + current[2]; // rata calkowita
                current[3] = current[4] - current[2]; // czesc kapitalowa
                current[5] = current[1] - current[3]; // kapital po splacie
            }

            creditSchedule.push(current);
            if (current[5] <= 0) break;
            onceOverpayment = 0;
        }

        return creditSchedule;
    }

    function getScheduleSmallerInstallment() {
        let creditInterest = getCreditInterest(credit.margin, credit.wibor);
        let amount = parseFloat(credit.amountOfCredit);
        let installmentTotal = getInstallment(credit.wibor);
        let interestPart = getInterestPart(creditInterest, amount);
        let capitalPart = getCapitalPart(installmentTotal, interestPart);
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
            let lastOverpayment = lastRow[6];
            let capitalToPay = lastRow[5] - lastRow[6];
            let lastCreditInterest = getCreditInterest(credit.margin, lastRow[7]);
            let currentDate = getNextMonth(lastRow[0]);
            let currentWibor = lastRow[7];
            let onceOverpayment = 0;
            let lastCapitalBeforePay = lastRow[5] - lastOverpayment;
            let newInstallmentTotal = lastRow[4];

            // TODO:
            wiborList.forEach(value => {
                if (value.start.month === lastRow[0].getMonth() &&
                    value.start.year === lastRow[0].getFullYear()) {
                    currentWibor = value.wibor
                }
            });

            overpayment.forEach(value => {
                let startDate = new Date(value.start.year, value.start.month);
                let endDate = new Date(value.end.year, value.end.month);
                let currentDate = getNextMonth(lastRow[0]);

                if (isDateInRange(currentDate, startDate, endDate)) {
                    onceOverpayment = value.overpayment;
                }
            });

            let current = [];

            if (lastOverpayment === 0) {
                current = [
                    currentDate,
                    capitalToPay,
                    getInterestPart(lastCreditInterest, capitalToPay),
                    getCapitalPart(newInstallmentTotal, getInterestPart(lastCreditInterest, capitalToPay)),
                    newInstallmentTotal,
                    getCapitalAfterPay(capitalToPay, getCapitalPart(newInstallmentTotal, getInterestPart(lastCreditInterest, capitalToPay))),
                    onceOverpayment,
                    currentWibor
                ];
            } else {
                newInstallmentTotal = getNewInstallment(lastCapitalBeforePay, index, currentWibor);
                current = [
                    currentDate,
                    capitalToPay,
                    getInterestPart(lastCreditInterest, capitalToPay),
                    getCapitalPart(newInstallmentTotal, getInterestPart(lastCreditInterest, capitalToPay)),
                    newInstallmentTotal,
                    getCapitalAfterPay(capitalToPay, getCapitalPart(newInstallmentTotal, getInterestPart(lastCreditInterest, capitalToPay))),
                    onceOverpayment,
                    currentWibor
                ];
            }

            creditSchedule.push(current);
            onceOverpayment = 0;
        }

        return creditSchedule;
    }

    return {
        getScheduleShorterPeriod,
        getScheduleSmallerInstallment,
        getSchedule,
        getInstallment,
    }
}
