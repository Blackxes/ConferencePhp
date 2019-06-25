/**********************************************************************************************
 * 
 * @File page header component
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import React from "react";

const Logo = ({ src, alt }) => (
	<div id="main-logo">
		<a href="/">
			<img src="uploads/emhashop-logo.png" alt="Logo" />
		</a>
	</div>
);

export default Logo;