import {useHelpers} from "@/Composables/useHelpers";

export function RatyMalejaceOprocentowanieDzienne(kredyt) {
    const {toDecimal} = useHelpers();

    // todo: przekazac daty do obliczen
    let firstDate = new Date("1/20/2023");
    let start = new Date("2/20/2023");
    let end = new Date("1/20/2048");
    let listDates = getListOfDates(start, end);

    /**
     * [0] - data spłaty raty
     * [1] - ilosc dni pomiedzy datami
     * [2] - kwota do splaty
     * [3] - czesc kapitalowa
     * [4] - czesc odsetkowa
     * [5] - rata calkowita
     * [6] - kwota po splacie
     */
    let harmonogramKredytu = [];

    let r30 = getOprocentowanie() / 12;
    let sredniaLiczbaDniKredytu = getNumberOfDays(firstDate, end) / (kredyt.okres * 12);
    let oprocentowanie30 = (r30 * sredniaLiczbaDniKredytu) / 30;

    function getOprocentowanie() {
        let oprocentowanie = 0;
        let tab = [kredyt.marza, kredyt.wibor, kredyt.prowizja].map(x => Number(x));
        tab.forEach(elm => oprocentowanie += elm);
        return oprocentowanie;
    }

    function prepareDataListArray() {
        for (let index = 0; index < kredyt.okres * 12; index++) {
            let row = [listDates[index]];
            harmonogramKredytu.push(row)
        }
    }

    function prepareDiffDates() {
        harmonogramKredytu[0][1] = getNumberOfDays(firstDate, start);
        for (let index = 1; index < kredyt.okres * 12; index++) {
            harmonogramKredytu[index][1] = getNumberOfDays(harmonogramKredytu[index - 1][0], harmonogramKredytu[index][0]);
        }
    }

    function getListOfDates(start, end) {
        let dates = [];
        let current = new Date(start.getFullYear(), start.getMonth(), 20);
        while (current <= end) {
            dates.push(new Date(current));
            current.setMonth(current.getMonth() + 1);
        }
        return dates;
    }

    function getNumberOfDays(date1, date2) {
        const oneDay = 1000 * 60 * 60 * 24;
        const diffInTime = date2.getTime() - date1.getTime();
        return Math.round(diffInTime / oneDay);
    }

    function czescKapitalowa(kwotaKredytu, okres) {
        return kwotaKredytu / (okres * 12);
    }

    function czescOdsetkowa(kwotaDoSplaty, liczbaDni) {
        return kwotaDoSplaty * ((toDecimal(oprocentowanie30) * liczbaDni) / 30);
    }

    function rataCalkowita(czescKapitalowa, czescOdsetkowa) {
        return czescKapitalowa + czescOdsetkowa;
    }

    function kapitalPosplacie(kapitalDoSplaty, czescKapitalowa) {
        return kapitalDoSplaty - czescKapitalowa;
    }

    function getHarmonogramRatyMalejace() {
        prepareDataListArray();
        prepareDiffDates();

        harmonogramKredytu[0][2] = kredyt.kwotaKredytu;
        harmonogramKredytu[0][3] = czescKapitalowa(kredyt.kwotaKredytu, kredyt.okres);
        harmonogramKredytu[0][4] = czescOdsetkowa(kredyt.kwotaKredytu, harmonogramKredytu[0][1]);
        harmonogramKredytu[0][5] = rataCalkowita(harmonogramKredytu[0][3], harmonogramKredytu[0][4]);
        harmonogramKredytu[0][6] = kapitalPosplacie(harmonogramKredytu[0][2], harmonogramKredytu[0][3]);

        for (let index = 1; index < kredyt.okres * 12; index++) {
            let ostatniaRata = harmonogramKredytu[index - 1];

            harmonogramKredytu[index][2] = ostatniaRata[6];
            harmonogramKredytu[index][3] = czescKapitalowa(kredyt.kwotaKredytu, kredyt.okres);
            harmonogramKredytu[index][4] = czescOdsetkowa(ostatniaRata[6], ostatniaRata[1]);
            harmonogramKredytu[index][5] = rataCalkowita(harmonogramKredytu[index][3], harmonogramKredytu[index][4]);
            harmonogramKredytu[index][6] = kapitalPosplacie(harmonogramKredytu[index][2], harmonogramKredytu[index][3]);
        }

        return harmonogramKredytu;
    }

    return {
        getHarmonogramRatyMalejace
    }
}
