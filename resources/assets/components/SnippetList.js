var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

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
		SnippetStore.addChangeListener(this._onChange);
	},
	componentWillUnmount: function() {
		SnippetStore.removeChangeListener(this._onChange);
	},
	_onChange: function() {
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
				items.push(<li><Link to="snippet" params={{id: _snippet.id}}>{_snippet.title}</Link></li>);
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