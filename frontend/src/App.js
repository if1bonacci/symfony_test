import React from "react";
import 'bootstrap/dist/css/bootstrap.min.css';
import { Container, Row } from 'react-bootstrap';
import './App.css';
import MyForm from "./Components/Form";

function App() {

  return (
    <div className="row align-items-center" id="my-container-test">
      <div className="mx-auto col-10 col-md-8 col-lg-6">
        <div className="App">
          <Container>
            <Row>
              <h1> Test work </h1>
              <MyForm />
            </Row>
          </Container>
        </div>
      </div>
    </div>
  );
}

export default App;
