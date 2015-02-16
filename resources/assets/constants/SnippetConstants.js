// keymirror
var keyMirror = require('keymirror');

// Map same value as key
// Exp) SNIPPET_CREATE == "SNIPPET_CREATE"
module.exports = keyMirror({
  SNIPPET_CREATE: null,
  SNIPPET_DESTROY: null,
  SNIPPET_UPDATE: null
});