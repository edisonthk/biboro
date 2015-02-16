
var EventEmitter = require('events').EventEmitter;
var assign = require('object-assign');

var AppDispatcher = require('../dispatcher/AppDispatcher');
var SnippetConstants = require('../constants/SnippetConstants');
var StoreHelper = require('./StoreHelper');


var CHANGE_EVENT = 'change';

var _snippets = {};


/**
 * Create a snippet
 * @param  {string} text The content of the snippet
 */
function create(text) {
  
  // _todos[id] = {
  //   id: id,
  //   complete: false,
  //   text: text
  // };
  console.log("create");
}

/**
 * Update a snippet
 * @param  {string} id
 * @param {object} updates An object literal containing only the data to be
 *     updated.
 */
function update(id, updates) {
  _todos[id] = assign({}, _todos[id], updates);
}

/**
 * Delete a TODO item.
 * @param  {string} id
 */
function destroy(id) {
  delete _todos[id];
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
  load: function(id) {
    var _t = this;
  	if(typeof id === 'undefined'){
  		// load all snippets
  		StoreHelper.readMultipleItems('/json/snippet/', function(err, res){
        if(res.forEach){
          _snippets = {};
          res.forEach(function(snippet){
            _snippets[snippet.id] = snippet;
          });
        }
  			_t.emitChange();
  		});
  	}else{
  		// load specific id
  		StoreHelper.readSingleItem('/json/snippet/'+id, function(err, res){
        _snippets[id] = res;
        _t.emitChange();
  		});
  	}
  },

  emitChange: function() {
    this.emit(CHANGE_EVENT);
  },

  /**
   * @param {function} callback
   */
  addChangeListener: function(callback) {
    this.on(CHANGE_EVENT, callback);
  },

  /**
   * @param {function} callback
   */
  removeChangeListener: function(callback) {
    this.removeListener(CHANGE_EVENT, callback);
  }
});

// 
SnippetStore.load();

// Register callback to handle all updates
AppDispatcher.register(function(action) {
  var text;

  switch(action.actionType) {
    case SnippetConstants.SNIPPET_CREATE:
      text = action.text.trim();
      if (text !== '') {
        create(text);
      }
      SnippetStore.emitChange();
      break;

    case SnippetConstants.SNIPPET_UPDATE:
      text = action.text.trim();
      if (text !== '') {
        update(action.id, {text: text});
      }
      SnippetStore.emitChange();
      break;

    case SnippetConstants.SNIPPET_DESTROY:
      destroy(action.id);
      SnippetStore.emitChange();
      break;

    default:
      // no op
  }
});

module.exports = SnippetStore;