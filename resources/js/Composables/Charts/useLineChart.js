import {ref} from "vue";

export function useLineChart() {
    const chartData = ref({
        labels: [],
        datasets: [
            {
                label: "Rata odsetkowa",
                fill: true,
                data: [],
                backgroundColor: '#DF2935',

            },
            {
                label: "Rata kapitałowa",
                fill: true,
                data: [],
                backgroundColor: '#1cb027',
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
