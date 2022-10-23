export function useInstallment(P,R,W) {

    function interestRate(margin, wibor, commission) {
        if (!(commission == 0)){

        }
        return margin + wibor + commission;
    }



    return { interestRate }
}
