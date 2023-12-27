const path = require('path');

module.exports = {
  // エントリーポイント: バンドル処理の開始点となるファイル
  entry: './src/SpiralPress/resources/script/index.js',

  // アウトプット: 生成されるファイルの設定
  output: {
    // 出力ディレクトリのパス（絶対パス指定）
    path: path.resolve(__dirname, 'src/SpiralPress/resources/script/bundle/'),
    // 出力ファイル名
    filename: 'bundle.js',
    libraryTarget: 'module'
  },

  // モード: 'development' か 'production' を指定（本番環境では 'production'）
  mode: 'development',
  experiments: {
    outputModule: true // 実験的機能を有効化
  },

  // ソースマップの生成設定（開発ツールでのデバッグに便利）
  devtool: false,

  // ローダーの設定: WebpackがJavaScript以外のファイルを理解できるようにする
  module: {
    rules: [
      {
        // Babelを使用して、ES6+のコードをES5に変換する設定
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
      // 他のローダー（例: CSSや画像ファイル用）の設定もここに追加
    ]
  },

  // プラグインの設定: 追加の機能やカスタマイズを行う
  plugins: [
    // プラグインの設定はここに追加
  ]
};