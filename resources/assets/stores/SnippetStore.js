
var EventEmitter = require('events').EventEmitter;
var assign = require('object-assign');

var AppDispatcher = require('../dispatcher/AppDispatcher');
var SnippetConstants = require('../constants/SnippetConstants');
var RequestHelper = require('./RequestHelper');

var SINGLE_SNIPPET_LOADED = 'single_snippet_loaded';
var SNIPPETS_LIST_LOADED = 'snippets_list_loaded';
// var CHANGE_EVENT = 'change';

var _snippets = {};


/**
 * Create a snippet
 * @param  {string} text The content of the snippet
 */
function create(snippet, cb) {
  RequestHelper.post('/json/snippet', snippet, function(err, res){
    if(!err){
      _snippets[parseInt(res.id)] = {
        title: res.title,
        content: res.content,
        tags: res.tags
      };

      console.log(_snippets[parseInt(res.id)]);
    }
    cb(err,res);
  });
}

/**
 * Update a snippet
 * @param  {string} id
 * @param {object} updates An object literal containing only the data to be
 *     updated.
 */
function update(id, snippet, cb) {
  if(id.match(/[0-9]+/)){
    RequestHelper.put('/json/snippet/'+id, snippet, function(err, res){
      if(!err){
        _snippets[res.id] = res;
      }
      cb(err,res);
    });
  }
}

/**
 * Delete a TODO item.
 * @param  {string} id
 */
function destroy(id, cb) {
  if(id.match(/[0-9]+/)){
    RequestHelper.delete('/json/snippet/'+id, function(err, res){
      if(!err){
        delete _snippets[id];
      }
      cb(err,res);
    });
  }
}

// assign method is "object-assign" Class
// assign method is used to join object key and object key
// EXP) assign({foo: 0}, {bar: 1});  // {foo:0, bar:1}
var SnippetStore = assign({}, EventEmitter.prototype, {

  /**
   * Get the entire collection of TODOs.
   * @return {object}
   */
  all: function() {
  	return _snippets;
  },

  get: function(id) {
    try{
      if(typeof _snippets[id].content === 'undefined'){
        return null;
      }
    }catch(err){
      return null;
    }
    
  	return _snippets[id];
  },

  // query data from server and update local/memory data
  load: function(id, cb) {
    var _t = this;
  	if(typeof id === 'undefined'){
  		// load all snippets
  		RequestHelper.get('/json/snippet/', function(err, res){
        if(err){
          return;
        }
        _snippets = {};
        res.forEach(function(snippet){
          _snippets[snippet.id] = snippet;
        });
  			_t.emitChange(SNIPPETS_LIST_LOADED);

  		});
  	}else{
  		// load specific id
  		RequestHelper.get('/json/snippet/'+id, function(err, res){
        if(err){
          return;
        }
        _snippets[id] = res;
        _t.emitChange(SINGLE_SNIPPET_LOADED);
  		});
  	}
  },

  emitChange: function(change_event) {
    this.emit(change_event);
  },

  /**
   *  adding or removing onChange Listener for SingleSnippetLoaded and SnippetsListLoaded
   */
  addSingleSnippetLoadedListener: function(callback) {
    this.on(SINGLE_SNIPPET_LOADED, callback);
  },
  removeSingleSnippetLoadedListener: function(callback) {
    this.removeListener(SINGLE_SNIPPET_LOADED, callback);
  },

  addSnippetsListLoadedListener: function(callback) {
    this.on(SNIPPETS_LIST_LOADED, callback);
  },  

  removeSnippetsListLoadedListener: function(callback) {
    this.removeListener(SNIPPETS_LIST_LOADED, callback);
  }

});

// 
SnippetStore.load();

// Register callback to handle all updates
AppDispatcher.register(function(action) {
  var text;

  switch(action.actionType) {
    case SnippetConstants.SNIPPET_CREATE:
      if (text !== '') {
        create({
          title: action.title.trim(), 
          content: action.content, 
          tags: action.tags 
        } , function(err, res){
          SnippetStore.emitChange(SNIPPETS_LIST_LOADED);
          if(typeof action.callback === 'function'){
            action.callback(err, res);
          }
        });
      }
      SnippetStore.emitChange();
      break;

    case SnippetConstants.SNIPPET_UPDATE:
      update(action.id,{
        title: action.title, 
        content: action.content, 
        tags: action.tags 
      } , function(err, res){
        SnippetStore.emitChange(SINGLE_SNIPPET_LOADED);
        SnippetStore.emitChange(SNIPPETS_LIST_LOADED);
        if(typeof action.callback === 'function'){
          action.callback(err, res);
        }
      });
      SnippetStore.emitChange();
      break;

    case SnippetConstants.SNIPPET_DESTROY:
      destroy(action.id, function(err, res){
        SnippetStore.emitChange(SNIPPETS_LIST_LOADED);
        if(typeof action.callback === 'function'){
          action.callback(err, res);
        }
      });
      break;

    default:
      // no op
  }
});

module.exports = SnippetStore;