/**********************************************************************************************
 * 
 * @File page header component
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import React from "react";

import Logo from "../components/Logo.jsx";
import Search from "../components/Search.jsx";
import UserTeaser from "../components/UserTeaser.jsx";

class Header extends React.Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<React.Fragment>
				<Logo />
				<Search />
				<UserTeaser />
			</React.Fragment>
		);
	}
}

export default Header;