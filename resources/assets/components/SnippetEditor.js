// System Library
var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

// Application Library
var SnippetConstants = require('../constants/SnippetConstants');
var SnippetStore = require('../stores/SnippetStore');


var PreviewArea = React.createClass({
	render: function() {

		var _style = {
			width: '200px',
			height: '200px',
			backgroundColor: '#3040e0'
		}

		return (
			<div className='Preview-area'>
				<div style={_style} ></div>
			</div>
		);
	}
});

var TextboxArea = React.createClass({

	render: function() {

		return (
			<div className='textbox-area'>
				<div className='input-group'>
					<label>タイトル</label>
					<input type='text' placeholder='タイトル' defaultValue={this.props.snippet.title}/>
				</div>
				<div className='input-group'>
					<label>内容</label>
					<textarea onChange={this.handleChange}>{this.props.snippet.content}</textarea>
				</div>
				<div className='input-group'>
					<label>タグ</label>
					<input type='text' placeholder='タグを入力' defaultValue={this.props.snippet.tags}/>
				</div>
			</div>
		);
	}

});


module.exports = React.createClass({

	mixins: [ Router.Navigation, Router.State ],

	getInitialState: function() {
		return this.getStateFromStore();
	}, 

	getStateFromStore: function() {
		var _params = this.getParams();
		var _snippet = null;


		if(_params.type === SnippetConstants.SNIPPET_UPDATE && _params.id){
			var _id = this.getParams().id;	
			_snippet = SnippetStore.get(_id);
			if(_snippet == null){
				SnippetStore.load(_id);
			}
		}

		return {
			snippet: _snippet
		};
	},

	handleChange: function(event) {
		console.log(event.target)
	    this.setState({value: event.target.value.substr(0, 140)});
	},


	render: function() {


		if(this.state.snippet || this.state.snippet == null) {
			this.state.snippet = {
				title: '',
				content: '',
				tags: ''
			};
		}

		console.log(this.state.snippet);

		return (
			<div className='editor'>
				<TextboxArea snippet={this.state.snippet} />
				<PreviewArea snippet={this.state.snippet}/>
			</div>
		);
	}
});