import {useHelpers} from "@/Composables/useHelpers";

export function useRatyStale() {
    const {toDecimal} = useHelpers();

    function czescOdsetkowa(oprocentowanie, kapitalDoSplaty) {
        let odsetkowa = ((toDecimal(oprocentowanie) / 12) * kapitalDoSplaty);
        return parseFloat(odsetkowa);
    }

    function czescKapitalowa(rataCalkowitaStala, czescOdsetkowa) {
        let kapitalowa = rataCalkowitaStala - czescOdsetkowa;
        return parseFloat(kapitalowa);
    }

    function kapitalPoSplacie(kapitalDoSplaty, kapitalowa) {
        let kapitalPo = kapitalDoSplaty - kapitalowa;
        return parseFloat(kapitalPo);
    }

    function getRataStala(kwota, n, oprocentowanie) {
        let mn = n * 12;
        oprocentowanie = toDecimal(parseFloat(oprocentowanie) / 12);
        let rata = kwota * oprocentowanie * (oprocentowanie + 1) ** mn / (((oprocentowanie + 1) ** mn) - 1);
        return parseFloat(rata);
    }

    function getHarmonogram(kwotaKredytu, n, oprocentowanie) {
        let kwota = parseFloat(kwotaKredytu);
        let rataCalkowita = getRataStala(kwota, n, oprocentowanie);
        let odsetkowa = czescOdsetkowa(oprocentowanie, kwota);
        let kapitalowa = rataCalkowita - odsetkowa;
        let kapitalPo = kapitalPoSplacie(kwota, kapitalowa);

        let harmonogramKredytu = [
            [
                kwota,
                odsetkowa,
                kapitalowa,
                rataCalkowita,
                kapitalPo
            ],
        ];

        for (let index = 1; index < n * 12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);
            let current = [
                ostatniaRata[4],
                czescOdsetkowa(oprocentowanie, ostatniaRata[4]),
                czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaRata[4])),
                rataCalkowita,
                kapitalPoSplacie(ostatniaRata[4], czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaRata[4])))
            ];

            harmonogramKredytu.push(current);
        }

        return harmonogramKredytu;
    }

    return {
        getHarmonogram,
        getRataStala
    }
}
