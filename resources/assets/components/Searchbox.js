var React = require('react');

var SnippetStore = require('../stores/SnippetStore');
var KeyConstants = require('../constants/KeyConstants');


module.exports = React.createClass({

	getInitialState: function() {
		return {
			value: ''
		};
	}, 

	handleOnKeyUp: function(e) {
		if(e.keyCode == KeyConstants.KEY_ESC){
			e.target.blur();
		}else{
			this.setState({value: e.target.value});	
		}
	},

	render: function() {
		return (
			<div class="searchbox">
				<input id="keywords" type="text" onKeyUp={this.handleOnKeyUp} refs="input" defaultValue={this.state.value} placeholder={this.props.placeholder || ''} />
			</div>
		);	
	}

});