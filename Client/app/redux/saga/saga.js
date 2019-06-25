/**********************************************************************************************
 * 
 * @File
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import $ from "jquery";
import { all, takeLatest, put, takeEvery, delay } from "redux-saga/effects";
import * as AT from "../actions/actionTypes.js";
import * as AC from "../actions/actionCreators.js";

import { watchCreateNotification, watchHideNotification } from "./notificationSaga.js";

function* watchUserLoginVerification() {
	const data = yield takeLatest( AT.REQUEST_USER_LOGIN_VERIFICATION, function* ({ type, pl }) {
		const resp = yield fetch( "api/user/login/verify", {
			method: "post",
			headers: { "Content-type": "application/json" },
			body: JSON.stringify( pl )
		});

		const data = yield resp.json();

		switch( data.type ) {
			case "ok":
				yield put( AC.requestCreateNotification(data.message + " Sie werden jeden Moment weitergeleitet.", "ok", null) );
				yield put( AC.requestUserLogin(data.data) );
				// location.reload();
			break;
			case "error":
				yield put( AC.requestCreateNotification(data.message, "error", null) );
			break;
			default:
				yield put( AC.requestCreateNotification("Es ist ein Fehler aufgetreten. Bitte laden Sie die Seite neu.", "error", null) );
		}
	});
}

function* watchUserLogin() {
	yield takeLatest( AT.REQUEST_USER_LOGIN, function* ({ type, pl }) {
		$.post( "api/user/login", pl )
		.then( (resp) => {
			// console.log( resp );

		});
		// console.log("done");
	});
}

//---------------------------------------------------------------------------------------------
export default function* rootSaga() {
	yield all([
		watchUserLogin(),
		watchUserLoginVerification(),
		watchCreateNotification(),
		watchHideNotification()
	]);
}