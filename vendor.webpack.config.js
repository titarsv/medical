var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: {
        vendor: [
            'jquery',
            'bootstrap',
            'sweetalert2',
            'chosen-js',
            'slick-carousel',
            'magnific-popup',
            'jscrollpane',
            'lightgallery',
            './node_modules/bootstrap/dist/css/bootstrap.min.css',
            './resources/assets/modules/blanks/index.scss',
            './resources/assets/modules/chosen',
            './resources/assets/modules/fancybox',
            './resources/assets/modules/fancyselect',
            './resources/assets/modules/forms',
            './resources/assets/modules/jscrollpane',
            './resources/assets/modules/jslider',
            './resources/assets/modules/lightgallery',
            './resources/assets/modules/popup',
            './resources/assets/modules/slider'
        ]
    },
    output: {
        path: path.join(__dirname, '/resources/assets/vendor'),
        filename: '[name].bundle.js',
        library: 'vendor_lib'
    },
    module: {
        loaders: [
            {
                test: /\.scss$/,
                loaders: ['style', 'css', 'sass']
            },
            {
                test: /\.(css|ico|png|jpg|jpeg|gif)$/i,
                loaders: ['url-loader?limit=4096&context=/app&name=assets/static/[ext]/[name]_[hash].[ext]']
            }
        ],
        noParse: /node_modules/
    },
    plugins: [
        new webpack.DllPlugin({
            name: 'vendor_lib',
            path: path.join(__dirname, '/resources/assets/vendor/vendor-manifest.json')
        })
    ]
};