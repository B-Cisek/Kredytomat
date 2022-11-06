export function useInstallment() {

    function toDecimal(percent) {
        percent = percent + '%';
        return parseFloat(percent) / 100;
    }

    function interestRate(amount, n, rate) {
        let mn = n * 12;
        rate = toDecimal(parseFloat(rate) / 12);

        let rata = amount * rate * (rate + 1) ** mn / (((rate + 1) ** mn) - 1);

        return new Intl.NumberFormat('pl-PL', {
            style: 'currency',
            currency: 'PLN',
            maximumFractionDigits: 2
        }).format(rata);
    }


    return {interestRate}
}
