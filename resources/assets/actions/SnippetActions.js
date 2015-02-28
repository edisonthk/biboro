// AuthActions
// ======================
// Anything that relate to authentication actions is handle here
// such as user login or logout. It is build based on AuthConstants and AuthStore.
// 

// Application modules
var AppDispatcher = require('../dispatcher/AppDispatcher');
var SnippetConstants = require('../constants/SnippetConstants');

module.exports = {
	destroy: function(id, cb) {
		AppDispatcher.dispatch({
			actionType: SnippetConstants.SNIPPET_DESTROY,
			id: id,
			callback: cb
		});
	},

	update: function(id, snippet,cb) {
		AppDispatcher.dispatch({
			actionType: SnippetConstants.SNIPPET_UPDATE,
			id: id,
			title: snippet.title,
			content: snippet.content, 
			tags: snippet.tags,
			callback: cb
		});	
	},

	create: function(snippet, cb) {
		AppDispatcher.dispatch({
			actionType: SnippetConstants.SNIPPET_CREATE,
			title: snippet.title,
			content: snippet.content, 
			tags: snippet.tags,
			callback: cb
		});		
	},
};