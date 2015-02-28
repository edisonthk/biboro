// React modules
var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

// Application modules
var SnippetStore = require('./stores/SnippetStore');
var ShortcutHandler = require('./components/ShortcutHandler');
var SnippetList = require('./components/SnippetList');
var SnippetDetail = require('./components/SnippetDetail');
var Searchbox = require('./components/Searchbox');
var SnippetEditor = require('./components/SnippetEditor');
var SnippetConstants = require('./constants/SnippetConstants');
var AuthActions = require('./actions/AuthActions');
var AuthStore = require('./stores/AuthStore');
var SnippetDashboard = require('./components/SnippetDashboard');

// App class
// The main of the whole codegarage application is handle here
var App = React.createClass({
  getInitialState: function() {
    return this.getAuthState();
  },
  getAuthState: function() {
    return {
      logined: AuthStore.isLogined()
    };
  },
  componentDidMount: function() {
    AuthStore.addSigninSuccessListener(this._onSigninSuccess);
  },
  componentWillUnmount: function() {
    AuthStore.removeSigninSuccessListener(this._onSigninSuccess);
  },
  _onSigninSuccess: function() {
    this.setState(this.getAuthState());
  },
  render: function () {

    var _secret_tabs = (
        <li><button onClick={AuthActions.signIn}>ログイン</button></li>
      );

    if(this.state.logined){
      _secret_tabs = [
        (<li><Link to="editor" params={{type: 'new'}}>新規作成</Link></li>),
        (<li><button onClick={AuthActions.signOut}>ログアウト</button></li>)
      ];
    }

    // If 
    return (
      <div className="app">
        <ShortcutHandler />
        <div className="nav">
          <div className="brand-name">
            <Link to="snippets">Home</Link>
          </div>
          <div className="nav-field">
            <Searchbox placeholder="Search ..." />  
            <ul>
              {_secret_tabs}
            </ul>
          </div>
        </div>
        <RouteHandler/>
      </div>
    );
  }
});

var Snippets = React.createClass({
  render: function(){
    //　
      return (
        <div className='snippets-field'>
          <SnippetList className='lists' />
          <div className='detail'>
            <RouteHandler />
          </div>
        </div>
      );
    }
});

var routes = (
  <Route handler={App}>
    <DefaultRoute handler={Snippets}/>

    <Route name="editor" handler={SnippetEditor} >
      <Route name="editor_update" path="/editor/snippet/:id" />
    </Route>

    <Route name="snippets" handler={Snippets}>
      <DefaultRoute handler={SnippetDashboard} />
      <Route name="snippet" path="/snippet/:id" handler={SnippetDetail} />
    </Route>
  </Route>
);

Router.run(routes, function (Handler) {
  React.render(
    <div>
      <Handler/>
    </div>
  , document.getElementById('example'));
});
