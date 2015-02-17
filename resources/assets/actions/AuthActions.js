// AuthActions
// ======================
// Anything that relate to authentication actions is handle here
// such as user login or logout. It is build based on AuthConstants and AuthStore.
// 

// Application modules
var AppDispatcher = require('../dispatcher/AppDispatcher');
var AuthConstants = require('../constants/AuthConstants');

module.exports = {
	signIn: function() {
		AppDispatcher.dispatch({
			actionType: AuthConstants.SIGN_IN
		});
	},

	signOut: function() {
		AppDispatcher.dispatch({
			actionType: AuthConstants.SIGN_OUT
		});	
	}
};