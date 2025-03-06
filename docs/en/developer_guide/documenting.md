# Documentation Guide

This project manages documentation using [MkDocs Material](https://squidfunk.github.io/mkdocs-material/).  
This guide explains how to edit existing documentation and add new pages.

## What is MkDocs?
[MkDocs](https://www.mkdocs.org/) is a static site generator that converts Markdown-based documentation into an HTML website.  
Using the `mkdocs-material` theme, you can create a stylish and readable documentation site.

## How to Edit Documentation
Documentation is managed as Markdown (`.md`) files under `docs/en/`.  
For example, this guide is located at `docs/en/developer_guide/documenting.md`.

### 1. Editing Existing Documentation
To edit an existing page, modify the corresponding `.md` file and create a Pull Request (PR).

### 2. Adding New Documentation
If you need to add a new page, follow these steps:

1. Create an appropriate folder and `.md` file under `docs/en/`
2. Add the new page to the `nav` section in `mkdocs.yml`
3. Commit the changes and create a PR

For example, to add a new “Payment API” guide:

```plaintext
docs/en/api/payment.md
```

Then, add the following entry to `mkdocs.yml`:

```yaml
nav:
  - Home: index.md
  - Developer Guide:
      - Documenting: developer_guide/documenting.md
  - API:
      - Payment API: api/payment.md
```

## Previewing Changes
To check your changes locally, run the following command:

```sh
docker compose up docs
```

Then, open `http://127.0.0.1:7777/` in your browser to preview the changes.

## Deployment Process
When changes to documentation are pushed to the `master` branch, GitHub Actions automatically deploys them.

The `deploy-docs.yml` GitHub Actions workflow performs the following steps:

1. Runs `mkdocs build` to generate static HTML
2. Deploys the documentation to the `gh-pages` branch

To manually trigger a deployment, run `workflow_dispatch` in GitHub Actions.

## Notes
- Keep `.md` file names and folder names in **alphanumeric characters** only.
- If a page is not added to the `nav` section in `mkdocs.yml`, it will not appear in the documentation site.
- To confirm a successful deployment, check the GitHub Pages URL.

---

Follow these steps to manage and update the documentation effectively.
