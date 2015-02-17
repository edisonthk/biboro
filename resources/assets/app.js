var React = require('react');
var Router = require('react-router');
var { Route, DefaultRoute, RouteHandler, Link } = Router;

var SnippetStore = require('./stores/SnippetStore');

var ShortcutHandler = require('./components/ShortcutHandler');
var SnippetList = require('./components/SnippetList');
var SnippetDetail = require('./components/SnippetDetail');
var Searchbox = require('./components/Searchbox');

var App = React.createClass({
  render: function () {
    return (
      <div className="nav">
        <div className="brand-name">
          <Link to="snippets">Home</Link>
        </div>
        <div className="nav-field">
          <Searchbox />  
          <ul>
            <li><Link to="dashboard">Dashboaffffffrd</Link></li>
            <li><Link to="snippets">Home</Link></li>
          </ul>
        </div>
        <RouteHandler/>
      </div>
    );
  }
});

var Snippets = React.createClass({
  render: function(){
      return (
        <div className="snippets-field">
          <SnippetList className='lists' />
          <div className='detail'>
            <RouteHandler />
          </div>
        </div>
      );
    }
});


var Dashboard = React.createClass({
  render: function () {
    return <h1>Dashboard</h1>;
  }
});

var Form = React.createClass({

  mixins: [ Router.Navigation ],

  statics: {
    willTransitionFrom: function (transition, element) {
      if (element.refs.userInput.getDOMNode().value !== '') {
        if (!confirm('You have unsaved information, are you sure you want to leave this page?')) {
          transition.abort();
        }
      }
    }
  },

  handleSubmit: function (event) {
    event.preventDefault();
    this.refs.userInput.getDOMNode().value = '';
    this.transitionTo('/');
  },

  render: function () {
    return (
      <div>
        <form onSubmit={this.handleSubmit}>
          <p>Click the dashboard link with text in the input.</p>
          <input type="text" ref="userInput" defaultValue="ohai" />
          <button type="submit">Go</button>
        </form>
      </div>
    );
  }
});

var routes = (
  <Route handler={App}>
    <DefaultRoute handler={Snippets}/>
    <Route name="dashboard" handler={Dashboard}/>
    <Route name="snippets" handler={Snippets}>
      <Route name="snippet" path="/snippet/:id" handler={SnippetDetail} />
    </Route>
  </Route>
);

Router.run(routes, function (Handler) {
  React.render(
    <div>
      <ShortcutHandler />
      <Handler/>
    </div>
  , document.getElementById('example'));
});
