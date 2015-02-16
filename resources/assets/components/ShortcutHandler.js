// ShortcutHandler.js
// This components is not used as view, it is used as Handler

var React = require('react');
var KeyConstants = require('../constants/KeyConstants');

module.exports = React.createClass({
	componentDidMount: function() {
		window.addEventListener('keyup', this.shortcutHandle,false);
	},
	componentDidUnmount: function(){
		window.removeEventListener('keyup', this.shortcutHandle);
	},
	shortcutHandle: function(e) {
		var kwElement = document.getElementById("keywords");
		var keyPressed = e.keyCode;

		if( (keyPressed >= KeyConstants.KEY_0 && keyPressed <= KeyConstants.KEY_9) || 
			( !(e.ctrlKey || e.metaKey) && keyPressed >= KeyConstants.KEY_A && keyPressed <= KeyConstants.KEY_Z )
			|| keyPressed == 219 || keyPressed == 221 ){
			kwElement.focus();
		}
		// console.log("KEY : "+event.keyCode);
	},
	render: function() {
		return (
			<div className='shortcut_handle'></div>
		);
	}
});
