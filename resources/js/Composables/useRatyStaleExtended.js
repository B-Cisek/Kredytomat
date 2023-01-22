import {useHelpers} from "@/Composables/useHelpers";

export function useRatyStaleExtended(kredyt) {
    const {toDecimal} = useHelpers();

    function getOprocentowanie() {
        let oprocentowanie = 0;
        let tab = [kredyt.marza, kredyt.wibor, kredyt.prowizja].map(x => Number(x));
        tab.forEach(elm => oprocentowanie += elm);
        return oprocentowanie;
    }

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

    function getRataStala() {
        let mn = kredyt.okres * 12;
        let oprocentowanie = toDecimal(parseFloat(getOprocentowanie()) / 12);
        let rata = kredyt.kwotaKredytu * oprocentowanie * (oprocentowanie + 1) ** mn / (((oprocentowanie + 1) ** mn) - 1);

        return parseFloat(rata);
    }

    function getHarmonogram() {
        let oprocentowanie = getOprocentowanie();
        let kwota = parseFloat(kredyt.kwotaKredytu);
        let rataCalkowita = getRataStala(kwota, kredyt.okres, oprocentowanie);
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

        for (let index = 1; index < kredyt.okres * 12; index++) {
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
        getRataStala,
    }
}
