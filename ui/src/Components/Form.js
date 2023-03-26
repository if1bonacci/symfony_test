import React, {useEffect, useState} from 'react';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import axiosInstance from '../Services/ApiCallService';
import {ThreeCircles} from 'react-loader-spinner';
import HistoricalQuotes from './HistoricalQuotes'
import HistoricalQuotesChart from './HistoricalQuotesChart';

function MyForm () {
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
  const [formErrors, setFormErrors] = useState([])

  const onChangeHandler = (event) => {
    const {name, value} = event
    setFormData((prev) => {

      return {...prev, [ name ]: value}
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

  const validate = async function (formData) {
    let formDataErrors = [];
    let currentDate = new Date().getTime();
    let startDateV = new Date(formData.startDate).getTime();
    let endDateV = new Date(formData.endDate).getTime();

    //symbol
    if (formData.symbol.length === 0 || formData.symbol === null || formData.symbol === undefined) {
      formDataErrors.push('Symbol is required field.')
    }

    let symbolIndex = await companies.find(e => e.Symbol === formData.symbol);
    if (symbolIndex === -1 || symbolIndex === undefined) {
      await formDataErrors.push('Symbol of company is incorrect.')
    }

    //email
    if (!formData.email.match(/^([\w.%+-]+)@([\w-]+\.)+([\w]{2,})$/i)) {
      formDataErrors.push('Email should be valid.')
    }
    if (formData.email.length === 0 || formData.email === null || formData.email === undefined) {
      formDataErrors.push('Email is required field.')
    }

    //startDate
    if (startDateV > currentDate || startDateV > endDateV) {
      formDataErrors.push('startDate should be less of equal than endDate and less or equal current date.')
    }

    //endDate
    if (endDateV > currentDate || startDateV > endDateV) {
      formDataErrors.push('endDate should be greater of equal than startDate and less or equal current date.')
    }
    //final
    await setFormErrors(formDataErrors);

    if (formDataErrors.length > 0) {
      throw new Error('invalid form');
    }
  };

  const popApiError = async (errorMessages) => {
    const listApiErrors = [];

    errorMessages.map((err) => {
      return listApiErrors.push(`${err[ 'propertyPath' ]}:  ${err[ 'message' ]}`);
    })

    await setFormErrors(listApiErrors);
  }

  const getListOfSymbols = function () {
    axiosInstance.get('/list-of-symbols')
      .then((response) => {
        setCompanies(response.data)
      }).catch((messages) => {
        let er = messages.response.data;
        if (er.hasOwnProperty('violations')) {
          popApiError(er.violations);
        } else {
          setFormErrors([er.error])
        }
    });
  }

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoaderStatus(true);
    setResponse([]);

    let data = {
      startDate: startDate,
      endDate: endDate,
      symbol: formData.symbol,
      email: formData.email,
    }
    await validate(data).then(async () => {
      await axiosInstance.post('/prices-list', {
        startDate: data.startDate.toISOString().split('T')[ 0 ],
        endDate: data.endDate.toISOString().split('T')[ 0 ],
        symbol: data.symbol,
        email: data.email,
      })
        .then((response) => {
          if (response.data.length > 0) {
            setResponse(response.data)
          } else {
            setFormErrors(['Nothing to show for the period'])
          }
        }).catch(function (error) {
          let er = error.response.data;
          if (er.hasOwnProperty('violations')) {
            popApiError(er.violations);
          } else {
            setFormErrors([er.error])
          }
        })
    }).catch((exp) => {
      console.log('error')
    }).finally(() => {
      setLoaderStatus(false);
    })
  }

  return (
    <>
      <div className="row">
        {formErrors.length > 0 && (
          <ul className="list-group" role="alert">
            {formErrors.map((error, key) => (
              <li className="list-group-item list-group-item-danger" key={key}>{error}</li>
            ))}
          </ul>
        )}
      </div>
      <div className="row">
        <div className="col-4">
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
                style={{display: 'block'}}
              >
                {companies.map(company => (
                  (company.Symbol && <option key={company.Symbol} value={company.Symbol}
                                             label={company[ 'Company Name' ]}>{company[ 'Company' +
                  ' Name' ]}</option>)
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
              {!loaderStatus && (<button type="submit" className="btn btn-primary">Submit</button>)}
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
        <div className="col-8">
          {response.length > 0 &&
            <HistoricalQuotes
              prices={response}
            />
          }
        </div>
      </div>
      <div className="row">
        {response.length > 0 &&
          <HistoricalQuotesChart
            prices={response}
          />
        }
      </div>
    </>
  );
}

export default MyForm;
