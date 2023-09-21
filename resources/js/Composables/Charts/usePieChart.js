import {ref} from "vue";

export function usePieChart(amountOfCredit, equalInstallmentCost, decreasingInstallmentCost, commissionResult) {
    const dataEqualInstallment = ref({
        labels: ['Kwota kredytu', 'Odsetki', 'Prowizja banku'],
        datasets: [
            {
                data: [amountOfCredit, equalInstallmentCost, commissionResult],
                backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
            },
        ],
    });

    const options = ref({
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
        }
    });

    const dataDecreasingInstallment = ref({
        labels: ['Kwota kredytu', 'Odsetki', 'Prowizja banku'],
        datasets: [
            {
                data: [amountOfCredit, decreasingInstallmentCost, commissionResult],
                backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
            },
        ],
    });

    return {
        dataEqualInstallment,
        options,
        dataDecreasingInstallment
    }
}
