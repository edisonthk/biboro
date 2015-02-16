var AppDispatcher = require('../AppDispatcher');
var EventEmitter = require('events').EventEmitter;
var ActionTypes = require('../constants/ActionTypes');
var merge = require('react/lib/merge');
var AuthActions = require('../actions/AuthActions');
var config = require('../config');
var superagent = require('superagent');
 
var SIGNIN_SUCCESS_EVENT = 'signinSuccess';
 
function signin(data) {
 
  superagent
    .post(config.apiRoot + '/signin')
    .send(data)
    .set('Accept', 'application/json')
    .end(function(res) {
      if (res.ok) {
         AuthActions.signinSuccess({});
       } else {
 
       }
    });
 
}
 
var AuthStore = merge(EventEmitter.prototype, {
  emitSigninSuccess: function() {
    this.emit(SIGNIN_SUCCESS_EVENT);
  },
  addSigninSuccessListener(callback) {
    this.on(SIGNIN_SUCCESS_EVENT, callback);
  },
  removeSigninSuccessListener(callback) {
    this.removeListener(SIGNIN_SUCCESS_EVENT, callback);
  }
});
 
AppDispatcher.register(function(payload) {
  var action = payload.action;
 
  switch(action.actionType) {
    case ActionTypes.AUTH_SIGNIN:
      signin(action.data);
      break;
 
    case ActionTypes.AUTH_SIGNIN_SUCCESS:
      AuthStore.emitSigninSuccess();
    default:
      return true;
  }
 
  return true; // No errors.  Needed by promise in Dispatcher.
});
 
module.exports = AuthStore;