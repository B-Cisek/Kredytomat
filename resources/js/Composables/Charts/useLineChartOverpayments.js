import {ref} from "vue";

export function useLineChartOverpayments() {
    const chartData = ref({
        labels: [],
        datasets: [
            {
                label: "Odsetki po nadpłacie",
                fill: true,
                data: [],
                backgroundColor: 'rgba(28, 176, 39, 0.2)',
                borderColor: '#1cb027'
            },
            {
                label: "Odsetki bez nadpłaty",
                fill: true,
                data: [],
                backgroundColor: 'rgba(223, 41, 53, 0.2)',
                borderColor: '#DF2935'
            },
        ],
    });

    const options = ref({
        elements: {
            point: {
                pointRadius: 0
            }
        },
        plugins: {
            title: {
                display: false,
                text: 'Stacked'
            },
        },
        responsive: true,
        interaction: {
            intersect: false,
        },
        scales: {
            x: {
                stacked: true,
            },
            y: {
                ticks: {
                    beginAtZero: true,
                    stacked: true
                }
            }
        }
    });


    return {
        options,
        chartData
    }
}
