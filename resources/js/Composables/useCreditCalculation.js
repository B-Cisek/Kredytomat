import {ref} from "vue";

export function useCreditCalculation() {
    const loading = ref(false);

    const getSchedule = async (type, credit, interestsRateChanges = {}, fees = {}, overpayment = {}) => {
        loading.value = true;

        try {
            let res = await axios.post(route('get-schedule'), {
                typeOfInstallment: type,
                date: {
                    year: credit.value.date.year,
                    month: credit.value.date.month
                },
                credit: {
                    amountOfCredit: credit.value.amountOfCredit,
                    period: credit.value.period,
                    periodType: credit.value.periodType,
                    margin: credit.value.margin,
                    wibor: Number(credit.value.wibor),
                    commission: credit.value.commission,
                    commissionType: credit.value.commissionType
                },
                interestsRateChange: interestsRateChanges.value,
                fees: fees.value
            });

            loading.value = false;
            return res;
        } catch (error) {
            loading.value = false;
            console.error(error); // TODO: add message error to toast
        }
    }

    const getSchedulev2 = async (typeOfInstallment, date, amountOfCredit, period, periodType, margin, wibor, commission, commissionType) => {
        loading.value = true;

        try {
            let res = await axios.post(route('get-schedule'), {
                typeOfInstallment: typeOfInstallment,
                date: {
                    year: date.year,
                    month: date.month
                },
                credit: {
                    amountOfCredit: amountOfCredit,
                    period: period,
                    periodType: periodType,
                    margin: margin,
                    wibor: Number(wibor),
                    commission: commission,
                    commissionType: commissionType
                },
            });

            loading.value = false;
            return res;
        } catch (error) {
            loading.value = false;
            console.error(error); // TODO: add message error to toast
        }
    }

    return {
        loading,
        getSchedule,
        getSchedulev2
    }
}
