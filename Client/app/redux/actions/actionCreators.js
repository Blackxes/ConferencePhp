/**********************************************************************************************
 * 
 * @File
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import * as AT from "./actionTypes.js";

export const requestUserLoginVerification = (values) => ({ type: AT.REQUEST_USER_LOGIN_VERIFICATION, pl: values });
export const receiveUserLoginVerification = (resp) => ({ type: AT.RECEIVE_USER_LOGIN_VERIFICATION, pl: resp });

export const requestUserLogin = (id) => ({ type: AT.REQUEST_USER_LOGIN, pl: id });

//---------------------------------------------------------------------------------------------
// notifications
export const requestCreateNotification = (message, type, duration) => ({ type: AT.REQUEST_CREATE_NOTIFICATION, pl: {message, type, duration} });
export const requestAddNotification = notifObject => ({ type: AT.REQUEST_ADD_NOTIFICATION, pl: notifObject });
export const requestHideNotification = id => ({ type: AT.REQUEST_HIDE_NOTIFICATION, pl: id });
export const receiveHideNotification = id => ({ type: AT.RECEIVE_HIDE_NOTIFICATION, pl: id });