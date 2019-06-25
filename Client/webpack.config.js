var path = require( "path" );
var HtmlWebpackPlugin = require( "html-webpack-plugin" );
var webpack = require( "webpack" );

module.exports = {
	entry: {
		app: [ "@babel/polyfill", "./app/index.js" ]
	},
	output: {
		path: path.resolve( __dirname, "dist" ),
		filename: "index.bundle.js",
		publicPath: "/"
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				use: "babel-loader"
			},
			{
				test: /\.scss$/,
				use: [ "style-loader", "css-loader", "sass-loader" ]
			}
		]
	},
	mode: "development",
	plugins: [
		new HtmlWebpackPlugin({
			template: "app/index.html"
		}),
		new webpack.HotModuleReplacementPlugin()
	],
	devServer: {
		hot: false,
		historyApiFallback: true
	},
	resolve: {
        alias: {
            'react-redux': path.join(__dirname, '/node_modules/react-redux/dist/react-redux.min')
        }
	}
}