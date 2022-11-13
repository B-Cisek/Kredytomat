import { useRataCalkowitaStala } from "@/Composables/useRataCalkowitaStala.js";

export function useHarmonogram() {
    const { rataStala, toDecimal  } = useRataCalkowitaStala();

    function czescOdsetkowa(oprocentowanie, kapitalDoSplaty) {
        let odsetkowa = ((toDecimal(oprocentowanie)/12) * kapitalDoSplaty);
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

    function harmonogram(kwotaKredytu, n, oprocentowanie) {
        let kwota = parseFloat(kwotaKredytu);
        let rataCalkowita = rataStala(kwota, n, oprocentowanie);
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



        for (let index = 1; index < n*12; index++) {
            let ostatniaRata = harmonogramKredytu.at(-1);
            let current = [
                ostatniaRata[4],
                czescOdsetkowa(oprocentowanie, ostatniaRata[4]),
                czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaRata[4])),
                rataCalkowita,
                kapitalPoSplacie(ostatniaRata[4],czescKapitalowa(rataCalkowita, czescOdsetkowa(oprocentowanie, ostatniaRata[4])))
            ];

            harmonogramKredytu.push(current);
        }

        return harmonogramKredytu;
    }

    function kosztKredytu(harmonogram) {
        let szumaKoszt = 0.00;
        for (let index = 0; index < harmonogram.length; index++) {
            szumaKoszt +=harmonogram[index][1];
        }
        return szumaKoszt;
    }


    return {
        harmonogram,
        kosztKredytu
    }
}
