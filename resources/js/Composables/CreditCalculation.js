import {useHelpers} from "@/Composables/useHelpers";

    // date: new Date(2023,1),
    // amountOfCredit: 300000,
    // period: 5,
    // margin: 1.93,
    // wibor: 7,
    // commission: 0

// Raty Stałe
export function CreditCalculation(credit, overpayment, wiborList) {
    const {toDecimal} = useHelpers();

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
     * @param wibor WIBOR
     * @returns Pierwszą rate kredytu
     */
    function getInstallment() {
        let mn = credit.period * 12;
        let creditInterest =
            toDecimal(parseFloat(getCreditInterest(credit.margin, credit.wibor, credit.commission)) / 12);
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

    function getSchedule() {
        let creditInterest = getCreditInterest(credit.margin, credit.wibor, credit.commission);
        let amount = parseFloat(credit.amountOfCredit);
        let installmentTotal = getInstallment();
        let interestPart = getInterestPart(creditInterest, amount);
        let capitalPart = getCapitalPart(installmentTotal, interestPart);
        let capitalAfterPay = getCapitalAfterPay(amount, capitalPart);
        let interestPerMonth = (credit.margin + credit.wibor) / 12;

        let creditSchedule = [
            [
                credit.date,
                amount,
                interestPart,
                capitalPart,
                installmentTotal,
                capitalAfterPay,
                overpayment[0] === undefined ? 0 : overpayment[0],
                credit.wibor
            ],
        ];

        for (let index = 1; index < credit.period * 12; index++) {
            let lastRow = creditSchedule.at(-1);
            let lastOverpayment = overpayment[index - 1] === undefined ? 0 : overpayment[index - 1];
            let capitalToPay = lastRow[5] - lastOverpayment;
            let lastCreditInterest = getCreditInterest(credit.margin, lastRow[7], credit.commission);

            let current = [
                credit.date,
                capitalToPay,
                getInterestPart(lastCreditInterest, capitalToPay),
                getCapitalPart(installmentTotal, getInterestPart(lastCreditInterest, capitalToPay)),
                installmentTotal,
                getCapitalAfterPay(capitalToPay, getCapitalPart(installmentTotal, getInterestPart(lastCreditInterest, capitalToPay))),
                overpayment[index] === undefined ? 0 : overpayment[index],
                credit.wibor
            ];

            creditSchedule.push(current);
        }

        return creditSchedule;
    }

    return {
        getInstallment,
        getSchedule
    }
}
