const http = require('http');
const fs   = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..', 'wp-content', 'themes', 'infinity-sky-theme');
const PORT = process.env.PORT || 5000;

const MIME = {
  '.html': 'text/html',
  '.css':  'text/css',
  '.js':   'application/javascript',
  '.json': 'application/json',
  '.png':  'image/png',
  '.jpg':  'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif':  'image/gif',
  '.svg':  'image/svg+xml',
  '.ico':  'image/x-icon',
  '.woff': 'font/woff',
  '.woff2':'font/woff2',
};

http.createServer((req, res) => {
  let urlPath = req.url.split('?')[0];
  if (urlPath === '/') urlPath = '/assets/css/main.css'; // default to main CSS

  const filePath = path.join(ROOT, urlPath);

  // Prevent path traversal
  if (!filePath.startsWith(ROOT)) {
    res.writeHead(403); res.end('Forbidden'); return;
  }

  fs.readFile(filePath, (err, data) => {
    if (err) {
      // List directory contents as HTML index
      if (err.code === 'ENOENT' || err.code === 'EISDIR') {
        const dir = fs.existsSync(filePath) ? filePath : ROOT;
        try {
          const entries = fs.readdirSync(dir);
          const links = entries.map(e => {
            const href = urlPath.replace(/\/$/, '') + '/' + e;
            return `<li><a href="${href}">${e}</a></li>`;
          }).join('');
          res.writeHead(200, { 'Content-Type': 'text/html' });
          res.end(`<!doctype html><html><body><h2>${urlPath}</h2><ul>${links}</ul></body></html>`);
        } catch {
          res.writeHead(404); res.end('Not found');
        }
      } else {
        res.writeHead(500); res.end(err.message);
      }
      return;
    }
    const ext = path.extname(filePath).toLowerCase();
    res.writeHead(200, { 'Content-Type': MIME[ext] || 'text/plain' });
    res.end(data);
  });
}).listen(PORT, () => console.log(`Infinity Sky theme assets → http://localhost:${PORT}`));
