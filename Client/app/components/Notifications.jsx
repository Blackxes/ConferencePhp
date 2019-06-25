/**********************************************************************************************
 * 
 * @File lists all notifications
 * 
 * @Author: Alexander Bassov
 * 
**********************************************************************************************/

import React from "react";

const NotificationList = ({ notifs, onDelete }) => {
	return !(notifs && notifs.length) ? null : (
		<ul className="notifications">
			{ notifs.map((notif, i) => {
				const classNames = [
					"notification",
					`notification-${notif.type}`,
					notif.hidden ? "hidden" : "active"
				];
				
				return (
					<li key={i} className={classNames.join(" ")}>
						<p>{ notif.message }</p>
						<div className="btn">
							<span>x</span>
						</div>
					</li>
				);
			})}
		</ul>
	)
};

export default NotificationList;