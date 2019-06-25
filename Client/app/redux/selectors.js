/**********************************************************************************************
 * 
 * @File
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

export const getNewNotificationId = store => store.notifications.idCounter;

export const getNotifications = store => store.notifications.list;
export const getNotification = (store, id) => store.notifications.list.filter( (v) => v.id == id );