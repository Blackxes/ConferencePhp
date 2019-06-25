/**********************************************************************************************
 * 
 * @File
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import * as AT from "../actions/actionTypes.js";

const initialState = {
	list: [],
	render: true,
	idCounter: 1
};

const notificationReducer = (state = initialState, {type, pl}) => {
	switch( type ) {
		case AT.REQUEST_ADD_NOTIFICATION:
			return { ...state, list: [...state.list, pl], idCounter: state.idCounter + 1 };
		break;
		case AT.RECEIVE_HIDE_NOTIFICATION:
			const index = state.list.findIndex( v => v.id == pl );
			let Notif = state.list[index];
			Notif.hidden = true;
			
			// replace updated notification
			const list = state.list;
			list.splice( index, 1, Notif );
			
			return { ...state, list: [...list ] };
		break;
		// case AT.REQUEST_DELETE_NOTIFICATION:
		// 	console.log( "delete" );
		// break;
	}
	return state;
} 

export default notificationReducer;