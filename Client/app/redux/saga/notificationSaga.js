/**********************************************************************************************
 * 
 * @File contains the saga of notifications
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import { put, takeEvery, select, cancel, delay, cancelled } from "redux-saga/effects";
import * as AT from "../actions/actionTypes.js";
import * as AC from "../actions/actionCreators.js";
// import * as PID from "../panels.js";
import * as SEL from "../selectors.js";
import * as Models from "../../logic/Models.js";

// watches the notification creation
export function* watchCreateNotification() {
	yield takeEvery( AT.REQUEST_CREATE_NOTIFICATION, function* ({ type, pl }) {
		
		// create and check notification
		const Notif = new Models.NotificationClass({ ...pl, id: yield select(SEL.getNewNotificationId) });
		
		yield put( AC.requestAddNotification(Notif) );
		
		// exit auto deletion when not enabled
		if ( !Notif.autoDeletion )
			yield cancel();
		
		yield put( AC.requestHideNotification(Notif.id) );
	});
}

// watches the hiding of a notification
export function* watchHideNotification() {
	try {
		yield takeEvery( AT.REQUEST_HIDE_NOTIFICATION, function* ({ type, pl }) {
			let Notif = yield select(SEL.getNotification, pl);
			
			// correct
			Notif = Notif.length ? Notif[0] : null;
			
			// not finding a notification is not an error
			if ( !Notif )
				throw new Error( `id '${pl}' not found` );
			
			yield delay( Notif.duration );
			yield put( AC.receiveHideNotification(pl) );
		});
	}
	catch( error ) {
		console.warn( `Hiding notification failed. Reason: ${error.message}` );
	}
}

// deletes a notifcation
// export function* watchDeleteNotification() {
// 	try {
// 		yield takeEvery( AT.REQUEST_DELETE_NOTIFICATION, function* ({ type, pl }) {
// 			const Notif = (yield select(SEL.getNotification, pl))[0];
			
// 			// cancel when already deleted
// 			if ( !Notif.length )
// 				throw new Error( `id '${pl}' not found` );
			
// 			yield put( AC.receiveDeleteNotification(pl) );
// 		});
// 	}
// 	catch( error ) {
// 		console.warn( `Deleting notification failed. Reason: ${error.message}` );
// 	}
// }