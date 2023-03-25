import React from "react";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import { Bar } from 'react-chartjs-2';

function HistoricalQuotesChart(props) {
  const [prices, setPrices] = React.useState(props.prices);
  ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
  );

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Bar Chart',
      },
    },
  };

  const labels = prices.map((item) => new Date(item.date * 1000).toUTCString());

  const data = {
    labels,
    datasets: [
      {
        label: 'Dataset Open',
        data: prices.map((item) => item.open),
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
      },
      {
        label: 'Dataset Close',
        data: prices.map((item) => item.close),
        backgroundColor: 'rgba(53, 162, 235, 0.5)',
      },
    ],
  };
  
  return (
    <>
      <Bar options={options} data={data} />
    </>
  );
}

export default HistoricalQuotesChart;
