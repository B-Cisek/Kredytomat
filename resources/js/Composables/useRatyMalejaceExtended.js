import {useHelpers} from "@/Composables/useHelpers";


export function useRatyMalejaceExtended(kredyt) {
    const {toDecimal} = useHelpers();

    function getOprocentowanie() {
        let oprocentowanie = 0;
        let tab = [kredyt.marza, kredyt.wibor, kredyt.prowizja].map(x => Number(x));
        tab.forEach(elm => oprocentowanie += elm);
        return oprocentowanie;
    }

    function czescOdsetkowa(kapitalDoSplaty, oprocentowanie) {
        oprocentowanie = toDecimal(parseFloat(oprocentowanie) / 12);
        return kapitalDoSplaty * oprocentowanie;
    }

    function czescKapitalowa(kwotaKapitalu, n) {
        let mn = n * 12;
        return kwotaKapitalu / mn;
    }

    function rataCalkowita(czescKapitalowa, czescOdsetkowa) {
        return czescKapitalowa + czescOdsetkowa;
    }

    function kapitalPoSplacie(kapital, kapitalowa) {
        return kapital - kapitalowa;
    }

    function getPierwszaRata() {
        let oprocentowanie = getOprocentowanie();
        let kwota = parseFloat(kredyt.kwotaKredytu);
        let kapitalowa = czescKapitalowa(kwota, kredyt.okres);
        let odsetkowa = czescOdsetkowa(kwota, oprocentowanie);
        return rataCalkowita(kapitalowa, odsetkowa);
    }

    function getHarmonogram() {
        let oprocentowanie = getOprocentowanie();
        let kwota = parseFloat(kredyt.kwotaKredytu);
        let kapitalowa = czescKapitalowa(kwota, kredyt.okres);
        let odsetkowa = czescOdsetkowa(kwota, oprocentowanie);
        let rata = rataCalkowita(kapitalowa, odsetkowa);
        let kapitalPo = kapitalPoSplacie(kwota, kapitalowa);

        let harmonogramKredytu = [
            [
                kwota,
                odsetkowa,
                kapitalowa,
                rata,
                kapitalPo
            ],
        ];

        for (let index = 1; index < kredyt.okres * 12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);

            let current = [
                ostatniaRata[4],
                czescOdsetkowa(ostatniaRata[4], oprocentowanie),
                kapitalowa,
                rataCalkowita(kapitalowa, czescOdsetkowa(ostatniaRata[4], oprocentowanie)),
                kapitalPoSplacie(ostatniaRata[4], kapitalowa)
            ];

            harmonogramKredytu.push(current);
        }

        return harmonogramKredytu;
    }

    return {getHarmonogram, getPierwszaRata};

}
