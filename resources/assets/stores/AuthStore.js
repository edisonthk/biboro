// Node modules
var EventEmitter = require('events').EventEmitter;
var Assign = require('object-assign');


// React modules
var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;


// Application modules
var AppDispatcher = require('../dispatcher/AppDispatcher');
var AuthActions = require('../actions/AuthActions');
var AuthConstants = require('../constants/AuthConstants');
var RequestHelper = require('./RequestHelper');
 
// if user is login successful, userinfo will not be null.
// 
var _userinfo = null;
var SIGNIN_SUCCESS_EVENT = "SIGNIN_SUCCESS_EVENT";

var checkIfLogin = function(callback) {
  RequestHelper.get('/account/userinfo',function(err, res){
    if(err){
      _userinfo = null;
    }else{
      _userinfo = res;
      AuthStore.emitSigninSuccess();
    }

    if(typeof callback !== 'undefined'){
      callback(err, res);  
    }
  });
};
checkIfLogin();

var signin = function() {

  if(_userinfo == null){
    checkIfLogin(function(res){

      if(_userinfo == null){
        // not login yet
        RequestHelper.get('/account/signin', function(err, res){
          if(res.auth_url){
            window.location.href=res.auth_url; 
          }else{
            console.error("something wrong in auth");
          } 
        });
      }else{
        // maybe already logined, check server if user session is available
        checkIfLogin();
      }
    });
  }else{
    console.log("already signin");
  }
};

var signout = function() {
  _userinfo = null;
  window.location.href="/account/signout"; 
};
 
var AuthStore = Assign({}, EventEmitter.prototype, {
  getUserinfo: function() {
    return _userinfo;
  },
  isLogined: function() {
    return _userinfo != null;
  },
  emitSigninSuccess: function() {
    this.emit(SIGNIN_SUCCESS_EVENT);
  },
  addSigninSuccessListener: function(callback) {
    this.on(SIGNIN_SUCCESS_EVENT, callback);
  },
  removeSigninSuccessListener: function(callback) {
    this.removeListener(SIGNIN_SUCCESS_EVENT, callback);
  }
});
 
AppDispatcher.register(function(action) {
  
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