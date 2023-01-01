import {useHelpers} from "@/Composables/useHelpers";

export function useRataCalkowitaMalejaca() {
    const {toDecimal} = useHelpers();


    function rataMalejaca(czescKapitalowa, czescOdsetkowa) {
        return czescKapitalowa + czescOdsetkowa;
    }

    function czescOdsetkowa(kapitalDoSplaty, oprocentowanie) {
        oprocentowanie = toDecimal(parseFloat(oprocentowanie) / 12);
        return kapitalDoSplaty * oprocentowanie;
    }

    function czescKapitalowa(kwotaKapitalu, n) {
        let mn = n * 12;
        return kwotaKapitalu / mn;
    }

    return 1;
}
