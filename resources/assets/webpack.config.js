var fs = require('fs');
var path = require('path');
var webpack = require('webpack');

module.exports = {
  devtool: "inline-source-map",
  entry: {
    bundle: './app.js'
  },

  output: {
    path: __dirname + '/../../public/js/',
    filename: '[name].js',
  },

  module: {
    loaders: [
      { test: /\.js$/, loader: 'jsx-loader?harmony' }
    ]
  },

  resolve: {
    alias: {
      'react-router': __dirname + '/../modules/index'
    },
    extensions: ['', '.js', '.jsx']
  },

  plugins: [
    // new webpack.DefinePlugin({
    //   'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    // })
  ]

};
