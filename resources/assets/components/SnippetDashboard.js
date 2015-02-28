// React modules
var React = require('react');

// Application modules
var AuthStore = require('../stores/AuthStore');

module.exports = React.createClass({
	// 
	// Two case, show the views respectively either user is logined or not
	// 
	// (1) Not logined 
	//		* create new private or public snippets note require to logined
	// 		● searching snippets by using shortcut a-zA-Z
	// 		● select snippet by click left menu bar or shortcut 0-9
	//		● for more, by using shortcut (?)
	//
	// (2) Logined but new
	//		* create new private or public snippets by click the button on top or use shortcut Cmd + N
	//		* how to modify dashboard
	// 		● searching snippets by using shortcut a-zA-Z
	// 		● select snippet by click left menu bar or shortcut 0-9
	//		● for more, by using shortcut (?)
	//
	// (3) logined but experiences with many snippet notes
	//		* show created snippet
	//  	* modify dashboard
	// 
	render: function() {
		return (
			<div>
				<h1>This is dashboard</h1>
			</div>
		);
	}
});