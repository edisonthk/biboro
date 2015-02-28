// React modules
var React = require('react');
var Router = require('react-router');
var Showdown = require('../../modules/showdown.js');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

// Application modules
var SnippetStore = require('../stores/SnippetStore');
var SnippetAction = require('../actions/SnippetActions')
var AuthStore = require('../stores/AuthStore');

var converter = new Showdown.converter();

module.exports = React.createClass({

	mixins: [ Router.Navigation, Router.State ],

	getInitialState: function() {
		return this.getStateFromStore();
	},

	getStateFromStore: function(_loading) {
		var _id = this.getParams().id;
		var _snippet = SnippetStore.get(_id);
		if(_snippet == null){
			SnippetStore.load(_id);
		}

		if(typeof _loading === 'undefined'){
			_loading = false;
		}

		return {
			snippet: _snippet,
			loading: _loading
		};
	},

	_onChange: function() {
		var _id = this.getParams().id;
		var _snippet = SnippetStore.get(_id);
		
		this.setState(this.getStateFromStore());
	},
	componentWillReceiveProps: function () {
		var _id = this.getParams().id;
		SnippetStore.load(_id);

	},

	componentDidMount: function () {
		SnippetStore.addSingleSnippetLoadedListener(this._onChange);
	},

	componentWillUnmount: function () {
		SnippetStore.removeSingleSnippetLoadedListener(this._onChange);
	},
	moveToUpdatePage: function() {
		this.transitionTo("editor_update",{id: this.getParams().id});
	},
	destroySnippet: function() {
		var _t = this;
		var idBeforeDelete = _t.getParams().id;

		// before delete action, get next current snippet id
		// 
		var idAfterDelete;
		var lastId;
		var _snippets = SnippetStore.all();
		var _next = false;
		for(var id in _snippets){
			if(_next){
				idAfterDelete = id;
			 	_next = false;
			}
			if(id === idBeforeDelete){
				_next = true;
			}else{
				lastId = id;
			}
		}
		if(typeof idAfterDelete !== "string"){
			idAfterDelete = lastId;
		}

		var _snippet = _t.state.snippet;
		SnippetAction.destroy(_snippet.id, function(err, res){
			// After delete action, if current id still exists don't transition
			var _exists = false;
			_snippets = SnippetStore.all();
			for(var id in _snippets){
				if(id === idBeforeDelete){
					_exists = true;
					break;
				}
			}
			if(!_exists){
				_t.transitionTo('snippet',{id: idAfterDelete});
			}
		});
	},

	render: function() {

		

		var _content = "",
			_title = "",
			_metadata,
			_tags = [],
			_snippet = this.state.snippet,
			userLogined = AuthStore.getUserinfo()
			;
		
		if(_snippet){
			// 
			_title = _snippet.title;
			_content = converter.makeHtml(_snippet.content);

			if(userLogined && this.state.snippet.editable){
				// if user is editable to this snippet, show editable tools
				// such as modify button, delete button
				_metadata = [
					(<span>あなた</span>),
					(<span>{_snippet.updated_at}</span>),
					(<button onClick={this.moveToUpdatePage}>編集</button>),
					(<button onClick={this.destroySnippet}>削除</button>)
				];

			}else{
				_metadata = [
					(<span>{this.state.snippet.creator_name}</span>),
					(<span>{this.state.snippet.updated_at}</span>)
				];
				
			}	
			
			// show tags of specific snippet
			_tags = [];
			for (var i = 0; i < this.state.snippet.tags.length; i++) {
				_tags.push(<li>{this.state.snippet.tags[i].name}</li>);
			};
		}
		

		return (
			<div className="snippet_detail">
				<h1>{_title}</h1>
				<div className="snippet_metadata">{_metadata}</div>
				<ul className="snippet_tags">{_tags}</ul>
				<div className="content" dangerouslySetInnerHTML={{ __html: _content }} />
			</div>
		);
	}
});