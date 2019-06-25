/**********************************************************************************************
 * 
 * @File
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

export class NotificationClass {
	constructor({ id, message, type = "ok", duration = 5000, autoDeletion = true }) {
		this.id = id;
		this.message = message;
		this.type = type;
		this.duration = duration;
		this.cancelled = false;
		this.hidden = false;
		this.autoDeletion = duration === null ? false : autoDeletion;
	}
}