/**********************************************************************************************
 * 
 * @File page header component
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import React from "react";

const Search = ({ props }) => (
	<div className="searchbar justify-self">
		<form className="flex" action="{{ conren_route_search }}" method="{{ method }}" target="{{ target }}">
			<input className="fullw" type="text" placeholder="Produkt finden .."/>
			<button className="btn-2 icon">
				<i className="fas fa-search"></i>
			</button>
		</form>
	</div>
);

export default Search;