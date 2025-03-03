# プラグインの新バージョンをWordPressストアにアップロードする方法

以下の手順で新しいバージョンをアップロードします。

1. `index.php` ファイル内の `Version: <version>` を更新します。  
2. `readme.txt` に新バージョンのための変更履歴（changelog entry）を追加します。  
3. （任意）各種PHPファイル（例: `class-wc-gateway-komoju.php` など）に散在しているバージョン番号を更新します。  
4. `git tag <your new version>` を実行します。  
5. `git push --tags` を実行します。  
6. [GitHub Actions](https://github.com/degica/komoju-woocommerce/actions) がデプロイを自動的に行います。

注意点：デプロイアクションはテストアクションの成功にチェックしません。`git push --tags` を実行する前にテストがパスしているかを確認するか、もしくはアクションを変更してデプロイがテストに依存するよう設定してください。