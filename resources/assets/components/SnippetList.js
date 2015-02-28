// React modules
var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

// Application modules
var SnippetStore = require('../stores/SnippetStore');

function getStateFromStores() {
	return {
		snippets: SnippetStore.all()
	};
}

module.exports = React.createClass({
	getInitialState: function() {
		return getStateFromStores();
	},
	componentDidMount: function() {
		SnippetStore.addSnippetsListLoadedListener(this._onChange);
	},
	componentWillUnmount: function() {
		SnippetStore.removeSnippetsListLoadedListener(this._onChange);
	},
	_onChange: function() {
		console.log("_onChange");
		console.log(getStateFromStores());
		this.setState(getStateFromStores());
	},
	render: function(){
		var isEmpty = true;
		for(var key in this.state.snippets){
			isEmpty = false;
			break;
		}

		var items = [];
		if(!isEmpty){
			for(var key in this.state.snippets){
				var _snippet = this.state.snippets[key];
				if(_snippet){
					items[_snippet.id] = (<li><Link to="snippet" params={{id: _snippet.id}}>{_snippet.title}</Link></li>);
				}
			}
		}

		return (
			<div className={this.props.className || ''} >
				<ul>
					{items}
				</ul>
			</div>
		);
	}
});