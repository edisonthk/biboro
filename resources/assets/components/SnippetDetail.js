var React = require('react');
var Router = require('react-router');
var Showdown = require('../../modules/showdown.js');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

var SnippetStore = require('../stores/SnippetStore');

var converter = new Showdown.converter();

module.exports = React.createClass({

	mixins: [ Router.Navigation, Router.State ],

	getInitialState: function() {
		return this.getStateFromStore();
	},

	getStateFromStore: function() {
		var _id = this.getParams().id;
		var _snippet = SnippetStore.get(_id);
		if(_snippet == null){
			SnippetStore.load(_id);
		}
		return {
			snippet: _snippet
		};
	},

	_onChange: function() {
		this.setState(this.getStateFromStore());
	},
	componentWillReceiveProps: function () {
	    this.setState(this.getStateFromStore());
	},

	componentDidMount: function () {
		SnippetStore.addChangeListener(this._onChange);
	},

	componentWillUnmount: function () {
		SnippetStore.removeChangeListener(this._onChange);
	},

	render: function() {
		var _content = "";
		var _title = "";
		if(this.state.snippet){
			_title = this.state.snippet.title;
			_content = converter.makeHtml(this.state.snippet.content);
		}
		return (
			<div className="snippet_detail">
				<h1>詳細 - {_title}</h1>
				<div className="content" dangerouslySetInnerHTML={{ __html: _content }} />
			</div>
		);
	}
});