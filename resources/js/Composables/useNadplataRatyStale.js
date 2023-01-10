import {useHelpers} from "@/Composables/useHelpers";

export function useNadplataRatyStale(kredyt, nadplata) {
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

    function getNowaRataStala(kapitalDoSplaty, index) {
        let mn = kredyt.okres * 12;
        let oprocentowanie = toDecimal(parseFloat(getOprocentowanie()) / 12);
        let rata = kapitalDoSplaty * oprocentowanie * (oprocentowanie + 1) ** (mn - index) / (((oprocentowanie + 1) ** (mn - index)) - 1);

        return parseFloat(rata);
    }

    function getRataStala() {
        let mn = kredyt.okres * 12;
        let oprocentowanie = toDecimal(parseFloat(getOprocentowanie()) / 12);
        let rata = kredyt.kwotaKredytu * oprocentowanie * (oprocentowanie + 1) ** mn / (((oprocentowanie + 1) ** mn) - 1);

        return parseFloat(rata);
    }


    // skrocenie okresu kredytowania
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
                kapitalPo,
                nadplata[0] === undefined ? 0 : nadplata[0]
            ],
        ];

        for (let index = 1; index < kredyt.okres * 12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);
            let ostatniaNadplata = nadplata[index - 1] === undefined ? 0 : nadplata[index - 1];

            // todo: bledna zanwa przed splata
            let ostatniaKwotaPoSplacie = ostatniaRata[4] - ostatniaNadplata;

            let current = [
                ostatniaKwotaPoSplacie,
                czescOdsetkowa(oprocentowanie, ostatniaKwotaPoSplacie),
                czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaKwotaPoSplacie)),
                rataCalkowita,
                kapitalPoSplacie(ostatniaKwotaPoSplacie, czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaKwotaPoSplacie))),
                nadplata[index] === undefined ? 0 : nadplata[index]
            ];

            // jezeli rata calkowita jest mniejsza od kapitalu do splaty
            if (current[3] > current[0]) {
                current[3] = current[0] + current[1]; // rata calkowita
                current[2] = current[3] - current[1]; // czesc kapitalowa
                current[4] = current[0] - current[2]; // kapital po splacie
            }

            harmonogramKredytu.push(current);

            if (current[4] <= 0) break;
        }

        return harmonogramKredytu;
    }

    function getHarmonogramNadplataZmniejsenieWyskosciRaty() {
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
                kapitalPo,
                nadplata[0] === undefined ? 0 : nadplata[0]
            ],
        ];

        for (let index = 1; index < kredyt.okres * 12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);
            let ostatniaNadplata = nadplata[index - 1] === undefined ? 0 : nadplata[index - 1];

            // todo: bledna zanwa przed splata
            let ostatniaKwotaDoSplaty = ostatniaRata[4] - ostatniaNadplata;
            let current = [];

            if (ostatniaNadplata === 0) {
                current = [
                    ostatniaKwotaDoSplaty,
                    czescOdsetkowa(oprocentowanie, ostatniaKwotaDoSplaty),
                    czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaKwotaDoSplaty)),
                    rataCalkowita,
                    kapitalPoSplacie(ostatniaKwotaDoSplaty, czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaKwotaDoSplaty))),
                    nadplata[index] === undefined ? 0 : nadplata[index]
                ];
            } else {
                rataCalkowita = getNowaRataStala(ostatniaKwotaDoSplaty, index);
                current = [
                    ostatniaKwotaDoSplaty,
                    czescOdsetkowa(oprocentowanie, ostatniaKwotaDoSplaty),
                    czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaKwotaDoSplaty)),
                    rataCalkowita,
                    kapitalPoSplacie(ostatniaKwotaDoSplaty, czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaKwotaDoSplaty))),
                    nadplata[index] === undefined ? 0 : nadplata[index]
                ];
            }

            harmonogramKredytu.push(current);
        }

        return harmonogramKredytu;
    }

    return {
        getHarmonogram,
        getRataStala,
        getHarmonogramNadplataZmniejsenieWyskosciRaty
    }
}
