# Branding Assets

To brand the interactive HTML report (`reports/<client>/qa_html/index.html`), place your logo here:

- `reporting/assets/logo.svg` (recommended, square icon format)
- `reporting/assets/logo.png`
- `reporting/assets/logo.jpg`
- `reporting/assets/logo.jpeg`

The HTML generator will automatically embed the logo as a base64 data URI so the report stays self-contained.
For best visual results in the report header, use a square icon (1:1 ratio) rather than a full wordmark lockup.

You can also provide a logo path directly when generating the HTML report:

```bash
npm run qa:html -- <client> --logo="/absolute/path/to/logo.png"
```
