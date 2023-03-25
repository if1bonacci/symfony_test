import React from "react";

function HistoricalQuotes(props) {
  const [prices, setPrices] = React.useState(props.prices);
  const [startDate, setStartDate] = React.useState(new Date(props.startDate).getTime());
  const [endDate, setEndDate] = React.useState(new Date(props.endDate).getTime());

  return (
    <>
      <table className="table">
        <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Date</th>
          <th scope="col">Open</th>
          <th scope="col">High</th>
          <th scope="col">Low</th>
          <th scope="col">Close</th>
          <th scope="col">Volume</th>
        </tr>
        </thead>
        <tbody>
          {prices.map((item, key) => (
            <tr key={key}>
              <th scope="row">{key + 1}</th>
              <td>{item.date}</td>
              <td>{item.open}</td>
              <td>{item.high}</td>
              <td>{item.low}</td>
              <td>{item.close}</td>
              <td>{item.volume}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}

export default HistoricalQuotes;
