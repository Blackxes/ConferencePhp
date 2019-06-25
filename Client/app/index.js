/**********************************************************************************************
 * 
 * @File
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import React from "react";
import ReactDOM from "react-dom";
import $ from "jquery";

import { Provider } from "react-redux";
import store from "./redux/store.js";

// import UserLogin from "./components/UserLogin.jsx";

require( "./styles/index.scss" );

import path from "path";

const idContainer = [
	{ id: "user-login", component: "UserLogin" },
	{ id: "header", component: "Header" }
];

const prod = process.env.NODE_ENV == "production";

$(document).ready( () => {
	idContainer.forEach( ({ id, component, props }) => {
		const DomContainer = document.getElementById( id );

		if ( !DomContainer )
			return !prod ? console.log( `container '${id}' not found` ) : true;
		
		const Component = require( "./container/" + component + ".jsx").default;
	
		if ( !Component )
			return !prod ? console.log( `component '${component}' not found` ) : true;
	
		ReactDOM.render(
			<Provider store={store}>{React.createElement( Component, props )}</Provider>, DomContainer
		);
	});
});