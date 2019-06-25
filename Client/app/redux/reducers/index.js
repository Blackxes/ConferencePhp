/**********************************************************************************************
 * 
 * @File combines and returns all reducers
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import { combineReducers } from "redux";

import notificationReducer from "./notificationReducer";

const reducers = {
	notifications: notificationReducer
}

export default ( !Object.entries(reducers).length )
	? () => {}
	: combineReducers( reducers );