import {useHelpers} from "@/Composables/useHelpers";


export function useNadplataRatyMalejace(kredyt, nadplata) {
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

    function nowaCzescKapitalowa(kwotaKapitalu, n, liczbDni) {
        let mn = n * 12;
        return kwotaKapitalu / (mn - liczbDni);
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

    function getHarmonogramNadplataZmniejsenieWyskosciRaty() {
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
                kapitalPo,
                nadplata[0] === undefined ? 0 : nadplata[0]
            ],
        ];

        let ostatniaRataKapitalowa = kapitalowa;
        for (let index = 1; index < kredyt.okres * 12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);
            let ostatniaNadplata = nadplata[index - 1] === undefined ? 0 : nadplata[index - 1];
            let ostatniaKwotaDoSplaty = ostatniaRata[4] - ostatniaNadplata;

            let current = [
                ostatniaKwotaDoSplaty,
                czescOdsetkowa(ostatniaKwotaDoSplaty, oprocentowanie),
                ostatniaNadplata === 0
                    ? ostatniaRataKapitalowa
                    : nowaCzescKapitalowa(ostatniaKwotaDoSplaty,kredyt.okres,index),
                rataCalkowita(kapitalowa, czescOdsetkowa(ostatniaRata[4], oprocentowanie)),
                kapitalPoSplacie(ostatniaKwotaDoSplaty, ostatniaRataKapitalowa),
                nadplata[index] === undefined ? 0 : nadplata[index]
            ];

            if (ostatniaNadplata !== 0) ostatniaRataKapitalowa = current[2];
            current[3] = rataCalkowita(current[2],current[1]);
            current[4] = kapitalPoSplacie(current[0],current[2]);

            harmonogramKredytu.push(current);
        }

        return harmonogramKredytu;
    }

    function getHarmonogramSkrocenieOkresuKredytowania() {
        let oprocentowanie = getOprocentowanie();
        let kwota = parseFloat(kredyt.kwotaKredytu);
        let rataCalkowitaPierwsza = getPierwszaRata(kwota, kredyt.okres, oprocentowanie);
        let odsetkowa = czescOdsetkowa(kwota, oprocentowanie);
        let kapitalowa = czescKapitalowa(kwota, kredyt.okres);
        let kapitalPo = kapitalPoSplacie(kwota, kapitalowa);

        let harmonogramKredytu = [
            [
                kwota,
                odsetkowa,
                kapitalowa,
                rataCalkowitaPierwsza,
                kapitalPo,
                nadplata[0] === undefined ? 0 : nadplata[0]
            ],
        ];


        for (let index = 1; index < kredyt.okres * 12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);
            let ostatniaNadplata = nadplata[index - 1] === undefined ? 0 : nadplata[index - 1];
            let ostatniaKwotaDoSplaty = ostatniaRata[4] - ostatniaNadplata;
            let current = [
                ostatniaKwotaDoSplaty,
                czescOdsetkowa(ostatniaKwotaDoSplaty, oprocentowanie),
                kapitalowa,
                rataCalkowita(kapitalowa, czescOdsetkowa(ostatniaKwotaDoSplaty, oprocentowanie)),
                kapitalPoSplacie(ostatniaKwotaDoSplaty, kapitalowa),
                nadplata[index] === undefined ? 0 : nadplata[index]
            ];

            harmonogramKredytu.push(current);

            if (current[4] <= 0) break;
        }

        return harmonogramKredytu;
    }

    return {
        getHarmonogramNadplataZmniejsenieWyskosciRaty,
        getHarmonogramSkrocenieOkresuKredytowania,
        getPierwszaRata
    };

}
