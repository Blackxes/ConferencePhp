/**********************************************************************************************
 * 
 * @File in here are generic helper functions
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import $ from "jquery";

//---------------------------------------------------------------------------------------------
// reduces the given array from jquery's default serializeArray function
// to a key: value pair object
export const serializeArrayAsKeyValuePairs = (form) => {
	return $(form).serializeArray().reduce( (s, c) => ({ ...s, [c.name]: c.value }), {} );
}