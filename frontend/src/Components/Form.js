import React, { useEffect, useState } from "react";
import DatePicker from 'react-datepicker';
import "react-datepicker/dist/react-datepicker.css";
import axiosInstance from '../Services/ApiCallService';
import { ThreeCircles } from  'react-loader-spinner';
import HistoricalQuotes from './HistoricalQuotes'

function MyForm() {
  let initialState = {
    symbol: 'GOOG',
    email: 'asdas@sadf.re'
  }

  const [formData, setFormData] = useState(initialState)
  const [startDate, setStartDate] = useState(new Date());
  const [endDate, setEndDate] = useState(new Date());
  const [companies, setCompanies] = useState([])
  const [loaderStatus, setLoaderStatus] = useState(false)
  const [response, setResponse] = useState([])
  const [formErrors, setFormErrors] = useState(null)

  const onChangeHandler = (event) => {
    const {name, value} = event
    setFormData((prev) => {

      return {...prev, [name]: value}
    })
  }
  const onChangeDate = (dates) => {
    const [start, end] = dates;
    setStartDate(start);
    setEndDate(end);
  };

  useEffect(() => {
    getListOfSymbols();
  }, []);
//--------------------------
  const validate = function (formData) {
    let formDataErrors = {
      symbol: [],
      email: [],
      startDate: [],
      endDate: []
    };
    let currentDate = new Date().getTime();
    let startDateV = new Date(formData.startDate).getTime();
    let endDateV = new Date(formData.endDate).getTime();
    setFormErrors(null);

    //symbol
    if (formData.symbol.length === 0 || formData.symbol === null || formData.symbol === undefined) {
      formDataErrors.symbol.push('Symbol is required field.')
    }

    let symbolIndex = companies.find(e => e.Symbol === formData.symbol);
    if (symbolIndex === -1) {
      formDataErrors.symbol.push('Symbol of company is incorrect.')
    }

    //email
    if (!formData.email.match(/^([\w.%+-]+)@([\w-]+\.)+([\w]{2,})$/i)) {
      formDataErrors.email.push('Email should be valid.')
    }
    if (formData.email.length === 0 || formData.email === null || formData.email === undefined) {
      formDataErrors.email.push('Email is required field.')
    }

    //startDate

    if ( startDateV > currentDate || startDateV > endDateV) {
      formDataErrors.startDate.push('startDate should be less of equal than endDate and less or equal current date.')
    }

    //endDate
    if ( endDateV > currentDate || startDateV > endDateV) {
      formDataErrors.endDate.push('endDate should be greater of equal than startDate and less or equal current date.')
    }

    //final
    setFormErrors(formDataErrors);
  };
//------------------------------
  const getListOfSymbols = function () {
    axiosInstance.get('/list-of-symbols')
      .then((response) => {
        setCompanies(response.data)
      }).catch((messages) =>{
      console.error(messages)
    });
  }

  const handleSubmit = (event) => {
    event.preventDefault();
    let data = {
      startDate: startDate.toISOString().split('T')[0],
      endDate: endDate.toISOString().split('T')[0],
      symbol: formData.symbol,
      email: formData.email,
    }

    validate(data);
    console.log(formErrors, 'res')
    // Validator(data)
    // setLoaderStatus(true);
    // setResponse([]);

    // Handle validations
    // axiosInstance.post('/prices-list',{
    //   startDate: startDate.toISOString().split('T')[0],
    //   endDate: endDate.toISOString().split('T')[0],
    //   symbol: formData.symbol,
    //   email: formData.email,
    // }). then((response) => {
    //   setLoaderStatus(false);
    //   setResponse(response.data)
    // }). catch(function (error) {
    //   setLoaderStatus(false);
    //   console.log(error)
    // })
  }

  return (
    <>
      <div className="row">
        <form
          onSubmit={handleSubmit}
          noValidate
          autoComplete="off"
        >
          <div className="mb-3">
            <label className="form-label">Company symbol</label>
            <select
              className="form-select"
              name="symbol"
              value={formData.symbol}
              onChange={(e) => onChangeHandler(e.target)}
              style={{ display: "block" }}
            >
              {companies.map(company => (
                (company.Symbol && <option key={company.Symbol} value={company.Symbol} label={company["Company Name"]}>{company["Company" +
                " Name"]}</option>)
              ))}
            </select>
          </div>
          <div className="mb-3">
            <label className="form-label">Date interval</label>
            <DatePicker
              className="form-control"
              disabled={loaderStatus}
              selected={startDate}
              onChange={onChangeDate}
              startDate={startDate}
              endDate={endDate}
              selectsRange
              inline
              maxDate={new Date()}
            />
          </div>
          <div className="mb-3">
            <label className="form-label">Email</label>
            <input
              type="email"
              disabled={loaderStatus}
              name="email"
              className="form-control"
              placeholder="Email address"
              onChange={(e) => onChangeHandler(e.target)}
              value={formData.email}
            />
          </div>
          <div>
            { !loaderStatus &&(<button type="submit" className="btn btn-primary">Submit</button>)}
            <ThreeCircles
              height="100"
              width="100"
              color="#4fa94d"
              wrapperStyle={{}}
              wrapperClass=""
              visible={loaderStatus}
              ariaLabel="three-circles-rotating"
            />
          </div>
        </form>
      </div>
      <div className="row">
        {response.length > 0 &&
          <HistoricalQuotes
            prices={response}
          />
        }
      </div>
    </>
  );
}

export default MyForm;
