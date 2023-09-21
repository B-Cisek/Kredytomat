import {ref} from "vue";
import {useToastsStore} from "@/Composables/useToastsStore";

export function useCreditCalculation() {
    const loading = ref(false);

    const getSchedule = async (creditData, interestsRateChanges = {}, fees = {}, overpayments = {}, overpaymentType = 'none') => {
        loading.value = true;

        try {
            let res = await axios.post(route('get-schedule'), {
                typeOfInstallment: creditData.value.typeOfInstallment,
                date: {
                    year: creditData.value.date.year,
                    month: creditData.value.date.month
                },
                credit: {
                    amountOfCredit: creditData.value.amountOfCredit,
                    period: creditData.value.period,
                    periodType: creditData.value.periodType,
                    margin: creditData.value.margin,
                    wibor: Number(creditData.value.wibor),
                    commission: creditData.value.commission,
                    commissionType: creditData.value.commissionType
                },
                interestsRateChange: interestsRateChanges.value,
                fees: fees.value,
                overpayments: overpayments.value,
                overpaymentType: overpaymentType.value
            });

            loading.value = false;
            return res;
        } catch (error) {
            loading.value = false;
            console.error(error); // TODO: add message error to toast
        }
    }

    const getSchedulev2 = async (typeOfInstallment, date, amountOfCredit, period, periodType, margin, wibor, commission, commissionType, interestsRateChange = {}, fees = {}, overpayments = {}, overpaymentType = 'none') => {
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
                interestsRateChange: interestsRateChange,
                fees: fees,
                overpayments: overpayments,
                overpaymentType: overpaymentType
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
