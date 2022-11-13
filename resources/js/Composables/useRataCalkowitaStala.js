export function useRataCalkowitaStala() {

    function toDecimal(percent) {
        percent = percent + '%';
        return parseFloat(percent) / 100;
    }

    function rataStalaFormatted(kwota, n, oprocentowanie) {
        let mn = n * 12;
        oprocentowanie = toDecimal(parseFloat(oprocentowanie) / 12);

        let rata = kwota * oprocentowanie * (oprocentowanie + 1) ** mn / (((oprocentowanie + 1) ** mn) - 1);

        return new Intl.NumberFormat('pl-PL', {
            style: 'currency',
            currency: 'PLN',
            maximumFractionDigits: 2
        }).format(rata);
    }

    function rataStala(kwota, n, oprocentowanie) {
        let mn = n * 12;
        oprocentowanie = toDecimal(parseFloat(oprocentowanie) / 12);
        let rata = kwota * oprocentowanie * (oprocentowanie + 1) ** mn / (((oprocentowanie + 1) ** mn) - 1);
        return parseFloat(rata);
    }


    return {
        rataStalaFormatted,
        rataStala,
        toDecimal
    }
}
