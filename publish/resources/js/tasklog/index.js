import React from 'react';
import ReactDOM from 'react-dom';
import App from "./App";
import {BrowserRouter} from "react-router-dom";

require('../bootstrap');


ReactDOM.render(
    <React.StrictMode>
        <BrowserRouter>
            <App/>
        </BrowserRouter>
    </React.StrictMode>,
    createReactDiv()
);

function createReactDiv() {
    const container = document.querySelector("#horizon .container .row .col-10");
    const div = document.createElement("div");
    div.id = "reactRoot";
    container.appendChild(div);
    return div;
}
