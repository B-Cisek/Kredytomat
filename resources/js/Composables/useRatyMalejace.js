import {useHelpers} from "@/Composables/useHelpers";

export function useRatyMalejace() {
    const {toDecimal} = useHelpers();

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

    function getPierwszaRata(kwotaKredytu, n, oprocentowanie) {
        let kwota = parseFloat(kwotaKredytu);
        let kapitalowa = czescKapitalowa(kwota, n);
        let odsetkowa = czescOdsetkowa(kwota, oprocentowanie);
        return rataCalkowita(kapitalowa, odsetkowa);
    }

    function getHarmonogram(kwotaKredytu, n, oprocentowanie) {
        let kwota = parseFloat(kwotaKredytu);
        let kapitalowa = czescKapitalowa(kwota, n);
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

        for (let index = 1; index < n * 12; index++) {
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
