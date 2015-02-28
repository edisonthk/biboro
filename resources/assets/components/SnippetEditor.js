// System Library
var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

// Application Library
var SnippetConstants = require('../constants/SnippetConstants');
var SnippetStore = require('../stores/SnippetStore');
var SnippetAction = require('../actions/SnippetActions');

var TYPE_UPDATE = 1,
	TYPE_CREATE = 2;


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

	componentDidMount: function() {
		window.addEventListener('keyup', this.shortcutHandler,false);
		// this.handleTextbox();
	},
	componentDidUnmount: function(){
		window.removeEventListener('keyup', this.shortcutHandler);
	},
	
	handleChange: function(_event) {
		var _t = _event.target;
		if(_t.name == 'title'){
			this.props.snippet.title = _t.value;
		}else if(_t.name == 'content'){
			this.props.snippet.content = _t.value;
		}else if(_t.name == 'tags'){
			this.props.snippet.tags = _t.value;
		}
	},
	handleTextbox: function() {
		// document.getElementsByClassName("input-group")
		var _cs = this.getDOMNode().getElementsByClassName("input-group");
		for (var i = 0; i < _cs.length; i++) {
			var _e = _cs[i].querySelector('input,textarea');
			if(_e.name == 'title'){
				_e.value = this.props.snippet.title;
			}else if(_e.name == 'content'){
				_e.value = this.props.snippet.content;
			}else if(_e.name == 'tags'){
				var _tags = this.props.snippet.tags;
				if(typeof _tags !== 'string'){
					_tags = "";
					for (var i = 0; i < this.props.snippet.tags.length; i++) {
						_tags += this.props.snippet.tags[i] + " ";
					};
				}
				_e.value = _tags.trim();
			}
		}
	},
	componentDidUpdate: function(prevProps, prevState) {
		this.handleTextbox();
	},


	render: function() {

		var _snippet = this.props.snippet;
		var tmp = "";
		for (var i = 0; i < _snippet.tags.length; i++) {
			tmp += _snippet.tags[i].name + " ";
		};
		_snippet.tags = tmp.trim();
		
		
		return (
			<div className='textbox-area'>
				<div className='input-group'>
					<label>タイトル</label>
					<input type='text' placeholder='タイトル' name='title' onChange={this.handleChange} defaultValue={_snippet.title}/>
				</div>
				<div className='input-group'>
					<label>内容</label>
					<textarea name='content' onChange={this.handleChange} defaultValue={_snippet.content} />
				</div>
				<div className='input-group'>
					<label>タグ</label>
					<input type='text' name='tags' placeholder='タグを入力' onChange={this.handleChange} defaultValue={_snippet.tags}/>
				</div>
			</div>
		);
	}

});


module.exports = React.createClass({
	submittedFlag: false,
	type: TYPE_CREATE,
	mixins: [ Router.Navigation, Router.State ],

	getInitialState: function() {
		return this.getStateFromStore();
	}, 

	getStateFromStore: function() {
		var _id = this.getParams().id,
			_snippet;
		

		if(_id){
			this.type = TYPE_UPDATE;
			_snippet = SnippetStore.get(_id);
			if(!_snippet){
				SnippetStore.load(_id);
			}
		}

		if(!_snippet){
			_snippet = {
				title: '',
				content: '',
				tags: ''
			};	
		}
		
		return {
			snippet: _snippet
		};
	},
	_onLoaded: function() {
		var _id = this.getParams().id;
		var _snippet = SnippetStore.get(_id);

		var _tags = "";
		for (var i = 0; i < _snippet.tags.length; i++) {
			_tags += _snippet.tags[i].name + " ";
		};

		this.setState({snippet: {
			title: _snippet.title, 
			content: _snippet.content,
			tags: _tags.trim()
		}});
	},
	componentDidMount: function () {
		SnippetStore.addSingleSnippetLoadedListener(this._onLoaded);
	},

	componentWillUnmount: function () {
		SnippetStore.removeSingleSnippetLoadedListener(this._onLoaded);
	},
	_submitCallback: function(err, res) {
		if(err){
			// failed to create new snippet
			console.error(err);
		}else{
			// success and redirect
			this.transitionTo('snippet',{id: res.id});	
		}
	},
	handleSubmit: function() {
		if(!this.submittedFlag){
			this.submittedFlag = true;
			if(this.type == TYPE_CREATE){
				var _snippet = this.state.snippet;

				_snippet.tags = _snippet.tags.trim().split(" ");
				SnippetAction.create(_snippet, this._submitCallback);
			}else if(this.type == TYPE_UPDATE){
				var _snippet = this.state.snippet;
				
				_snippet.tags = _snippet.tags.trim().split(" ");
				
				SnippetAction.update(this.getParams().id ,_snippet, this._submitCallback);
			}
		}
	},

	render: function() {
		// console.log(this.state.snippet);

		return (
			<div className='editor'>
				<TextboxArea snippet={this.state.snippet} />
				<PreviewArea snippet={this.state.snippet}/>
				<button onClick={this.handleSubmit}>新規作成</button>
			</div>
		);
	}
});