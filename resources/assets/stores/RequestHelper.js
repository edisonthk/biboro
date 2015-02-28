var request = require("superagent");

module.exports = {
	
	post: function (path, data , resultHandler) {
		resultHandler = resultHandler || function(result) { return result; };
		request.post(path)
			.set("Accept", "application/json")
			.type("json")
			.send(data)
			.end(function(err, res) {
				if(err) return resultHandler(err);
				if(res.status !== 200)
					return resultHandler(new Error("Request failed with " + res.status + ": " + res.text), res.body);
				resultHandler(null, res.body);
			});
	},

	put: function (path, data , resultHandler) {
		resultHandler = resultHandler || function(result) { return result; };
		request.put(path)
			.set("Accept", "application/json")
			.type("json")
			.send(data)
			.end(function(err, res) {
				if(err) return resultHandler(err);
				if(res.status !== 200)
					return resultHandler(new Error("Request failed with " + res.status + ": " + res.text), res.body);
				resultHandler(null, res.body);
			});
	},

	get: function (path, resultHandler) {
		resultHandler = resultHandler || function(result) { return result; };
		request.get(path)
			.set("Accept", "application/json")
			.type("json")
			.end(function(err, res) {
				if(err) return resultHandler(err);
				if(res.status !== 200)
					return resultHandler(new Error("Request failed with " + res.status + ": " + res.text), res.body);
				resultHandler(null, res.body);
			});
	},

	delete: function (path, resultHandler) {
		resultHandler = resultHandler || function(result) { return result; };
		request.del(path)
			.set("Accept", "application/json")
			.type("json")
			.end(function(err, res) {
				if(err) return resultHandler(err);
				if(res.status !== 200)
					return resultHandler(new Error("Request failed with " + res.status + ": " + res.text), res.body);
				resultHandler(null, res.body);
			});
	},

};