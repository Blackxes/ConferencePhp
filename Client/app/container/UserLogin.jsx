/**********************************************************************************************
 * 
 * user model
 * 
 * @author: Alexander Bassov
 * 
/*********************************************************************************************/

import React from "react";
import { connect } from "react-redux";
import { Form, Field } from "react-final-form";

import * as AC from "../redux/actions/actionCreators";

import NotificationList from "../components/Notifications.jsx";

const isEmail = ( email ) => {
	return !email || email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/) === null
		? "Der Wert muss eine Email sein."
		: false;
}

class UserLogin extends React.Component {
	constructor(props) {
		super(props);
	}
	
	render() {
		const props = { ...this.props };

		return (
			<React.Fragment>
				<Form
					onSubmit={props.onSubmit}
					render={({ handleSubmit, valid, pristine }) => (
						<div className="conren-login-container container-box container-40-20">
							<form action="user/login" className="fullw" method="post" onSubmit={handleSubmit}>
								<div className="title-wrap center">
									<h2>Benutzer Login</h2>
								</div>
								<Field name="user_login" component="input" type="hidden" value="1" />
								<div className="form-group fullw">
									<div className="form-container">
										<div className="form-input">
											<Field 
												id="form-email"
												name="email"
												component="input"
												placeholder="Email"
												validate={(value) => isEmail(value)}
											>
												{({ input, meta, placeholder }) => (
													<React.Fragment>
														<input {...input} placeholder={meta.error && meta.touched ? meta.error : placeholder } />
														{ meta.error && meta.touched && <span>{meta.error}</span> }
													</React.Fragment>
												)}
											</Field>
										</div>
									</div>
									<div className="form-container">
										<div className="form-input">
											<Field id="form-password" name="password" component="input" type="password" placeholder="Passwort" />
										</div>
									</div>
									<div>
										<button type="submit" className="btn btn-warning container-nomargin fullw">Login</button>
										<NotificationList notifs={props.notifs} />
									</div>
								</div>
							</form>
						</div>
					)}
				/>
			</React.Fragment>
		);
	}
}

const mapStateToProps = (state) => ({
	notifs: state.notifications.list
});

const mapDispatchToProps = (dispatch) => ({
	onSubmit: (values) => dispatch(AC.requestUserLoginVerification(values))
});

export default connect(mapStateToProps, mapDispatchToProps)( UserLogin );