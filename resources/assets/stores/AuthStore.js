// Node modules
var EventEmitter = require('events').EventEmitter;
var superagent = require('superagent');
var assign = require('object-assign');


// React modules
var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;


// Application modules
var AppDispatcher = require('../dispatcher/AppDispatcher');
var AuthActions = require('../actions/AuthActions');
var AuthConstants = require('../constants/AuthConstants');
 
// if user is login successful, userinfo will not be null.
// 
var _userinfo = null;

var checkIfLogin = function(callback) {
  superagent
      .get('/account/signin')
      .set('Accept', 'application/json')
      .end(function(res) {
        callback(res);
      });
}

var signin = function() {

  if(_userinfo == null){
    checkIfLogin(function(res){
      console.log(res);

      if(success){
        // not login yet
        superagent
          .get('/account/signin')
          .set('Accept', 'application/json')
          .end(function(res) {
            if(res.auth_url){
              window.location.href=res.auth_url; 
            }else{
              console.error("something wrong in auth");
            } 
          });
      }else{
        // already login
        _userinfo = res.body;
      }
    });
  }else{
    console.log("already signin");
  }
}

var signout = function() {
  _userinfo = null;
  window.location.href="/account/signout"; 
}
 
var AuthStore = assign({}, EventEmitter.prototype, {
  getUserinfo: function() {
    return _userinfo;
  },
  isLogined: function() {
    return _userinfo && _userinfo !== null;
  }
  // emitSigninSuccess: function() {
  //   this.emit(SIGNIN_SUCCESS_EVENT);
  // },
  // addSigninSuccessListener(callback) {
  //   this.on(SIGNIN_SUCCESS_EVENT, callback);
  // },
  // removeSigninSuccessListener(callback) {
  //   this.removeListener(SIGNIN_SUCCESS_EVENT, callback);
  // }
});
 
AppDispatcher.register(function(payload) {
  var action = payload.action;
  console.log(action);
  switch(action.actionType) {
    case AuthConstants.SIGN_IN:
      signin();
      break;
 
    case AuthConstants.SIGN_OUT:
      signout();
      break;
    default:
      return true;
  }
 
  return true; // No errors.  Needed by promise in Dispatcher.
});
 
module.exports = AuthStore;