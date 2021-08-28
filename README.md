<p align="center">
  <img src="https://i.imgur.com/9kksNkw.png"/>
</p>
<p align="center">
  <a href="https://github.com/KrbDevelopment/Articly-Documentation/wiki">Documentation</a> |
  <a href="https://github.com/KrbDevelopment/Articly-Documentation/wiki/Changelog">Changelog</a> |
  <a href="https://github.com/KrbDevelopment/Articly-Documentation/wiki/Roadmap">Roadmap</a>
</p>


<h3 align="center">
  Currently under heavy development & testing.
</h3>

<p align="center">
  Fast, easy and reliable wiki software running in the web.
</p>

<p align="center">
  <a href="https://github.com/KrbDevelopment">
	<img src="https://i.imgur.com/lk2p7IU.png" width="200" />
  </a>
</p>

## What is Articly?

<p align="center">
Articly is a open-source & free wiki software based on Vue.js & TailwindCSS in the frontend and Laravel in the backend.

<img alt="Overview Screenshot" src="https://i.imgur.com/qvCtJIF.png" />

<img alt="Overview Screenshot" src="https://i.imgur.com/xGl5oOc.png" />
</p>

## Installing

<img src="https://i.imgur.com/HrtcGGA.png" width="200" />
<img src="https://i.imgur.com/K8lWuuc.png" width="200" />
<img src="https://i.imgur.com/bzi2Ckq.png" width="200" />

Install Articly for Nginx and [get started](https://github.com/KrbDevelopment/Articly-Documentation/wiki/Installation).

```bash
git clone https://github.com/KrbDevelopment/Wiki-Software && cd Wiki-Software
```

### Installing Frontend
```bash
cd Frontend
npm i
npm run build
```

Point your webserver configuration onto the `dist`-folder's index.html. Double-check if the webserver has access to your `dist`-folder by using:

```bash 
chown -R www-data:www-data dist
```

For development purposes, you can use `npm run serve` instead of `npm run build`. This will launch a local dev server on port 8080.

### Installing Backend

```bash
cd Backend
composer i
```

Copy over the .env.example file into a file called .env. Fill out all important data, except for the `APP_KEY`.

Generate the app key by:
```bash 
php artisan key:generate
```

Install & setup passport for authentication:
```bash 
php artisan passport:install
```

Migrate database:
```bash 
php artisan migrate
```

Link storage folder for accessing images:
```bash 
php artisan storage:link
```

Optional: If you're experiencing weird error's, clearing the cache could help you:
```bash 
php artisan optimize 
```


## Contributing

Please see our [Contributing Guideline](https://github.com/KrbDevelopment/Articly-Documentation/wiki/Contribution) which explains repo organization, linting, testing, and other steps.

## License

<a href="./LICENSE"><img src="https://i.imgur.com/3dWTkcP.png" width="200" /></a>

This project is licensed under the terms of the [MIT license](/LICENSE).
