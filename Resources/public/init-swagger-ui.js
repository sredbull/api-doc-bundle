// This file is part of the SRedbullApiDocBundle project.
//
// (c) Sven Roodbol <roodbol.sven@gmail.com>
//
// For the full copyright and license information, please view the LICENSE
// file that was distributed with this source code.

window.onload = () => {
  const data = JSON.parse(document.getElementById('swagger-data').innerText);
  const ui = SwaggerUIBundle({
    spec: data.spec,
    dom_id: '#swagger-ui',
    validatorUrl: null,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: 'BaseLayout'
  });

  window.ui = ui;
};
