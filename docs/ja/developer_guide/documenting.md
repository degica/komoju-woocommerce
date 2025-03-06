# ドキュメントの作成方法

このプロジェクトでは、[MkDocs Material](https://squidfunk.github.io/mkdocs-material/) を使用してドキュメントを管理しています。  
ドキュメントの編集や新しいページの追加方法について説明します。

## MkDocs とは？
[MkDocs](https://www.mkdocs.org/) は、Markdown で書かれたドキュメントを HTML サイトとして生成する静的サイトジェネレーターです。  
特に `mkdocs-material` テーマを使用することで、スタイリッシュで見やすいドキュメントサイトを作成できます。

## ドキュメントの編集方法
ドキュメントは `docs/ja/` 以下に Markdown (`.md`) ファイルとして管理されています。  
例えば、このガイドは `docs/ja/developer_guide/documenting.md` にあります。

### 1. 既存のドキュメントを編集する
既存のページを編集する場合、対象の `.md` ファイルを修正し、Pull Request (PR) を作成してください。

### 2. 新しいドキュメントを追加する
新しいページを追加する場合、次の手順で作業してください。

1. `docs/ja/` 配下に適切なフォルダと `.md` ファイルを作成
2. `mkdocs.yml` の `nav` セクションに新しいページを追加
3. 変更をコミットして PR を作成

例えば、新しい「決済 API」ガイドを追加する場合:

```plaintext
docs/ja/api/payment.md
```

そして、`mkdocs.yml` に次のように追加します。

```yaml
nav:
  - ホーム: index.md
  - 開発者ガイド:
      - ドキュメント作成: developer_guide/documenting.md
  - API:
      - 決済 API: api/payment.md
```

## プレビュー方法
ローカル環境で変更を確認するには、以下のコマンドを実行してください。

```sh
docker compose up docs
```

ブラウザで `http://127.0.0.1:7777/` にアクセスすると、変更内容を確認できます。

## デプロイ方法
ドキュメントの変更が `master` ブランチにプッシュされると、GitHub Actions により自動的にデプロイされます。

GitHub Actions の `deploy-docs.yml` により、以下の手順が実行されます。

1. `mkdocs build` を実行し、静的 HTML を生成
2. `gh-pages` ブランチにデプロイ

手動でデプロイをトリガーする場合は、GitHub Actions の `workflow_dispatch` を実行してください。

## 注意事項
- `.md` ファイルのファイル名やフォルダ名は **英数字** で統一してください。
- `mkdocs.yml` の `nav` にページを追加しないと、サイトに表示されません。
- デプロイが正常に行われたか確認するには、GitHub Pages の URL にアクセスしてください。

---

以上の手順に従って、ドキュメントを管理・更新してください。

